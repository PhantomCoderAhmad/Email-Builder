<?php

namespace Modules\Templates\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Templates\Database\factories\MediaLibraryFactory;

class MediaLibrary extends Model
{
    use HasFactory;
    protected $table = 'media_library';
    protected $fillable = ['filename', 'path'];
}
