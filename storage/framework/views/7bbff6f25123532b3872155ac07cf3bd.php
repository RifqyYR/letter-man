<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo e(url('/')); ?>">
        <div class="sidebar-brand-icon">
            <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                viewBox="0 0 384 512">
                <style>
                    svg {
                        fill: #ffffff
                    }
                </style>
                <path
                    d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128z" />
            </svg>
        </div>
        <div class="sidebar-brand-text mx-3">TEST</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Beranda -->
    <li class="nav-item <?php echo e(Request::is('/') ? 'active' : ''); ?>">
        <a class="nav-link" href="<?php echo e(url('/')); ?>">
            <span>Beranda</span></a>
    </li>

    
    <li class="nav-item <?php echo e(Request::is('nota-dinas') ? 'active' : ''); ?>">
        <a class="nav-link" href="/nota-dinas">
            <span>Nota Dinas</span>
        </a>
    </li>

    
    <li class="nav-item <?php echo e(Request::is('berita-acara') ? 'active' : ''); ?>">
        <a class="nav-link" href="/berita-acara">
            <span>Berita Acara</span>
        </a>
    </li>

    
    <hr class="sidebar-divider">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->
<?php /**PATH C:\Users\muham\Documents\Programming\project\pelindo\e-agreement\resources\views/includes/sidebar.blade.php ENDPATH**/ ?>