<?php

namespace Modules\Templates\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class EmailTrigger
 *
 * @package App\Models
 */
class EmailTrigger extends Model
{
    use HasFactory;
    protected $table = 'email_triggers';
    protected $fillable = [
        'name',
        'description',
        'event',
        'email_builder_template_id',
        'content_path',
        'is_active',
        'type',
        'email',
        'push',
        'data'
    ];
    protected $casts = [
        'data' => 'array'
    ];

    /**
     * Get the email builder template associated with the email trigger.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function emailBuilderTemplate()
    {
        return $this->belongsTo(EmailBuilderTemplate::class);
    }
    /**
     * Generate a unique event name from the trigger name.
     *
     * @param string $name
     * @return string
     */
    public static function generateUniqueEventName($name)
    {
        $eventName = strtolower(str_replace(' ', '_', $name));
        $originalEventName = $eventName;
        $counter = 1;

        while (self::where('event', $eventName)->exists()) {
            $eventName = $originalEventName . '_' . $counter;
            $counter++;
        }

        return $eventName;
    }
}