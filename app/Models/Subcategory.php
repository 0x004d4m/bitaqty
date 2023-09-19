<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class Subcategory extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;
    use HasTranslations;

    protected $table = 'subcategories';
    protected $translatable = ['name'];
    protected $fillable = [
        'name',
        'image',
        'is_active',
        'has_limit',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'subcategory_vendors');
    }

    public function clients()
    {
        return $this->belongsToMany('App\Models\Client', 'subcategory_clients');
    }

    public function fields()
    {
        return $this->belongsToMany(Field::class, 'subcategory_fields');
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
