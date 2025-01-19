<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static where(string $string, int|string|null $id)
 */
class Basket extends Model
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
        'user_id',
        'completed',
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

    /**
     * Relationship with BasketItem.
     */
    public function items(): HasMany
    {
        return $this->hasMany(BasketItem::class);
    }

    /**
     * Relationship with User (optional).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
