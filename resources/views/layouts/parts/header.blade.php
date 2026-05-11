<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>M&T Cafe — CRM</title>
  <link rel="icon" type="image/jpeg" href="{{ asset('Adminlte/dist/img/logo coffe M&T.jpg') }}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
  <link rel="stylesheet" href="{{ asset('Adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.3.7/css/dataTables.dataTables.min.css">
  <link rel="stylesheet" href="{{ asset('Adminlte/dist/css/adminlte.min.css') }}">
  <style>
    /* ===== GLOBAL RESET & FONTS ===== */
    * { font-family: 'Inter', sans-serif; }

    body.hold-transition { background: #0f1117; }
    .wrapper { background: #f0f2f7; }

    /* ===== TOP NAVBAR ===== */
    .main-header.navbar {
      background: linear-gradient(135deg, #1a1f2e 0%, #252d3d 100%) !important;
      border-bottom: 1px solid rgba(255,255,255,0.06) !important;
      box-shadow: 0 2px 20px rgba(0,0,0,0.4) !important;
      min-height: 58px;
      padding: 0 1rem;
    }
    .main-header .nav-link {
      color: rgba(255,255,255,0.75) !important;
      transition: all 0.2s ease;
    }
    .main-header .nav-link:hover { color: #fff !important; }

    /* Brand toggle button */
    .main-header [data-widget="pushmenu"] {
      font-size: 1.1rem;
      padding: 0.4rem 0.75rem;
      border-radius: 8px;
      color: rgba(255,255,255,0.8) !important;
    }
    .main-header [data-widget="pushmenu"]:hover {
      background: rgba(255,255,255,0.08);
      color: #fff !important;
    }

    /* Branch selector */
    .branch-badge {
      display: inline-flex; align-items: center; gap: 6px;
      background: rgba(139,92,246,0.15);
      border: 1px solid rgba(139,92,246,0.3);
      color: #a78bfa !important;
      border-radius: 20px;
      padding: 4px 14px;
      font-size: 0.8rem;
      font-weight: 500;
      transition: all 0.2s;
    }
    .branch-badge:hover {
      background: rgba(139,92,246,0.25) !important;
      color: #c4b5fd !important;
    }

    /* Navbar right icons */
    .navbar-right-icon {
      width: 36px; height: 36px;
      display: inline-flex; align-items: center; justify-content: center;
      border-radius: 10px;
      color: rgba(255,255,255,0.7) !important;
      transition: all 0.2s;
    }
    .navbar-right-icon:hover {
      background: rgba(255,255,255,0.1);
      color: #fff !important;
    }

    /* User avatar / profile area */
    .navbar-user-area {
      display: flex; align-items: center; gap: 8px;
      padding: 4px 12px;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.2s;
    }
    .navbar-user-area:hover { background: rgba(255,255,255,0.08); }
    .navbar-user-avatar {
      width: 34px; height: 34px;
      border-radius: 50%;
      background: linear-gradient(135deg, #7c3aed, #2563eb);
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-weight: 600; font-size: 0.85rem;
      flex-shrink: 0;
    }
    .navbar-user-name { color: #fff; font-size: 0.85rem; font-weight: 500; line-height:1.1; }
    .navbar-user-role { color: rgba(255,255,255,0.5); font-size: 0.7rem; }

    /* Logout btn */
    .btn-logout {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 6px 14px;
      border-radius: 10px;
      background: rgba(239,68,68,0.1);
      border: 1px solid rgba(239,68,68,0.25);
      color: #f87171 !important;
      font-size: 0.8rem;
      font-weight: 500;
      transition: all 0.2s;
      cursor: pointer;
    }
    .btn-logout:hover {
      background: rgba(239,68,68,0.2);
      border-color: rgba(239,68,68,0.4);
      color: #fca5a5 !important;
    }

    /* Dropdown menus */
    .navbar-dropdown {
      background: #1e2435;
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 14px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.5);
      min-width: 200px;
      padding: 8px;
      margin-top: 8px;
    }
    .navbar-dropdown .dropdown-item {
      border-radius: 8px;
      color: rgba(255,255,255,0.75);
      padding: 8px 12px;
      font-size: 0.85rem;
      transition: all 0.15s;
    }
    .navbar-dropdown .dropdown-item:hover {
      background: rgba(255,255,255,0.07);
      color: #fff;
    }
    .navbar-dropdown .dropdown-item.active {
      background: rgba(124,58,237,0.2);
      color: #a78bfa;
    }
    .navbar-dropdown .dropdown-header {
      color: rgba(255,255,255,0.4);
      font-size: 0.72rem;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      padding: 6px 12px 4px;
    }
    .navbar-dropdown .dropdown-divider { border-color: rgba(255,255,255,0.06); }

    /* ===== CONTENT WRAPPER ===== */
    .content-wrapper {
      background: #f0f2f7 !important;
      background-image:
        radial-gradient(circle at 20% 20%, rgba(124,58,237,0.04) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(79,70,229,0.03) 0%, transparent 50%) !important;
      min-height: calc(100vh - 58px);
    }

    /* ===== CONTENT HEADER ===== */
    .content-header {
      padding: 24px 24px 0 24px;
      margin-bottom: 0;
    }
    .content-header h1 {
      font-size: 1.45rem;
      font-weight: 700;
      color: #111827;
      letter-spacing: -0.02em;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .content-header h1 i {
      width: 38px; height: 38px;
      background: linear-gradient(135deg, #7c3aed, #4f46e5);
      border-radius: 10px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      color: #fff;
      box-shadow: 0 4px 12px rgba(124,58,237,0.35);
      flex-shrink: 0;
    }

    /* Breadcrumb */
    .breadcrumb {
      background: transparent;
      padding: 0;
      margin: 0;
      font-size: 0.8rem;
    }
    .breadcrumb-item + .breadcrumb-item::before { color: #d1d5db; }
    .breadcrumb-item a { color: #7c3aed; text-decoration: none; }
    .breadcrumb-item a:hover { color: #5b21b6; text-decoration: underline; }
    .breadcrumb-item.active { color: #9ca3af; }

    /* ===== CONTENT BODY ===== */
    .content { padding: 20px 24px 24px; }

    /* ===== CARDS ===== */
    .card {
      border: none !important;
      border-radius: 18px !important;
      box-shadow: 0 1px 4px rgba(0,0,0,0.05), 0 4px 20px rgba(0,0,0,0.05) !important;
      overflow: hidden;
      background: #fff !important;
      transition: box-shadow 0.25s ease, transform 0.2s ease;
    }
    .card:hover { box-shadow: 0 4px 8px rgba(0,0,0,0.06), 0 12px 40px rgba(0,0,0,0.08) !important; }

    .card-header {
      background: #fff !important;
      border-bottom: 1px solid #f3f4f6 !important;
      padding: 18px 22px !important;
    }
    .card-title {
      font-size: 1rem !important;
      font-weight: 700 !important;
      color: #111827 !important;
      margin: 0 !important;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .card-title i { color: #7c3aed; }
    .card-body { padding: 20px 22px !important; background: #fff; }
    .card-footer {
      background: #fafafa !important;
      border-top: 1px solid #f3f4f6 !important;
      padding: 14px 22px !important;
    }

    /* Card variants */
    .card-primary-gradient .card-header {
      background: linear-gradient(135deg, #7c3aed, #4f46e5) !important;
      border-bottom: none !important;
    }
    .card-primary-gradient .card-title { color: #fff !important; }

    /* Stat cards */
    .stat-card {
      border-radius: 18px !important;
      padding: 22px !important;
      position: relative;
      overflow: hidden;
    }
    .stat-card::before {
      content: '';
      position: absolute;
      top: -30px; right: -30px;
      width: 100px; height: 100px;
      border-radius: 50%;
      background: rgba(255,255,255,0.08);
    }
    .stat-card-value {
      font-size: 1.8rem;
      font-weight: 800;
      color: #fff;
      line-height: 1;
    }
    .stat-card-label {
      font-size: 0.8rem;
      color: rgba(255,255,255,0.75);
      font-weight: 500;
      margin-top: 4px;
    }

    /* ===== TABLES ===== */
    .table-responsive { border-radius: 0 0 18px 18px; overflow: hidden; }
    .table { margin-bottom: 0 !important; }
    .table thead tr th {
      background: #f8fafc !important;
      color: #64748b !important;
      font-size: 0.73rem !important;
      font-weight: 700 !important;
      text-transform: uppercase !important;
      letter-spacing: 0.07em !important;
      border-bottom: 2px solid #e2e8f0 !important;
      border-top: none !important;
      padding: 13px 16px !important;
      white-space: nowrap;
    }
    .table tbody td {
      padding: 13px 16px !important;
      vertical-align: middle !important;
      color: #374151 !important;
      font-size: 0.875rem !important;
      border-top: 1px solid #f3f4f6 !important;
    }
    .table tbody tr { transition: background 0.15s; }
    .table-hover tbody tr:hover { background: #faf5ff !important; cursor: default; }
    .table-striped tbody tr:nth-of-type(even) { background: #fafafa; }
    .table-striped tbody tr:nth-of-type(even):hover { background: #faf5ff !important; }

    /* ===== BUTTONS ===== */
    .btn {
      border-radius: 10px !important;
      font-weight: 600 !important;
      font-size: 0.85rem !important;
      letter-spacing: 0.01em;
      transition: all 0.2s ease !important;
      display: inline-flex !important;
      align-items: center !important;
      gap: 6px !important;
    }
    .btn-sm { font-size: 0.78rem !important; padding: 5px 12px !important; border-radius: 8px !important; }
    .btn-lg { font-size: 0.95rem !important; padding: 12px 24px !important; }

    .btn-primary {
      background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%) !important;
      border: none !important;
      color: #fff !important;
    }
    .btn-primary:hover {
      background: linear-gradient(135deg, #6d28d9 0%, #4338ca 100%) !important;
      box-shadow: 0 6px 20px rgba(124,58,237,0.4) !important;
      transform: translateY(-1px);
    }
    .btn-success {
      background: linear-gradient(135deg, #10b981, #059669) !important;
      border: none !important; color: #fff !important;
    }
    .btn-success:hover {
      box-shadow: 0 6px 20px rgba(16,185,129,0.35) !important;
      transform: translateY(-1px);
    }
    .btn-danger {
      background: linear-gradient(135deg, #ef4444, #dc2626) !important;
      border: none !important; color: #fff !important;
    }
    .btn-danger:hover {
      box-shadow: 0 6px 20px rgba(239,68,68,0.35) !important;
      transform: translateY(-1px);
    }
    .btn-warning {
      background: linear-gradient(135deg, #f59e0b, #d97706) !important;
      border: none !important; color: #fff !important;
    }
    .btn-warning:hover {
      box-shadow: 0 6px 20px rgba(245,158,11,0.35) !important;
      transform: translateY(-1px);
    }
    .btn-info {
      background: linear-gradient(135deg, #06b6d4, #0284c7) !important;
      border: none !important; color: #fff !important;
    }
    .btn-info:hover {
      box-shadow: 0 6px 20px rgba(6,182,212,0.35) !important;
      transform: translateY(-1px);
    }
    .btn-secondary {
      background: #f1f5f9 !important; border: 1.5px solid #e2e8f0 !important;
      color: #475569 !important;
    }
    .btn-secondary:hover { background: #e2e8f0 !important; color: #334155 !important; }
    .btn-outline-primary {
      background: transparent !important;
      border: 1.5px solid #7c3aed !important;
      color: #7c3aed !important;
    }
    .btn-outline-primary:hover {
      background: rgba(124,58,237,0.08) !important;
    }

    /* Action buttons group in table */
    .btn-action {
      width: 32px; height: 32px;
      border-radius: 8px !important;
      padding: 0 !important;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      font-size: 0.78rem !important;
      transition: all 0.18s !important;
      gap: 0 !important;
    }
    .btn-action:hover { transform: translateY(-1px) scale(1.05); }

    /* ===== BADGES ===== */
    .badge {
      border-radius: 7px !important;
      font-weight: 600 !important;
      font-size: 0.72rem !important;
      padding: 4px 9px !important;
      letter-spacing: 0.02em;
    }
    .badge-success { background: #d1fae5 !important; color: #065f46 !important; }
    .badge-danger  { background: #fee2e2 !important; color: #991b1b !important; }
    .badge-warning { background: #fef3c7 !important; color: #92400e !important; }
    .badge-info    { background: #dbeafe !important; color: #1e40af !important; }
    .badge-primary { background: #ede9fe !important; color: #5b21b6 !important; }
    .badge-secondary { background: #f1f5f9 !important; color: #475569 !important; }
    .badge-dark    { background: #1f2937 !important; color: #f9fafb !important; }

    /* ===== FORMS ===== */
    label {
      font-size: 0.83rem !important;
      font-weight: 600 !important;
      color: #374151 !important;
      margin-bottom: 6px !important;
      display: block;
    }
    .form-control, select.form-control, textarea.form-control,
    .form-select, input[type="date"], input[type="datetime"],
    input[type="number"], input[type="text"], input[type="email"],
    input[type="password"] {
      border-radius: 10px !important;
      border: 1.5px solid #e5e7eb !important;
      font-size: 0.875rem !important;
      color: #374151 !important;
      background: #fafafa !important;
      padding: 9px 14px !important;
      transition: all 0.2s ease !important;
      width: 100%;
      box-shadow: none !important;
    }
    .form-control:focus, select.form-control:focus, textarea.form-control:focus,
    input[type="date"]:focus, input[type="text"]:focus, input[type="email"]:focus,
    input[type="password"]:focus, input[type="number"]:focus {
      border-color: #7c3aed !important;
      background: #fff !important;
      box-shadow: 0 0 0 3px rgba(124,58,237,0.12) !important;
      outline: none !important;
    }
    .form-control::placeholder { color: #c4c9d4 !important; }
    .form-group { margin-bottom: 1.1rem; }
    .input-group .form-control { border-radius: 10px 0 0 10px !important; }
    .input-group .input-group-append .btn { border-radius: 0 10px 10px 0 !important; }

    /* Form sections (used in create/edit pages) */
    .form-section {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 1px 4px rgba(0,0,0,0.04), 0 4px 20px rgba(0,0,0,0.04);
      padding: 24px;
      margin-bottom: 20px;
      border: 1px solid rgba(124,58,237,0.08);
      transition: box-shadow 0.25s;
    }
    .form-section:hover { box-shadow: 0 4px 8px rgba(0,0,0,0.05), 0 12px 32px rgba(0,0,0,0.06); }
    .form-section-title {
      font-size: 0.95rem;
      font-weight: 700;
      color: #111827;
      margin-bottom: 20px;
      padding-bottom: 14px;
      border-bottom: 2px solid #f3f4f6;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .form-section-title i {
      width: 32px; height: 32px;
      background: linear-gradient(135deg, rgba(124,58,237,0.12), rgba(79,70,229,0.08));
      border-radius: 8px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 0.85rem;
    }
    .form-section-title i.text-primary { color: #7c3aed !important; }
    .form-section-title i.text-success { color: #10b981 !important; background: linear-gradient(135deg, rgba(16,185,129,0.12), rgba(5,150,105,0.08)) !important; }
    .form-section-title i.text-info { color: #06b6d4 !important; background: linear-gradient(135deg, rgba(6,182,212,0.12), rgba(2,132,199,0.08)) !important; }
    .form-section-title i.text-warning { color: #f59e0b !important; background: linear-gradient(135deg, rgba(245,158,11,0.12), rgba(217,119,6,0.08)) !important; }

    /* ===== MODALS ===== */
    .modal-content {
      border: none !important;
      border-radius: 20px !important;
      box-shadow: 0 25px 80px rgba(0,0,0,0.18) !important;
      overflow: hidden;
    }
    .modal-header {
      padding: 20px 24px !important;
      border-bottom: 1px solid #f3f4f6 !important;
    }
    .modal-header .modal-title { font-weight: 700 !important; font-size: 1rem !important; }
    .modal-body { padding: 22px 24px !important; }
    .modal-footer {
      padding: 14px 24px !important;
      border-top: 1px solid #f3f4f6 !important;
      background: #fafafa;
    }
    .modal-header.bg-gradient-primary {
      background: linear-gradient(135deg, #7c3aed, #4f46e5) !important;
    }

    /* ===== MODAL FIX - nut dong/huy luon click duoc ===== */
    .modal-backdrop {
      z-index: 1040 !important;
      opacity: 0.45 !important;
    }
    .modal {
      z-index: 1050 !important;
      pointer-events: none;
    }
    .modal.show {
      pointer-events: none;
    }
    .modal-dialog {
      pointer-events: all !important;
      z-index: 1060 !important;
    }
    .modal-content {
      pointer-events: all !important;
    }
    /* Button dong modal (X) - phải là block/inline-block, không dùng flex */
    button.close {
      display: inline-block !important;
      float: right !important;
      font-size: 1.4rem !important;
      font-weight: 700 !important;
      line-height: 1 !important;
      color: inherit !important;
      opacity: 0.7 !important;
      background: transparent !important;
      border: none !important;
      padding: 0 !important;
      cursor: pointer !important;
      pointer-events: all !important;
      position: relative !important;
      z-index: 10 !important;
    }
    button.close:hover { opacity: 1 !important; }
    button.close span { pointer-events: none; }
    /* Tất cả nút bên trong modal */
    .modal-header button,
    .modal-footer button,
    .modal-body button,
    .modal-footer a,
    .modal [data-dismiss="modal"] {
      pointer-events: all !important;
      cursor: pointer !important;
      position: relative !important;
      z-index: 5 !important;
    }
    /* Không cho .btn override float của nút close */
    .modal-header .close.btn,
    .modal-header button.close {
      display: inline-block !important;
      float: right !important;
    }

    /* ===== ALERTS ===== */
    .alert {
      border-radius: 12px !important;
      border: none !important;
      padding: 14px 18px !important;
      font-size: 0.875rem;
      font-weight: 500;
    }
    .alert-success { background: #d1fae5; color: #065f46; }
    .alert-danger  { background: #fee2e2; color: #991b1b; }
    .alert-info    { background: #dbeafe; color: #1e40af; }
    .alert-warning { background: #fef3c7; color: #92400e; }

    /* ===== SELECT2 & DATATABLE ===== */
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
      border-radius: 8px !important;
      border: 1.5px solid #e5e7eb !important;
      padding: 5px 10px !important;
      font-size: 0.82rem !important;
      color: #374151 !important;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
      border-color: #7c3aed !important;
      box-shadow: 0 0 0 3px rgba(124,58,237,0.1) !important;
      outline: none !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
      border-radius: 7px !important;
      font-size: 0.82rem !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
      background: linear-gradient(135deg, #7c3aed, #4f46e5) !important;
      color: #fff !important;
      border: none !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
      background: #f3f4f6 !important;
      color: #374151 !important;
      border: none !important;
    }

    /* ===== PAGE HEADER WITH BACK BUTTON ===== */
    .page-action-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 12px;
      margin-bottom: 20px;
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
      text-align: center;
      padding: 60px 20px;
      color: #9ca3af;
    }
    .empty-state i { font-size: 3rem; opacity: 0.3; margin-bottom: 12px; display: block; }
    .empty-state h5 { color: #6b7280; font-weight: 600; }

    /* ===== SCROLLBAR ===== */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

    /* ===== SWAL override ===== */
    .swal2-popup { border-radius: 18px !important; font-family: 'Inter', sans-serif !important; }
    .swal2-title { font-size: 1.15rem !important; font-weight: 700 !important; }
    .swal2-confirm { background: linear-gradient(135deg, #7c3aed, #4f46e5) !important; border-radius: 10px !important; }
    .swal2-cancel { border-radius: 10px !important; }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand">
    <!-- Left -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
          <i class="fas fa-bars"></i>
        </a>
      </li>
    </ul>

    <!-- Right -->
    <ul class="navbar-nav ml-auto align-items-center" style="gap:6px">

      {{-- Branch Selector (Super Admin) --}}
      @if(auth()->check() && auth()->user()->isSuperAdmin())
      <li class="nav-item dropdown">
        <a class="nav-link branch-badge dropdown-toggle" data-toggle="dropdown" href="#">
          <i class="fas fa-store" style="font-size:0.75rem"></i>
          {{ session('selected_branch_name') ?? 'Tất cả chi nhánh' }}
        </a>
        <div class="dropdown-menu navbar-dropdown dropdown-menu-right">
          <div class="dropdown-header">Chọn chi nhánh</div>
          <a href="{{ route('branches.select', ['branch_id' => 'all']) }}" class="dropdown-item {{ !session()->has('selected_branch_id') ? 'active' : '' }}">
            <i class="fas fa-globe mr-2" style="width:16px"></i> Tất cả chi nhánh
          </a>
          <div class="dropdown-divider"></div>
          @foreach(\App\Models\Branch::where('status', 0)->get() as $branch)
            <a href="{{ route('branches.select', ['branch_id' => $branch->id]) }}" class="dropdown-item {{ session('selected_branch_id') == $branch->id ? 'active' : '' }}">
              <i class="fas fa-store mr-2" style="width:16px"></i> {{ $branch->name }}
            </a>
          @endforeach
        </div>
      </li>
      @else
      <li class="nav-item">
        <span class="nav-link" style="color:rgba(255,255,255,0.5);font-size:0.8rem">
          <i class="fas fa-map-marker-alt mr-1" style="color:#a78bfa"></i>
          {{ auth()->user()->branch?->name ?? 'Dữ liệu chung' }}
        </span>
      </li>
      @endif

      {{-- Fullscreen --}}
      <li class="nav-item">
        <a class="nav-link navbar-right-icon" data-widget="fullscreen" href="#" role="button" title="Toàn màn hình">
          <i class="fas fa-expand-arrows-alt" style="font-size:0.85rem"></i>
        </a>
      </li>

      {{-- User info + dropdown --}}
      <li class="nav-item dropdown">
        @php $navUser = auth()->user(); $initials = strtoupper(substr($navUser->name ?? 'U', 0, 2)); @endphp
        <a class="nav-link navbar-user-area dropdown-toggle p-0 mr-1" data-toggle="dropdown" href="#" style="text-decoration:none">
          <div class="navbar-user-avatar">{{ $initials }}</div>
          <div class="d-none d-md-block ml-1">
            <div class="navbar-user-name">{{ Str::limit($navUser->name ?? 'Tài khoản', 18) }}</div>
            <div class="navbar-user-role">{{ $navUser->getAccountTypeName() ?: 'Nhân viên' }}</div>
          </div>
          <i class="fas fa-chevron-down ml-2" style="font-size:0.65rem;color:rgba(255,255,255,0.4)"></i>
        </a>
        <div class="dropdown-menu navbar-dropdown dropdown-menu-right" style="min-width:180px">
          <div class="dropdown-header">{{ $navUser->name }}</div>
          <div class="dropdown-divider"></div>
          <a href="{{ route('profile.edit') }}" class="dropdown-item">
            <i class="fas fa-user-circle mr-2" style="width:16px;color:#7c3aed"></i> Hồ sơ cá nhân
          </a>
          <div class="dropdown-divider"></div>
          <form action="{{ route('logout') }}" method="POST" class="px-2 py-1">
            @csrf
            <button type="submit" class="btn-logout w-100 border-0">
              <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </button>
          </form>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
