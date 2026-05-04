# Module TuyenDung

## 1. Mục đích
Module `TuyenDung` dùng để:
- tạo post tuyển dụng trong trang quản trị
- hiển thị danh sách post tuyển dụng trong giao diện admin
- đổ post tuyển dụng ra trang chủ `home/index`
- dùng lại dữ liệu database có sẵn, không thay đổi cấu trúc bảng

---

## 2. Luồng chạy của module

### 2.1. Luồng quản trị
Người dùng vào sidebar:
- **Nhân sự → Tuyển dụng**

Các bước:
1. Sidebar gọi route `tuyendung.list`
2. `Modules\TuyenDung\Http\Controllers\TuyenDungController@index`
3. View `Modules/TuyenDung/resources/views/TuyenDung/index.blade.php`
4. DataTable gọi route `tuyendung.data`
5. `TuyenDungController@data`
6. Query bảng `recruitments`
7. Trả JSON về DataTable

### 2.2. Luồng tạo post
1. Bấm `Tạo post mới`
2. Route `tuyendung.create`
3. `TuyenDungController@create`
4. Load `parts` và `positions`
5. View `tuyendung::TuyenDung.create`

### 2.3. Luồng lưu post
1. Submit form `tuyendung.store`
2. `TuyenDungController@store`
3. Validate dữ liệu
4. Gán `user_id = Auth::id()`
5. Lưu vào `recruitments`
6. Redirect về `tuyendung.list`

### 2.4. Luồng public
1. Route `/`
2. `HomeController@index`
3. Query 6 tuyển dụng mới nhất
4. Trả view `home.index`
5. Home hiển thị block “Tuyển dụng”

---

## 3. Các file liên quan

### 3.1. Cấu hình module
- `Modules/TuyenDung/module.json`
- `Modules/TuyenDung/app/Providers/TuyenDungServiceProvider.php`
- `Modules/TuyenDung/app/Providers/EventServiceProvider.php`
- `Modules/TuyenDung/app/Providers/RouteServiceProvider.php`

### 3.2. Route
- `Modules/TuyenDung/routes/web.php`
- `routes/web.php`

### 3.3. Controller
- `Modules/TuyenDung/app/Http/Controllers/TuyenDungController.php`
- `app/Http/Controllers/HomeController.php`

### 3.4. Model
- `app/Models/Recruitment.php`

### 3.5. View
- `Modules/TuyenDung/resources/views/TuyenDung/index.blade.php`
- `Modules/TuyenDung/resources/views/TuyenDung/create.blade.php`
- `resources/views/home/index.blade.php`
- `resources/views/layouts/parts/sidebar.blade.php`

### 3.6. Bootstrap / trạng thái module
- `bootstrap/providers.php`
- `modules_statuses.json`

---

## 4. Database đang được sử dụng
Module tuyển dụng hiện dùng database có sẵn:
- `recruitments`
- `parts`
- `positions`
- `users`

Không thêm migration mới nào cho module này.

---

## 5. Luồng dữ liệu DataTable
Route:
- `route('tuyendung.data')`

Controller:
- `TuyenDungController@data(Request $request)`

Xử lý:
1. Check request AJAX
2. Query `Recruitment::with(['part', 'position', 'user'])->latest()`
3. Map dữ liệu:
   - `part_name`
   - `position_name`
   - `user_name`
   - `prioritize`
   - `status`
   - `result`
   - `deadline`
4. Trả JSON cho DataTable

Cột hiển thị:
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

## 6. Điểm đã tối ưu để dễ bảo trì

### 6.1. Model `Recruitment`
Đã chuẩn hoá:
- `PRIORITY_LOW`
- `PRIORITY_MEDIUM`
- `PRIORITY_HIGH`
- `STATUS_RECRUITING`
- `STATUS_DONE`
- `STATUS_LATE`

Helper:
- `getPriorityLabel()`
- `getPriorityBadgeClass()`
- `getStatusLabel()`
- `getStatusBadgeClass()`

### 6.2. Sidebar
Sidebar đã được cập nhật để:
- có link `Nhân sự → Tuyển dụng`
- tự mở đúng menu khi ở route `tuyen-dung*`

### 6.3. Trang tuyển dụng
Trang admin:
- dùng layout chuẩn admin
- có DataTable
- có route AJAX riêng
- giao diện giữ đúng style hiện tại

### 6.4. Trang chủ
Trang home:
- không query DB trong route nữa
- logic đã chuyển qua `HomeController`

---

## 7. Những chỗ cần chú ý khi bảo trì sau này

### Dễ sửa
- đổi label trạng thái
- đổi màu badge
- thêm cột DataTable
- thêm filter tìm kiếm
- thêm action edit/delete

### Nên tách tiếp nếu module lớn lên
- tách query tuyển dụng sang service/repository
- tách map response DataTable sang helper riêng
- tách logic public/home nếu sau này có nhiều block khác

---

## 8. Kết luận
Module `TuyenDung` hiện tại:
- đang dùng database có sẵn
- không thay đổi cấu trúc dự án gốc
- có route, controller, view, sidebar, public page
- đã được chuẩn hoá thêm để dễ bảo trì hơn
- luồng chạy rõ ràng, dễ debug và dễ mở rộng
