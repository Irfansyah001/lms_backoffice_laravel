<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'author',
        'publisher',
        'publication_year',
        'stock',
        'shelf_location',
        'status',
        'created_by',
        'updated_by',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function isAvailable(): bool
    {
        return $this->status === 'tersedia' && $this->stock > 0;
    }

    public function syncAvailabilityStatus(): void
    {
        $this->status = $this->stock > 0 ? 'tersedia' : 'tidak_tersedia';
        $this->saveQuietly();
    }
}
