<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Apartment
 *
 * @property int $id
 * @property string|null $number
 * @property int|null $user_id
 * @property string|null adult_number
 * @property string|null children_number

 */
class Apartment extends Model
{
    protected $table = 'apartments';
    protected $casts = [
        'user_id' => 'int',
    ];

    protected $fillable = [
        'account_id',
        'number',
        'user_id',
        'adult_number',
        'children_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function scopeTenant(Builder $query): Builder
    {
        return $query->where('account_id', optional(Filament::getTenant())->id);
    }
}
