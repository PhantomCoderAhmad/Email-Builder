<?php

namespace Modules\Templates\Livewire\Trigger;

use Livewire\Component;
use Modules\Templates\App\Models\EmailTrigger;
use Modules\Templates\App\Models\EmailBuilderTemplate;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;


class EditEmailTrigger extends Component
{
    use WithFileUploads;
    public EmailTrigger $trigger;
    public string $name;
    public string $description = '';
    public int $email_builder_template_id = 0;
    public bool $is_active;
    public $type;
    public $email;
    public $push;
    public string $from = '';
    public string $subject = '';
    public string $templateContent = '';
    public string $push_title = '';
    public string $push_body = '';
    public array $push_data_fields = [['key' => '', 'value' => '']];
    


    protected $listeners = ['resetTemplateContent', 'triggerPushFieldUpdate' => 'triggerPushFieldUpdate'];

    

    public function mount(EmailTrigger $trigger)
    {
        $this->trigger = $trigger;
        $this->name = $trigger->name;
        $this->description = $trigger->description ?? '';
        $this->email_builder_template_id = $trigger->email_builder_template_id ?? 0;
        $this->is_active = (bool) $trigger->is_active;
        $this->type = $trigger->type;
        $this->email = (bool) $trigger->email;
        $this->push = (bool) $trigger->push;
        $data = is_array($trigger->data) ? $trigger->data : json_decode($trigger->data ?? '{}', true);
        $this->from = $data['from'] ?? '';
        $this->subject = $data['subject'] ?? '';

        $this->push_title = $data['push_title'] ?? '';
        $this->push_body = $data['push_body'] ?? '';
        $this->push_data_fields = [];

        if (!empty($data['key']) && is_array($data['key'])) {
            foreach ($data['key'] as $index => $key) {
                $this->push_data_fields[] = [
                    'key' => $key,
                    'value' => $data['value'][$index] ?? '',
                ];
            }
        }



        if ($this->trigger->content_path && Storage::disk('trigger')->exists($this->trigger->content_path)) {
            $this->templateContent = Storage::disk('trigger')->get($this->trigger->content_path);
            // dd($this->templateContent);
        } else {
            $this->templateContent = ''; // fallback if not found
        }
    }
    public function updateType()
    {
        // Reset push if the type is not 'custom'
        if ($this->type !== 'custom') {
            $this->push = false;
        }
    }

    public function updatePushFields()
    {
        // If push is false, reset fields
        if (!$this->push) {
            $this->push_title = '';
            $this->push_body = '';
            $this->push_data_fields = [['key' => '', 'value' => '']];
        } else {
            // If push is true, check if the data exists in the database
            if ($this->trigger->data) {
                // If data is present, assign it to the push fields
                $data = $this->trigger->data;

                $this->push_title = $data['push_title'] ?? '';
                $this->push_body = $data['push_body'] ?? '';

                // Populate push_data_fields with existing data if available
                $this->push_data_fields = [];

                if (!empty($data['key']) && is_array($data['key'])) {
                    foreach ($data['key'] as $index => $key) {
                        $this->push_data_fields[] = [
                            'key' => $key,
                            'value' => $data['value'][$index] ?? '',
                        ];
                    }
                }
            } else {
                // If no push data is available in the database, reset fields
                $this->push_title = '';
                $this->push_body = '';
                $this->push_data_fields = [['key' => '', 'value' => '']];
            }
        }
    }

    public function addPushField()
    {
        $this->push_data_fields[] = ['key' => '', 'value' => ''];
    }

    public function removePushField($index)
    {
        unset($this->push_data_fields[$index]);
        $this->push_data_fields = array_values($this->push_data_fields); // reindex
    }


    public function resetTemplateContent()
    {
        $template = EmailBuilderTemplate::find($this->email_builder_template_id);

        if (!$template) {
            $this->dispatch('notify', title: 'Template not found', message: 'Unable to reset. Template not found.', type: 'error');
            return;
        }

        if ($template->content_path && Storage::disk('email_builder')->exists($template->content_path)) {
            $templateData = json_decode(Storage::disk('email_builder')->get($template->content_path), true);

            // Validate and assign HTML content
            if (!empty($templateData['html'])) {
                $this->templateContent = is_array($templateData['html']) ? json_encode($templateData['html']) : (string) $templateData['html'];

                // Save it to the trigger content file
                if ($this->trigger->content_path) {
                    $html = $this->templateContent;
                    Storage::disk('trigger')->put($this->trigger->content_path, $html);
                }
                $this->templateContent = Storage::disk('trigger')->get($this->trigger->content_path);

                // Notify + reset the frontend editor
                $this->dispatch('notify', title: 'Reset successful', message: 'Template reset to original.', type: 'success');
                $this->dispatch('resetEditor', content: (string) $this->templateContent);
            } else {
                $this->dispatch('notify', title: 'Template empty', message: 'Selected template has no HTML content.', type: 'warning');
            }
        } else {
            $this->dispatch('notify', title: 'Template not found', message: 'Template file not found on disk.', type: 'error');
        }
    }
    public function updateEmailTemplate($templateId)
    {
        if ($templateId) {
            
            $template = EmailBuilderTemplate::find($templateId);

            if ($template->content_path && Storage::disk('email_builder')->exists($template->content_path)) {
                $templateData = json_decode(Storage::disk('email_builder')->get($template->content_path), true);

                // Validate and assign HTML content
                if (!empty($templateData['html'])) {
                    $this->templateContent = is_array($templateData['html']) ? json_encode($templateData['html']) : (string) $templateData['html'];
                    // Notify + reset the frontend editor
                    $this->dispatch('notify', title: 'Change successful', message: 'Template change Successfully.', type: 'success');
                    $this->dispatch('renderTemplateInEditor', content: (string) $this->templateContent);
                } else {
                    $this->dispatch('notify', title: 'Template empty', message: 'Selected template has no HTML content.', type: 'warning');
                }
            } else {
                $this->dispatch('notify', title: 'Template not found', message: 'Template file not found on disk.', type: 'error');
            }
        }
    }
    public function triggerPushFieldUpdate()
    {
        $this->push_data_fields = collect($this->push_data_fields)->toArray(); // force reactivity
    }

    public function updatedPushDataFields($value, $key)
    {
        // Split the key into index and property (e.g., "0.key")
        [$index, $property] = explode('.', $key);
    
        // If the key is "key" and the value is "image", reset the corresponding value
        if ($property === 'key' && $value === 'image') {
            $this->push_data_fields[$index]['value'] = null;
        }
    }
    public function save()
    {
        // ✅ First, check and delete image if push is being disabled
        if (!$this->push) {
            $storedData = $this->trigger->data;
        
            if (!empty($storedData['key']) && !empty($storedData['value'])) {
                // Reconstruct the fields
                $fields = array_map(function ($key, $value) {
                    return ['key' => $key, 'value' => $value];
                }, $storedData['key'], $storedData['value']);
        
                foreach ($fields as $field) {
                    if (
                        isset($field['key'], $field['value']) &&
                        $field['key'] === 'image' &&
                        is_string($field['value']) &&
                        !empty($field['value'])
                    ) {
                        $imagePath = $field['value'];
        
                        try {
                            if (Storage::disk('push_notification_images')->exists($imagePath)) {
                                Storage::disk('push_notification_images')->delete($imagePath);
                            }
                        } catch (\Exception $e) {
                            $this->dispatch('notify', title: 'Image Deletion Error', message: $e->getMessage(), type: 'error');
                        }
                    }
                }
            }
        }
        // ✅ Then handle uploads
        foreach ($this->push_data_fields as $i => $field) {
            if (
                isset($field['key'], $field['value']) &&
                $field['key'] === 'image' &&
                $field['value'] instanceof \Illuminate\Http\UploadedFile
            ) {
                try {
                    $file = $field['value'];
                    $hashName = md5(time() . $file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
                    $fileContents = file_get_contents($file->getRealPath());

                    Storage::disk('push_notification_images')->put('images/' . $hashName, $fileContents);

                    $this->push_data_fields[$i]['value'] = 'images/' . $hashName;
                } catch (\Exception $e) {
                    $this->dispatch('notify', title: 'File Upload Error', message: $e->getMessage(), type: 'error');
                    return;
                }
            }
        }
    
        // Extract keys and values from the dynamic fields
        $keys = array_column($this->push_data_fields, 'key');
        $values = array_column($this->push_data_fields, 'value');
    
        // Validate required fields
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'email_builder_template_id' => 'required|exists:email_builder_templates,id',
            'from' => 'required|email',
            'subject' => 'required|string|max:255',
        ]);
    
        // Keep track of original name for event regeneration
        $originalName = $this->trigger->getOriginal('name');
    
        // Fill trigger fields
        $this->trigger->fill([
            'name' => $this->name,
            'description' => $this->description,
            'email_builder_template_id' => $this->email_builder_template_id,
            'is_active' => $this->is_active,
            'type' => $this->type,
            'email' => $this->email,
            'push' => $this->push,
        ]);
    
        // Regenerate event if name changed
        if ($this->name !== $originalName) {
            $this->trigger->event = EmailTrigger::generateUniqueEventName($this->name);
        }
    
        // Set structured data
        // Handle the push data (only if push is true)
        if ($this->push) {
            $this->trigger->data = [
                'from' => $this->from,
                'subject' => $this->subject,
                'push_title' => $this->push_title,
                'push_body' => $this->push_body,
                'key' => $keys,
                'value' => $values,
            ];
        } else {
            $this->trigger->data = [
                'from' => $this->from,
                'subject' => $this->subject,
            ];
        }
        
    
        // Save the trigger
        $this->trigger->save();
    
        // Save updated template HTML (raw without wrapper)
        if ($this->trigger->content_path && Storage::disk('trigger')->exists($this->trigger->content_path)) {
            $html = $this->templateContent;
            Storage::disk('trigger')->put($this->trigger->content_path, $html);
        }

        // Dispatch success notification
        $this->dispatch('notify', title: 'Updated successfully', message: 'Email trigger updated successfully.', type: 'success');
    }
    

    public function render()
    {
        return view('templates::livewire.trigger.edit-email-trigger', [
            'templates' => EmailBuilderTemplate::all(),
            'templateContent' => $this->templateContent,
        ]);
    }

}