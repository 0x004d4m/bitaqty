<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
