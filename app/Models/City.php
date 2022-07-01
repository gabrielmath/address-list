<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\City
 *
 * @property int $id
 * @property int $ibge_id
 * @property string $name
 * @property int $uf_ibge_id
 * @property string $uf
 * @property string $uf_full_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City query()
 * @method static \Illuminate\Database\Eloquent\Builder|City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereIbgeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereUf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereUfFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereUfIbgeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Address[] $addresses
 * @property-read int|null $addresses_count
 */
class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'ibge_id',
        'name',
        'uf_ibge_id',
        'uf',
        'uf_full_name'
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class, 'city_id');
    }
}
