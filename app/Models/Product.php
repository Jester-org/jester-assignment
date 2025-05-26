<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'tax_rate_id',
        'name',
        'description',
        'sku',
        'base_price',
        'vat',
        'unit_price',
        'reorder_threshold',
    ];

    protected $appends = ['promotion_details'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function taxRate()
    {
        return $this->belongsTo(TaxRate::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class);
    }

    public function promotions()
    {
        return $this->morphToMany(Promotion::class, 'promotable', 'promotable');
    }

    public function getApplicablePromotions()
    {
        $today = now()->startOfDay();
        $productPromotions = $this->promotions()
            ->where('is_active', true)
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->get();

        $categoryPromotions = Promotion::whereHas('categories', function ($query) {
            $query->where('categories.id', $this->category_id);
        })
            ->where('is_active', true)
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->get();

        return $productPromotions->merge($categoryPromotions)->sortByDesc(function ($promotion) {
            return $promotion->products()->where('products.id', $this->id)->exists() ? 1 : 0;
        });
    }

    public function calculateDiscountedPrice()
    {
        $promotions = $this->getApplicablePromotions();
        $price = $this->base_price;

        foreach ($promotions as $promotion) {
            if ($promotion->type === 'discount' && $promotion->is_active) {
                if ($promotion->discount_type === 'fixed') {
                    $price = max(0, $price - $promotion->discount_value);
                } elseif ($promotion->discount_type === 'percentage') {
                    $price = max(0, $price * (1 - $promotion->discount_value / 100));
                }
            }
        }

        return $price;
    }

    public function getPromotionDetailsAttribute()
    {
        $promotions = $this->getApplicablePromotions();
        if ($promotions->isEmpty()) {
            return 'No active promotions';
        }

        $details = [];
        foreach ($promotions as $promotion) {
            if ($promotion->type === 'discount') {
                $discount = $promotion->discount_type === 'fixed'
                    ? 'Fixed: $' . number_format($promotion->discount_value, 2)
                    : $promotion->discount_value . '%';
                $details[] = "Discount: $discount (Active until {$promotion->end_date->format('Y-m-d')})";
            } else {
                $freeItem = $promotion->freeItem->name ?? 'N/A';
                $details[] = "Buy and Get Free: $freeItem (Active until {$promotion->end_date->format('Y-m-d')})";
            }
        }

        return implode('; ', $details);
    }
}