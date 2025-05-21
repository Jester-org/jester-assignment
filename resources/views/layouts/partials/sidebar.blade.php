<aside class="sidebar">
    <div class="sidebar-brand"><a href="/home">{{ session()->get('name', 'Guest') }}</a></div>
    <ul>
        <li>
            <a href="#">
                <i class="fas fa-tachometer-alt"></i> Dashboard
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="/home" class="{{ Request::is('home') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="/reports" class="{{ Request::is('reports*') ? 'active' : '' }}"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li><a href="/audit-logs" class="{{ Request::is('audit-logs*') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> Audit Logs</a></li>
            </ul>
        </li>
        <li>
            <a href="#">
                <i class="fas fa-users"></i> Users & Roles
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="/users" class="{{ Request::is('users*') ? 'active' : '' }}"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="/roles" class="{{ Request::is('roles*') ? 'active' : '' }}"><i class="fas fa-user-shield"></i> Roles</a></li>
                <li><a href="/permissions" class="{{ Request::is('permissions*') ? 'active' : '' }}"><i class="fas fa-key"></i> Permissions</a></li>
                <li><a href="/customers" class="{{ Request::is('customers*') ? 'active' : '' }}"><i class="fas fa-user-friends"></i> Customers</a></li>
            </ul>
        </li>
        <li>
            <a href="#">
                <i class="fas fa-shop"></i> Inventory Management
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="/products" class="{{ Request::is('products*') ? 'active' : '' }}"><i class="fas fa-shop"></i> Products</a></li>
                <li><a href="/categories" class="{{ Request::is('categories*') ? 'active' : '' }}"><i class="fas fa-box"></i> Categories</a></li>
                <li><a href="/inventories" class="{{ Request::is('inventories*') ? 'active' : '' }}"><i class="fas fa-warehouse"></i> Inventories</a></li>
                <li><a href="/inventory-adjustments" class="{{ Request::is('inventory-adjustments*') ? 'active' : '' }}"><i class="fas fa-tools"></i> Inventory Adjustments</a></li>
                <li><a href="/batches" class="{{ Request::is('batches*') ? 'active' : '' }}"><i class="fas fa-cubes"></i> Batches</a></li>
                <li><a href="/expiry_dates" class="{{ Request::is('expiry_dates*') ? 'active' : '' }}"><i class="fas fa-clock"></i> Expiry Dates</a></li>
            </ul>
        </li>
        <li>
            <a href="#">
                <i class="fas fa-shopping-cart"></i> Sales & Purchases
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="/sales" class="{{ Request::is('sales*') ? 'active' : '' }}"><i class="fas fa-shopping-cart"></i> Sales</a></li>
                <li><a href="/sale-items" class="{{ Request::is('sale-items*') ? 'active' : '' }}"><i class="fas fa-list"></i> Sale Items</a></li>
                <li><a href="/purchases" class="{{ Request::is('purchases*') ? 'active' : '' }}"><i class="fas fa-truck"></i> Purchases</a></li>
                <li><a href="/purchase-items" class="{{ Request::is('purchase-items*') ? 'active' : '' }}"><i class="fas fa-list-alt"></i> Purchase Items</a></li>
                <li><a href="/suppliers" class="{{ Request::is('suppliers*') ? 'active' : '' }}"><i class="fas fa-industry"></i> Suppliers</a></li>
                <li><a href="/promotions" class="{{ Request::is('promotions*') ? 'active' : '' }}"><i class="fas fa-tag"></i> Promotions</a></li>
            </ul>
        </li>
        <li>
            <a href="#">
                <i class="fas fa-money-bill"></i> Payments
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="/transactions" class="{{ Request::is('transactions*') ? 'active' : '' }}"><i class="fas fa-exchange-alt"></i> Transactions</a></li>
                <li><a href="/payments" class="{{ Request::is('payments*') ? 'active' : '' }}"><i class="fas fa-money-bill"></i> Payments</a></li>
                <li><a href="/payment-methods" class="{{ Request::is('payment-methods*') ? 'active' : '' }}"><i class="fas fa-credit-card"></i> Payment Methods</a></li>
                <li><a href="/tax-rates" class="{{ Request::is('tax-rates*') ? 'active' : '' }}"><i class="fas fa-percentage"></i> Tax Rates</a></li>
            </ul>
        </li>
        <li>
            <a href="#">
                <i class="fas fa-calendar-alt"></i> HR Management
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="/attendances" class="{{ Request::is('attendances*') ? 'active' : '' }}"><i class="fas fa-calendar-check"></i> Attendances</a></li>
                <li><a href="/leaves" class="{{ Request::is('leaves*') ? 'active' : '' }}"><i class="fas fa-umbrella-beach"></i> Leaves</a></li>
                <li><a href="/leave-types" class="{{ Request::is('leave-types*') ? 'active' : '' }}"><i class="fas fa-list-ul"></i> Leave Types</a></li>
            </ul>
        </li>
    </ul>
</aside>

<style>
   /* Sidebar Styles */
    .sidebar {
        width: 250px;
        background: linear-gradient(135deg, #333 0%, #222 100%);
        color: white;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        transition: transform 0.4s cubic-bezier(0.215, 0.61, 0.355, 1);
        z-index: 2000; /* Match mobile z-index */
        transform: translateX(-100%);
        overflow-y: auto;
    }

    .sidebar.expanded {
        transform: translateX(0);
    }

    .sidebar-brand {
        padding: 20px;
        font-size: 1.5rem;
        font-weight: bold;
        text-align: center;
        z-index: 2001; /* Above sidebar */
    }

    .sidebar ul {
        list-style: none;
    }

    .sidebar ul li a {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        color: white;
        text-decoration: none;
        transition: background 0.3s ease;
        position: relative;
        z-index: 2001; /* Ensure links are clickable */
        pointer-events: auto;
    }

    .sidebar ul li a.active {
        background: rgb(36, 1, 1);
    }

    .sidebar ul li a:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .sidebar ul li a i {
        margin-right: 10px;
    }

    .dropdown-icon {
        margin-left: auto;
        transition: transform 0.3s ease;
    }

    .sidebar ul li.open .dropdown-icon {
        transform: rotate(180deg);
    }

    .submenu {
        max-height: 0;
        overflow: hidden;
        background: rgba(0, 0, 0, 0.2);
        padding-left: 20px;
        opacity: 0;
        transform: translateY(-10px);
        transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1),
                    opacity 0.3s ease,
                    transform 0.3s ease;
        z-index: 2001; /* Ensure submenu is above overlay */
    }

    .sidebar ul li.open .submenu {
        max-height: 200px;
        opacity: 1;
        transform: translateY(0);
    }

    .submenu li a {
        padding: 10px 20px;
        font-size: 0.9rem;
        z-index: 2001;
        position: relative;
        pointer-events: auto;
    }
</style>
