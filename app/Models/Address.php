<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Address
 *
 * @property int $id
 * @property int $city_id
 * @property string $street
 * @property string $number
 * @property string $neighborhood
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address query()
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereNeighborhood($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\City $city
 * @property-read mixed $full_address
 */
class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'street',
        'number',
        'neighborhood'
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function getFullAddressAttribute()
    {
        return "Endereço: {$this->street}, Nº: {$this->number}, {$this->neighborhood} - {$this->city->name}/{$this->city->uf}";
    }
}
