<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  @php
    $user = auth()->user();
    $user?->loadMissing('part', 'typeAccount', 'branch');

    $isDashboard  = request()->is('dashboard');
    $isFinance    = request()->is('finance*') || request()->is('salary*') || request()->is('expenses*');
    $isPurchasing = request()->is('arranges*') || request()->is('shipments*') || request()->is('customers*') || request()->is('nhap-hang*');
    $isSales      = request()->is('ban-hang*') || request()->is('sales*');
    $isHr         = request()->is('recruitments*') || request()->is('facilities*') || request()->is('timesheets*');
    $isConfig     = request()->is('salary-mechanism*') || request()->is('settings*');
    $isAccount    = request()->is('users') || request()->is('account') || request()->is('chi-nhanh*');

    // Phân quyền
    $hasNoRole    = !$user?->typeAccount && !$user?->part; // user chưa cấu hình role → mặc định thấy hết
    $canWarehouse = $hasNoRole || ($user?->canAccessWarehouse() ?? false);
    $canSales     = $hasNoRole || ($user?->canAccessSales()     ?? false);
    $canFinance   = $hasNoRole || ($user?->isSuperAdminOrAdmin() ?? false);
    $canHR        = $hasNoRole || ($user?->isSuperAdminOrAdmin() ?? false);
    $isAdminOrMgr = $hasNoRole || ($user?->isSuperAdminOrAdmin() ?? false);
  @endphp

  <!-- Brand Logo -->
  <a href="/" class="brand-link d-flex align-items-center">
    <img
      src="{{ asset('Adminlte/dist/img/logo coffe M&T.jpg') }}"
      alt="CRM Chuỗi Cafe"
      class="brand-image img-circle elevation-3"
      style="opacity: .9"
    >
    <div class="d-flex flex-column">
      <span class="brand-text font-weight-semibold">CRM Chuỗi Cafe</span>
      @if($user?->branch)
        <small class="text-light" style="font-size: 11px; letter-spacing: .5px;">
          {{ $user->branch->name }}
        </small>
      @else
        <small class="text-light" style="font-size: 11px; letter-spacing: .5px;">Quản trị vận hành</small>
      @endif
    </div>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center border-bottom border-secondary">
      <div class="image">
        <img src="{{ asset('Adminlte/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="/dashboard" class="d-block font-weight-semibold">{{ $user?->name ?? 'Hệ thống quản trị' }}</a>
        <small class="text-muted">
          {{ $user?->isSuperAdmin() ? 'Ban Giám Đốc' : ($user?->isAdmin() ? 'Quản lý chi nhánh' : ($user?->getPartName() ?: 'Chuỗi cửa hàng cafe')) }}
          @if($user?->isSuperAdmin()) <span class="badge badge-danger ml-1" style="font-size:9px">S.Admin</span>
          @elseif($user?->isAdmin()) <span class="badge badge-warning ml-1" style="font-size:9px">Admin</span>
          @endif
        </small>
      </div>
    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline mb-2">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Tìm nhanh..." aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar" type="button">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-header text-uppercase">Điều hướng</li>

        <li class="nav-item">
          <a href="/dashboard" class="nav-link {{ $isDashboard ? 'active' : '' }}">
            <i class="nav-icon fas fa-chart-line"></i>
            <p>Tổng quan</p>
          </a>
        </li>

        <li class="nav-header text-uppercase">Nghiệp vụ</li>

        {{-- TÀI CHÍNH - chỉ Admin/Quản lý/Kế toán --}}
        @if($canFinance)
        <li class="nav-item {{ $isFinance ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $isFinance ? 'active' : '' }}">
            <i class="nav-icon fas fa-wallet"></i>
            <p>
              Tài chính
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Bảng quyết toán</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Bảng lương</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Quản lý chi tiêu</p>
              </a>
            </li>
          </ul>
        </li>
        @endif

        {{-- NHẬP HÀNG - Kho/Vận hành/Admin/Quản lý --}}
        @if($canWarehouse)
        <li class="nav-item {{ $isPurchasing ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $isPurchasing ? 'active' : '' }}">
            <i class="nav-icon fas fa-truck-loading"></i>
            <p>
              Nhập hàng
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('nhaphang.list') }}" class="nav-link {{ request()->is('nhap-hang') || request()->is('nhap-hang/') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Xếp lô hàng</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('nhaphang.don-nhap') }}" class="nav-link {{ request()->is('nhap-hang/don-nhap*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Quản lý đơn nhập</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('nhaphang.nha-cung-cap') }}" class="nav-link {{ request()->is('nhap-hang/nha-cung-cap*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Nhà cung cấp</p>
              </a>
            </li>
          </ul>
        </li>
        @endif

        {{-- BÁN HÀNG - Kinh doanh/Sale/Admin/Quản lý --}}
        @if($canSales)
        <li class="nav-item {{ $isSales ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $isSales ? 'active' : '' }}">
            <i class="nav-icon fas fa-cash-register"></i>
            <p>
              Bán hàng
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('banhang.thuc-uong') }}" class="nav-link {{ request()->is('ban-hang/thuc-uong*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Menu thức uống</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('banhang.ton-kho') }}" class="nav-link {{ request()->is('ban-hang/ton-kho*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Tồn kho</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('banhang.giao-dich') }}" class="nav-link {{ request()->is('ban-hang/giao-dich*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Giao dịch bán hàng</p>
              </a>
            </li>
          </ul>
        </li>
        @endif

        {{-- NHÂN SỰ - HR/Admin/Quản lý --}}
        @if($canHR)
        <li class="nav-item {{ $isHr ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $isHr ? 'active' : '' }}">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>
              Nhân sự
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Tuyển dụng</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Cơ sở vật chất</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Chấm công</p>
              </a>
            </li>
          </ul>
        </li>
        @endif

        {{-- HỆ THỐNG - chỉ Admin/Quản lý --}}
        @if($isAdminOrMgr)
        <li class="nav-header text-uppercase">Hệ thống</li>

        <li class="nav-item {{ request()->is('chi-nhanh*') ? 'active' : '' }}">
          <a href="{{ route('branches.list') }}" class="nav-link {{ request()->is('chi-nhanh*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-store-alt"></i>
            <p>Chi nhánh</p>
          </a>
        </li>

        <li class="nav-item {{ $isConfig ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $isConfig ? 'active' : '' }}">
            <i class="nav-icon fas fa-sliders-h"></i>
            <p>
              Cấu hình
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Cơ chế lương</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item {{ $isAccount ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $isAccount ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-shield"></i>
            <p>
              Tài khoản
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/account" class="nav-link {{ request()->is('account') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Hệ thống tài khoản</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/users" class="nav-link {{ request()->is('users') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Danh sách nhân sự</p>
              </a>
            </li>
          </ul>
        </li>
        @endif

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
