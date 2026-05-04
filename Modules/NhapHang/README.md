# Module NhapHang

## 1. Mục đích
Module `NhapHang` dùng để:
- quản lý lô hàng
- quản lý đơn nhập
- quản lý nhà cung cấp
- theo dõi dữ liệu nhập hàng theo từng đợt

---

## 2. Luồng chạy của module

### 2.1. Luồng vào danh sách lô hàng
1. Người dùng vào route `/nhap-hang`
2. Route gọi `NhapHangController@index`
3. View trả về `nhaphang::NhapHang.list`
4. DataTable gọi route `/nhap-hang/data`

### 2.2. Luồng đổ dữ liệu lô hàng
1. `NhapHangController@getData(Request $request)`
2. Controller gọi repository `NhapHangRepositoryInterface`
3. Repository xử lý query và filter
4. Trả JSON về DataTable

### 2.3. Luồng tạo lô hàng
1. Submit form `/nhap-hang/store`
2. `NhapHangController@store`
3. Convert field rỗng thành `null`
4. Validate dữ liệu
5. Gọi repository để lưu
6. Trả JSON thành công

### 2.4. Luồng cập nhật lô hàng
1. Submit form `/nhap-hang/update/{arrange}`
2. `NhapHangController@update`
3. Convert field rỗng thành `null`
4. Validate dữ liệu
5. Gọi repository để cập nhật
6. Trả JSON thành công

### 2.5. Luồng đơn nhập
1. Người dùng vào `/nhap-hang/don-nhap`
2. `NhapHangController@donNhap`
3. View `nhaphang::NhapHang.don-nhap`
4. DataTable gọi `/nhap-hang/don-nhap/data`
5. `NhapHangController@donNhapData`
6. Query `Shipment::with('arrange', 'customer')`
7. Render danh sách đơn nhập

### 2.6. Luồng nhà cung cấp
1. Người dùng vào `/nhap-hang/nha-cung-cap`
2. `NhapHangController@nhaCungCap`
3. View `nhaphang::NhapHang.nha-cung-cap`
4. DataTable gọi `/nhap-hang/nha-cung-cap/data`
5. `NhapHangController@nhaCungCapData`
6. Query `Customer::withCount('shipments')`
7. Render danh sách nhà cung cấp

---

## 3. Các file liên quan

### 3.1. Route
- `Modules/NhapHang/routes/web.php`

### 3.2. Controller
- `Modules/NhapHang/app/Http/Controllers/NhapHangController.php`

### 3.3. Provider
- `Modules/NhapHang/app/Providers/NhapHangServiceProvider.php`
- `Modules/NhapHang/app/Providers/RouteServiceProvider.php`
- `Modules/NhapHang/app/Providers/EventServiceProvider.php`

### 3.4. View
- `Modules/NhapHang/resources/views/`

### 3.5. Repository
- `Modules/NhapHang/app/Repositories/`
- `Modules/NhapHang/app/Repositories/Interfaces/`

---

## 4. Database đang được sử dụng
Module `NhapHang` dùng các bảng có sẵn:
- `arranges`
- `shipments`
- `customers`
- `parts`
- `teams`
- `users`

Không thêm migration mới nào cho module này.

---

## 5. Đặc điểm bảo trì
- Module đã tách repository cho phần lô hàng chính
- Controller còn chứa nhiều nhóm nghiệp vụ
- Luồng dữ liệu khá rõ:
  - lô hàng
  - đơn nhập
  - nhà cung cấp
- Dễ debug nếu lỗi thường nằm ở:
  - repository binding
  - route DataTable
  - quan hệ `arrange`, `customer`
  - view admin / modal / AJAX

---

## 6. Kết luận
Module `NhapHang` là module quản lý đầu vào hàng hoá của hệ thống:
- luồng rõ
- repository giúp tách query
- phù hợp để mở rộng thêm filter, thống kê, quản lý chi tiết sau này
