<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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

    public function creditType()
    {
        return $this->belongsTo(CreditType::class);
    }

    public function creditStatus()
    {
        return $this->belongsTo(CreditStatus::class);
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
