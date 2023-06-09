<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;
    use HasTranslations;

    protected $table = 'news';
    protected $translatable = [
        'title',
        'description',
    ];
    protected $fillable = [
        'title',
        'description',
        'action',
    ];
}
