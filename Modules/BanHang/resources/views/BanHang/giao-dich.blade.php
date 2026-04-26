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
        <!-- <div class="col-md-3">
          <div class="info-box shadow-sm">
            <span class="info-box-icon bg-warning"><i class="fas fa-chart-line"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Tổng lợi nhuận</span>
              <span class="info-box-number" id="stat-gd-profit" style="font-size:16px">—</span>
            </div>
          </div>
        </div> -->
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
                <th>Tên khách hàng</th>
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
          <div class="col-md-6">
            <label>Ngày bán <span class="text-danger">*</span></label>
            <input type="date" id="sell-day" class="form-control" value="{{ date('Y-m-d') }}">
          </div>
          <div class="col-md-6">
            <label>Tên khách hàng</label>
            <input type="text" id="sell-name" class="form-control" placeholder="VD: Nguyễn Văn A">
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-4">
            <label>Trạng thái <span class="text-danger">*</span></label>
            <select id="sell-status" class="form-control">
              <option value="1">Đã bán</option>
              <option value="0">Chưa bán</option>
            </select>
          </div>
          <div class="col-md-4">
            <label>Hình thức thanh toán <span class="text-danger">*</span></label>
            <select id="sell-payment-method" class="form-control">
              <option value="">-- Chọn hình thức --</option>
              <option value="cash">Tiền mặt</option>
              <option value="transfer">Chuyển khoản</option>
            </select>
          </div>
          <div class="col-md-4" id="customer-paid-container">
            <label>Khách thanh toán</label>
            <input type="number" id="sell-paid" class="form-control" min="0" value="0" placeholder="Nhập số tiền khách đưa">
          </div>
        </div>

        <div class="row mb-3 d-none" id="transfer-qr-row">
          <div class="col-12">
            <div class="card card-outline card-info">
              <div class="card-body d-flex flex-column flex-md-row align-items-center gap-3">
                <div class="qr-preview">
                  <img id="payment-qr" src="" alt="QR chuyển khoản" style="max-width:180px; width:100%; height:auto;" />
                </div>
                <div>
                  <h6 class="font-weight-bold mb-2">Quét mã chuyển khoản</h6>
                  <div class="mb-1"><strong>Số tiền:</strong> <span id="qr-amount-label">0 đ</span></div>
                  <div class="mb-1"><strong>STK:</strong> 0123456789</div>
                  <div><strong>Ngân hàng:</strong> ACB</div>
                </div>
              </div>
            </div>
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

      <div class="row mt-3">
        <div class="col-md-6">
          <div class="card card-outline card-secondary">
            <div class="card-body p-3">
              <div class="d-flex justify-content-between mb-2">
                <span class="font-weight-bold">Tổng số sản phẩm</span>
                <span id="total-items" class="font-weight-bold">1</span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span class="font-weight-bold">Thanh toán</span>
                <span id="payment-method-label">Tiền mặt</span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span class="font-weight-bold">Khách đưa</span>
                <span id="paid-amount-label">0 đ</span>
              </div>
              <div class="d-flex justify-content-between">
                <span class="font-weight-bold">Tiền thừa</span>
                <span id="change-amount-label">0 đ</span>
              </div>
            </div>
          </div>
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
        <button type="button" class="btn btn-warning" id="btn-edit-sell-modal"><i class="fas fa-edit mr-1"></i>Sửa</button>
        <button type="button" class="btn btn-primary" id="btn-print-invoice"><i class="fas fa-print mr-1"></i>In</button>
      </div>
    </div>
  </div>
</div>

<aside class="control-sidebar control-sidebar-dark"></aside>
@include('layouts/parts/footer')

<style>
.badge-result { padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600; }
.badge-hoanthanh { color:#15803d; }
.badge-nhaplieu { color:#854d0e; }
.badge-luu { color:#0369a1; }
.btn-action { border:none;border-radius:6px;padding:4px 8px;font-size:12px;cursor:pointer;margin:0 2px; }
.btn-edit { color:#1d4ed8; }
.btn-del  { color:#b91c1c; }
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
    let items = 0;
    $('.total-cell').each(function() {
        grand += parseInt($(this).text().replace(/\D/g,'')) || 0;
    });
    $('.qty-input').each(function() {
        items += parseInt($(this).val()) || 0;
    });
    $('#grand-total').text(grand.toLocaleString('vi-VN') + ' đ');
    $('#total-items').text(items || 0);
    updatePaymentSummary();
}

function updatePaymentSummary() {
    const grand = parseInt($('#grand-total').text().replace(/\D/g,'')) || 0;
    const methodEl = $('#sell-payment-method').find(':selected');
    const methodStr = methodEl.val() ? methodEl.text() : '—';
    const methodValue = methodEl.val();
    
    if (methodValue === 'transfer') {
        $('#customer-paid-container').hide();
    } else {
        $('#customer-paid-container').show();
    }
    
    const paid = parseInt($('#sell-paid').val()) || 0;
    // Nếu chuyển khoản thì coi như Khách thanh toán = Thành tiền (không lấy sell-paid)
    // Nếu tiền mặt thì lấy số tiền khách đưa. 
    const displayPaid = methodValue === 'transfer' ? grand : paid;
    const change = Math.max(0, displayPaid - grand);
    
    $('#payment-method-label').text(methodStr);
    $('#paid-amount-label').text(displayPaid.toLocaleString('vi-VN') + ' đ');
    $('#change-amount-label').text(change.toLocaleString('vi-VN') + ' đ');
    updateTransferQr();
}

function buildEmvTag(id, value) {
    return id + String(value.length).padStart(2, '0') + value;
}

function crc16Ccitt(data) {
    let crc = 0xFFFF;
    for (let i = 0; i < data.length; i++) {
        crc ^= data.charCodeAt(i) << 8;
        for (let j = 0; j < 8; j++) {
            crc = (crc & 0x8000) ? ((crc << 1) ^ 0x1021) : (crc << 1);
            crc &= 0xFFFF;
        }
    }
    return crc.toString(16).toUpperCase().padStart(4, '0');
}

function generateVietQrPayload(accountNumber, amount, description) {
    const merchantName = 'QUAN CAFE';
    const merchantCity = 'HA NOI';
    const accountInfo = buildEmvTag('00', 'A000000727') + buildEmvTag('01', accountNumber);
    let payload = '';
    payload += buildEmvTag('00', '01');
    payload += buildEmvTag('01', '12');
    payload += buildEmvTag('26', accountInfo);
    payload += buildEmvTag('52', '0000');
    payload += buildEmvTag('53', '704');
    if (amount > 0) {
        payload += buildEmvTag('54', amount.toString());
    }
    payload += buildEmvTag('58', 'VN');
    payload += buildEmvTag('59', merchantName);
    payload += buildEmvTag('60', merchantCity);
    if (description) {
        payload += buildEmvTag('62', buildEmvTag('01', description));
    }
    payload += '6304';
    payload += crc16Ccitt(payload);
    return payload;
}

function updateTransferQr() {
    const method = $('#sell-payment-method').val();
    const grand = parseInt($('#grand-total').text().replace(/\D/g,'')) || 0;
    const accountNumber = '0123456789';

    if (method === 'transfer' && grand > 0) {
        $('#transfer-qr-row').removeClass('d-none');
        $('#qr-amount-label').text(grand.toLocaleString('vi-VN') + ' đ');
        const qrText = generateVietQrPayload(accountNumber, grand, 'Thanh toán hóa đơn');
        const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=${encodeURIComponent(qrText)}`;
        $('#payment-qr').attr('src', qrUrl);
    } else {
        $('#transfer-qr-row').addClass('d-none');
        $('#payment-qr').attr('src', '');
    }
}

$('#sellModal').on('shown.bs.modal', function() {
    calcGrandTotal();
    updatePaymentSummary();
});

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
}).on('change', '#sell-payment-method, #sell-paid', function() {
    updatePaymentSummary();
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
    calcGrandTotal();
});

$('#btn-new-sell').click(function() {
    window.currentEditSellId = null;
    $('#sellModal .modal-title').html('<i class="fas fa-plus-circle mr-2"></i>Tạo Hóa Đơn');
    
    $('#sell-day').val('{{ date("Y-m-d") }}');
    $('#sell-name').val('');
    $('#sell-status').val('1');
    $('#sell-payment-method').val('');
    $('#sell-paid').val('0');
    
    $('.item-row:not(:first)').remove();
    const firstRow = $('.item-row:first');
    firstRow.find('.product-select').val('');
    firstRow.find('.qty-input').val(1);
    firstRow.find('.price-input').val(0);
    firstRow.find('.total-cell').text('0 đ');
    firstRow.find('.ton-kho-badge').text('—').css('color','');
    
    calcGrandTotal();
    updatePaymentSummary();
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

    const statusVal = $('#sell-status').val();
    const methodVal = $('#sell-payment-method').val();
    const paidVal = parseInt($('#sell-paid').val()) || 0;

    if (statusVal == '1') {
        if (!methodVal) {
            Swal.fire('Lỗi', 'Vui lòng chọn hình thức thanh toán!', 'warning');
            return;
        }
        if (methodVal === 'cash' && paidVal <= 0) {
            Swal.fire('Lỗi', 'Vui lòng nhập số tiền khách đã thanh toán (Tiền mặt)!', 'warning');
            return;
        }
    }
    
    const data = {
        sell_day:        $('#sell-day').val(),
        name:            $('#sell-name').val().trim(),
        shipment_id:     null,
        status:          $('#sell-status').val(),
        payment_method:  $('#sell-payment-method').val(),
        paid_amount:     parseInt($('#sell-paid').val()) || 0,
        items:           items,
    };

    const url = window.currentEditSellId 
        ? '/ban-hang/giao-dich/update/' + window.currentEditSellId
        : '{{ route("banhang.giao-dich.store") }}';

    $.post(url, data)
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
let currentSellId = null;
window.currentEditSellId = null;

window.viewSell = function(id) {
    currentSellId = id;
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
                <div class="col-md-6"><strong>Tên khách hàng:</strong> ${res.name ?? '—'}</div>
                <div class="col-md-6"><strong>Hình thức thanh toán:</strong> ${({cash:'Tiền mặt',card:'Thẻ',transfer:'Chuyển khoản',momo:'Ví điện tử'}[res.payment_method] ?? res.payment_method ?? '—')}</div>
                <div class="col-md-6 mt-2"><strong>Khách đã trả:</strong> <span class="text-success">${(res.paid_amount ?? 0).toLocaleString('vi-VN')} đ</span></div>
                <div class="col-md-6 mt-2"><strong>Doanh thu:</strong> <span class="text-success">${(res.shipment_revenue ?? 0).toLocaleString('vi-VN')} đ</span></div>
                <div class="col-md-6 mt-2"><strong>Lợi nhuận:</strong> <span class="${(res.profit??0)>=0?'text-success':'text-danger'}">${(res.profit ?? 0).toLocaleString('vi-VN')} đ</span></div>
            </div>
            <table class="table table-bordered text-sm">
                <thead class="bg-light"><tr><th>Sản phẩm</th><th>SL</th><th>Đơn giá</th><th>Thành tiền</th></tr></thead>
                <tbody>${rows}</tbody>
            </table>`);
            
        if (res.status == 0) {
            $('#btn-print-invoice').hide();
        } else {
            $('#btn-print-invoice').show();
        }
        
        $('#viewSellModal').modal('show');
    });
};

$('#btn-edit-sell-modal').click(function() {
    $('#viewSellModal').modal('hide');
    if (!currentSellId) return;
    
    $.get('/ban-hang/giao-dich/get/' + currentSellId, function(res) {
        window.currentEditSellId = currentSellId;
        $('#sellModal .modal-title').html('<i class="fas fa-edit mr-2"></i>Cập nhật Hóa Đơn');
        
        $('#sell-day').val(res.sell_day ? res.sell_day.split(' ')[0] : '{{ date("Y-m-d") }}');
        $('#sell-name').val(res.name ?? '');
        $('#sell-status').val(res.status ?? 0);
        $('#sell-payment-method').val(res.payment_method ?? '');
        $('#sell-paid').val(res.paid_amount ?? 0);
        
        $('.item-row:not(:first)').remove();
        
        const products = res.sell_products || [];
        if (products.length > 0) {
            products.forEach((sp, index) => {
                let row;
                if (index === 0) {
                    row = $('.item-row:first');
                } else {
                    row = $('.item-row:first').clone();
                    $('#sell-items').append(row);
                }
                
                row.find('.product-select').val(sp.product_id);
                row.find('.qty-input').val(sp.number_sell ?? 1);
                row.find('.price-input').val(sp.price_sell ?? 0);
                
                const opt = row.find('.product-select option:selected');
                const tonkho = opt.data('tonkho') || 0;
                
                row.find('.ton-kho-badge').text(tonkho > 0 ? 'Còn: ' + tonkho : 'Hết hàng').css('color', tonkho <= 0 ? 'red' : 'green');
                calcRow(row);
            });
        }
        
        calcGrandTotal();
        $('#sellModal').modal('show');
    });
});

$('#btn-print-invoice').click(function() {
    if (currentSellId) {
        $.get('/ban-hang/giao-dich/get/' + currentSellId, function(res) {
            let itemRows = '';
            let totalRevenue = 0;
            (res.sell_products || []).forEach(sp => {
                const revenue = sp.number_sell * sp.price_sell;
                totalRevenue += revenue;
                itemRows += `<tr>
                    <td class="text-left">${sp.product?.name ?? '—'}</td>
                    <td class="text-center">${sp.number_sell ?? 0}</td>
                    <td class="text-right">${(sp.price_sell ?? 0).toLocaleString('vi-VN')}</td>
                    <td class="text-right"><strong>${revenue.toLocaleString('vi-VN')}</strong></td>
                </tr>`;
            });
            const paymentMethod = {cash:'Tiền mặt',card:'Thẻ',transfer:'Chuyển khoản',momo:'Ví điện tử'}[res.payment_method] ?? res.payment_method ?? '—';
            
            let printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>Hóa đơn</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; margin-bottom: 20px; }
                        .header h3 { margin: 0; font-weight: bold; }
                        .header h5 { margin: 5px 0 0 0; color: #666; }
                        hr { margin: 15px 0; }
                        .info-row { display: flex; justify-content: space-between; margin: 5px 0; }
                        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
                        th { border: 1px solid #ddd; padding: 8px; background: #f5f5f5; text-align: left; }
                        td { border: 1px solid #ddd; padding: 8px; }
                        .text-right { text-align: right; }
                        .text-center { text-align: center; }
                        .summary { margin-top: 15px; }
                        .summary-row { display: flex; justify-content: space-between; padding: 5px 0; }
                        .footer { text-align: center; margin-top: 30px; color: #999; font-size: 12px; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h3>HÓA ĐƠN BÁN HÀNG</h3>
                        <h5>Quán Cà Phê M&T</h5>
                    </div>
                    <hr>
                    <div class="info-row"><strong>Tên khách hàng:</strong> <span>${res.name ?? '—'}</span></div>
                    <div class="info-row"><strong>Ngày:</strong> <span>${new Date(res.created_at).toLocaleDateString('vi-VN')}</span></div>
                    <div class="info-row"><strong>Trạng thái:</strong> <span>${({1:'Đã bán',0:'Chưa bán'}[res.status] ?? res.status ?? '—')}</span></div>
                    <hr>
                    <table>
                        <thead>
                            <tr>
                                <th class="text-left">Sản phẩm</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-right">Đơn giá</th>
                                <th class="text-right">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itemRows}
                        </tbody>
                    </table>
                    <div class="summary">
                        <div class="summary-row">
                            <strong>Đơn giá:</strong>
                            <strong>${totalRevenue.toLocaleString('vi-VN')} đ</strong>
                        </div>
                        <div class="summary-row">
                            <span>Hình thức thanh toán:</span>
                            <span>${paymentMethod}</span>
                        </div>
                        <div class="summary-row">
                            <span>Khách đưa:</span>
                            <span>${(res.paid_amount ?? 0).toLocaleString('vi-VN')} đ</span>
                        </div>
                        <div class="summary-row" style="border-top: 1px solid #ddd; padding-top: 8px; margin-top: 8px;">
                            <strong>Tiền thừa:</strong>
                            <strong>${Math.max(0, (res.paid_amount ?? 0) - totalRevenue).toLocaleString('vi-VN')} đ</strong>
                        </div>
                    </div>
                    <hr>
                    <div class="footer">Cảm ơn quý khách! Hẹn gặp lại.</div>
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        });
    }
});


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
