<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AllowedCategory extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'allowed_categories';
    protected $fillable = [
        'category_id',
        "userable_type",
        "userable_id",
    ];

    public function setUserableTypeAttribute()
    {
        $this->attributes["userable_type"] = request("userable_type");
    }

    public function setUserableIdAttribute()
    {
        $this->attributes["userable_id"] = request("userable");
    }

    public function userable()
    {
        return $this->morphTo();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
