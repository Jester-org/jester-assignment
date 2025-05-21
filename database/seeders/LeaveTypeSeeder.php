<?php
namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $leaveTypes = [
            ['name' => 'Annual Leave', 'description' => 'Paid leave for vacation or personal time.'],
            ['name' => 'Sick Leave', 'description' => 'Leave for medical reasons.'],
            ['name' => 'Maternity Leave', 'description' => 'Leave for childbirth and recovery.'],
            ['name' => 'Paternity Leave', 'description' => 'Leave for new fathers.'],
            ['name' => 'Unpaid Leave', 'description' => 'Leave without pay for personal reasons.'],
        ];

        foreach ($leaveTypes as $type) {
            LeaveType::create($type);
        }
    }
}