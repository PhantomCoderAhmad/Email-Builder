<?php

namespace Modules\Templates\Livewire\Trigger;

use App\Events\EmailTriggerFired;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

use Modules\Templates\App\Models\EmailBuilderTemplate;
use Modules\Templates\App\Models\EmailTrigger;
use Illuminate\Support\Facades\Validator;

class EmailTriggerList extends Component
{
    public $name;
    public $description;
    public $subject;
    public $from;
    public $email_template;
    public $emailTemplates;
    public $emailTriggers; // Add property to store email triggers
    public $search = '';
    public $deleteId = null; // Track which ID is being deleted
    public $confirmDeleteModal = false; 
    public $type = 'system'; // Default value

    /**
     * Mount the component.
     *
     * @return void
     */
    public function mount()
    {
        $this->emailTemplates = EmailBuilderTemplate::all();
        $this->emailTriggers = EmailTrigger::all(); // Fetch all email triggers
        $this->from = config('app.name'); // Set default from value to app name
    }
    public function updatedSearch()
    {
        // Re-fetch email triggers and apply filtering
        $this->emailTriggers = EmailTrigger::with('emailBuilderTemplate')
            ->get()
            ->filter(function ($trigger) {
                // Check if the search query matches the trigger name or the associated template name
                return empty($this->search) || // If no search query, include all triggers
                       stripos($trigger->name, $this->search) !== false || // Match by trigger name
                       (
                           $trigger->emailBuilderTemplate && // Ensure the template exists
                           stripos($trigger->emailBuilderTemplate->name, $this->search) !== false // Match by template name
                       );
            })
            ->values(); // Reset keys for proper rendering
    }
    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('templates::livewire.trigger.email-trigger-list', [
            'emailTemplates' => $this->emailTemplates,
            'emailTriggers' => $this->emailTriggers, // Pass email triggers to the view
        ]);
    }

    /**
     * Add a new email trigger.
     *
     * @return void
     */
    public function addEmailTrigger()
    {
        // Validate data
        $validatedData = Validator::make([
            'name' => $this->name,
            'description' => $this->description,
            'subject' => $this->subject,
            'from' => $this->from,
            'email_template' => $this->email_template,
            'type' => $this->type,
        ], [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'required|string|max:255',
            'from' => 'required|string|max:255',
            'email_template' => 'required|exists:email_builder_templates,id',
            'type' => 'required|in:system,custom',
        ])->validate();
        

        // Generate unique event name
        $eventName = EmailTrigger::generateUniqueEventName($validatedData['name']);

        // Create EmailTrigger record (initially without content_path)
        $trigger = EmailTrigger::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'event' => $eventName,
            'email_builder_template_id' => $validatedData['email_template'],
            'is_active' => true,
            'type' => $validatedData['type'],
            'data' => [
                'subject' => $validatedData['subject'],
                'from' => $validatedData['from'],
            ],
        ]);

        // Get content_path from email_template and fetch its HTML part
        $template = EmailBuilderTemplate::find($validatedData['email_template']);

        if ($template && Storage::disk('email_builder')->exists($template->content_path)) {
            $templateContent = json_decode(Storage::disk('email_builder')->get($template->content_path), true);

            if (!empty($templateContent['html'])) {
                $htmlContent = $templateContent['html'];
                $fileName = Str::slug($validatedData['name']) . '_' . $trigger->id . '.html';
                $fullPath = 'trigger-templates/' . $fileName;
        
                // Save HTML content
                Storage::disk('trigger')->put($fullPath, $htmlContent);
        
                // Save relative path to DB
                $trigger->update([
                    'content_path' => $fullPath,
                ]);
            }
        }

        // Reset form fields
        $this->reset(['name', 'description', 'subject', 'from', 'email_template', 'type']);

        // Emit event to close modal
        $this->dispatch('notify', title: 'Emails triggered Created successfully', message: 'Emails triggered Created successfully.', type: 'success');
        // Refresh list
        $this->emailTriggers = EmailTrigger::all();
    }


    public function triggerEmail($id)
    {
        $trigger = EmailTrigger::find($id);
        if (!$trigger) {
            $this->dispatch('emailTriggerError', [
                'type' => 'notify',
                'success' => false,
                'message' => 'Trigger not found for this template.',
            ]);
            return;
        }
    
        // Array of users (normally this would come from your DB or a selection UI)
        $authUserId = 1;
    
        // Trigger event with the array of users
        event(new EmailTriggerFired($trigger->event, $authUserId));
    
        $this->dispatch('notify', title: 'Emails triggered successfully', message: 'Emails triggered successfully.', type: 'success');
    }
    public function confirmDelete($triggerId)
    {
        // Store the triggerId for deletion
        $this->deleteId = $triggerId;
        // Show confirmation modal
        $this->confirmDeleteModal = true;
    }

    public function deleteTrigger()
    {
        if ($this->deleteId) {
            $trigger = EmailTrigger::find($this->deleteId);

            if ($trigger) {
                // Delete the email trigger
                $trigger->delete();

                // Refresh email triggers list
                $this->emailTriggers = EmailTrigger::all();

                // Hide the modal after deletion
                $this->confirmDeleteModal = false;
            }
        }
    }

}