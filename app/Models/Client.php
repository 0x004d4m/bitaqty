<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class Client extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;
    use HasTranslations;
    use HasApiTokens;

    protected $table = 'clients';
    protected $translatable = [
        "name",
        "address",
        "commercial_name",
    ];
    protected $fillable = [
        "name",
        "address",
        "phone",
        "password",
        "commercial_name",
        "email",
        "country_id",
        "state_id",
        "currency_id",
        "image",
        "credit",
        "is_approved",
        "is_blocked",
        "can_give_credit",
        "vendor_id",
        "group_id",
        "fcm_token",
        "otp_token",
        "otp_code",
        "access_token",
        "refresh_token",
        "forget_token",
        "is_email_verified",
        "is_phone_verified",
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

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

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'userable');
    }

    public function orders()
    {
        return $this->morphMany(Order::class, 'userable');
    }

    public function allowedCategories()
    {
        return $this->morphMany(AllowedCategory::class, 'userable');
    }

    public function issues()
    {
        return $this->morphMany(Issue::class, 'userable');
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function setImageAttribute($value)
    {
        $attribute_name = "image";
        $destination_path = "public/uploads";

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
        } else {
            $this->attributes[$attribute_name] = null;
        }
    }

    public function getImageAttribute()
    {
        if ($this->attributes['image'] != null) {
            return url($this->attributes['image']);
        }
        return null;
    }

    public function getCreditAttribute($value)
    {
        return round($value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function setCreditAttribute($value)
    {
        return round($this->attributes['credit'] = $value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }
}
