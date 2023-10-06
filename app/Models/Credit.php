<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class Credit extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'credits';
    protected $fillable = [
        "image",
        "amount",
        "notes",
        "deposit_or_withdraw",
        "credit_before",
        "credit_after",
        "credit_type_id",
        "credit_status_id",
        "userable_type",
        "userable_id",
        "supported_account_id",
        "userable_from_type",
        "userable_from_id",
        "credit_from_before",
        "credit_from_after",
    ];

    public function userable()
    {
        return $this->morphTo();
    }

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

    public function userableFrom()
    {
        return $this->morphTo();
    }

    public function setUserableFromTypeAttribute($value)
    {
        if (request("userable_from_type")) {
            $this->attributes["userable_from_type"] = request("userable_from_type");
        }
        if($value==null){
            $this->attributes["userable_from_type"] = User::class;
        }
        $this->attributes["userable_from_type"] = $value;
    }

    public function setUserableFromIdAttribute($value)
    {
        if (request("userable_from")) {
            $this->attributes["userable_from_id"] = request("userable_from");
        }
        if ($value == null) {
            $this->attributes["userable_from_id"] = backpack_user()->id;
        }
        $this->attributes["userable_from_id"] = $value;
    }

    public function creditType()
    {
        return $this->belongsTo(CreditType::class);
    }

    public function supportedAccount()
    {
        return $this->belongsTo(SupportedAccount::class);
    }

    public function creditStatus()
    {
        return $this->belongsTo(CreditStatus::class);
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

    public function getAmountAttribute($value)
    {
        return round($value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function setAmountAttribute($value)
    {
        return round($this->attributes['amount'] = $value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function getCreditBeforeAttribute($value)
    {
        return round($value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function setCreditBeforeAttribute($value)
    {
        return round($this->attributes['credit_before'] = $value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function getCreditAfterAttribute($value)
    {
        return round($value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function setCreditAfterAttribute($value)
    {
        return round($this->attributes['credit_after'] = $value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function getCreditFromBeforeAttribute($value)
    {
        return round($value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function setCreditFromBeforeAttribute($value)
    {
        return round($this->attributes['credit_from_before'] = $value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function getCreditFromAfterAttribute($value)
    {
        return round($value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function setCreditFromAfterAttribute($value)
    {
        return round($this->attributes['credit_from_after'] = $value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    // public static function boot()
    // {
    //     parent::boot();

    //     static::updating(function ($model) {
    //         $original_credit_status_id = $model->getOriginal()['credit_status_id'];
    //         if($original_credit_status_id != $model->credit_status_id){
    //             if($model->userable_type == 'App\Models\Client'){
    //                 $Client = Client::where('id', $model->userable_id)->first();
    //                 $model->credit_before;
    //                 $model->credit_after;
    //             }else if('App\Models\Vendor'){

    //             }
    //         }
    //     });
    // }
}
