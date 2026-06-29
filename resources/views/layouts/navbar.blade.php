<nav class="navbar navbar-expand navbar-light navbar-bg">
    <a class="sidebar-toggle js-sidebar-toggle">
        <i class="hamburger align-self-center"></i>
    </a>
    <div class="navbar-collapse collapse">
        <ul class="navbar-nav navbar-align">
            <li class="nav-item dropdown">
                <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                    <i class="align-middle" data-feather="settings"></i>
                </a>
                <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                    <span class="avatar rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center me-1"
                        style="width: 36px; height: 36px; font-size: 0.8rem; font-weight: 700;"
                        data-profile-initials data-profile-name="{{ Auth::user()->name }}">
                        ?
                    </span>
                    <span class="text-dark">{{ Auth::user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end pt-0">
                    <div class="message-body">
                        <div class="d-flex align-items-center p-3 border-bottom">
                            <span class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center flex-shrink-0"
                                style="width: 40px; height: 40px; font-size: 0.85rem; font-weight: 700;"
                                data-profile-initials data-profile-name="{{ Auth::user()->name }}">
                                ?
                            </span>
                            <div class="ms-3">
                                <p class="mb-1 fw-bold">{{ Auth::user()->name }}</p>
                                <p class="mb-0 text-muted small">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="btn btn-outline-danger mx-3 mt-2 d-block">Logout</a>
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-profile-initials]').forEach(function (avatar) {
            var name = (avatar.getAttribute('data-profile-name') || '').trim();
            var initials = name
                .split(/\s+/)
                .filter(Boolean)
                .slice(0, 2)
                .map(function (part) {
                    return part.charAt(0).toUpperCase();
                })
                .join('');

            avatar.textContent = initials || '?';
        });
    });
</script>
