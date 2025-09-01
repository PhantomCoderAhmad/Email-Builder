<?php

namespace Modules\Templates\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailBuilderTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'content_path'];
    protected $table = 'email_builder_templates';
    protected $appends = ['content'];

    public function getContentAttribute()
    {
        if ($this->content_path == null) {
            return '';
        }
        if (Storage::disk('email_builder')->exists($this->content_path)) {
            $data =  Storage::disk('email_builder')->get($this->content_path);
            return json_decode($data, true);
        } else {
            return '';
        }
    }

    public function setContentAttribute($value)
    {
        $path = 'email-builder-templates/' . $this->id . '.json';
        if (Storage::disk('email_builder')->exists($path)) {
            Storage::disk('email_builder')->delete($path);
        }
        Storage::disk('email_builder')->put($path, json_encode($value, JSON_PRETTY_PRINT));
        $this->attributes['content_path'] = $path;
    }
}
