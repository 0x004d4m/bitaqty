<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserGroup extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'user_groups';
    protected $fillable = [
        "group_id",
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

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
