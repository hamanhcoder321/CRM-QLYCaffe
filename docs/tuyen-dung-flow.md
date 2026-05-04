# Luồng chạy module TuyenDung

## 1. Mục đích của module
Module `TuyenDung` dùng để:
- tạo post tuyển dụng trong trang quản trị
- hiển thị danh sách post tuyển dụng trong giao diện admin
- đổ post tuyển dụng ra trang chủ `home/index`
- dùng lại dữ liệu database có sẵn, không thay đổi cấu trúc bảng

---

## 2. Tổng quan kiến trúc hiện tại

Module đang chạy theo 2 luồng chính:

### 2.1. Luồng quản trị
Người dùng vào sidebar:
- **Nhân sự → Tuyển dụng**

Sau đó đi qua các bước:
1. Sidebar gọi route `tuyendung.list`
2. `Modules\TuyenDung\Http\Controllers\TuyenDungController@index`
3. View `Modules/TuyenDung/resources/views/TuyenDung/index.blade.php`
4. DataTable gọi route `tuyendung.data`
5. `TuyenDungController@data`
6. Query bảng `recruitments`
7. Trả JSON về DataTable

### 2.2. Luồng public
Trang chủ:
- route `/`
- `app/Http/Controllers/HomeController@index`
- lấy 6 post tuyển dụng mới nhất
- truyền sang `resources/views/home/index.blade.php`
- hiển thị block tuyển dụng ở trang ngoài

---

## 3. Luồng quản trị chi tiết

### 3.1. Vào danh sách tuyển dụng
File tham gia:
- `resources/views/layouts/parts/sidebar.blade.php`
- `Modules/TuyenDung/routes/web.php`
- `Modules/TuyenDung/app/Http/Controllers/TuyenDungController.php`
- `Modules/TuyenDung/resources/views/TuyenDung/index.blade.php`

Luồng:
1. Người dùng mở sidebar
2. Chọn **Nhân sự → Tuyển dụng**
3. Route `tuyendung.list` được gọi
4. `TuyenDungController@index()` trả về view `tuyendung::TuyenDung.index`
5. View hiển thị layout admin:
   - `layouts/parts/header`
   - `layouts/parts/sidebar`
   - `layouts/parts/footer`

### 3.2. Đổ dữ liệu DataTable
Route:
- `route('tuyendung.data')`

Controller:
- `TuyenDungController@data(Request $request)`

Xử lý:
1. Check request AJAX
2. Query:
   - `Recruitment::with(['part', 'position', 'user'])->latest()`
3. Map dữ liệu:
   - `part_name`
   - `position_name`
   - `user_name`
   - `prioritize`
   - `status`
   - `result`
   - `deadline`
4. Trả JSON cho DataTables

Cột hiển thị ở bảng:
- `#`
- `Bộ phận`
- `Vị trí`
- `Số lượng`
- `Ưu tiên`
- `Hạn chót`
- `Người tạo`
- `Trạng thái`
- `Kết quả`

---

## 4. Luồng tạo post tuyển dụng

### 4.1. Mở form tạo mới
Route:
- `route('tuyendung.create')`

Controller:
- `TuyenDungController@create()`

Xử lý:
1. Load danh sách `parts`
2. Load danh sách `positions`
3. Trả về view `tuyendung::TuyenDung.create`

### 4.2. Submit form
Route:
- `route('tuyendung.store')`

Controller:
- `TuyenDungController@store(Request $request)`

Xử lý:
1. Validate dữ liệu:
   - `part_id`
   - `position_id`
   - `number`
   - `prioritize`
   - `deadline`
   - `social`
   - `obstacle`
   - `solution`
   - `status`
   - `result`
2. Gán:
   - `user_id = Auth::id()`
   - `status` mặc định nếu chưa truyền
   - `result` mặc định nếu chưa truyền
3. Lưu vào bảng `recruitments`
4. Redirect về `tuyendung.list`

---

## 5. Luồng public trên trang chủ

### 5.1. Route trang chủ
File:
- `routes/web.php`

Hiện tại route `/` gọi:
- `HomeController@index`

### 5.2. Controller trang chủ
File:
- `app/Http/Controllers/HomeController.php`

Xử lý:
1. Query `Recruitment::with(['part', 'position', 'user'])`
2. Lấy 6 post mới nhất
3. Trả về `resources/views/home/index.blade.php`

### 5.3. View trang chủ
File:
- `resources/views/home/index.blade.php`

Hiển thị block:
- `Tuyển dụng`
- danh sách post mới nhất
- các thông tin:
  - vị trí
  - bộ phận
  - số lượng
  - người đăng
  - hạn chót
  - trạng thái
  - kênh đăng tuyển
  - vấn đề
  - giải pháp

---

## 6. Các file chính liên quan

### 6.1. Cấu hình module
- `Modules/TuyenDung/module.json`
- `Modules/TuyenDung/app/Providers/TuyenDungServiceProvider.php`
- `Modules/TuyenDung/app/Providers/EventServiceProvider.php`
- `Modules/TuyenDung/app/Providers/RouteServiceProvider.php`

### 6.2. Route
- `Modules/TuyenDung/routes/web.php`
- `routes/web.php`

### 6.3. Controller
- `Modules/TuyenDung/app/Http/Controllers/TuyenDungController.php`
- `app/Http/Controllers/HomeController.php`

### 6.4. Model
- `app/Models/Recruitment.php`

### 6.5. View
- `Modules/TuyenDung/resources/views/TuyenDung/index.blade.php`
- `Modules/TuyenDung/resources/views/TuyenDung/create.blade.php`
- `resources/views/home/index.blade.php`
- `resources/views/layouts/parts/sidebar.blade.php`

### 6.6. Bootstrap / trạng thái module
- `bootstrap/providers.php`
- `modules_statuses.json`

---

## 7. Database đang được sử dụng

Module tuyển dụng hiện dùng database có sẵn:
- `recruitments`
- `parts`
- `positions`
- `users`

Không có migration mới nào được thêm cho module này.

---

## 8. Điểm đã tối ưu để dễ bảo trì

### 8.1. Model `Recruitment`
Đã chuẩn hoá:
- `PRIORITY_LOW`
- `PRIORITY_MEDIUM`
- `PRIORITY_HIGH`
- `STATUS_RECRUITING`
- `STATUS_DONE`
- `STATUS_LATE`

Và helper:
- `getPriorityLabel()`
- `getPriorityBadgeClass()`
- `getStatusLabel()`
- `getStatusBadgeClass()`

### 8.2. Sidebar
Sidebar đã được cập nhật để:
- có link `Nhân sự → Tuyển dụng`
- tự mở đúng menu khi ở route `tuyen-dung*`

### 8.3. Trang tuyển dụng
Trang admin:
- dùng layout chuẩn admin
- có DataTable
- có route AJAX riêng
- giao diện giữ đúng style hiện tại

### 8.4. Trang chủ
Trang home:
- không query DB trong route nữa
- logic đã chuyển qua `HomeController`

---

## 9. Những chỗ cần chú ý khi bảo trì sau này

### 9.1. Dễ sửa
- đổi label trạng thái
- đổi màu badge
- thêm cột DataTable
- thêm filter tìm kiếm
- thêm action edit/delete

### 9.2. Nên tách tiếp nếu module lớn lên
- tách query tuyển dụng sang service/repository
- tách map response DataTable sang helper riêng
- tách logic public/home nếu sau này có nhiều block khác

---

## 10. Luồng hoạt động hiện tại, mô tả ngắn gọn

### Admin
Sidebar → Tuyển dụng → danh sách post → DataTable → dữ liệu từ `recruitments`

### Tạo post
Tạo post mới → form → validate → lưu `recruitments` → quay lại danh sách

### Public
Trang chủ → lấy 6 post mới nhất → hiển thị block tuyển dụng

---

## 11. Kết luận
Module `TuyenDung` hiện tại:
- đang dùng database có sẵn
- không thay đổi cấu trúc dự án gốc
- có route, controller, view, sidebar, public page
- đã được chuẩn hoá thêm để dễ bảo trì hơn
- luồng chạy rõ ràng, dễ debug và dễ mở rộng
