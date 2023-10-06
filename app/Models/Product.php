<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;
    use HasTranslations;

    protected $table = 'products';
    protected $translatable = [
        'name',
        'description',
        'unavailable_notes',
        'how_to_use',
    ];
    protected $fillable = [
        'name',
        'description',
        'unavailable_notes',
        'how_to_use',
        'image',
        'price',
        'suggested_price',
        'cost_price',
        'selling_price',
        'stock',
        'stock_limit',
        'is_active',
        'is_vip',
        'type_id',
        'category_id',
        'subcategory_id',
    ];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function fields()
    {
        return $this->belongsToMany(Field::class, 'product_fields');
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

    public function getPriceAttribute($value)
    {
        return round($value / Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function setPriceAttribute($value)
    {
        return round($this->attributes['price'] = $value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function getSuggestedPriceAttribute($value)
    {
        return round($value / Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function setSuggestedPriceAttribute($value)
    {
        return round($this->attributes['suggested_price'] = $value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function getCostPriceAttribute($value)
    {
        return round($value / Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function setCostPriceAttribute($value)
    {
        return round($this->attributes['cost_price'] = $value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function getSellingPriceAttribute($value)
    {
        return round($value / Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function setSellingPriceAttribute($value)
    {
        return round($this->attributes['selling_price'] = $value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }
}
