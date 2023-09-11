<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class Issue extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'issues';
    protected $fillable = [
        "description",
        "image",
        "solution",
        "is_solved",
        "is_duplicate",
        "issue_type_id",
        "userable_type",
        "userable_id",
    ];

    public function setUserableTypeAttribute($value)
    {
        if (request("userable_type")) {
            $this->attributes["userable_type"] = request("userable_type");
        }
        $this->attributes["userable_type"] = $value;
    }

    public function setUserableIdAttribute($value)
    {
        if (request("userable")) {
            $this->attributes["userable_id"] = request("userable");
        }
        $this->attributes["userable_id"] = $value;
    }

    public function userable()
    {
        return $this->morphTo();
    }

    public function issueType()
    {
        return $this->belongsTo(IssueType::class);
    }

    public function setImageAttribute($value)
    {
        $attribute_name = "image";
        $destination_path = "public/uploads";

        if ($value == null) {
            $this->attributes[$attribute_name] = null;
        }

        if (Str::startsWith($value, 'data:image')) {
            $image = Image::make($value)->encode('png', 90);
            $filename = md5($value . time()) . '.png';
            Storage::put($destination_path . '/' . $filename, $image->stream());
            $public_destination_path = Str::replaceFirst('public/', 'storage/', $destination_path);
            $this->attributes[$attribute_name] = $public_destination_path . '/' . $filename;
        } elseif ($value) {
            $image = Image::make($value)->encode('png', 90);
            $filename = md5($value . time()) . '.png';
            Storage::put($destination_path . '/' . $filename, $image->stream());
            $public_destination_path = Str::replaceFirst('public/', 'storage/', $destination_path);
            $this->attributes[$attribute_name] = $public_destination_path . '/' . $filename;
        }
    }

    public function getImageAttribute()
    {
        if ($this->attributes['image'] != null) {
            return url($this->attributes['image']);
        }
        return null;
    }
}
