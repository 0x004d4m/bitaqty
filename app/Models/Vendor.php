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

class Vendor extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;
    use HasTranslations;

    protected $table = 'vendors';
    protected $translatable = [
        "name",
        "address",
    ];
    protected $fillable = [
        "name",
        "address",
        "phone",
        "password",
        "credit",
        "dept",
        "email",
        "image",
        "is_blocked",
        "country_id",
        "state_id",
        "currency_id",
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'userable');
    }

    public function orders()
    {
        return $this->morphMany(Order::class, 'userable');
    }

    public function userGroups()
    {
        return $this->morphMany(UserGroup::class, 'userable');
    }

    public function allowedCategories()
    {
        return $this->morphMany(AllowedCategory::class, 'userable');
    }

    public function issues()
    {
        return $this->morphMany(Issue::class, 'userable');
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
}
