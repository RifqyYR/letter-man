<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->
    @php
        $notAllowedRoute = ['home', 'officialmemo.create.show', 'officialmemo.detail.show', 'edit', 'user', 'officialmemo.edit.show', 'news.create.show', 'news.detail.show', 'documentauthorizationletter.create.show', 'documentauthorizationletter.detail.show', 'documentauthorizationletter.edit.show', 'outgoingmail.create.show', 'outgoingmail.detail.show', 'outgoingmail.edit.show'];
    @endphp
    @if (!in_array(Route::currentRouteName(), $notAllowedRoute))
        <form action="" method="POST"
            class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
                <input type="text" class="form-control bg-light border-2 small"
                    placeholder="Cari berdasarkan judul dan nomor surat" aria-label="Search"
                    aria-describedby="basic-addon2" id="search" name="search">
            </div>
        </form>
    @endif

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                <img class="img-profile rounded-circle" src="{{ url('backend/img/undraw_profile.svg') }}">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                @if (Auth::user()->role != 0)
                    <a class="dropdown-item" href="{{ url('register') }}">
                        Tambah User
                    </a>
                @endif
                @if (Auth::user()->role != 0)
                    <a class="dropdown-item" href="{{ url('user') }}">
                        Kelola User
                    </a>
                    <div class="dropdown-divider"></div>
                @endif
                <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#logoutModal">
                    Keluar
                </a>
            </div>
        </li>
    </ul>
</nav>
<!-- End of Topbar -->
