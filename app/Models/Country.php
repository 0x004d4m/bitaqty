<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;
    use HasTranslations;

    protected $table = 'countries';
    protected $translatable = ['name'];
    protected $fillable = ['name'];

    public function states()
    {
        return $this->hasMany(State::class);
    }

    public function currencies()
    {
        return $this->hasMany(Currency::class);
    }
}
