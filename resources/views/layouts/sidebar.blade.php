<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
      
        
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            @if (auth()->user()->level == 1)
            {{-- Admin Full Access --}}
            <li class="header">MASTER</li>
            <li>
                <a href="{{ route('kategori.index') }}">
                    <i class="fa fa-cube"></i> <span>Category</span>
                </a>
            </li>
            <li>
                <a href="{{ route('produk.index') }}">
                    <i class="fa fa-cubes"></i> <span>Product</span>
                </a>
            </li>
            <li>
                <a href="{{ route('member.index') }}">
                    <i class="fa fa-id-card"></i> <span>Member</span>
                </a>
            </li>
            <li class="header">TRANSACTION</li>
            <li>
                <a href="{{ route('pengeluaran.index') }}">
                    <i class="fa fa-money"></i> <span>Expenses</span>
                </a>
            </li>
            <!-- <li>
                <a href="{{ route('pembelian.index') }}">
                    <i class="fa fa-download"></i> <span>Purchase</span>
                </a>
            </li> -->
            <li>
                <a href="{{ route('penjualan.index') }}">
                    <i class="fa fa-dollar"></i> <span>Sales List</span>
                </a>
            </li>
            <li>
                <a href="{{ route('transaksi.baru') }}">
                    <i class="fa fa-cart-plus"></i> <span>New Transaction</span>
                </a>
            </li>
            <li>
                <a href="{{ route('transaksi.aktif') }}">
                    <i class="fa fa-cart-arrow-down"></i> <span>Active Transaction</span>
                </a>
            </li>
            <li class="header">REPORT</li>
            <li class="treeview {{ request()->is('laporan*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-pie-chart"></i> <span>Reports</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ request()->is('laporan') ? 'active' : '' }}">
                        <a href="{{ route('laporan.hub') }}"><i class="fa fa-circle-o"></i> Reports Hub</a>
                    </li>
                    <li class="{{ request()->is('laporan/income*') ? 'active' : '' }}">
                        <a href="{{ route('laporan.index') }}"><i class="fa fa-circle-o"></i> Income</a>
                    </li>
                    <li class="{{ request()->is('laporan/sales*') ? 'active' : '' }}">
                        <a href="{{ route('laporan.sales') }}"><i class="fa fa-circle-o"></i> Sales</a>
                    </li>
                    <li class="{{ request()->is('laporan/expenses*') ? 'active' : '' }}">
                        <a href="{{ route('laporan.expenses') }}"><i class="fa fa-circle-o"></i> Expenses</a>
                    </li>
                    <li class="{{ request()->is('laporan/products*') ? 'active' : '' }}">
                        <a href="{{ route('laporan.products') }}"><i class="fa fa-circle-o"></i> Products</a>
                    </li>
                </ul>
            </li>
            <li class="header">SYSTEM</li>
            <li>
                <a href="{{ route('user.index') }}">
                    <i class="fa fa-users"></i> <span>User</span>
                </a>
            </li>
            <li>
                <a href="{{ route("setting.index") }}">
                    <i class="fa fa-cogs"></i> <span>Settings</span>
                </a>
            </li>
            @elseif (auth()->user()->level == 2)
            {{-- Manager Access --}}
            <li class="header">MANAGER MENU</li>
            
            <li class="{{ request()->is('dashboard*') ? 'active' : '' }}">
                
            </li>
            <li class="{{ request()->is('transaksi/baru*') ? 'active' : '' }}">
                <a href="{{ route('transaksi.baru') }}">
                    <i class="fa fa-cart-plus"></i> <span>New Transaction</span>
                </a>
            </li>
            <li class="{{ request()->is('transaksi/aktif*') ? 'active' : '' }}">
                <a href="{{ route('transaksi.aktif') }}">
                    <i class="fa fa-refresh"></i> <span>Active Transactions</span>
                </a>
            </li>
            <li class="{{ request()->is('penjualan/history*') ? 'active' : '' }}">
                <a href="{{ route('penjualan.history') }}">
                    <i class="fa fa-history"></i> <span>My Sales</span>
                </a>
            </li>
            <li class="{{ request()->is('produk*') ? 'active' : '' }}">
                <a href="{{ route('produk.index') }}">
                    <i class="fa fa-cubes"></i> <span>View Products</span>
                </a>
            </li>
            <li class="{{ request()->is('kategori*') ? 'active' : '' }}">
                <a href="{{ route('kategori.index') }}">
                    <i class="fa fa-cube"></i> <span>View Categories</span>
                </a>
            </li>
            <li class="treeview {{ request()->is('laporan*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-pie-chart"></i> <span>Reports</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ request()->is('laporan') ? 'active' : '' }}">
                        <a href="{{ route('laporan.hub') }}"><i class="fa fa-circle-o"></i> Reports Hub</a>
                    </li>
                    <li class="{{ request()->is('laporan/income*') ? 'active' : '' }}">
                        <a href="{{ route('laporan.index') }}"><i class="fa fa-circle-o"></i> Income</a>
                    </li>
                    <li class="{{ request()->is('laporan/sales*') ? 'active' : '' }}">
                        <a href="{{ route('laporan.sales') }}"><i class="fa fa-circle-o"></i> Sales</a>
                    </li>
                    <li class="{{ request()->is('laporan/expenses*') ? 'active' : '' }}">
                        <a href="{{ route('laporan.expenses') }}"><i class="fa fa-circle-o"></i> Expenses</a>
                    </li>
                    <li class="{{ request()->is('laporan/products*') ? 'active' : '' }}">
                        <a href="{{ route('laporan.products') }}"><i class="fa fa-circle-o"></i> Products</a>
                    </li>
                </ul>
            </li>
            @else
            {{-- Cashier Access --}}
            <li class="header">CASHIER MENU</li>
            <li class="{{ request()->is('dashboard*') ? 'active' : '' }}">
                
            </li>
            <li class="{{ request()->is('transaksi/baru*') ? 'active' : '' }}">
                <a href="{{ route('transaksi.baru') }}">
                    <i class="fa fa-cart-plus"></i> <span>New Transaction</span>
                </a>
            </li>
            <li class="{{ request()->is('transaksi/aktif*') ? 'active' : '' }}">
                <a href="{{ route('transaksi.aktif') }}">
                    <i class="fa fa-refresh"></i> <span>Active Transaction</span>
                </a>
            </li>
            <li class="{{ request()->is('penjualan/history*') ? 'active' : '' }}">
                <a href="{{ route('penjualan.history') }}">
                    <i class="fa fa-history"></i> <span>My Sales</span>
                </a>
            </li>
            @endif
            <li class="header">ACCOUNT</li>
            @if (auth()->check())
            <li class="{{ request()->is('chat*') ? 'active' : '' }}">
                <a href="{{ route('chat.index') }}">
                    <i class="fa fa-comments"></i> <span>Chat</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.profil') }}">
                    <i class="fa fa-user"></i> <span>Profile</span>
                </a>
            </li>
            @endif
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-power-off"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside><!-- visit "codeastro" for more projects! -->