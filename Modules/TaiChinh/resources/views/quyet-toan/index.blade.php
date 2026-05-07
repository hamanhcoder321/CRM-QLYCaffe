@include('layouts/parts/header')
@include('layouts/parts/sidebar')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold">Bảng quyết toán tháng {{ $month }}/{{ $year }}</h1>
                    <p class="text-muted mb-0">Tính lương nhân sự và quản lý chi phí phát sinh.</p>
                </div>
                <div class="col-sm-6">
                    <form action="{{ route('taichinh.quyet-toan') }}" method="GET" class="form-inline float-sm-right">
                        <div class="form-group mr-1">
                            <select name="branch_id" class="form-control form-control-sm">
                                <option value="">Tất cả chi nhánh</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mr-1">
                            <select name="user_type" class="form-control form-control-sm">
                                <option value="">Nhân sự/Quản lý</option>
                                <option value="manager" {{ request('user_type') == 'manager' ? 'selected' : '' }}>Chỉ Quản lý</option>
                                <option value="staff" {{ request('user_type') == 'staff' ? 'selected' : '' }}>Chỉ Nhân viên</option>
                            </select>
                        </div>
                        <div class="form-group mr-1">
                            <select name="month" class="form-control form-control-sm">
                                @for($m=1; $m<=12; $m++)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>Tháng {{ $m }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group mr-1">
                            <select name="year" class="form-control form-control-sm">
                                @for($y=date('Y')-1; $y<=date('Y')+1; $y++)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Năm {{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-filter"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            {{-- THỐNG KÊ TỔNG QUAN --}}
            <div class="row">
                @php
                    $activeSalaries = collect($salaryData)->filter();
                    $totalPaid = $activeSalaries->sum('final_salary');
                    $totalExpenses = $expenses->sum('money');
                @endphp
                <div class="col-md-4">
                    <div class="small-box bg-info shadow-sm">
                        <div class="inner">
                            <h3>{{ number_format($totalPaid) }} <small class="text-white" style="font-size: 15px">VNĐ</small></h3>
                            <p>Tổng quỹ lương chi trả</p>
                        </div>
                        <div class="icon"><i class="fas fa-users"></i></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-success shadow-sm">
                        <div class="inner">
                            <h3>{{ number_format($totalPaid + $totalExpenses) }} <small class="text-white" style="font-size: 15px">VNĐ</small></h3>
                            <p>Tổng chi phí quyết toán</p>
                        </div>
                        <div class="icon"><i class="fas fa-calculator"></i></div>
                    </div>
                </div>
            </div>

            {{-- BẢNG LƯƠNG NHÂN SỰ --}}
            <div class="card card-outline card-primary shadow-sm mb-4">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-user-tie mr-1"></i> Bảng lương nhân sự</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="thead-light">
                                    <tr>
                                        <th style="width: 20%">Nhân sự</th>
                                        <th>Vị trí</th>
                                        <th>Công (Ngày/Giờ)</th>
                                        <th>Mức lương thỏa thuận</th>
                                        <th>Tiền lương theo công</th>
                                        <th>Phụ cấp</th>
                                        <th>Giữ lương</th>
                                        <th class="text-right">Thực nhận</th>
                                    </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    @php $s = $salaryData[$user->id] ?? null; @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $user->name }}</strong>
                                            <div class="text-muted small">{{ $user->branch?->name ?? '—' }}</div>
                                            @if(!$s)
                                                <div class="text-danger small font-italic"><i class="fas fa-exclamation-triangle"></i> Chưa cấu hình lương</div>
                                            @endif
                                        </td>
                                        <td>{{ $user->position?->name ?? '—' }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ number_format($s->total_days ?? 0, 1) }} ngày</span><br>
                                            <span class="badge badge-secondary">{{ number_format($s->total_hours ?? 0, 1) }} giờ</span>
                                            @if($s && $s->total_hours > 0)
                                                <div class="mt-1">
                                                    <a href="javascript:void(0)" class="text-xs text-primary" onclick="viewWorkDetail({{ $user->id }}, '{{ $user->name }}')">
                                                        <i class="fas fa-search-plus"></i> Chi tiết
                                                    </a>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $s ? number_format($s->agreement_salary) : '—' }}</strong>
                                            <div class="text-muted small">VNĐ /{{ $user->isManagerSalary() ? 'tháng' : 'giờ' }}</div>
                                        </td>
                                        <td>
                                            <span class="text-success font-weight-bold">{{ $s ? number_format($s->base_salary) : '—' }}</span>
                                            <div class="text-muted small">VNĐ (Công thực tế)</div>
                                        </td>
                                        <td>{{ $s ? number_format($s->allowances) : '—' }} <small>VNĐ</small></td>
                                        <td class="text-danger">{{ $s ? number_format($s->keep_salary) : '—' }} <small>VNĐ</small></td>
                                        <td class="text-right font-weight-bold text-primary">
                                            {{ $s ? number_format($s->final_salary) : '0' }} <small>VNĐ</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-center">Không có dữ liệu nhân sự.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL CHI TIẾT CÔNG --}}
<div class="modal fade" id="modalWorkDetail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-calendar-alt mr-2"></i>Chi tiết công: <span id="detail-user-name"></span></h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="pl-3">Ngày</th>
                            <th>Ca làm</th>
                            <th>Số giờ</th>
                            <th>Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody id="detail-work-body">
                        {{-- Data via AJAX --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('layouts/parts/footer')

<script>
function viewWorkDetail(userId, userName) {
    $('#detail-user-name').text(userName);
    $('#detail-work-body').html('<tr><td colspan="4" class="text-center py-3"><i class="fas fa-spinner fa-spin mr-1"></i> Đang tải dữ liệu...</td></tr>');
    $('#modalWorkDetail').modal('show');

    $.ajax({
        url: '{{ route("taichinh.quyet-toan.detail", ":id") }}'.replace(':id', userId),
        type: 'GET',
        data: {
            month: '{{ $month }}',
            year: '{{ $year }}'
        },
        success: function(res) {
            let html = '';
            if (res.length > 0) {
                res.forEach(item => {
                    let date = new Date(item.day);
                    let formattedDate = date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear();
                    html += `<tr>
                        <td class="pl-3">${formattedDate}</td>
                        <td><span class="badge badge-info">${item.shift}</span></td>
                        <td><strong>${item.hour}</strong> giờ</td>
                        <td class="text-muted small">${item.note || '—'}</td>
                    </tr>`;
                });
            } else {
                html = '<tr><td colspan="4" class="text-center py-3 text-muted">Không có dữ liệu chi tiết.</td></tr>';
            }
            $('#detail-work-body').html(html);
        }
    });
}
</script>
