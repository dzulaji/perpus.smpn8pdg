   <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

       <!-- Sidebar - Brand -->
       <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/admin">
           <div class="sidebar-brand-icon rotate-n-15">
               <i class="bi bi-book-fill"></i>
           </div>
           <div class="sidebar-brand-text mx-3">PANEL ADMIN</div>
       </a>

       <!-- Divider -->
       <hr class="sidebar-divider my-0">

       <!-- Nav Item - Dashboard -->
       <li class="nav-item {{ Request::is('admin') ? 'active' : '' }}">
           <a class="nav-link" href="/admin">
               <i class="bi bi-layout-sidebar-inset"></i>
               <span>Dashboard</span></a>
       </li>

       <!-- Nav Item - Books -->
       @can('admin')
           <li class="nav-item {{ Request::is('admin/books*') ? 'active' : '' }}">
               <a class="nav-link" href="/admin/books">
                   <i class="bi bi-book-half"></i>
                   <span>Buku</span></a>
           </li>
       @endcan

       <!-- Nav Item - Bookings -->
       <li class="nav-item {{ Request::is('admin/booking*') ? 'active' : '' }}">
           <a class="nav-link" href="/admin/booking">
               <i class="bi bi-journal-bookmark-fill"></i>
               <span>Peminjaman</span></a>
       </li>

       <!-- Nav Item - Users -->
       @can('admin')
           <li class="nav-item {{ Request::is('admin/users') ? 'active' : '' }}">
               <a class="nav-link" href="/admin/users">
                   <i class="bi bi-people-fill"></i>
                   <span>Pengguna</span></a>
           </li>
       @endcan

       <!-- Nav Item - Questions -->
       @can('admin')
           <li class="nav-item {{ Request::is('admin/kriteria*') ? 'active' : '' }}">
               <a class="nav-link" href="/admin/kriteria">
                   <i class="bi bi-question-circle"></i>
                   <span>Pertanyaan dan Kriteria</span></a>
           </li>
       @endcan

       <!-- Nav Item - Criteria
       @can('admin')
           <li class="nav-item {{ Request::is('admin/calculation*') ? 'active' : '' }}">
               <a class="nav-link" href="/admin/calculation">
                   <i class="bi bi-list-check"></i>
                   <span>Calculation</span></a>
           </li>
       @endcan  -->

       <!-- Divider -->
       <hr class="sidebar-divider d-none d-md-block">

       <!-- Sidebar Toggler (Sidebar) -->
       <div class="text-center d-none d-md-inline">
           <button class="rounded-circle border-0" id="sidebarToggle"></button>
       </div>

   </ul>
