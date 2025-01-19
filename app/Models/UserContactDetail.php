<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static updateOrCreate(array $array, array $array1)
 */
class UserContactDetail extends Model
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
        'phone',
        'address_line_1',
        'city',
        'postal_code',
        'country',
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
     * The user that owns the contact details.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
