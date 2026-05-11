{{--
FILE: resources/views/home/index.blade.php
MỤC ĐÍCH: Trang chủ (Landing Page) — ai cũng thấy, không cần đăng nhập
ROUTE: GET / → home.index
--}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M&T Cafe Management — Hệ thống quản lý chuỗi cafe</title>

    {{-- Favicon logo M&T --}}
    <link rel="icon" type="image/jpeg" href="{{ asset('Adminlte/dist/img/logo coffe M&T.jpg') }}">

    {{-- Font đẹp từ Google --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    {{-- Icon pack FontAwesome --}}
    <link rel="stylesheet" href="{{ asset('Adminlte/plugins/fontawesome-free/css/all.min.css') }}">

    <style>
        :root {
            --cafe-brown: #6f4e37;
            --cafe-dark: #3d2b1f;
            --cafe-light: #f5ece4;
            --cafe-gold: #c8963e;
            --white: #ffffff;
            --text-gray: #666666;
            --radius: 12px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Be Vietnam Pro', sans-serif; background: var(--white); color: #2c2c2c; line-height: 1.6; }
        a { text-decoration: none; color: inherit; }

        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 999;
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 60px; background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(10px); border-bottom: 1px solid rgba(111, 78, 55, 0.1);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
        }
        .navbar-logo { display: flex; align-items: center; gap: 12px; }
        .navbar-logo img { width: 42px; height: 42px; border-radius: 50%; object-fit: cover; border: 2px solid var(--cafe-brown); }
        .navbar-logo span { font-size: 20px; font-weight: 800; color: var(--cafe-dark); }
        .btn-nav { padding: 10px 28px; border-radius: 50px; font-weight: 600; font-size: 15px; cursor: pointer; transition: all 0.2s; }
        .btn-nav-solid { background: var(--cafe-brown); color: var(--white); border: 2px solid var(--cafe-brown); }
        .btn-nav-solid:hover { background: var(--cafe-dark); border-color: var(--cafe-dark); }
        .btn-nav-outline { background: transparent; color: var(--cafe-brown); border: 2px solid var(--cafe-brown); }
        .btn-nav-outline:hover { background: var(--cafe-light); }
        .logout-form button { background: var(--cafe-brown); color: var(--white); border: 2px solid var(--cafe-brown); padding: 10px 28px; border-radius: 50px; font-weight: 600; font-size: 15px; cursor: pointer; transition: all 0.2s; }
        .logout-form button:hover { background: var(--cafe-dark); }

        .hero {
            min-height: 100vh; display: flex; align-items: center; padding: 120px 60px 80px;
            background: linear-gradient(135deg, rgba(61, 43, 31, 0.9) 0%, rgba(111, 78, 55, 0.78) 100%),
            url('https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=1600') center/cover no-repeat;
        }
        .hero-content { max-width: 700px; }
        .hero-badge { display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.15); color: var(--white); padding: 6px 18px; border-radius: 50px; font-size: 14px; font-weight: 500; margin-bottom: 24px; border: 1px solid rgba(255, 255, 255, 0.3); }
        .hero h1 { font-size: 54px; font-weight: 800; color: var(--white); line-height: 1.15; margin-bottom: 20px; letter-spacing: -1px; }
        .hero h1 span { color: var(--cafe-gold); }
        .hero p { font-size: 18px; color: rgba(255, 255, 255, 0.85); margin-bottom: 40px; max-width: 540px; }
        .hero-actions { display: flex; gap: 16px; flex-wrap: wrap; }
        .btn-hero-primary { background: var(--cafe-gold); color: var(--white); padding: 16px 40px; border-radius: 50px; font-size: 16px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; transition: all 0.25s; }
        .btn-hero-primary:hover { background: #b8822c; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(200, 150, 62, 0.4); }
        .btn-hero-secondary { background: rgba(255, 255, 255, 0.15); color: var(--white); padding: 16px 40px; border-radius: 50px; font-size: 16px; font-weight: 600; border: 2px solid rgba(255, 255, 255, 0.4); display: inline-flex; align-items: center; gap: 8px; transition: all 0.25s; }
        .btn-hero-secondary:hover { background: rgba(255, 255, 255, 0.25); }
        .hero-stats { display: flex; gap: 48px; margin-top: 64px; flex-wrap: wrap; }
        .stat-item .number { font-size: 36px; font-weight: 800; color: var(--white); }
        .stat-item .label { font-size: 14px; color: rgba(255, 255, 255, 0.7); margin-top: 2px; }

        section { padding: 90px 60px; }
        .section-header { text-align: center; margin-bottom: 60px; }
        .section-badge { display: inline-block; background: var(--cafe-light); color: var(--cafe-brown); padding: 6px 16px; border-radius: 50px; font-size: 13px; font-weight: 600; margin-bottom: 14px; text-transform: uppercase; letter-spacing: 0.5px; }
        .section-title { font-size: 36px; font-weight: 800; color: var(--cafe-dark); margin-bottom: 14px; letter-spacing: -0.5px; }
        .section-sub { font-size: 16px; color: var(--text-gray); max-width: 540px; margin: 0 auto; }

        .reveal { opacity: 0; transform: translateY(40px) scale(0.98); transition: opacity 0.7s ease, transform 0.7s ease; will-change: opacity, transform; }
        .reveal.show { opacity: 1; transform: translateY(0) scale(1); }
        .delay-1 { transition-delay: 0.08s; }
        .delay-2 { transition-delay: 0.16s; }
        .delay-3 { transition-delay: 0.24s; }
        .delay-4 { transition-delay: 0.32s; }
        .delay-5 { transition-delay: 0.4s; }
        .delay-6 { transition-delay: 0.48s; }

        .features { background: #fafafa; }
        .features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; max-width: 1100px; margin: 0 auto; }
        .feature-card { background: var(--white); border-radius: var(--radius); padding: 32px 28px; border: 1px solid rgba(0, 0, 0, 0.06); transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease, opacity 0.7s ease; }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 12px 36px rgba(111, 78, 55, 0.12); border-color: var(--cafe-brown); }
        .feature-icon { width: 52px; height: 52px; border-radius: 14px; background: var(--cafe-light); display: flex; align-items: center; justify-content: center; margin-bottom: 18px; font-size: 22px; color: var(--cafe-brown); }
        .feature-card h3 { font-size: 17px; font-weight: 700; color: var(--cafe-dark); margin-bottom: 8px; }
        .feature-card p { font-size: 14px; color: var(--text-gray); line-height: 1.7; }

        .steps { background: var(--white); }
        .steps-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px; max-width: 860px; margin: 0 auto; text-align: center; }
        .step-item { padding: 8px; transition: transform 0.25s ease; }
        .step-item:hover { transform: translateY(-4px); }
        .step-number { width: 58px; height: 58px; border-radius: 50%; background: var(--cafe-brown); color: var(--white); font-size: 22px; font-weight: 800; display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; }
        .step-item h3 { font-size: 17px; font-weight: 700; color: var(--cafe-dark); margin-bottom: 8px; }
        .step-item p { font-size: 14px; color: var(--text-gray); }

        .cta-banner { background: linear-gradient(135deg, var(--cafe-dark), var(--cafe-brown)); text-align: center; padding: 80px 60px; }
        .cta-banner h2 { font-size: 36px; font-weight: 800; color: var(--white); margin-bottom: 14px; }
        .cta-banner p { color: rgba(255, 255, 255, 0.8); font-size: 17px; margin-bottom: 34px; }
        .btn-cta { background: var(--cafe-gold); color: var(--white); padding: 16px 48px; border-radius: 50px; font-size: 17px; font-weight: 700; display: inline-flex; align-items: center; gap: 10px; transition: all 0.25s; }
        .btn-cta:hover { background: #b8822c; transform: translateY(-2px); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3); }

        .recruitment { background: #fff; }
        .recruitment-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; max-width: 1100px; margin: 0 auto; }
        .recruitment-card { background: #fff; border: 1px solid rgba(111, 78, 55, 0.12); border-radius: 16px; padding: 24px; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05); transition: transform 0.25s ease, box-shadow 0.25s ease; }
        .recruitment-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(15, 23, 42, 0.1); }
        .recruitment-card h3 { font-size: 18px; font-weight: 800; color: var(--cafe-dark); margin-bottom: 8px; }
        .recruitment-meta { font-size: 13px; color: var(--text-gray); margin-bottom: 14px; }
        .recruitment-tag { display: inline-block; padding: 5px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; margin-bottom: 14px; }
        .tag-high { background: #fee2e2; color: #b91c1c; }
        .tag-medium { background: #fef3c7; color: #b45309; }
        .tag-low { background: #e0e7ff; color: #4338ca; }
        .recruitment-info { display: grid; gap: 8px; font-size: 14px; color: #374151; margin-bottom: 14px; }
        .recruitment-info strong { color: #111827; }
        .recruitment-desc { font-size: 14px; color: var(--text-gray); line-height: 1.7; }

        .menu { background: #fafafa; }
        .menu-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; max-width: 1100px; margin: 0 auto; }
        .menu-card { background: #fff; border: 1px solid rgba(111, 78, 55, 0.12); border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05); transition: transform 0.25s ease, box-shadow 0.25s ease; text-align: center; }
        .menu-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(15, 23, 42, 0.1); }
        .menu-image { width: 100%; height: 200px; object-fit: cover; background: var(--cafe-light); display: flex; align-items: center; justify-content: center; font-size: 40px; color: var(--cafe-brown); }
        .menu-content { padding: 20px; }
        .menu-card h3 { font-size: 18px; font-weight: 800; color: var(--cafe-dark); margin-bottom: 8px; }
        .menu-price { font-size: 16px; font-weight: 700; color: var(--cafe-gold); }

        .empty-box { max-width: 1100px; margin: 0 auto; padding: 24px; border: 1px dashed rgba(111, 78, 55, 0.25); border-radius: 16px; text-align: center; color: var(--text-gray); background: rgba(245, 236, 228, 0.35); }

        footer { background: var(--cafe-dark); color: rgba(255, 255, 255, 0.7); text-align: center; padding: 32px 60px; font-size: 14px; }
        footer strong { color: var(--white); }

        /* ===== BẢN ĐỒ CHI NHÁNH ===== */
        .branches { background: var(--white); }
        .branches-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 32px;
            max-width: 1100px;
            margin: 0 auto;
        }
        .branch-card {
            background: #fff;
            border: 1px solid rgba(111, 78, 55, 0.12);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(61, 43, 31, 0.08);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .branch-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(61, 43, 31, 0.15);
        }
        .branch-map {
            width: 100%;
            height: 240px;
            border: none;
            display: block;
        }
        .branch-info {
            padding: 20px 24px;
        }
        .branch-name {
            font-size: 18px;
            font-weight: 800;
            color: var(--cafe-dark);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .branch-name i { color: var(--cafe-brown); font-size: 16px; }
        .branch-address {
            font-size: 14px;
            color: var(--text-gray);
            margin-bottom: 12px;
            display: flex;
            align-items: flex-start;
            gap: 6px;
            line-height: 1.6;
        }
        .branch-address i { color: var(--cafe-gold); margin-top: 3px; flex-shrink: 0; }
        .branch-meta {
            display: flex;
            gap: 16px;
            font-size: 13px;
            color: var(--text-gray);
        }
        .branch-meta span { display: flex; align-items: center; gap: 5px; }
        .branch-meta i { color: var(--cafe-brown); }
        .branch-badge {
            display: inline-block;
            background: var(--cafe-light);
            color: var(--cafe-brown);
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 999px;
            margin-left: 8px;
            vertical-align: middle;
        }
        @media (max-width: 900px) {
            .branches-grid { grid-template-columns: 1fr; }
        }

        
        .btn-apply {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; padding: 12px; margin-top: 16px;
            background: var(--cafe-brown); color: #fff; border: none; border-radius: 10px;
            font-weight: 700; cursor: pointer; transition: all 0.2s;
            font-family: inherit;
        }
        .btn-apply:hover { background: var(--cafe-dark); transform: scale(1.02); }

        /* Modal Styles */
        .modal {
            display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5); align-items: center; justify-content: center;
        }
        .modal.show { display: flex; }
        .modal-content {
            background: #fff; padding: 32px; border-radius: 16px; width: 100%; max-width: 450px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.2); position: relative; animation: modalSlide 0.3s ease;
        }
        @keyframes modalSlide { from { transform: translateY(-30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .modal-header { margin-bottom: 24px; }
        .modal-header h2 { font-size: 24px; font-weight: 800; color: var(--cafe-dark); }
        .modal-close { position: absolute; top: 20px; right: 20px; font-size: 24px; cursor: pointer; color: var(--text-gray); }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: var(--cafe-dark); }
        .form-group input {
            width: 100%; padding: 12px 16px; border: 1.5px solid #eee; border-radius: 10px;
            font-size: 15px; transition: border-color 0.2s; outline: none;
        }
        .form-group input:focus { border-color: var(--cafe-brown); }
        .btn-submit {
            width: 100%; padding: 14px; background: var(--cafe-gold); color: #fff;
            border: none; border-radius: 10px; font-size: 16px; font-weight: 700;
            cursor: pointer; transition: all 0.2s; margin-top: 10px;
        }
        .btn-submit:hover { background: #b8822c; }
        .btn-submit:disabled { background: #ccc; cursor: not-allowed; }

        @media (max-width: 900px) {
            .navbar { padding: 14px 20px; }
            .hero { padding: 100px 20px 60px; }
            .hero h1 { font-size: 34px; }
            section { padding: 60px 20px; }
            .features-grid, .steps-grid, .recruitment-grid, .menu-grid { grid-template-columns: 1fr; }
            .hero-stats { gap: 24px; }
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <a href="{{ route('home') }}" class="navbar-logo">
            <img src="{{ asset('Adminlte/dist/img/logo coffe M&T.jpg') }}" alt="M&T Cafe">
            <span>M&T Cafe</span>
        </a>

        <div style="display:flex; gap:12px; align-items:center;">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-nav btn-nav-outline">
                    <i class="fas fa-tachometer-alt"></i> Vào Dashboard
                </a>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-nav btn-nav-solid">
                    <i class="fas fa-sign-in-alt"></i> Đăng nhập
                </a>
            @endauth
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <div class="hero-badge reveal">
                <i class="fas fa-coffee"></i>
                Hệ thống quản lý chuỗi cafe
            </div>

            <h1 class="reveal delay-1">Quản lý <span>M&T Cafe</span><br>thông minh & hiệu quả</h1>

            <p class="reveal delay-2">
                Nền tảng quản lý toàn diện: nhân sự, doanh thu, kho hàng, đa chi nhánh —
                tất cả trong một hệ thống đơn giản, dễ dùng.
            </p>

            <div class="hero-actions reveal delay-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-hero-primary">
                        <i class="fas fa-tachometer-alt"></i> Vào Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-hero-primary">
                        <i class="fas fa-sign-in-alt"></i> Đăng nhập ngay
                    </a>
                @endauth

                <a href="#features" class="btn-hero-secondary">
                    <i class="fas fa-arrow-down"></i> Xem tính năng
                </a>
            </div>

            <div class="hero-stats">
                <div class="stat-item reveal delay-1">
                    <div class="number">22+</div>
                    <div class="label">Module quản lý</div>
                </div>
                <div class="stat-item reveal delay-2">
                    <div class="number">3</div>
                    <div class="label">Cấp phân quyền</div>
                </div>
                <div class="stat-item reveal delay-3">
                    <div class="number"><i class="fas fa-home"></i></div>
                    <div class="label">Chi nhánh</div>
                </div>
            </div>
        </div>
    </section>

    <section class="features" id="features">
        <div class="section-header reveal">
            <div class="section-badge">Tính năng</div>
            <h2 class="section-title">Đầy đủ mọi nghiệp vụ quán cafe</h2>
            <p class="section-sub">Từ nhân sự, doanh thu đến kho hàng — thiết kế đơn giản, dễ dùng.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card reveal delay-1">
                <div class="feature-icon"><i class="fas fa-store"></i></div>
                <h3>Quản lý đa chi nhánh</h3>
                <p>Mỗi chi nhánh có dữ liệu riêng. Admin thấy tất cả, quản lý chi nhánh chỉ thấy của mình.</p>
            </div>
            <div class="feature-card reveal delay-2">
                <div class="feature-icon"><i class="fas fa-users"></i></div>
                <h3>Quản lý nhân sự</h3>
                <p>Hồ sơ, chấm công, phân ca, lương thưởng và tuyển dụng theo từng bộ phận.</p>
            </div>
            <div class="feature-card reveal delay-3">
                <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                <h3>Báo cáo doanh thu</h3>
                <p>Theo dõi doanh thu, lợi nhuận theo ngày / tháng / năm. So sánh giữa các chi nhánh.</p>
            </div>
            <div class="feature-card reveal delay-4">
                <div class="feature-icon"><i class="fas fa-boxes"></i></div>
                <h3>Quản lý kho hàng</h3>
                <p>Nhập xuất kho, tồn kho nguyên liệu, cảnh báo khi hàng sắp hết.</p>
            </div>
            <div class="feature-card reveal delay-5">
                <div class="feature-icon"><i class="fas fa-user-shield"></i></div>
                <h3>Phân quyền bảo mật</h3>
                <p>3 cấp: Admin, Quản lý chi nhánh, Nhân viên. Mỗi người chỉ thấy đúng dữ liệu của mình.</p>
            </div>
            <!-- <div class="feature-card reveal delay-6">
                <div class="feature-icon"><i class="fas fa-handshake"></i></div>
                <h3>Chăm sóc khách hàng</h3>
                <p>Lưu lịch sử mua hàng, chăm sóc khách hàng thân thiết từng chi nhánh.</p>
            </div> -->
        </div>
    </section>

    <section class="menu" id="menu">
        <div class="section-header reveal">
            <div class="section-badge">Thực đơn</div>
            <h2 class="section-title">Menu Nổi Bật</h2>
            <p class="section-sub">Những món thức uống được yêu thích nhất tại hệ thống M&T Cafe.</p>
        </div>

        @if(isset($drinks) && $drinks->count())
            <div class="menu-grid">
                @foreach($drinks as $drink)
                    <div class="menu-card reveal delay-{{ min($loop->iteration, 6) }}">
                        @if($drink->image)
                            <img src="{{ asset('uploads/' . $drink->image) }}" alt="{{ $drink->name }}" class="menu-image">
                        @else
                            <div class="menu-image">
                                <i class="fas fa-coffee"></i>
                            </div>
                        @endif
                        <div class="menu-content">
                            <h3>{{ $drink->name }}</h3>
                            <div class="menu-price">{{ number_format($drink->price, 0, ',', '.') }} VNĐ</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-box reveal">
                Chưa có sản phẩm nào trên menu.
            </div>
        @endif
    </section>

    <section class="recruitment" id="recruitment">
        <div class="section-header reveal">
            <div class="section-badge">Tuyển dụng</div>
            <h2 class="section-title">Việc làm đang tuyển</h2>
            <p class="section-sub">Các vị trí mới nhất được đăng từ hệ thống tuyển dụng.</p>
        </div>

        @if(isset($recruitments) && $recruitments->count())
            <div class="recruitment-grid">
                @foreach($recruitments as $recruitment)
                    <div class="recruitment-card reveal delay-{{ min($loop->iteration, 6) }}">
                        <div class="recruitment-tag {{ $recruitment->prioritize == 2 ? 'tag-high' : ($recruitment->prioritize == 1 ? 'tag-medium' : 'tag-low') }}">
                            {{ $recruitment->prioritize == 2 ? 'Ưu tiên cao' : ($recruitment->prioritize == 1 ? 'Ưu tiên trung bình' : 'Ưu tiên thấp') }}
                        </div>
                        <h3>{{ $recruitment->position?->name ?? 'Vị trí đang tuyển' }}</h3>
                        <div class="recruitment-meta">
                            Bộ phận: <strong>{{ $recruitment->part?->name ?? '—' }}</strong> ·
                            Số lượng: <strong>{{ $recruitment->number }}</strong>
                        </div>
                        <div class="recruitment-info">
                            <div><strong>Người đăng:</strong> {{ $recruitment->user?->name ?? 'Hệ thống' }}</div>
                            <div><strong>Hạn chót:</strong> {{ $recruitment->deadline ? \Illuminate\Support\Carbon::parse($recruitment->deadline)->format('d/m/Y H:i') : 'Chưa có' }}</div>
                            <div><strong>Trạng thái:</strong>
                                {{ match((int) $recruitment->status) {
                                    0 => 'Đang tuyển',
                                    1 => 'Hoàn thành',
                                    2 => 'Trễ',
                                    default => '—',
                                } }}
                            </div>
                        </div>
                        <div class="recruitment-desc">
                            <strong>Kênh:</strong> {{ $recruitment->social ?? '—' }}<br>
                            <strong>Vấn đề:</strong> {{ $recruitment->obstacle ?? '—' }}<br>
                            <strong>Giải pháp:</strong> {{ $recruitment->solution ?? '—' }}
                        </div>
                        <button class="btn-apply" onclick="openApplyModal({{ $recruitment->id }}, '{{ $recruitment->position?->name ?? 'Vị trí đang tuyển' }}')">
                            <i class="fas fa-paper-plane"></i> Ứng tuyển ngay
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-box reveal">
                Chưa có post tuyển dụng nào được đăng.
            </div>
        @endif
    </section>

    <section class="steps">
        <div class="section-header reveal">
            <div class="section-badge">Bắt đầu</div>
            <h2 class="section-title">Dùng hệ thống chỉ 3 bước</h2>
            <p class="section-sub">Đơn giản, không cần kỹ thuật phức tạp.</p>
        </div>

        <div class="steps-grid">
            <div class="step-item reveal delay-1">
                <div class="step-number">1</div>
                <h3>Đăng nhập</h3>
                <p>Dùng tài khoản được Admin cấp. Mật khẩu mã hoá, an toàn tuyệt đối.</p>
            </div>
            <div class="step-item reveal delay-2">
                <div class="step-number">2</div>
                <h3>Chọn chi nhánh</h3>
                <p>Hệ thống tự nhận diện chi nhánh và hiển thị đúng dữ liệu của bạn.</p>
            </div>
            <div class="step-item reveal delay-3">
                <div class="step-number">3</div>
                <h3>Quản lý</h3>
                <p>Nhân sự, doanh thu, kho hàng — tất cả trực quan trên Dashboard.</p>
            </div>
        </div>
    </section>

    {{-- ===== BẢN ĐỒ 2 CHI NHÁNH ===== --}}
    <section class="branches">
        <div class="section-header">
            <span class="section-badge reveal">📍 Vị trí</span>
            <h2 class="section-title reveal">Hệ thống chi nhánh M&T Cafe</h2>
            <p class="section-sub reveal">Tìm chúng tôi tại 2 địa điểm — tiện lợi, gần gũi với bạn.</p>
        </div>

        <div class="branches-grid">
            {{-- Chi nhánh Tây Mỗ --}}
            <div class="branch-card reveal delay-1">
                <iframe
                    class="branch-map"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.8945823420896!2d105.74127931536827!3d21.01035919417978!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135abc6f9a63e23%3A0x8d2498e0a96c3c10!2zVMOieSBN4buXLCBOYW0gVOG7qyBMacOqbSwgSMOgIE7hu5lp!5e0!3m2!1svi!2svn!4v1715000100000!5m2!1svi!2svn"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
                <div class="branch-info">
                    <div class="branch-name">
                        <i class="fas fa-store"></i>
                        Chi nhánh Tây Mỗ
                        <span class="branch-badge">Cơ sở chính</span>
                    </div>
                    <div class="branch-address">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Phường Tây Mỗ, Quận Nam Từ Liêm, Hà Nội</span>
                    </div>
                    <div class="branch-meta">
                        <span><i class="fas fa-clock"></i> 07:00 – 22:00</span>
                        <span><i class="fas fa-phone"></i> 0901 234 567</span>
                    </div>
                </div>
            </div>

            {{-- Chi nhánh Mỹ Đình --}}
            <div class="branch-card reveal delay-2">
                <iframe
                    class="branch-map"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.1865624316284!2d105.77464931536876!3d21.028160394223424!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab5f50e040b1%3A0x3b6faba7ace28dd9!2zTXkgRGluaCwgTmFtIFThu6sgTGnDqm0sIEjDoCBO4buZaQ!5e0!3m2!1svi!2svn!4v1715000200000!5m2!1svi!2svn"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
                <div class="branch-info">
                    <div class="branch-name">
                        <i class="fas fa-store"></i>
                        Chi nhánh Mỹ Đình
                        <span class="branch-badge" style="background:#e0f2fe;color:#0369a1;">Mới mở</span>
                    </div>
                    <div class="branch-address">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Mỹ Đình, Quận Nam Từ Liêm, Hà Nội</span>
                    </div>
                    <div class="branch-meta">
                        <span><i class="fas fa-clock"></i> 07:00 – 23:00</span>
                        <span><i class="fas fa-phone"></i> 0901 234 890</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="cta-banner reveal" id="cta-banner">
        <h2>Sẵn sàng bắt đầu? ☕</h2>
        <p>Đăng nhập ngay để quản lý chuỗi cafe M&T của bạn.</p>
        @auth
            <a href="{{ route('dashboard') }}" class="btn-cta">
                <i class="fas fa-tachometer-alt"></i> Vào Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="btn-cta">
                <i class="fas fa-sign-in-alt"></i> Đăng nhập ngay
            </a>
        @endauth
    </div>

    <footer class="reveal">
        <p>© {{ date('Y') }} <strong>M&T Cafe Management</strong> — Hệ thống quản lý chuỗi cafe.</p>
    </footer>

    <!-- Modal Ứng Tuyển -->
    <div id="applyModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeApplyModal()">&times;</span>
            <div class="modal-header">
                <h2 id="modalTitle">Ứng tuyển vị trí</h2>
                <p style="font-size: 14px; color: var(--text-gray);">Vui lòng điền thông tin để chúng tôi liên hệ.</p>
            </div>
            <form id="applyForm">
                @csrf
                <input type="hidden" name="recruitment_id" id="modalRecruitmentId">
                <div class="form-group">
                    <label>Họ và tên <span style="color:red">*</span></label>
                    <input type="text" name="name" placeholder="VD: Nguyễn Văn A" required>
                </div>
                <div class="form-group">
                    <label>Email <span style="color:red">*</span></label>
                    <input type="email" name="email" placeholder="email@example.com" required>
                </div>
                <div class="form-group">
                    <label>Số điện thoại <span style="color:red">*</span></label>
                    <input type="tel" name="phone" placeholder="09xxxxxxxx" required>
                </div>
                <div class="form-group">
                    <label>Kinh nghiệm làm việc</label>
                    <textarea name="experience" rows="3" placeholder="Mô tả kinh nghiệm của bạn (nếu có)..." style="width: 100%; padding: 12px 16px; border: 1.5px solid #eee; border-radius: 10px; font-size: 15px; resize: vertical;"></textarea>
                </div>
                <div class="form-group">
                    <label>Kỹ năng nổi bật</label>
                    <textarea name="skills" rows="3" placeholder="Các kỹ năng mềm, kỹ năng chuyên môn..." style="width: 100%; padding: 12px 16px; border: 1.5px solid #eee; border-radius: 10px; font-size: 15px; resize: vertical;"></textarea>
                </div>
                <button type="submit" class="btn-submit" id="btnSubmitApply">Gửi hồ sơ ứng tuyển</button>
            </form>
        </div>
    </div>

    {{-- Script cần thiết --}}
    <script src="{{ asset('Adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('Adminlte/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <script>
        function openApplyModal(id, title) {
            document.getElementById('modalRecruitmentId').value = id;
            document.getElementById('modalTitle').innerText = 'Ứng tuyển: ' + title;
            document.getElementById('applyModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeApplyModal() {
            document.getElementById('applyModal').classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        // Đóng khi click ra ngoài
        window.onclick = function(event) {
            if (event.target == document.getElementById('applyModal')) {
                closeApplyModal();
            }
        }

        $(document).ready(function() {
            $('#applyForm').on('submit', function(e) {
                e.preventDefault();
                const $btn = $('#btnSubmitApply');
                $btn.prop('disabled', true).text('Đang gửi...');

                $.ajax({
                    url: "{{ route('apply.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: res.message,
                                confirmButtonColor: '#6f4e37'
                            });
                            closeApplyModal();
                            $('#applyForm')[0].reset();
                        }
                    },
                    error: function(err) {
                        let msg = 'Có lỗi xảy ra, vui lòng thử lại sau.';
                        if (err.status === 422) {
                            const errors = err.responseJSON.errors;
                            msg = Object.values(errors).flat().join('\n');
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: msg,
                        });
                    },
                    complete: function() {
                        $btn.prop('disabled', false).text('Gửi hồ sơ ứng tuyển');
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const revealItems = document.querySelectorAll('.reveal');

            const revealObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    entry.target.classList.add('show');
                    observer.unobserve(entry.target);
                });
            }, {
                root: null,
                rootMargin: '0px 0px -80px 0px',
                threshold: 0.15
            });

            revealItems.forEach((item) => {
                revealObserver.observe(item);
            });
        });
    </script>
</body>
</html>
