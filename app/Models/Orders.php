<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Orders extends Model
{
    use HasFactory;

    protected $table = 'order';
    protected $primaryKey = 'id';
    protected $connection = 'mysql';

    protected $fillable = [
        'customer_name',
        'email',
        'phone'
    ];

    /**
     * @return BelongsToMany
     */
    public function cars(): BelongsToMany
    {
        return $this->belongsToMany(
            Cars::class,
            "order_cars",
            'cars_id',
            'orders_id',
            'id',
            'id',
        );
    }

    public function carsRelation()
    {
        return $this->belongsToMany(OrderCars::class, 'cars');
    }
}
