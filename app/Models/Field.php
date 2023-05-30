<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Field extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;
    use HasTranslations;

    protected $table = 'fields';
    protected $translatable = ['name'];
    protected $fillable = [
        'name',
        'field_type_id',
        'is_confirmed',
    ];

    public function fieldType()
    {
        return $this->belongsTo(FieldType::class);
    }
}
