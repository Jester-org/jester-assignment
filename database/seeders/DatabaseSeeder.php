<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            CustomerSeeder::class,
            CategorySeeder::class,
            TaxRateSeeder::class,
            ProductSeeder::class,
            PromotionSeeder::class,
            InventorySeeder::class,
            InventoryAdjustmentSeeder::class,
            SupplierSeeder::class,
            PurchaseSeeder::class,
            PurchaseItemSeeder::class,
            SaleSeeder::class,
            SaleItemSeeder::class,
            TransactionSeeder::class,
            PaymentMethodSeeder::class,
            PaymentSeeder::class,
            ReportSeeder::class,
            AuditLogSeeder::class,
            BatchSeeder::class,
            ExpiryDateSeeder::class,
            AttendanceSeeder::class,
            LeaveTypeSeeder::class,
            LeaveSeeder::class,
        ]);
    }
}

