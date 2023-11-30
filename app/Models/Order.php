<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class Order extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'orders';
    protected $fillable = [
        "quantity",
        "device_name",
        "price",
        "profit",
        "credit_before",
        "credit_after",
        "product_id",
        "order_status_id",
        "userable_type",
        "userable_id",
        "type_id",
        "category_id",
        "subcategory_id",
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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function orderPrepaidCardStocks()
    {
        return $this->hasMany(OrderPrepaidCardStock::class);
    }

    public function getPriceAttribute($value)
    {
        return round($value / Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function setPriceAttribute($value)
    {
        return round($this->attributes['price'] = $value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function getCreditBeforeAttribute($value)
    {
        return round($value / Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function setCreditBeforeAttribute($value)
    {
        return round($this->attributes['credit_before'] = $value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function getCreditAfterAttribute($value)
    {
        return round($value / Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function setCreditAfterAttribute($value)
    {
        return round($this->attributes['credit_after'] = $value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function scopeCreatedAt($query, $dates, $date2)
    {
        return $query->whereBetween('created_at', [$dates, $date2]);
    }

    public static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if (backpack_user()) {
                $original_order_status_id = $model->getOriginal()['order_status_id'];
                if ($original_order_status_id != $model->order_status_id) {
                    if ($model->order_status_id == 2) {
                        if ($model->userable_type == Client::class) {
                            $Client = Client::where('id', $model->userable_id)->first();
                            $credit_after = $Client->credit - $model->price;
                            if($credit_after<0){
                                throw new Exception(__('admin.credit_not_enough'));
                            }else{
                                $Client->update([
                                    "credit" => $credit_after
                                ]);
                            }
                            if($Client->vendor){
                                $Vendor = Vendor::where('id', $Client->vendor_id)->first();
                                $Vendor->update([
                                    "profit" => $Vendor->profit + $model->profit
                                ]);
                                VendorProfit::create([
                                    'vendor_id'=> $Client->vendor_id ,
                                    'notes' => '+',
                                    'amount' => $model->profit
                                ]);
                            }
                        }
                    }
                }
            }
        });
    }
}
