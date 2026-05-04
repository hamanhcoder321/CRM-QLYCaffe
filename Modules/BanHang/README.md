# Module BanHang

## 1. Mục đích
Module `BanHang` dùng để:
- quản lý thực đơn / menu
- quản lý nguyên liệu kho
- quản lý tồn kho
- quản lý giao dịch bán hàng

---

## 2. Luồng chạy của module

### 2.1. Luồng thực đơn / menu
1. Người dùng vào route `/ban-hang/thuc-don`
2. Route gọi `BanHangController@thucDon`
3. View `banhang::BanHang.thuc-don`
4. DataTable gọi route `/ban-hang/thuc-don/data`
5. `BanHangController@thucDonData`
6. Query `Drink::with('recipes.product')`
7. Trả JSON về DataTable

### 2.2. Luồng tạo / cập nhật menu
1. Submit form `/ban-hang/thuc-don/store` hoặc `/ban-hang/thuc-don/update/{drink}`
2. Validate dữ liệu
3. Tạo / cập nhật `Drink`
4. Đồng thời lưu công thức `Recipe`
5. Trả JSON thành công

### 2.3. Luồng nguyên liệu kho
1. Người dùng thao tác với nguyên liệu
2. Controller gọi repository `BanHangRepositoryInterface`
3. Repository xử lý dữ liệu
4. Trả kết quả về giao diện

### 2.4. Luồng tồn kho
1. Người dùng vào `/ban-hang/ton-kho`
2. `BanHangController@tonKho`
3. DataTable gọi `/ban-hang/ton-kho/data`
4. Controller gọi repository `getTonKho()`
5. Trả JSON về DataTable

### 2.5. Luồng giao dịch bán hàng
1. Người dùng vào `/ban-hang/giao-dich`
2. `BanHangController@giaoDich`
3. Load shipments + drinks để chọn
4. DataTable gọi `/ban-hang/giao-dich/data`
5. `BanHangController@giaoDichData`
6. Query sells + sell_products
7. Render danh sách giao dịch

---

## 3. Các file liên quan

### 3.1. Route
- `Modules/BanHang/routes/web.php`

### 3.2. Controller
- `Modules/BanHang/app/Http/Controllers/BanHangController.php`

### 3.3. Provider
- `Modules/BanHang/app/Providers/BanHangServiceProvider.php`
- `Modules/BanHang/app/Providers/RouteServiceProvider.php`

### 3.4. View
- `Modules/BanHang/resources/views/`

### 3.5. Repository
- `Modules/BanHang/app/Repositories/`
- `Modules/BanHang/app/Repositories/Interfaces/`

### 3.6. Model liên quan
- `App\Models\Drink`
- `App\Models\Product`
- `App\Models\Sell`
- `App\Models\SellProduct`
- `App\Models\Recipe`

---

## 4. Database đang được sử dụng
Module `BanHang` dùng các bảng có sẵn:
- `drinks`
- `recipes`
- `products`
- `sells`
- `sell_products`
- `shipments`
- `atms`

Không thêm migration mới cho module này.

---

## 5. Đặc điểm bảo trì
- Đây là module nghiệp vụ lớn và phức tạp nhất hiện tại
- Có repository nên query đã được tách một phần
- Controller vẫn còn dài do chứa nhiều nhóm chức năng
- Dễ debug nếu lỗi thường nằm ở:
  - repository binding
  - quan hệ `recipes.product`
  - route DataTable
  - logic tính tồn kho / doanh thu

---

## 6. Kết luận
Module `BanHang` là module quản lý bán hàng và kho của hệ thống:
- luồng chạy rõ
- đã có repository
- phù hợp tách tiếp nếu cần clean architecture hơn trong tương lai
