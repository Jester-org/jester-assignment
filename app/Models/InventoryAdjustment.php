<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class InventoryAdjustment extends Model
{
    use HasFactory;
    protected $fillable = ['inventory_id', 'adjustment_type', 'quantity', 'reason', 'adjustment_date'];
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}

