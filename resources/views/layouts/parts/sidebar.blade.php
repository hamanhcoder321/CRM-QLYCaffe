<!-- Main Sidebar -->
<aside class="main-sidebar elevation-0" style="
  background: linear-gradient(180deg, #1a1f2e 0%, #1e2435 60%, #171c29 100%);
  border-right: 1px solid rgba(255,255,255,0.05);
  box-shadow: 4px 0 24px rgba(0,0,0,0.3);
">
  @php
    $user = auth()->user();
    $user?->loadMissing('part', 'typeAccount', 'branch');

    $isDashboard  = request()->is('dashboard');
    $isFinance    = request()->is('finance*') || request()->is('salary*') || request()->is('expenses*') || request()->is('tai-chinh*');
    $isPurchasing = request()->is('arranges*') || request()->is('shipments*') || request()->is('customers*') || request()->is('nhap-hang*');
    $isSales      = request()->is('ban-hang*') || request()->is('sales*');
    $isHr         = request()->is('recruitments*') || request()->is('facilities*') || request()->is('timesheets*') || request()->is('tuyen-dung*') || request()->is('*cham-cong*');
    $isConfig     = request()->is('salary-mechanism*') || request()->is('settings*');
    $isAccount    = request()->is('users') || request()->is('account') || request()->is('chi-nhanh*');

    $hasNoRole    = !$user?->typeAccount && !$user?->part;
    $canWarehouse = $hasNoRole || ($user?->canAccessWarehouse() ?? false);
    $canSales     = $hasNoRole || ($user?->canAccessSales()     ?? false);
    $canFinance   = $hasNoRole || ($user?->isSuperAdminOrAdmin() ?? false);
    $canHR        = $hasNoRole || ($user?->isSuperAdminOrAdmin() ?? false);
    $isAdminOrMgr = $hasNoRole || ($user?->isSuperAdminOrAdmin() ?? false);
  @endphp

  <!-- Brand Logo -->
  <a href="/dashboard" class="brand-link d-flex align-items-center px-3 py-3" style="
    border-bottom: 1px solid rgba(255,255,255,0.06);
    text-decoration: none;
    background: rgba(255,255,255,0.02);
  ">
    <div style="
      width: 36px; height: 36px;
      background: linear-gradient(135deg, #7c3aed, #4f46e5);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
      box-shadow: 0 4px 12px rgba(124,58,237,0.4);
    ">
      <i class="fas fa-coffee text-white" style="font-size: 0.9rem"></i>
    </div>
    <div class="ml-2">
      <div style="color:#fff;font-weight:700;font-size:0.95rem;line-height:1.2">M&T Cafe</div>
      <div style="color:rgba(255,255,255,0.4);font-size:0.7rem">
        @if($user?->branch){{ Str::limit($user->branch->name, 22) }}@else Hệ thống CRM @endif
      </div>
    </div>
  </a>

  <!-- Sidebar -->
  <div class="sidebar" style="overflow-y: auto; scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.1) transparent;">

    <!-- User panel -->
    <div class="mx-3 my-3 p-3" style="
      background: rgba(255,255,255,0.04);
      border: 1px solid rgba(255,255,255,0.07);
      border-radius: 14px;
    ">
      <div class="d-flex align-items-center gap-2">
        <div style="
          width: 40px; height: 40px;
          background: linear-gradient(135deg, #7c3aed, #2563eb);
          border-radius: 50%;
          display: flex; align-items: center; justify-content: center;
          color: #fff; font-weight: 700; font-size: 0.9rem;
          flex-shrink: 0;
        ">{{ strtoupper(substr($user?->name ?? 'U', 0, 2)) }}</div>
        <div style="min-width:0">
          <div style="color:#fff;font-weight:600;font-size:0.88rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
            {{ Str::limit($user?->name ?? 'Hệ thống', 20) }}
          </div>
          <div style="display:flex;align-items:center;gap:4px;margin-top:2px">
            @if($user?->isSuperAdmin())
              <span style="background:rgba(239,68,68,0.15);color:#f87171;border:1px solid rgba(239,68,68,0.25);border-radius:5px;padding:1px 7px;font-size:0.65rem;font-weight:600">S.ADMIN</span>
            @elseif($user?->isAdmin())
              <span style="background:rgba(245,158,11,0.15);color:#fbbf24;border:1px solid rgba(245,158,11,0.25);border-radius:5px;padding:1px 7px;font-size:0.65rem;font-weight:600">ADMIN</span>
            @else
              <span style="background:rgba(16,185,129,0.15);color:#34d399;border:1px solid rgba(16,185,129,0.25);border-radius:5px;padding:1px 7px;font-size:0.65rem;font-weight:600">NHÂN VIÊN</span>
            @endif
            <span style="color:rgba(255,255,255,0.35);font-size:0.7rem">{{ $user?->getPartName() ?: '' }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="px-2">
      <style>
        /* Sidebar nav styles */
        .sidebar-nav-label {
          color: rgba(255,255,255,0.3);
          font-size: 0.65rem;
          font-weight: 700;
          text-transform: uppercase;
          letter-spacing: 0.1em;
          padding: 12px 10px 4px;
          display: block;
        }
        .sidebar-nav-item { list-style: none; margin-bottom: 2px; }
        .sidebar-nav-link {
          display: flex;
          align-items: center;
          gap: 10px;
          padding: 9px 12px;
          border-radius: 10px;
          color: rgba(255,255,255,0.65) !important;
          font-size: 0.86rem;
          font-weight: 500;
          text-decoration: none !important;
          transition: all 0.18s ease;
          position: relative;
        }
        .sidebar-nav-link:hover {
          background: rgba(255,255,255,0.07) !important;
          color: rgba(255,255,255,0.95) !important;
        }
        .sidebar-nav-link.active-link {
          background: linear-gradient(135deg, rgba(124,58,237,0.25), rgba(79,70,229,0.15)) !important;
          color: #fff !important;
          border: 1px solid rgba(124,58,237,0.3);
        }
        .sidebar-nav-link.active-link .sidebar-nav-icon {
          color: #a78bfa !important;
        }
        .sidebar-nav-icon {
          width: 18px;
          text-align: center;
          font-size: 0.88rem;
          color: rgba(255,255,255,0.45);
          flex-shrink: 0;
          transition: color 0.18s;
        }
        .sidebar-nav-link:hover .sidebar-nav-icon { color: rgba(255,255,255,0.8); }
        .sidebar-arrow {
          margin-left: auto;
          font-size: 0.7rem;
          color: rgba(255,255,255,0.3);
          transition: transform 0.2s;
        }
        /* Submenu */
        .sidebar-submenu {
          list-style: none;
          padding: 2px 0 2px 30px;
          margin: 0;
          overflow: hidden;
          display: none;
        }
        .sidebar-submenu.open { display: block; }
        .sidebar-submenu-link {
          display: flex;
          align-items: center;
          gap: 8px;
          padding: 7px 10px;
          border-radius: 8px;
          color: rgba(255,255,255,0.5) !important;
          font-size: 0.82rem;
          font-weight: 400;
          text-decoration: none !important;
          transition: all 0.15s;
          margin-bottom: 1px;
        }
        .sidebar-submenu-link:hover {
          color: rgba(255,255,255,0.9) !important;
          background: rgba(255,255,255,0.05);
        }
        .sidebar-submenu-link.active-sub {
          color: #a78bfa !important;
          background: rgba(124,58,237,0.12);
        }
        .submenu-dot {
          width: 5px; height: 5px;
          border-radius: 50%;
          background: rgba(255,255,255,0.25);
          flex-shrink: 0;
        }
        .active-sub .submenu-dot { background: #a78bfa; }
        /* Group with arrow open */
        .sidebar-group.is-open > .sidebar-nav-link { color: #fff !important; background: rgba(255,255,255,0.05) !important; }
        .sidebar-group.is-open > .sidebar-nav-link .sidebar-arrow { transform: rotate(-90deg); }
        .sidebar-divider { border-top: 1px solid rgba(255,255,255,0.05); margin: 8px 0; }
      </style>

      <ul style="list-style:none;padding:0;margin:0">

        {{-- ĐIỀU HƯỚNG --}}
        @if($isAdminOrMgr)
        <li><span class="sidebar-nav-label">Điều hướng</span></li>
        <li class="sidebar-nav-item">
          <a href="/dashboard" class="sidebar-nav-link {{ $isDashboard ? 'active-link' : '' }}">
            <i class="fas fa-chart-line sidebar-nav-icon"></i>
            <span>Tổng quan</span>
          </a>
        </li>
        @endif

        {{-- NGHIỆP VỤ --}}
        <li><span class="sidebar-nav-label">Nghiệp vụ</span></li>

        {{-- TÀI CHÍNH --}}
        @if($canFinance)
        <li class="sidebar-nav-item sidebar-group {{ $isFinance ? 'is-open' : '' }}">
          <a href="#" class="sidebar-nav-link {{ $isFinance ? 'active-link' : '' }}" data-toggle-group>
            <i class="fas fa-wallet sidebar-nav-icon"></i>
            <span>Tài chính</span>
            <i class="fas fa-angle-left sidebar-arrow"></i>
          </a>
          <ul class="sidebar-submenu {{ $isFinance ? 'open' : '' }}">
            <li><a href="{{ route('taichinh.quyet-toan') }}" class="sidebar-submenu-link {{ request()->is('tai-chinh*') ? 'active-sub' : '' }}">
              <span class="submenu-dot"></span>Bảng quyết toán
            </a></li>
          </ul>
        </li>
        @endif

        {{-- NHẬP HÀNG --}}
        @if($canWarehouse)
        <li class="sidebar-nav-item sidebar-group {{ $isPurchasing ? 'is-open' : '' }}">
          <a href="#" class="sidebar-nav-link {{ $isPurchasing ? 'active-link' : '' }}" data-toggle-group>
            <i class="fas fa-truck-loading sidebar-nav-icon"></i>
            <span>Nhập hàng</span>
            <i class="fas fa-angle-left sidebar-arrow"></i>
          </a>
          <ul class="sidebar-submenu {{ $isPurchasing ? 'open' : '' }}">
            <li><a href="{{ route('nhaphang.list') }}" class="sidebar-submenu-link {{ request()->is('nhap-hang') || request()->is('nhap-hang/') ? 'active-sub' : '' }}">
              <span class="submenu-dot"></span>Xếp lô hàng
            </a></li>
            <li><a href="{{ route('nhaphang.don-nhap') }}" class="sidebar-submenu-link {{ request()->is('nhap-hang/don-nhap*') ? 'active-sub' : '' }}">
              <span class="submenu-dot"></span>Quản lý đơn nhập
            </a></li>
            <li><a href="{{ route('nhaphang.nha-cung-cap') }}" class="sidebar-submenu-link {{ request()->is('nhap-hang/nha-cung-cap*') ? 'active-sub' : '' }}">
              <span class="submenu-dot"></span>Nhà cung cấp
            </a></li>
          </ul>
        </li>
        @endif

        {{-- BÁN HÀNG --}}
        @if($canSales)
        <li class="sidebar-nav-item sidebar-group {{ $isSales ? 'is-open' : '' }}">
          <a href="#" class="sidebar-nav-link {{ $isSales ? 'active-link' : '' }}" data-toggle-group>
            <i class="fas fa-cash-register sidebar-nav-icon"></i>
            <span>Bán hàng</span>
            <i class="fas fa-angle-left sidebar-arrow"></i>
          </a>
          <ul class="sidebar-submenu {{ $isSales ? 'open' : '' }}">
            <li><a href="{{ route('banhang.thuc-don') }}" class="sidebar-submenu-link {{ request()->is('ban-hang/thuc-don*') ? 'active-sub' : '' }}">
              <span class="submenu-dot"></span>Thực đơn / Menu
            </a></li>
            <li><a href="{{ route('banhang.ton-kho') }}" class="sidebar-submenu-link {{ request()->is('ban-hang/ton-kho*') ? 'active-sub' : '' }}">
              <span class="submenu-dot"></span>Kho nguyên liệu
            </a></li>
            <li><a href="{{ route('banhang.giao-dich') }}" class="sidebar-submenu-link {{ request()->is('ban-hang/giao-dich*') ? 'active-sub' : '' }}">
              <span class="submenu-dot"></span>Giao dịch bán hàng
            </a></li>
          </ul>
        </li>
        @endif

        {{-- NHÂN SỰ --}}
        <li class="sidebar-nav-item sidebar-group {{ $isHr ? 'is-open' : '' }}">
          <a href="#" class="sidebar-nav-link {{ $isHr ? 'active-link' : '' }}" data-toggle-group>
            <i class="fas fa-users-cog sidebar-nav-icon"></i>
            <span>Nhân sự</span>
            <i class="fas fa-angle-left sidebar-arrow"></i>
          </a>
          <ul class="sidebar-submenu {{ $isHr ? 'open' : '' }}">
            @if($canHR)
            <li><a href="{{ route('tuyendung.list') }}" class="sidebar-submenu-link {{ request()->is('tuyen-dung') || (request()->is('tuyen-dung/*') && !request()->is('tuyen-dung/applications*')) ? 'active-sub' : '' }}">
              <span class="submenu-dot"></span>Tuyển dụng
            </a></li>
            <li><a href="{{ route('tuyendung.applications.list') }}" class="sidebar-submenu-link {{ request()->is('tuyen-dung/applications*') ? 'active-sub' : '' }}">
              <span class="submenu-dot"></span>Danh sách ứng tuyển
            </a></li>
            <li><a href="{{ route('nhansu.facilities') }}" class="sidebar-submenu-link {{ request()->is('facilities*') ? 'active-sub' : '' }}">
              <span class="submenu-dot"></span>Cơ sở vật chất
            </a></li>
            @endif
            <li><a href="{{ route('nhansu.cham-cong') }}" class="sidebar-submenu-link {{ request()->is('*cham-cong*') ? 'active-sub' : '' }}">
              <span class="submenu-dot"></span>Chấm công
            </a></li>
          </ul>
        </li>

        {{-- HỆ THỐNG --}}
        @if($isAdminOrMgr)
        <li><div class="sidebar-divider"></div></li>
        <li><span class="sidebar-nav-label">Hệ thống</span></li>

        <li class="sidebar-nav-item">
          <a href="{{ route('branches.list') }}" class="sidebar-nav-link {{ request()->is('chi-nhanh*') ? 'active-link' : '' }}">
            <i class="fas fa-store-alt sidebar-nav-icon"></i>
            <span>Chi nhánh</span>
          </a>
        </li>

        <li class="sidebar-nav-item sidebar-group {{ $isConfig ? 'is-open' : '' }}">
          <a href="#" class="sidebar-nav-link {{ $isConfig ? 'active-link' : '' }}" data-toggle-group>
            <i class="fas fa-sliders-h sidebar-nav-icon"></i>
            <span>Cấu hình</span>
            <i class="fas fa-angle-left sidebar-arrow"></i>
          </a>
          <ul class="sidebar-submenu {{ $isConfig ? 'open' : '' }}">
            @if($user?->isSuperAdmin())
            <li><a href="{{ route('cocheluong.index') }}" class="sidebar-submenu-link {{ request()->is('salary-mechanism*') ? 'active-sub' : '' }}">
              <span class="submenu-dot"></span>Cơ chế lương
            </a></li>
            @endif
          </ul>
        </li>

        <li class="sidebar-nav-item sidebar-group {{ $isAccount ? 'is-open' : '' }}">
          <a href="#" class="sidebar-nav-link {{ $isAccount ? 'active-link' : '' }}" data-toggle-group>
            <i class="fas fa-user-shield sidebar-nav-icon"></i>
            <span>Tài khoản</span>
            <i class="fas fa-angle-left sidebar-arrow"></i>
          </a>
          <ul class="sidebar-submenu {{ $isAccount ? 'open' : '' }}">
            <li><a href="/account" class="sidebar-submenu-link {{ request()->is('account') ? 'active-sub' : '' }}">
              <span class="submenu-dot"></span>Hệ thống tài khoản
            </a></li>
            <li><a href="/users" class="sidebar-submenu-link {{ request()->is('users') ? 'active-sub' : '' }}">
              <span class="submenu-dot"></span>Danh sách nhân sự
            </a></li>
          </ul>
        </li>
        @endif

        <!-- {{-- AI --}}
        <li><div class="sidebar-divider"></div></li>
        <li class="sidebar-nav-item">
          <a href="#" class="sidebar-nav-link" id="open-ai-chat" style="
            background: linear-gradient(135deg, rgba(124,58,237,0.15), rgba(79,70,229,0.1));
            border: 1px solid rgba(124,58,237,0.2);
          ">
            <i class="fas fa-robot sidebar-nav-icon" style="color:#a78bfa"></i>
            <span style="color:#c4b5fd">Trợ lý AI</span>
            <span style="margin-left:auto;background:rgba(124,58,237,0.3);color:#a78bfa;border-radius:5px;padding:1px 7px;font-size:0.65rem;font-weight:700">BETA</span>
          </a>
        </li> -->

      </ul>
    </nav>
  </div>

  <script>
    // Toggle submenu groups
    document.querySelectorAll('[data-toggle-group]').forEach(function(link) {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        var group = this.closest('.sidebar-group');
        var submenu = group.querySelector('.sidebar-submenu');
        var isOpen = group.classList.contains('is-open');
        // Close all others
        document.querySelectorAll('.sidebar-group.is-open').forEach(function(g) {
          if (g !== group) {
            g.classList.remove('is-open');
            g.querySelector('.sidebar-submenu').style.display = 'none';
            g.querySelector('.sidebar-submenu').classList.remove('open');
          }
        });
        if (isOpen) {
          group.classList.remove('is-open');
          submenu.style.display = 'none';
        } else {
          group.classList.add('is-open');
          submenu.style.display = 'block';
        }
      });
    });
    // Open AI chat
    var aiBtn = document.getElementById('open-ai-chat');
    if (aiBtn) {
      aiBtn.addEventListener('click', function(e) {
        e.preventDefault();
        var chat = document.getElementById('ai-chat-widget');
        if (chat) chat.style.display = chat.style.display === 'none' ? 'flex' : 'none';
      });
    }
  </script>
</aside>
