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
        'barcode',
        'unit_price',
        'reorder_threshold'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function taxRate()
    {
        return $this->belongsTo(TaxRate::class);
    }

    public function promotions()
    {
        return $this->belongsToMany(Promotion::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'product_supplier')
                   ->withTimestamps();
    }
}