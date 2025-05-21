<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ExpiryDate extends Model
{
    use HasFactory;
    protected $fillable = ['batch_id', 'expiry_date', 'notes'];
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}

