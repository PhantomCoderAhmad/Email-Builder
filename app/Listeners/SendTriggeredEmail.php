<?php

namespace App\Listeners;

use App\Events\EmailTriggerFired;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Modules\Templates\App\Models\EmailTrigger;
use Symfony\Component\Mime\Part\TextPart;
use App\Models\User; // or your actual User model path

class SendTriggeredEmail implements ShouldQueue
{
    use InteractsWithQueue;

    public $connection = 'database';
    public $queue = 'emails';

    public function handle(EmailTriggerFired $event)
    {
        try {
            $trigger = EmailTrigger::where('event', $event->triggerEvent)->first();
    
            if (!$trigger) {
                \Log::warning('[SendTriggeredEmail] Trigger not found for event: ' . $event->triggerEvent);
                return;
            }
    
            // Fetch the user with profile
            $user = User::with(['profile'])->find($event->authUserId);
            if (!$user || empty($user->email)) {
                \Log::warning('[SendTriggeredEmail] User not found or email is missing. User ID: ' . $event->authUserId);
                return;
            }
    
            $htmlTemplate = '';
            if ($trigger->content_path && Storage::disk('trigger')->exists($trigger->content_path)) {
                $htmlTemplate = Storage::disk('trigger')->get($trigger->content_path);
            } else {
                \Log::warning('[SendTriggeredEmail] Template file not found at: ' . ($trigger->content_path ?? 'NULL'));
                return;
            }
    
            $from = $trigger->data['from'] ?? config('mail.from.address');
            $subject = $trigger->data['subject'] ?? 'No Subject';
    
            \Log::info('[SendTriggeredEmail] Preparing email for: ' . $user->email);
    
            // Explicit merge fields mapping
            $mergedData = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'full_name' => trim($user->first_name . ' ' . $user->last_name),
                'email' => $user->email,
                'user_type' => $user->type ?? '',
                'account_date' => optional($user->created_at)->format('Y-m-d') ?? '',
                'last_active' => $user->last_active ?? '',
                'scanned_products' => $user->profile->scanned_count ?? '',
                'scanned_points' => $user->profile->scan_points ?? '',
                'country' => $user->profile->country ?? '',
                'city' => $user->profile->city ?? '',
                'address' => $user->profile->address ?? '',
            ];
    
            // Merge the fields in the template
            $personalizedHtml = $this->replaceMergeFields($htmlTemplate, $mergedData);
    
            // Check if email sending is enabled
            if ($trigger->email) {
                \Log::info('[SendTriggeredEmail] Email sending enabled, sending email to: ' . $user->email);
    
                // Send the email
                Mail::send([], [], function ($message) use ($user, $from, $subject, $personalizedHtml) {
                    $message->to($user->email)
                            ->from($from)
                            ->subject($subject)
                            ->setBody(new TextPart($personalizedHtml, 'utf-8', 'html'));
                });
            } else {
                \Log::info('[SendTriggeredEmail] Email sending is disabled for trigger: ' . $event->triggerEvent);
            }
    
            // Log for push notifications
            if ($trigger->push) {
                \Log::info('[SendTriggeredEmail] Push notification triggered, but no push notification sent. Event: ' . $event->triggerEvent);
            }
    
        } catch (\Exception $e) {
            \Log::error('[SendTriggeredEmail] Exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    


    /**
     * Replace merge fields like {$first_name} in the template with actual user values.
     */
    private function replaceMergeFields(string $html, array $data): string
    {
        return preg_replace_callback('/{\$(\w+)}/', function ($matches) use ($data) {
            $field = $matches[1];
            return $data[$field] ?? ''; // fallback to empty string if not available
        }, $html);
    }
}
