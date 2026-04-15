@include('layouts/parts/header')
@include('layouts/parts/sidebar')

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2 align-items-center">
        <div class="col-sm-6">
          <h1 class="m-0 font-weight-bold"><i class="fas fa-cash-register text-success mr-2"></i>Giao Dịch Bán Hàng</h1>
        </div>
        <div class="col-sm-6 d-flex justify-content-end">
          <ol class="breadcrumb mr-3 mb-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Bán hàng</a></li>
            <li class="breadcrumb-item active">Giao dịch</li>
          </ol>
          <button class="btn btn-success btn-sm pl-3 pr-3" id="btn-new-sell" data-toggle="modal" data-target="#sellModal">
            <i class="fas fa-plus mr-1"></i>Tạo giao dịch
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">

      {{-- Stat cards --}}
      <div class="row mb-4">
        <div class="col-md-3">
          <div class="info-box shadow-sm">
            <span class="info-box-icon bg-success"><i class="fas fa-receipt"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Tổng giao dịch</span>
              <span class="info-box-number" id="stat-gd-total">—</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="info-box shadow-sm">
            <span class="info-box-icon bg-primary"><i class="fas fa-check-double"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Đã bán</span>
              <span class="info-box-number" id="stat-gd-done">—</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="info-box shadow-sm">
            <span class="info-box-icon bg-info"><i class="fas fa-dollar-sign"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Tổng doanh thu</span>
              <span class="info-box-number" id="stat-gd-revenue" style="font-size:16px">—</span>
            </div>
          </div>
        </div>
        {{-- <div class="col-md-3">
          <div class="info-box shadow-sm">
            <span class="info-box-icon bg-warning"><i class="fas fa-chart-line"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Tổng lợi nhuận</span>
              <span class="info-box-number" id="stat-gd-profit" style="font-size:16px">—</span>
            </div>
          </div>
        </div> --}}
      </div>

      <div class="card shadow-sm">
        <div class="card-header border-0">
          <h3 class="card-title font-weight-bold mb-0"><i class="fas fa-list mr-2 text-success"></i>Danh sách giao dịch</h3>
        </div>
        <div class="card-body table-responsive p-0">
          <table id="sell-table" class="table table-hover table-striped mb-0 text-sm">
            <thead class="bg-gradient-success">
              <tr>
                <th class="pl-3">#</th>
                <th>Tên giao dịch</th>
                <th>Lô hàng</th>
                <th>Ngày bán</th>
                <th>Sản phẩm</th>
                <th>Doanh thu</th>
                <th>Lợi nhuận</th>
                <th>Trạng thái</th>
                <th class="text-center">Thao tác</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>

{{-- MODAL TẠO GIAO DỊCH --}}
<div class="modal fade" id="sellModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header bg-gradient-success text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i>Tạo Hóa Đơn</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-md-4">
            <label>Ngày bán <span class="text-danger">*</span></label>
            <input type="date" id="sell-day" class="form-control" value="{{ date('Y-m-d') }}">
          </div>
          <div class="col-md-4">
            <label>Tên giao dịch</label>
            <input type="text" id="sell-name" class="form-control" placeholder="VD: Bán sáng T2 24/3">
          </div>
          <div class="col-md-4">
            <label>Lô hàng liên kết</label>
            <select id="sell-shipment" class="form-control">
              <option value="">-- Chọn lô hàng --</option>
              @foreach($shipments as $s)
                <option value="{{ $s['id'] }}">{{ $s['name'] }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-4">
            <label>Trạng thái <span class="text-danger">*</span></label>
            <select id="sell-status" class="form-control">
              <option value="1">Đã bán</option>
              <option value="0">Chưa bán</option>
              <option value="2">Lưu kho</option>
            </select>
          </div>
        </div>

        <hr>
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="font-weight-bold mb-0"><i class="fas fa-shopping-cart mr-2 text-success"></i>Chi tiết sản phẩm</h6>
          <button type="button" class="btn btn-sm btn-outline-success" id="btn-add-item">
            <i class="fas fa-plus mr-1"></i>Thêm sản phẩm
          </button>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered text-sm" id="item-table">
            <thead class="bg-light">
              <tr>
                <th style="width:35%">Thức uống</th>
                <th style="width:15%">Tồn kho</th>
                <th style="width:15%">Số lượng bán</th>
                <th style="width:15%">Đơn giá (đ)</th>
                <th style="width:15%">Thành tiền</th>
                <th style="width:5%"></th>
              </tr>
            </thead>
            <tbody id="sell-items">
              <tr class="item-row">
                <td>
                  <select class="form-control form-control-sm product-select">
                    <option value="">-- Chọn thức uống --</option>
                    @foreach($products as $p)
                      <option value="{{ $p->id }}" data-price="{{ $p->price }}" data-tonkho="{{ $p->ton_kho }}">
                        {{ $p->name }} (còn {{ $p->ton_kho }})
                      </option>
                    @endforeach
                  </select>
                </td>
                <td><span class="ton-kho-badge text-muted">—</span></td>
                <td><input type="number" class="form-control form-control-sm qty-input" value="1" min="1"></td>
                <td><input type="number" class="form-control form-control-sm price-input" value="0" min="0"></td>
                <td><span class="total-cell font-weight-bold text-success">0 đ</span></td>
                <td><button type="button" class="btn btn-sm btn-danger btn-remove-item"><i class="fas fa-times"></i></button></td>
              </tr>
            </tbody>
            <tfoot>
              <tr class="bg-light">
                <td colspan="4" class="text-right font-weight-bold">Tổng doanh thu:</td>
                <td colspan="2" class="font-weight-bold text-success" id="grand-total">0 đ</td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-1"></i>Hủy</button>
        <button type="button" class="btn btn-success" id="btn-save-sell"><i class="fas fa-save mr-1"></i>Lưu giao dịch</button>
      </div>
    </div>
  </div>
</div>

{{-- MODAL XEM CHI TIẾT --}}
<div class="modal fade" id="viewSellModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-gradient-info text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-eye mr-2"></i>Chi tiết giao dịch</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body" id="view-sell-body">Đang tải...</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>

<aside class="control-sidebar control-sidebar-dark"></aside>
@include('layouts/parts/footer')

<style>
.badge-result { padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600; }
.badge-hoanthanh { background:#dcfce7;color:#15803d; }
.badge-nhaplieu { background:#fef9c3;color:#854d0e; }
.badge-luu { background:#e0f2fe;color:#0369a1; }
.btn-action { border:none;border-radius:6px;padding:4px 8px;font-size:12px;cursor:pointer;margin:0 2px; }
.btn-edit { background:#dbeafe;color:#1d4ed8; }
.btn-del  { background:#fee2e2;color:#b91c1c; }
</style>

<script>
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

// DataTable
const sellTable = $('#sell-table').DataTable({
    processing: true, serverSide: true,
    ajax: { url: '{{ route("banhang.giao-dich.data") }}' },
    columns: [
        { data: 'DT_RowIndex', orderable: false, className:'pl-3' },
        { data: 'name', defaultContent: '—' },
        { data: 'arrange_name', orderable: false, defaultContent: '—' },
        { data: 'sell_day', defaultContent: '—' },
        { data: 'so_sp', orderable: false },
        { data: 'shipment_revenue' },
        { data: 'profit' },
        { data: 'status', orderable: false },
        { data: 'action', orderable: false, className:'text-center' },
    ],
    language: { processing:'Đang tải...', search:'Tìm:', emptyTable:'Chưa có giao dịch nào', info:'_START_-_END_ / _TOTAL_', lengthMenu:'Hiển thị _MENU_', paginate:{next:'›',previous:'‹'} },
    drawCallback: loadStats,
});

// Stats
function loadStats() {
    $.get('{{ route("banhang.giao-dich.data") }}', {draw:1,start:0,length:10000}, function(res) {
        const data = res.data || [];
        $('#stat-gd-total').text(res.recordsTotal ?? data.length);
        $('#stat-gd-done').text(data.filter(d => d.status && d.status.includes('Đã')).length);

        let totalRevenue = 0, totalProfit = 0;
        data.forEach(d => {
            totalRevenue += d.shipment_revenue_raw ?? 0;
            totalProfit  += d.profit_raw ?? 0;
        });
        $('#stat-gd-revenue').text(totalRevenue > 0 ? totalRevenue.toLocaleString('vi-VN') + ' đ' : '—');
        const profitEl = $('#stat-gd-profit');
        profitEl.text(totalProfit !== 0 ? (totalProfit > 0 ? '+' : '') + totalProfit.toLocaleString('vi-VN') + ' đ' : '—');
        profitEl.css('color', totalProfit >= 0 ? '#15803d' : '#b91c1c');
    });
}

// ===== ITEM TABLE LOGIC =====
function calcRow(row) {
    const qty   = parseInt($(row).find('.qty-input').val()) || 0;
    const price = parseInt($(row).find('.price-input').val()) || 0;
    const total = qty * price;
    $(row).find('.total-cell').text(total.toLocaleString('vi-VN') + ' đ');
    calcGrandTotal();
}

function calcGrandTotal() {
    let grand = 0;
    $('.total-cell').each(function() {
        grand += parseInt($(this).text().replace(/\D/g,'')) || 0;
    });
    $('#grand-total').text(grand.toLocaleString('vi-VN') + ' đ');
}

$(document).on('change', '.product-select', function() {
    const opt = $(this).find(':selected');
    const row = $(this).closest('tr');
    const price  = opt.data('price') || 0;
    const tonkho = opt.data('tonkho') || 0;
    row.find('.price-input').val(price);
    row.find('.ton-kho-badge').text(tonkho > 0 ? 'Còn: ' + tonkho : 'Hết hàng').css('color', tonkho <= 0 ? 'red' : 'green');
    calcRow(row);
}).on('input', '.qty-input, .price-input', function() {
    calcRow($(this).closest('tr'));
}).on('click', '.btn-remove-item', function() {
    if ($('.item-row').length > 1) { $(this).closest('tr').remove(); calcGrandTotal(); }
    else Swal.fire('Thông báo','Phải có ít nhất 1 sản phẩm!','info');
});

$('#btn-add-item').click(function() {
    const newRow = $('.item-row:first').clone();
    newRow.find('.product-select').val('');
    newRow.find('.qty-input').val(1);
    newRow.find('.price-input').val(0);
    newRow.find('.total-cell').text('0 đ');
    newRow.find('.ton-kho-badge').text('—').css('color','');
    $('#sell-items').append(newRow);
});

// ===== LƯU GIAO DỊCH =====
$('#btn-save-sell').click(function() {
    const items = [];
    let valid = true;
    $('.item-row').each(function() {
        const pid = $(this).find('.product-select').val();
        const qty = parseInt($(this).find('.qty-input').val()) || 0;
        const price = parseInt($(this).find('.price-input').val()) || 0;
        if (!pid || qty < 1) { valid = false; return false; }
        items.push({ product_id: pid, number_sell: qty, price_sell: price });
    });
    if (!valid) { Swal.fire('Lỗi','Vui lòng chọn đầy đủ sản phẩm và số lượng!','warning'); return; }

    const data = {
        sell_day:    $('#sell-day').val(),
        name:        $('#sell-name').val().trim(),
        shipment_id: $('#sell-shipment').val() || null,
        status:      $('#sell-status').val(),
        items:       items,
    };

    $.post('{{ route("banhang.giao-dich.store") }}', data)
        .done(res => {
            if(res.success) {
                $('#sellModal').modal('hide');
                sellTable.ajax.reload();
                Swal.fire({icon:'success', title: res.message, text: 'Tồn kho đã được cập nhật tự động.', timer:2500, showConfirmButton:false});
            }
        })
        .fail(xhr => {
            const err = xhr.responseJSON?.errors;
            Swal.fire({icon:'error', title:'Lỗi dữ liệu', html: err ? Object.values(err).flat().join('<br>') : 'Có lỗi!'});
        });
});

// ===== XEM CHI TIẾT =====
window.viewSell = function(id) {
    $.get('/ban-hang/giao-dich/get/' + id, function(res) {
        let rows = '';
        (res.sell_products || []).forEach(sp => {
            rows += `<tr>
                <td>${sp.product?.name ?? '—'}</td>
                <td>${sp.number_sell ?? 0}</td>
                <td>${(sp.price_sell ?? 0).toLocaleString('vi-VN')} đ</td>
                <td><strong>${(sp.revenue ?? 0).toLocaleString('vi-VN')} đ</strong></td>
            </tr>`;
        });
        $('#view-sell-body').html(`
            <div class="row mb-3">
                <div class="col-md-6"><strong>Giao dịch:</strong> ${res.name ?? '—'}</div>
                <div class="col-md-6"><strong>Lô hàng:</strong> ${res.shipment?.arrange?.name_arrange ?? '—'}</div>
                <div class="col-md-6 mt-2"><strong>Doanh thu:</strong> <span class="text-success">${(res.shipment_revenue ?? 0).toLocaleString('vi-VN')} đ</span></div>
                <div class="col-md-6 mt-2"><strong>Lợi nhuận:</strong> <span class="${(res.profit??0)>=0?'text-success':'text-danger'}">${(res.profit ?? 0).toLocaleString('vi-VN')} đ</span></div>
            </div>
            <table class="table table-bordered text-sm">
                <thead class="bg-light"><tr><th>Sản phẩm</th><th>SL</th><th>Đơn giá</th><th>Thành tiền</th></tr></thead>
                <tbody>${rows}</tbody>
            </table>`);
        $('#viewSellModal').modal('show');
    });
};

// ===== XÓA =====
window.deleteSell = function(id) {
    Swal.fire({title:'Xóa giao dịch?',text:'Tồn kho sẽ được hoàn trả!',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',cancelButtonText:'Hủy',confirmButtonText:'Xóa'})
        .then(r => {
            if(r.isConfirmed) {
                $.ajax({ url:'/ban-hang/giao-dich/delete/'+id, type:'DELETE' })
                    .done(res => { sellTable.ajax.reload(); Swal.fire({icon:'success',title:res.message,timer:1500,showConfirmButton:false}); });
            }
        });
};
</script>
