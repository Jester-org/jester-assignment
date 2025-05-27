<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\User;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\Report;
use App\Models\TaxRate;
use App\Models\Promotion;
use App\Models\Category;
use App\Models\Inventory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        'payment_method_id',
        'total_amount',
        'sale_date',
    ];

    protected $casts = [
        'sale_date' => 'datetime',
    ];    
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, SaleItem::class);
    }

    public function paymentMethods()
    {
        return $this->hasManyThrough(PaymentMethod::class, Payment::class);
    }

    public function taxRates()
    {
        return $this->hasManyThrough(TaxRate::class, Product::class);
    }

    public function promotions()
    {
        return $this->hasManyThrough(Promotion::class, Product::class);
    }

    public function inventories()
    {
        return $this->hasManyThrough(Inventory::class, Product::class);
    }

    public function categories()
    {
        return $this->hasManyThrough(Category::class, Product::class);
    }
}