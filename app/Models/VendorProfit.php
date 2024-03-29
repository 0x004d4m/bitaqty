<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorProfit extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'vendor_profits';
    protected $fillable = ['vendor_id', 'notes', 'amount'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (backpack_user()) {
                $Vendor = Vendor::where('id', $model->vendor_id)->first();
                $Vendor->update([
                    "credit" => $model->amount
                ]);
            }
        });
    }
}
