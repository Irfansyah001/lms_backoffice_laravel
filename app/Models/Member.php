<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'status',
        'created_by',
        'updated_by',
    ];

    protected static function booted(): void
    {
        static::created(function (Member $member) {
            if (blank($member->member_code)) {
                $member->member_code = 'AGT-'.str_pad((string) $member->id, 3, '0', STR_PAD_LEFT);
                $member->saveQuietly();
            }
        });
    }

    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    public function activeBorrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class)->where('status', 'dipinjam');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
