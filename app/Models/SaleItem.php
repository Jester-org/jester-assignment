<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sale;
use App\Models\Product;
use App\Models\TaxRate;
use App\Models\Promotion;
use App\Models\Category;
use App\Models\Inventory;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
        'tax_rate_id',
        'promotion_id',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function taxRate()
    {
        return $this->belongsTo(TaxRate::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function category()
    {
        return $this->hasOneThrough(
            Category::class,
            Product::class,
            'id',          // Foreign key on Product
            'id',          // Foreign key on Category
            'product_id',  // Local key on SaleItem
            'category_id'  // Local key on Product
        );
    }

    public function inventory()
    {
        return $this->hasOneThrough(
            Inventory::class,
            Product::class,
            'id',          // Foreign key on Product
            'product_id',  // Foreign key on Inventory
            'product_id',  // Local key on SaleItem
            'id'           // Local key on Product
        );
    }
}
