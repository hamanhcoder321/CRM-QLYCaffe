# Module User

## 1. Mục đích
Module `User` dùng để:
- quản lý tài khoản nhân sự
- tạo mới / cập nhật / xoá nhân sự
- lọc và xem danh sách user

---

## 2. Luồng chạy của module

### 2.1. Luồng vào danh sách
1. Người dùng vào route `/users`
2. Route gọi `UserController@index`
3. Controller trả về view `users::users.list`
4. View dùng DataTable để gọi route `/users/data`

### 2.2. Luồng đổ dữ liệu
1. DataTable gọi `UserController@getUsersData`
2. Controller gọi repository `UserRepositoryInterface`
3. Repository xử lý query
4. Trả JSON về DataTable

### 2.3. Luồng tạo mới
1. Người dùng vào `/users/created`
2. `UserController@created`
3. Load options từ repository
4. Trả về form tạo mới

### 2.4. Luồng lưu user
1. Submit form `/users/create`
2. `UserController@store`
3. Validate dữ liệu
4. Gọi repository để lưu
5. Redirect về danh sách user

### 2.5. Luồng cập nhật
1. Vào `/users/update/{user}`
2. `UserController@edit`
3. Load data user + option
4. Submit form `/users/update/{user}`
5. `UserController@update`
6. Validate và gọi repository update
7. Redirect về danh sách

### 2.6. Luồng xoá
1. Bấm xoá ở danh sách
2. Gọi route `/users/delete/{user}`
3. `UserController@destroy`
4. Repository xoá dữ liệu
5. Redirect về danh sách

---

## 3. Các file liên quan

### 3.1. Route
- `Modules/User/routes/web.php`

### 3.2. Controller
- `Modules/User/app/Http/Controllers/UserController.php`

### 3.3. Provider
- `Modules/User/app/Providers/UserServiceProvider.php`
- `Modules/User/app/Providers/RouteServiceProvider.php`
- `Modules/User/app/Providers/EventServiceProvider.php`

### 3.4. View
- `Modules/User/resources/views/`

### 3.5. Repository
- `Modules/User/app/Repositories/`
- `Modules/User/app/Repositories/Interfaces/`

---

## 4. Đặc điểm bảo trì
- Module này đã tách repository khá rõ
- Controller tương đối mỏng
- Dễ thay đổi query / filter / option sau này
- Nếu gặp lỗi thường kiểm tra:
  - repository binding trong provider
  - route `users.data`
  - view DataTable
  - namespace `Modules\User\...`

---

## 5. Kết luận
Module `User` là module mẫu cho các nghiệp vụ quản trị nhân sự trong hệ thống:
- có luồng rõ ràng
- dùng repository
- dễ debug
- dễ mở rộng
