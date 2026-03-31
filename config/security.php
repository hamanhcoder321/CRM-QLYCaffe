<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Security Baseline Configuration
    |--------------------------------------------------------------------------
    |
    | Đây là file cấu hình bảo mật nền tảng cho dự án.
    | File này KHÔNG tự động thay đổi hành vi của hệ thống hiện tại nếu bạn
    | chưa chủ động đọc và áp dụng các giá trị này trong middleware, service
    | hoặc controller. Vì vậy nó an toàn để thêm vào dự án đang chạy.
    |
    | Mục tiêu:
    | - Gom các quy tắc bảo mật về một nơi
    | - Giúp người mới dễ quản lý
    | - Có thể mở rộng dần mà không phá vỡ hệ thống hiện tại
    |
    */

    'project' => [
        'environment' => env('APP_ENV', 'production'),
        'debug_enabled' => (bool) env('APP_DEBUG', false),
        'app_url' => env('APP_URL', 'http://localhost'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Force Safe Defaults
    |--------------------------------------------------------------------------
    |
    | Chỉ là các cờ định hướng. Chúng chưa tác động đến hệ thống nếu chưa được
    | sử dụng trong code.
    |
    */

    'safe_defaults' => [
        'hide_debug_in_production' => true,
        'require_https_in_production' => true,
        'rotate_logs' => true,
        'prevent_user_enumeration' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    |
    | Dùng để chuẩn hóa rule cho đăng nhập / tài khoản.
    |
    */

    'authentication' => [
        'max_login_attempts' => 5,
        'lockout_minutes' => 15,
        'require_email_verification' => false,
        'password_timeout' => 10800,
        'remember_session' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Policy
    |--------------------------------------------------------------------------
    |
    | Đây là chính sách đề xuất. Chưa tự động áp dụng nếu bạn chưa gọi config()
    | từ Request validation hoặc logic đổi mật khẩu.
    |
    */

    'password_policy' => [
        'min_length' => 12,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_number' => true,
        'require_symbol' => true,
        'check_common_passwords' => true,
        'password_history' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Session & Cookie
    |--------------------------------------------------------------------------
    |
    | Các giá trị định hướng để kiểm tra session/cookie đang đủ an toàn chưa.
    |
    */

    'session' => [
        'session_lifetime_minutes' => (int) env('SESSION_LIFETIME', 120),
        'encrypt_session' => (bool) env('SESSION_ENCRYPT', false),
        'http_only_cookie' => true,
        'same_site' => 'lax',
        'secure_cookie_when_https' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Access Control
    |--------------------------------------------------------------------------
    |
    | Danh sách IP / route / vai trò có thể mở rộng sau này.
    |
    */

    'access_control' => [
        'admin_roles' => ['admin', 'super_admin'],
        'trusted_proxies_only' => false,
        'whitelisted_ips' => [],
        'blacklisted_ips' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Input Protection
    |--------------------------------------------------------------------------
    |
    | Rule tham chiếu để chống dữ liệu xấu, spam, hoặc payload quá lớn.
    |
    */

    'input_protection' => [
        'trim_input' => true,
        'strip_invisible_characters' => true,
        'max_text_length' => 10000,
        'max_search_length' => 100,
        'block_script_tags' => true,
        'sanitize_html_input' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Protection
    |--------------------------------------------------------------------------
    |
    | Dùng khi bạn bắt đầu làm tính năng upload file.
    |
    */

    'file_upload' => [
        'enabled' => true,
        'max_size_kb' => 2048,
        'allowed_mimes' => [
            'image/jpeg',
            'image/png',
            'image/webp',
            'application/pdf',
        ],
        'blocked_extensions' => [
            'php',
            'phtml',
            'phar',
            'exe',
            'sh',
            'bat',
            'js',
        ],
        'scan_filename' => true,
        'store_outside_public_if_possible' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | API Security
    |--------------------------------------------------------------------------
    |
    | Dùng khi dự án có API nội bộ hoặc public API.
    |
    */

    'api' => [
        'enable_rate_limit' => true,
        'default_requests_per_minute' => 60,
        'log_abnormal_requests' => true,
        'require_api_token_rotation' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Headers
    |--------------------------------------------------------------------------
    |
    | Chưa tự động apply vào response. Đây là danh sách tham chiếu để bạn hoặc
    | middleware áp dụng sau.
    |
    */

    'headers' => [
        'x_frame_options' => 'SAMEORIGIN',
        'x_content_type_options' => 'nosniff',
        'referrer_policy' => 'strict-origin-when-cross-origin',
        'x_xss_protection' => '1; mode=block',
        'content_security_policy' => "default-src 'self'; img-src 'self' data: https:; style-src 'self' 'unsafe-inline' https:; script-src 'self' 'unsafe-inline' 'unsafe-eval' https:;",
        'permissions_policy' => 'camera=(), microphone=(), geolocation=()',
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit & Monitoring
    |--------------------------------------------------------------------------
    |
    | Gợi ý cho việc log các hành vi quan trọng.
    |
    */

    'audit' => [
        'enabled' => true,
        'log_login' => true,
        'log_logout' => true,
        'log_failed_login' => true,
        'log_permission_denied' => true,
        'log_sensitive_data_changes' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Safety
    |--------------------------------------------------------------------------
    |
    | Dùng để nhắc team tuân thủ truy vấn an toàn.
    |
    */

    'database' => [
        'use_parameter_binding' => true,
        'prevent_raw_queries_without_review' => true,
        'mask_sensitive_logs' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Notes
    |--------------------------------------------------------------------------
    |
    | Mục này chỉ để ghi chú triển khai.
    |
    */

    'notes' => [
        'do_not_store_secrets_in_code' => true,
        'do_not_commit_env_file' => true,
        'turn_off_debug_on_production' => true,
        'always_validate_user_input' => true,
        'always_authorize_sensitive_actions' => true,
    ],

];
