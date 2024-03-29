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

class InAppMessage extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;
    use HasTranslations;

    protected $table = 'in_app_messages';
    protected $translatable = [
        'title',
        'description',
    ];
    protected $fillable = [
        'type',
        'title',
        'description',
        'image',
        'action',
        'is_important',
        'is_active',
    ];

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

    public static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if($model->is_active != $model->getOriginal()['is_active'] && $model->is_active==1){
                $InAppMessages = InAppMessage::get();
                foreach ($InAppMessages as $InAppMessage) {
                    $InAppMessage->update([
                        'is_active' => 0
                    ]);
                }
            }
        });
    }
}
