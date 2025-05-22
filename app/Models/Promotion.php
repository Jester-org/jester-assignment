<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',             // single product
        'discount_percentage',
        'start_date',
        'end_date',
    ];

    // Single product relation
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Many-to-many products
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_promotion')->withTimestamps();
    }

    // Many-to-many categories
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_promotion')->withTimestamps();
    }
}
