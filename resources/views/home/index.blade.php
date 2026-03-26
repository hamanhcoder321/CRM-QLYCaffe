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
        /* 
           BIẾN MÀU SẮC — đổi ở đây là đổi toàn trang
            */
        :root {
            --cafe-brown: #6f4e37;
            /* nâu cafe chính */
            --cafe-dark: #3d2b1f;
            /* nâu đậm */
            --cafe-light: #f5ece4;
            /* kem nhạt */
            --cafe-gold: #c8963e;
            /* vàng đồng */
            --white: #ffffff;
            --text-gray: #666666;
            --radius: 12px;
        }

        /* RESET */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background: var(--white);
            color: #2c2c2c;
            line-height: 1.6;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* 
           NAVBAR — thanh trên cùng, cố định khi cuộn
            */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 60px;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(111, 78, 55, 0.1);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
        }

        .navbar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .navbar-logo img {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--cafe-brown);
        }

        .navbar-logo span {
            font-size: 20px;
            font-weight: 800;
            color: var(--cafe-dark);
        }

        .btn-nav {
            padding: 10px 28px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-nav-solid {
            background: var(--cafe-brown);
            color: var(--white);
            border: 2px solid var(--cafe-brown);
        }

        .btn-nav-solid:hover {
            background: var(--cafe-dark);
            border-color: var(--cafe-dark);
        }

        .btn-nav-outline {
            background: transparent;
            color: var(--cafe-brown);
            border: 2px solid var(--cafe-brown);
        }

        .btn-nav-outline:hover {
            background: var(--cafe-light);
        }

        /* logout button dùng form POST (bảo mật) */
        .logout-form button {
            background: var(--cafe-brown);
            color: var(--white);
            border: 2px solid var(--cafe-brown);
            padding: 10px 28px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .logout-form button:hover {
            background: var(--cafe-dark);
        }

        /* 
           HERO — banner chính đầu trang
            */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 120px 60px 80px;
            background:
                linear-gradient(135deg, rgba(61, 43, 31, 0.9) 0%, rgba(111, 78, 55, 0.78) 100%),
                url('https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=1600') center/cover no-repeat;
        }

        .hero-content {
            max-width: 700px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.15);
            color: var(--white);
            padding: 6px 18px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 24px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .hero h1 {
            font-size: 54px;
            font-weight: 800;
            color: var(--white);
            line-height: 1.15;
            margin-bottom: 20px;
            letter-spacing: -1px;
        }

        .hero h1 span {
            color: var(--cafe-gold);
        }

        .hero p {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 40px;
            max-width: 540px;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            background: var(--cafe-gold);
            color: var(--white);
            padding: 16px 40px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.25s;
        }

        .btn-hero-primary:hover {
            background: #b8822c;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(200, 150, 62, 0.4);
        }

        .btn-hero-secondary {
            background: rgba(255, 255, 255, 0.15);
            color: var(--white);
            padding: 16px 40px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            border: 2px solid rgba(255, 255, 255, 0.4);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.25s;
        }

        .btn-hero-secondary:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .hero-stats {
            display: flex;
            gap: 48px;
            margin-top: 64px;
            flex-wrap: wrap;
        }

        .stat-item .number {
            font-size: 36px;
            font-weight: 800;
            color: var(--white);
        }

        .stat-item .label {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 2px;
        }

        /* 
           SECTION CHUNG
            */
        section {
            padding: 90px 60px;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-badge {
            display: inline-block;
            background: var(--cafe-light);
            color: var(--cafe-brown);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .section-title {
            font-size: 36px;
            font-weight: 800;
            color: var(--cafe-dark);
            margin-bottom: 14px;
            letter-spacing: -0.5px;
        }

        .section-sub {
            font-size: 16px;
            color: var(--text-gray);
            max-width: 540px;
            margin: 0 auto;
        }

        /* 
           FEATURES — 6 thẻ tính năng
            */
        .features {
            background: #fafafa;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .feature-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 32px 28px;
            border: 1px solid rgba(0, 0, 0, 0.06);
            transition: all 0.25s;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 36px rgba(111, 78, 55, 0.12);
            border-color: var(--cafe-brown);
        }

        .feature-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: var(--cafe-light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            font-size: 22px;
            color: var(--cafe-brown);
        }

        .feature-card h3 {
            font-size: 17px;
            font-weight: 700;
            color: var(--cafe-dark);
            margin-bottom: 8px;
        }

        .feature-card p {
            font-size: 14px;
            color: var(--text-gray);
            line-height: 1.7;
        }

        /* 
           STEPS — 3 bước sử dụng
            */
        .steps {
            background: var(--white);
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            max-width: 860px;
            margin: 0 auto;
            text-align: center;
        }

        .step-number {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: var(--cafe-brown);
            color: var(--white);
            font-size: 22px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
        }

        .step-item h3 {
            font-size: 17px;
            font-weight: 700;
            color: var(--cafe-dark);
            margin-bottom: 8px;
        }

        .step-item p {
            font-size: 14px;
            color: var(--text-gray);
        }

        /* 
           CTA BANNER
            */
        .cta-banner {
            background: linear-gradient(135deg, var(--cafe-dark), var(--cafe-brown));
            text-align: center;
            padding: 80px 60px;
        }

        .cta-banner h2 {
            font-size: 36px;
            font-weight: 800;
            color: var(--white);
            margin-bottom: 14px;
        }

        .cta-banner p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 17px;
            margin-bottom: 34px;
        }

        .btn-cta {
            background: var(--cafe-gold);
            color: var(--white);
            padding: 16px 48px;
            border-radius: 50px;
            font-size: 17px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.25s;
        }

        .btn-cta:hover {
            background: #b8822c;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        /* 
           FOOTER
            */
        footer {
            background: var(--cafe-dark);
            color: rgba(255, 255, 255, 0.7);
            text-align: center;
            padding: 32px 60px;
            font-size: 14px;
        }

        footer strong {
            color: var(--white);
        }

        /* 
           RESPONSIVE — mobile
            */
        @media (max-width: 900px) {
            .navbar {
                padding: 14px 20px;
            }

            .hero {
                padding: 100px 20px 60px;
            }

            .hero h1 {
                font-size: 34px;
            }

            section {
                padding: 60px 20px;
            }

            .features-grid,
            .steps-grid {
                grid-template-columns: 1fr;
            }

            .hero-stats {
                gap: 24px;
            }
        }
    </style>
</head>

<body>

    {{-- 
    NAVBAR
    Logic: @auth/@else kiểm tra đăng nhập để hiện nút phù hợp
     --}}
    <nav class="navbar">

        {{-- Logo trái --}}
        <a href="{{ route('home') }}" class="navbar-logo">
            <img src="{{ asset('Adminlte/dist/img/logo coffe M&T.jpg') }}" alt="M&T Cafe">
            <span>M&T Cafe</span>
        </a>

        {{-- Nút phải — thay đổi tuỳ trạng thái đăng nhập --}}
        <div style="display:flex; gap:12px; align-items:center;">

            @auth
                {{-- login: hiện "vào Dashboard" + "Đăng xuất" --}}
                <a href="{{ route('dashboard') }}" class="btn-nav btn-nav-outline">
                    <i class="fas fa-tachometer-alt"></i> Vào Dashboard
                </a>

                {{--
                ĐĂNG XUẤT phải dùng POST + @csrf để bảo mật.
                Không được dùng GET vì dễ bị tấn công CSRF.
                --}}
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </button>
                </form>

            @else
                {{-- CHƯA ĐĂNG NHẬP: hiện "Đăng nhập" --}}
                <a href="{{ route('login') }}" class="btn-nav btn-nav-solid">
                    <i class="fas fa-sign-in-alt"></i> Đăng nhập
                </a>
            @endauth

        </div>
    </nav>


    {{-- 
    HERO
     --}}
    <section class="hero">
        <div class="hero-content">

            <div class="hero-badge">
                <i class="fas fa-coffee"></i>
                Hệ thống quản lý chuỗi cafe
            </div>

            <h1>Quản lý <span>M&T Cafe</span><br>thông minh & hiệu quả</h1>

            <p>
                Nền tảng quản lý toàn diện: nhân sự, doanh thu, kho hàng, đa chi nhánh —
                tất cả trong một hệ thống đơn giản, dễ dùng.
            </p>

            <div class="hero-actions">
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

            {{-- Thống kê nhanh --}}
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="number">22+</div>
                    <div class="label">Module quản lý</div>
                </div>
                <div class="stat-item">
                    <div class="number">3</div>
                    <div class="label">Cấp phân quyền</div>
                </div>
                <div class="stat-item">
                    <div class="number">∞</div>
                    <div class="label">Chi nhánh</div>
                </div>
            </div>

        </div>
    </section>


    {{-- 
    tính năng
     --}}
    <section class="features" id="features">
        <div class="section-header">
            <div class="section-badge">Tính năng</div>
            <h2 class="section-title">Đầy đủ mọi nghiệp vụ quán cafe</h2>
            <p class="section-sub">Từ nhân sự, doanh thu đến kho hàng — thiết kế đơn giản, dễ dùng.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-store"></i></div>
                <h3>Quản lý đa chi nhánh</h3>
                <p>Mỗi chi nhánh có dữ liệu riêng. Admin thấy tất cả, quản lý chi nhánh chỉ thấy của mình.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-users"></i></div>
                <h3>Quản lý nhân sự</h3>
                <p>Hồ sơ, chấm công, phân ca, lương thưởng và tuyển dụng theo từng bộ phận.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                <h3>Báo cáo doanh thu</h3>
                <p>Theo dõi doanh thu, lợi nhuận theo ngày / tháng / năm. So sánh giữa các chi nhánh.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-boxes"></i></div>
                <h3>Quản lý kho hàng</h3>
                <p>Nhập xuất kho, tồn kho nguyên liệu, cảnh báo khi hàng sắp hết.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-user-shield"></i></div>
                <h3>Phân quyền bảo mật</h3>
                <p>3 cấp: Admin, Quản lý chi nhánh, Nhân viên. Mỗi người chỉ thấy đúng dữ liệu của mình.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-handshake"></i></div>
                <h3>Chăm sóc khách hàng</h3>
                <p>Lưu lịch sử mua hàng, chăm sóc khách hàng thân thiết từng chi nhánh.</p>
            </div>
        </div>
    </section>


    {{-- 
    Start
     --}}
    <section class="steps">
        <div class="section-header">
            <div class="section-badge">Bắt đầu</div>
            <h2 class="section-title">Dùng hệ thống chỉ 3 bước</h2>
            <p class="section-sub">Đơn giản, không cần kỹ thuật phức tạp.</p>
        </div>

        <div class="steps-grid">
            <div class="step-item">
                <div class="step-number">1</div>
                <h3>Đăng nhập</h3>
                <p>Dùng tài khoản được Admin cấp. Mật khẩu mã hoá, an toàn tuyệt đối.</p>
            </div>
            <div class="step-item">
                <div class="step-number">2</div>
                <h3>Chọn chi nhánh</h3>
                <p>Hệ thống tự nhận diện chi nhánh và hiển thị đúng dữ liệu của bạn.</p>
            </div>
            <div class="step-item">
                <div class="step-number">3</div>
                <h3>Quản lý</h3>
                <p>Nhân sự, doanh thu, kho hàng — tất cả trực quan trên Dashboard.</p>
            </div>
        </div>
    </section>


    {{-- 
    Cta banner
     --}}
    <div class="cta-banner">
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


    {{-- 
    footer
     --}}
    <footer>
        <p>© {{ date('Y') }} <strong>M&T Cafe Management</strong> — Hệ thống quản lý chuỗi cafe.</p>
    </footer>

</body>

</html>