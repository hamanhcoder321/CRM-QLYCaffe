<style>
    .form-section {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        padding: 25px;
        margin-bottom: 25px;
        border: 1px solid #f3f4f6;
        transition: box-shadow 0.3s ease;
    }

    .form-section:hover {
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.06);
    }

    .form-section-title {
        font-size: 1.15rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 22px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-section-title i {
        font-size: 1.25rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        font-weight: 500;
        color: #4b5563;
        margin-bottom: 8px;
        font-size: 0.95rem;
        display: block;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        border: 1px solid #d1d5db;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        color: #374151;
        background-color: #f9fafb;
        transition: all 0.25s ease;
        width: 100%;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #3b82f6;
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        outline: none;
    }

    .text-danger.small {
        margin-top: 6px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .form-control::placeholder {
        color: #9ca3af;
    }
</style>

<div class="row">
    <!-- Thông tin tài khoản -->
    <div class="col-12">
        <div class="form-section">
            <h4 class="form-section-title">
                <i class="fas fa-user-circle text-primary"></i> Thông Tin Tài Khoản
            </h4>
            <div class="row">
                <div class="col-md-3 form-group">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="admin@example.com"
                        value="{{ old('email', $user->email ?? '') }}">
                    @error('email')<div class="text-danger small"><i class="fas fa-exclamation-circle"></i>
                    {{ $message }}</div>@enderror
                </div>
                <div class="col-md-3 form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="Bỏ trống nếu không đổi" value="">
                    @error('password')<div class="text-danger small"><i class="fas fa-exclamation-circle"></i>
                    {{ $message }}</div>@enderror
                </div>
                <div class="col-md-3 form-group">
                    <label for="type_account">Loại tài khoản</label>
                    <select name="type_account" id="type_account" class="form-control form-select">
                        <option value="">-- Chọn loại tài khoản --</option>
                        @if(isset($option['type_account']))
                            @foreach($option['type_account'] as $items)
                                <option value="{{ $items['id'] }}" {{ ($items['id'] == old('type_accounts_id', $user->type_accounts_id ?? '')) ? 'selected' : '' }}>
                                    {{ $items['text'] }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('type_account')<div class="text-danger small"><i class="fas fa-exclamation-circle"></i>
                    {{ $message }}</div>@enderror
                </div>
                <div class="col-md-3 form-group">
                    <label for="status">Trạng thái</label>
                    <select name="status" id="status" class="form-control form-select">
                        <option value="">-- Chọn trạng thái --</option>
                        @if(isset($option['status']))
                            @foreach($option['status'] as $items)
                                <option value="{{ $items['id'] }}" {{ ($items['id'] == old('status', $user->status ?? '')) ? 'selected' : '' }}>
                                    {{ $items['text'] }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('status')<div class="text-danger small"><i class="fas fa-exclamation-circle"></i>
                    {{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Thông tin cá nhân -->
    <div class="col-md-12">
        <div class="form-section">
            <h4 class="form-section-title">
                <i class="fas fa-id-card text-success"></i> Thông Tin Cá Nhân
            </h4>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="name">Họ Tên <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="VD: Nguyễn Văn A"
                        value="{{ old('name', $user->name ?? '') }}">
                    @error('name')<div class="text-danger small"><i class="fas fa-exclamation-circle"></i>
                    {{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="birthday">Ngày Sinh</label>
                    <input type="date" name="birthday" id="birthday" class="form-control"
                        value="{{ old('birthday', $user->birthday ?? '') }}">
                    @error('birthday')<div class="text-danger small"><i class="fas fa-exclamation-circle"></i>
                    {{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="">Giới Tính</label>
                    <select name="sex" class="form-control form-select">
                        <option value="">-- Chọn giới tính --</option>
                        @foreach($option['genders'] as $items)
                            <option value="{{ $items['id'] }}" {{ ($items['id'] == old('sex', $user->sex ?? '')) ? 'selected' : '' }}>
                                {{ $items['text'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('sex')<div class="text-danger small"><i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>@enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="phone">Số điện thoại</label>
                    <input type="text" name="phone" id="phone" class="form-control" placeholder="VD: 09xxxxxxxxx"
                        value="{{ old('phone', $user->phone ?? '') }}">
                    @error('phone')<div class="text-danger small"><i class="fas fa-exclamation-circle"></i>
                    {{ $message }}</div>@enderror
                </div>
                <div class="col-md-8 form-group">
                    <label for="address">Địa chỉ</label>
                    <input type="text" name="address" id="address" class="form-control"
                        placeholder="Nhập đầy đủ địa chỉ thường trú" value="{{ old('address', $user->address ?? '') }}">
                    @error('address')<div class="text-danger small"><i class="fas fa-exclamation-circle"></i>
                    {{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Thông tin công việc -->
    <div class="col-md-12">
        <div class="form-section">
            <h4 class="form-section-title">
                <i class="fas fa-briefcase text-info"></i> Thông Tin Công Việc
            </h4>
            <div class="row">
                <div class="col-md-3 form-group">
                    <label for="branch_name">Chi nhánh</label>
                    <select name="branch_id" id="branch" class="form-control form-select">
                        <option value="">-- Chọn chi nhánh --</option>
                        @if(isset($option['branch']))
                            @foreach($option['branch'] as $items)
                                <option value="{{ $items['id'] }}" {{ ($items['id'] == old('branch_id', $user->branch_id ?? '')) ? 'selected' : '' }}>
                                    {{ $items['text'] }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('branch_id')<div class="text-danger small"><i class="fas fa-exclamation-circle"></i>
                    {{ $message }}</div>@enderror
                </div>
                <div class="col-md-3 form-group">
                    <label for="part">Bộ phận</label>
                    <select name="part" id="part" class="form-control form-select">
                        <option value="">-- Chọn bộ phận --</option>
                        @if(isset($option['part']))
                            @foreach($option['part'] as $items)
                                <option value="{{ $items['id'] }}" {{ ($items['id'] == old('part', $user->part_id ?? '')) ? 'selected' : '' }}>
                                    {{ $items['text'] }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('part')<div class="text-danger small"><i class="fas fa-exclamation-circle"></i>
                    {{ $message }}</div>@enderror
                </div>
                <div class="col-md-3 form-group">
                    <label for="team">Đội nhóm</label>
                    <select name="team" id="team" class="form-control form-select">
                        <option value="">-- Chọn đội nhóm --</option>
                        @if(isset($option['team']))
                            @foreach($option['team'] as $items)
                                <option value="{{ $items['id'] }}" {{ ($items['id'] == old('team', $user->team_id ?? '')) ? 'selected' : '' }}>
                                    {{ $items['text'] }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('team')<div class="text-danger small"><i class="fas fa-exclamation-circle"></i>
                    {{ $message }}</div>@enderror
                </div>
                <div class="col-md-3 form-group">
                    <label for="position">Vị trí</label>
                    <select name="position" id="position" class="form-control form-select">
                        <option value="">-- Chọn vị trí --</option>
                        @if(isset($option['position']))
                            @foreach($option['position'] as $items)
                                <option value="{{ $items['id'] }}" {{ ($items['id'] == old('position', $user->position_id ?? '')) ? 'selected' : '' }}>
                                    {{ $items['text'] }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('position')<div class="text-danger small"><i class="fas fa-exclamation-circle"></i>
                    {{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="type_work">Hình thức làm việc</label>
                    <select name="type_work" id="type_work" class="form-control form-select">
                        <option value="">-- Chọn hình thức --</option>
                        @if(isset($option['type_work']))
                            @foreach($option['type_work'] as $items)
                                <option value="{{ $items['id'] }}" {{ ($items['id'] == old('type_work', $user->type_work ?? '')) ? 'selected' : '' }}>
                                    {{ $items['text'] }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('type_work')<div class="text-danger small"><i class="fas fa-exclamation-circle"></i>
                    {{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="start_day">Ngày bắt đầu làm việc</label>
                    <input type="datetime" name="start_day" id="start_day" class="form-control"
                        value="{{ old('start_day', $user->start_day ?? '') }}">
                    @error('start_day')<div class="text-danger small"><i class="fas fa-exclamation-circle"></i>
                    {{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="end_day">Ngày nghỉ việc</label>
                    <input type="datetime" name="end_day" id="end_day" class="form-control"
                        value="{{ old('end_day', $user->end_day ?? '') }}">
                    @error('end_day')<div class="text-danger small"><i class="fas fa-exclamation-circle"></i>
                    {{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>
</div>
