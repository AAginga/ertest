<template>
<!-- Navbar -->
<div class="app">
  <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
      <span class="brand-text font-weight-light">ER Test</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <router-link to="/dashboard" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt blue"></i>
              <p>
                Dashboard
              </p>
            </router-link>
          </li>   
          <li class="nav-item">
            <router-link to="/suppliers" class="nav-link">
              <i class="fas fa-users nav-icon"></i>
              <p>
                 Suppliers
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/products" class="nav-link">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                 Products
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/orders" class="nav-link">
              <i class="nav-icon fas fa-user orange"></i>
              <p>
                 Orders
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/logout">
                  <i class="nav-icon fas fa-power-off red"></i>
                  <p>
                    Log Out
                  </p>
            </router-link>
          </li>  
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <router-view></router-view>
        <!-- set progressbar -->
        <vue-progress-bar></vue-progress-bar>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2019 <a href=""></a>.</strong> All rights reserved.
  </footer>

</div>


</template>