<?php

namespace App\Models;

use App\Enums\StatusEnum;
use App\Events\UpdateNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Number extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'account_id',
        'name',
        'phone_number',
        'status'
    ];

    protected $casts = [
        'status' => StatusEnum::class
    ];

    protected static function booted()
    {
        static::updated(function ($number) {
            event(new UpdateNumber($number));
        });
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function isActive(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === StatusEnum::ACTIVE,
        )->shouldCache();
    }

    public function evolutionInstanceName(): Attribute
    {
        return Attribute::make(
            get: fn() => "{$this->account->id}-{$this->phone_number}",
        )->shouldCache();
    }
}
