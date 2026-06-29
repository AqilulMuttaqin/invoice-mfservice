<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="index.html">
            <span class="align-middle">MF Service</span>
        </a>
        <ul class="sidebar-nav">

            <li class="sidebar-header">Home</li>
            <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('dashboard') }}">
                    <i data-feather="layout"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="sidebar-header">Master Data</li>
            <li class="sidebar-item {{ request()->routeIs('device-types') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('device-types') }}">
                    <i data-feather="cpu"></i>
                    <span>Device Types</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('services') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('services') }}">
                    <i data-feather="briefcase"></i>
                    <span>Services</span>
                </a>
            </li>

            <li class="sidebar-header">Transactions</li>
            <li class="sidebar-item {{ request()->routeIs('invoices') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('invoices') }}">
                    <i data-feather="file-text"></i>
                    <span>Invoices</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('warranty-checks') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('warranty-checks') }}">
                    <i data-feather="shield"></i>
                    <span>Warranty Checks</span>
                </a>
            </li>

        </ul>
    </div>
</nav>
