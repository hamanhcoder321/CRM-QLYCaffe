<?php
/**
 * File này dùng để setup nhanh khi đưa project Laravel lên Shared Hosting (cPanel, DirectAdmin...)
 * Cách dùng: Đưa toàn bộ code lên Hosting, sau đó chạy đường dẫn: https://ten-mien-cua-ban.com/setup_hosting.php
 */

echo "<h1>BẮT ĐẦU CÀI ĐẶT CƠ BẢN CHO HOSTING</h1>";

// 1. Tạo Storage Link cho hình ảnh
$targetFolder = __DIR__.'/storage/app/public';
$linkFolder = __DIR__.'/public/storage';

echo "<h3>1. Cấu hình thư mục hình ảnh (Storage Link)</h3>";

if (file_exists($linkFolder)) {
    echo "<p style='color: orange;'>Thư mục /public/storage đã tồn tại (Có thể đã được link trước đó).</p>";
} else {
    try {
        symlink($targetFolder, $linkFolder);
        echo "<p style='color: green;'>Tạo Storage Link thành công! Ảnh sẽ hiển thị bình thường.</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>Lỗi khi tạo Storage Link: " . $e->getMessage() . "</p>";
        echo "<p>Vui lòng đảm bảo hosting của bạn hỗ trợ symlink, hoặc liên hệ nhà cung cấp hosting.</p>";
    }
}

// 2. Chạy lệnh tối ưu hóa và xóa Cache nếu cần thiết
// Ở trên hosting thường không có console, nên ta có thể gọi qua shell_exec nếu được phép.
echo "<h3>2. Xoá Cache cấu hình</h3>";
try {
    // Xóa file cache nếu tồn tại
    $cacheFiles = [
        __DIR__.'/bootstrap/cache/config.php',
        __DIR__.'/bootstrap/cache/routes.php',
        __DIR__.'/bootstrap/cache/packages.php',
        __DIR__.'/bootstrap/cache/services.php',
    ];
    
    foreach($cacheFiles as $file) {
        if(file_exists($file)) {
            unlink($file);
            echo "<p>Đã xoá: ".basename($file)."</p>";
        }
    }
    echo "<p style='color: green;'>Đã dọn dẹp cache cấu hình thành công!</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Lỗi xoá cache: " . $e->getMessage() . "</p>";
}

echo "<hr><h2 style='color: blue;'>Cài đặt hoàn tất! Bạn hãy xóa file <b>setup_hosting.php</b> này đi để bảo mật nhé!</h2>";
echo "<a href='/'>Quay lại trang chủ</a>";
