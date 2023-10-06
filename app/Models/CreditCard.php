<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class CreditCard extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'credit_cards';
    protected $fillable = [
        "number",
        "value",
        "qr",
        "is_used",
    ];

    public function setQrAttribute($value)
    {
        return $this->attributes['qr'] = url("/api/clients/credits/qr/". md5($this->attributes['number']));
    }

    public function getValueAttribute($value)
    {
        return round($value / Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function setValueAttribute($value)
    {
        return round($this->attributes['value'] = $value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }
}
