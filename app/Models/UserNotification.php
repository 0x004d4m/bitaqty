<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserNotification extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTranslations;

    protected $table = 'user_notifications';
    protected $translatable = [
        "title",
        "description"
    ];
    protected $fillable = [
        "title",
        "description",
        "image",
        "data",
        "is_read",
        "userable_type",
        "userable_id",
    ];

    public function userable()
    {
        return $this->morphTo();
    }

    public function getImageAttribute()
    {
        if ($this->attributes['image'] != null) {
            return url($this->attributes['image']);
        }
        return null;
    }
}
