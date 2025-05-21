<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Transaction extends Model
{
    use HasFactory;
    protected $fillable = ['sale_id', 'amount', 'transaction_date', 'status'];
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}

