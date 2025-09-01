<?php

namespace Modules\Templates\App\Observers;

use Illuminate\Support\Facades\Storage;
use Modules\Templates\App\Models\EmailTrigger;

class EmailTriggerObserver
{
    /**
     * Handle the EmailTrigger "created" event.
     */
    public function created(EmailTrigger $emailtrigger): void
    {
        //
    }

    /**
     * Handle the EmailTrigger "updated" event.
     */
    public function updated(EmailTrigger $emailtrigger): void
    {
        //
    }

    /**
     * Handle the EmailTrigger "deleted" event.
     */
    public function deleted(EmailTrigger $emailTrigger): void
    {
        if ($emailTrigger->content_path && Storage::disk('trigger')->exists($emailTrigger->content_path)) {
            Storage::disk('trigger')->delete($emailTrigger->content_path);
        }
    }

    /**
     * Handle the EmailTrigger "restored" event.
     */
    public function restored(EmailTrigger $emailtrigger): void
    {
        //
    }

    /**
     * Handle the EmailTrigger "force deleted" event.
     */
    public function forceDeleted(EmailTrigger $emailtrigger): void
    {
        //
    }
}
