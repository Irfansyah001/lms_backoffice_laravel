<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'book_id',
        'borrowed_at',
        'due_date',
        'returned_at',
        'status',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'borrowed_at' => 'date',
            'due_date' => 'date',
            'returned_at' => 'date',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function isReturned(): bool
    {
        return in_array($this->status, ['dikembalikan', 'terlambat'], true);
    }

    public function isCanceled(): bool
    {
        return $this->status === 'dibatalkan';
    }

    public function isClosed(): bool
    {
        return in_array($this->status, ['dikembalikan', 'terlambat', 'dibatalkan'], true);
    }
}
