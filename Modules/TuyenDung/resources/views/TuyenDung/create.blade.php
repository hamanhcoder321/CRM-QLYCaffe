@include('layouts/parts/header')
@include('layouts/parts/sidebar')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold">Tạo post tuyển dụng</h1>
                    <p class="text-muted mb-0">Đăng thông báo tuyển vị trí theo bộ phận, số lượng và mức ưu tiên.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('tuyendung.list') }}">Tuyển dụng</a></li>
                        <li class="breadcrumb-item active">Tạo mới</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('tuyendung.store') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="part_id">Bộ phận</label>
                                <select name="part_id" id="part_id" class="custom-select" required>
                                    <option value="">-- Chọn bộ phận --</option>
                                    @foreach ($parts as $part)
                                        <option value="{{ $part->id }}" @selected(old('part_id') == $part->id)>{{ $part->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="position_id">Vị trí</label>
                                <select name="position_id" id="position_id" class="custom-select" required>
                                    <option value="">-- Chọn vị trí --</option>
                                    @foreach ($positions as $position)
                                        <option value="{{ $position->id }}" @selected(old('position_id') == $position->id)>{{ $position->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="number">Số lượng cần tuyển</label>
                                <input type="number" min="1" name="number" id="number" class="form-control" value="{{ old('number', 1) }}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="prioritize">Mức ưu tiên</label>
                                <select name="prioritize" id="prioritize" class="custom-select" required>
                                    <option value="0" @selected(old('prioritize', 0) == 0)>Thấp</option>
                                    <option value="1" @selected(old('prioritize') == 1)>Trung bình</option>
                                    <option value="2" @selected(old('prioritize') == 2)>Cao</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="deadline">Hạn chót</label>
                                <input type="datetime-local" name="deadline" id="deadline" class="form-control" value="{{ old('deadline') }}">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="social">Kênh đăng tuyển</label>
                                <input type="text" name="social" id="social" class="form-control" value="{{ old('social') }}" placeholder="Facebook, Zalo, Website...">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="status">Trạng thái</label>
                                <select name="status" id="status" class="custom-select">
                                    <option value="0" @selected(old('status', 0) == 0)>Đang tuyển</option>
                                    <option value="1" @selected(old('status') == 1)>Hoàn thành</option>
                                    <option value="2" @selected(old('status') == 2)>Trễ</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="obstacle">Vấn đề / trở ngại</label>
                                <input type="text" name="obstacle" id="obstacle" class="form-control" value="{{ old('obstacle') }}" placeholder="Ví dụ: thiếu ứng viên phù hợp">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="solution">Giải pháp</label>
                                <input type="text" name="solution" id="solution" class="form-control" value="{{ old('solution') }}" placeholder="Ví dụ: tăng ngân sách quảng cáo">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="result">Kết quả</label>
                                <select name="result" id="result" class="custom-select">
                                    <option value="0" @selected(old('result', 0) == 0)>Chưa có</option>
                                    <option value="1" @selected(old('result') == 1)>Đạt</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('tuyendung.list') }}" class="btn btn-light mr-2">Hủy</a>
                            <button type="submit" class="btn btn-primary">Đăng tuyển</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<aside class="control-sidebar control-sidebar-dark"></aside>
@include('layouts/parts/footer')
