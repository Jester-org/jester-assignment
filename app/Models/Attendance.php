<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'check_in', 'check_out', 'status', 'leave_type_id'];

    // Cast dates properly
    protected $dates = ['check_in', 'check_out'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Optional: if attendance tracks leave type (like sick, vacation)
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }
}
