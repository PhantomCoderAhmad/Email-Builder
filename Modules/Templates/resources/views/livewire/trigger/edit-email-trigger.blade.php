<div class="space-y-6">
    <form wire:submit.prevent="save" class="space-y-4">
        <!-- Name -->
        <div>
            <label class="block mb-1 text-sm font-semibold text-gray-700 dark:text-white">Name</label>
            <input type="text" wire:model.defer="name" class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Description -->
        <div>
            <label class="block mb-1 text-sm font-semibold text-gray-700 dark:text-white">Description</label>
            <textarea wire:model.defer="description" class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" rows="3"></textarea>
            @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- From -->
        <div>
            <label class="block mb-1 text-sm font-semibold text-gray-700 dark:text-white">From</label>
            <input type="email" wire:model.defer="from" class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            @error('from') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Subject -->
        <div>
            <label class="block mb-1 text-sm font-semibold text-gray-700 dark:text-white">Subject</label>
            <input type="text" wire:model.defer="subject" class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            @error('subject') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
        <!-- Type -->
        <div>
            <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Type</label>
            <select id="type" wire:model="type" wire:change="updateType"
                class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="system">System</option>
                <option value="custom">Custom</option>
            </select>
            @error('type') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
        <!-- Email Builder Template -->
        <div>
            <label class="block mb-1 text-sm font-semibold text-gray-700 dark:text-white">Email Template</label>
            <select wire:model.defer="email_builder_template_id" 
                wire:change="updateEmailTemplate($event.target.value)"
                class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">-- Select Template --</option>
                @foreach ($templates as $template)
                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                @endforeach
            </select>
            @error('email_builder_template_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
        
        <div class="flex gap-2">
            <!-- Is Active -->
            <div class="flex items-center gap-2">
                <input type="checkbox" wire:model.defer="is_active" class="rounded dark:bg-gray-700 dark:border-gray-600">
                <label class="text-sm font-semibold text-gray-700 dark:text-white">Active</label>
            </div>
           <!-- Email Checkbox -->
            <div class="px-6 py-4">
                <div class="flex items-center gap-2">
                    <input type="checkbox" wire:model.defer="email"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label class="text-sm text-gray-900 dark:text-white">Email</label>
                </div>
            </div>

            @if ($type === 'custom')
                <!-- Push Checkbox -->
                <div class="px-6 py-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" wire:model="push" id="push_checkbox" wire:change="updatePushFields"
                            class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500">
                        <span class="text-sm text-gray-900">Push</span>
                    </label>
                </div>
            @endif

        </div>

        <!-- Push Fields -->
        @if ($push)
            <div x-data="{ push: @entangle('push') }" x-show="push" x-transition>
                <div class="px-6 py-4 space-y-4 border-t border-gray-300 bg-white shadow rounded-md">
                    <h3 class="text-lg font-semibold text-gray-800">Push Template</h3>

                    <!-- Title -->
                    <div>
                        <label for="push_title" class="block mb-1 text-sm font-medium text-gray-700">Push Title</label>
                        <input type="text" id="push_title" wire:model.defer="push_title"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500">
                    </div>

                    <!-- Body -->
                    <div>
                        <label for="push_body" class="block mb-1 text-sm font-medium text-gray-700">Push Body</label>
                        <textarea id="push_body" wire:model.defer="push_body" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500"></textarea>
                    </div>

                    <!-- Data Fields -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center mb-1">
                            <h4 class="font-medium text-gray-700">Data Fields</h4>
                            <button type="button" wire:click="addPushField"
                                class="px-3 py-1 text-sm text-white bg-green-600 rounded hover:bg-green-700">Add</button>
                        </div>

                        @foreach ($push_data_fields as $index => $field)
                            <div wire:key="push-field-{{ $index }}-{{ $field['key'] ?? 'none' }}" class="grid grid-cols-12 gap-2 items-start bg-gray-50 p-2 rounded">
                                <!-- Key Dropdown -->
                                <div class="col-span-4">
                                    <select wire:model="push_data_fields.{{ $index }}.key"
                                        wire:change="triggerPushFieldUpdate()"
                                        class="w-full px-2 py-2 border border-gray-300 rounded-md">
                                        <option value="">Select Key</option>
                                        <option value="type">Type</option>
                                        <option value="action">Action</option>
                                        <option value="title">Title</option>
                                        <option value="description">Description</option>
                                        <option value="targetUrl">Target Url</option>
                                        <option value="buttonText">Button Text</option>
                                        <option value="image">Image</option>
                                    </select>
                                </div>

                                <!-- Value Field -->
                                <div class="col-span-7">
                                    @if ($field['key'] === 'image')
                                        <!-- File Input for Image -->
                                        <input type="file" wire:model="push_data_fields.{{ $index }}.value"
                                            class="w-full px-2 py-2 border border-gray-300 rounded-md">

                                        <!-- Preview Uploaded Image -->
                                        @if (!empty($field['value']) && is_string($field['value']))
                                        <div class="mt-2">
                                            <a href="{{ Storage::disk('push_notification_images')->url($field['value']) }}" target="_blank">
                                                <img src="{{ Storage::disk('push_notification_images')->url($field['value']) }}"
                                                    class="w-24 h-24 object-cover rounded border">
                                            </a>
                                        </div>

                                        @endif
                                    @else
                                        <!-- Text Input for Other Fields -->
                                        <input type="text" wire:model.defer="push_data_fields.{{ $index }}.value"
                                            class="w-full px-2 py-2 border border-gray-300 rounded-md" placeholder="Value">
                                    @endif
                                </div>

                                <!-- Delete Button -->
                                <div class="col-span-1">
                                    <button type="button" wire:click="removePushField({{ $index }})"
                                        class="px-2 py-1 text-sm text-white bg-red-600 rounded hover:bg-red-700">✕</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif


        <!-- Summernote Editor -->
        <div wire:ignore>
            <label class="block mb-1 text-sm font-semibold text-gray-700 dark:text-white">Email Template</label>
            <textarea id="templateContentEditor" class="summernote w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                {!! $templateContent !!}
            </textarea>
        </div>
        

        <div class="flex justify-between items-center">
            <button type="submit" class="px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition">
                Update Trigger
            </button>
            <button type="button" onclick="confirmResetTemplate()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                Reset Changes
            </button>
        </div>
    </form>

    <!-- Merge Fields -->
    <div class="table">
        <div class="placeholder-info">
            <p>Available Merge Fields:</p>
            <table>
                @php
                    $fields = [
                        'First Name' => '{$first_name}',
                        'Last Name' => '{$last_name}',
                        'Full Name' => '{$full_name}',
                        'User Email' => '{$email}',
                        'User Address' => '{$address}',
                        'User City' => '{$city}',
                        'User Country' => '{$country}',
                        'Number of scanned products by user' => '{$scanned_products}', //scan_count
                        'Users Type' => '{$user_type}',
                        'Scanned Points' => '{$scanned_points}', //scan points
                        'Last Active' => '{$last_active}',
                        'Account Creation Date' => '{$account_date}',
                    ];
                @endphp
                @foreach ($fields as $label => $placeholder)
                    <tr>
                        <td>{{ $label }}</td>
                        <td><span class="placeholder cursor-pointer text-blue-600 underline" onclick="copyToClipboard('{{ $placeholder }}')">{{ $placeholder }}</span></td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    function initSummernote() {
    const editor = $('#templateContentEditor');
    if (!editor.hasClass('summernote-initialized')) {
        editor.summernote({
            height: 500,
            fontNames: ['Arial', 'Helvetica', 'Times New Roman', 'Courier New', 'CustomFont'], // Add your custom font here
            fontNamesIgnoreCheck: ['CustomFont'], // Ignore browser checks for custom fonts
            callbacks: {
                onInit: function () {
                    setTimeout(() => {
                        if (window.Livewire?.first()) {
                            const content = window.Livewire.first().get('templateContent');
                            editor.summernote('code', content);
                        }
                    }, 100);
                },
                onChange: function (contents) {
                    if (window.Livewire?.first()) {
                        window.Livewire.first().set('templateContent', contents);
                    }
                },
                onFocus: function () {
                    editor.summernote('saveRange');
                }
            }
        });
        editor.addClass('summernote-initialized');
    }
}

    function confirmResetTemplate() {
        if (confirm('Are you sure you want to reset the template? This will discard all your changes.')) {
            Livewire.dispatch('resetTemplateContent');
        }
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text)
            .then(() => alert('Copied: ' + text))
            .catch(() => alert('Failed to copy: ' + text));
    }

    document.addEventListener('DOMContentLoaded', function () {
        initSummernote();
        if (window.Livewire) {
            Livewire.hook('message.processed', () => {
                initSummernote();
            });
            Livewire.on('resetEditor', content => {
                console.log('resetEditor triggered:', content);

                // Ensure content is always a string
                let finalHtml = typeof content === 'string' ? content : JSON.stringify(content);

                // If content is JSON, extract the HTML part
                try {
                    const parsedContent = JSON.parse(finalHtml);
                    if (parsedContent && parsedContent.content) {
                        finalHtml = parsedContent.content;
                    }
                } catch (error) {
                    // Content is not JSON, use it as-is
                }

                // Update the Summernote editor content
                const editor = $('#templateContentEditor');
                if (editor.hasClass('summernote-initialized')) {
                    editor.summernote('code', finalHtml); // Set the editor content
                } else {
                    // If the editor is not initialized, initialize it first
                    editor.html(finalHtml); // Set the raw HTML content
                    initSummernote(); // Initialize Summernote
                }
            });

            Livewire.on('renderTemplateInEditor', content => {
                console.log('renderTemplateInEditor triggered:', content);

                // Ensure content is always a string
                let finalHtml = typeof content === 'string' ? content : JSON.stringify(content);

                // If content is JSON, extract the HTML part
                try {
                    const parsedContent = JSON.parse(finalHtml);
                    if (parsedContent && parsedContent.content) {
                        finalHtml = parsedContent.content;
                    }
                } catch (error) {
                    // Content is not JSON, use it as-is
                }

                // Update the Summernote editor content
                const editor = $('#templateContentEditor');
                if (editor.hasClass('summernote-initialized')) {
                    editor.summernote('code', finalHtml); // Set the editor content
                } else {
                    // If the editor is not initialized, initialize it first
                    editor.html(finalHtml); // Set the raw HTML content
                    initSummernote(); // Initialize Summernote
                }
            });
        }
       
    Livewire.on('updatePushFields', () => {
        Livewire.refresh(); // Force Livewire to refresh the component
    });
    });

</script>
