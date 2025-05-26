<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'discount_type',
        'discount_value',
        'free_item_id',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function products()
    {
        return $this->morphedByMany(Product::class, 'promotable', 'promotable');
    }

    public function categories()
    {
        return $this->morphedByMany(Category::class, 'promotable', 'promotable');
    }

    public function freeItem()
    {
        return $this->belongsTo(Product::class, 'free_item_id');
    }
}