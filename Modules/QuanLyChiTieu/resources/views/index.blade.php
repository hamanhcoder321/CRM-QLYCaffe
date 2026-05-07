@include('layouts/parts/header')
@include('layouts/parts/sidebar')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold">Quản lý chi tiêu phát sinh</h1>
                    <p class="text-muted mb-0">Theo dõi và quản lý các khoản chi phí hàng ngày của chi nhánh.</p>
                </div>
                <div class="col-sm-6 text-right">
                    <button class="btn btn-primary" onclick="addExpense()">
                        <i class="fas fa-plus mr-1"></i> Thêm chi phí
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <form action="{{ route('quanlychitieu.index') }}" method="GET" class="form-inline">
                        <select name="branch_id" class="form-control form-control-sm mr-2">
                            <option value="">Tất cả chi nhánh</option>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm btn-info"><i class="fas fa-filter"></i> Lọc</button>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0" id="expenseTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>Ngày</th>
                                    <th>Nội dung</th>
                                    <th>Số tiền</th>
                                    <th>Loại chi phí</th>
                                    <th>Hình thức</th>
                                    <th>Chi nhánh</th>
                                    <th style="width: 120px">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $e)
                                <tr>
                                    <td>{{ date('d/m/Y', strtotime($e->day)) }}</td>
                                    <td>{{ $e->content }}</td>
                                    <td class="font-weight-bold text-danger">{{ number_format($e->money) }}</td>
                                    <td><span class="badge badge-warning">{{ $e->typeFee?->name ?? 'Khác' }}</span></td>
                                    <td>{{ $e->atm?->name ?? 'Tiền mặt' }}</td>
                                    <td>{{ $e->branch?->name ?? '—' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info mr-1" onclick="editExpense({{ $e->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('quanlychitieu.destroy', $e->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="expenseModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="expenseForm">
                @csrf
                <input type="hidden" name="id" id="expense_id">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold" id="modalTitle">Thêm chi phí mới</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Ngày chi</label>
                        <input type="date" name="day" id="day" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label>Nội dung chi</label>
                        <input type="text" name="content" id="content" class="form-control" placeholder="Ví dụ: Tiền điện tháng 5" required>
                    </div>
                    <div class="form-group">
                        <label>Số tiền (VNĐ)</label>
                        <input type="number" name="money" id="money" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Loại chi phí</label>
                        <select name="type_fee_id" id="type_fee_id" class="form-control" required>
                            @foreach($typeFees as $tf)
                                <option value="{{ $tf->id }}">{{ $tf->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Hình thức thanh toán</label>
                        <select name="atm_id" id="atm_id" class="form-control">
                            <option value="">Tiền mặt</option>
                            @foreach($atms as $atm)
                                <option value="{{ $atm->id }}">{{ $atm->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Chi nhánh</label>
                        <select name="branch_id" id="branch_id" class="form-control" required>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}" {{ auth()->user()->branch_id == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu lại</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function addExpense() {
        $('#expenseForm')[0].reset();
        $('#expense_id').val('');
        $('#modalTitle').text('Thêm chi phí mới');
        $('#expenseModal').modal('show');
    }

    function editExpense(id) {
        $.get("{{ url('quan-ly-chi-tieu/edit') }}/" + id, function(res) {
            $('#expense_id').val(res.id);
            $('#day').val(res.day);
            $('#content').val(res.content);
            $('#money').val(res.money);
            $('#type_fee_id').val(res.type_fee_id);
            $('#atm_id').val(res.atm_id);
            $('#branch_id').val(res.branch_id);
            $('#modalTitle').text('Cập nhật chi phí');
            $('#expenseModal').modal('show');
        });
    }

    $('#expenseForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#expense_id').val();
        let url = id ? "{{ url('quan-ly-chi-tieu/update') }}/" + id : "{{ route('quanlychitieu.store') }}";
        
        $.ajax({
            url: url,
            method: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                Swal.fire('Thành công!', res.message, 'success').then(() => {
                    location.reload();
                });
            },
            error: function(err) {
                Swal.fire('Lỗi!', 'Vui lòng kiểm tra lại thông tin.', 'error');
            }
        });
    });
</script>
@endpush

@include('layouts/parts/footer')
