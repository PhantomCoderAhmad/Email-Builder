<?php

namespace Modules\Templates\Livewire\Template;

use Livewire\Component;
use Modules\Templates\App\Models\EmailBuilderTemplate;

class EmailTemplateList extends Component
{
    // Public property to hold the search query
    public $search = '';

    // Property to store the filtered email templates
    public $emailTemplates = [];

    /**
     * Lifecycle hook: Called when the component is mounted.
     */
    public function mount()
    {
        // Initialize the email templates (optional: fetch all templates initially)
        $this->emailTemplates = EmailBuilderTemplate::all();
    }
    /**
     * Lifecycle hook: Called when the $search property is updated.
     */
    public function updatedSearch()
    {
        // Query the database based on the search term
        $this->emailTemplates = EmailBuilderTemplate::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('content_path', 'like', '%' . $this->search . '%');
            })
            ->get();
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('templates::livewire.template.email-template-list', [
            'emailTemplates' => $this->emailTemplates,
        ]);
    }
}