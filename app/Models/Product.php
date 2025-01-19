<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static whereIn(string $string, $pluck)
 * @method static find(int|string $productId)
 *
 * @property string $id
 * @property int $stock
 */
class Product extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'description',
        'stock',
        'price',
    ];

    /**
     * Generates UUID for id.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Uuid::uuid4();
            }
        });
    }
}
