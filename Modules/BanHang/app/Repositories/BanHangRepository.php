<?php

namespace Modules\BanHang\Repositories;

use App\Models\Product;
use App\Models\Sell;
use App\Models\SellProduct;
use App\Models\Shipment;
use App\Models\Storage;
use Illuminate\Support\Facades\DB;
use Modules\BanHang\Repositories\Interfaces\BanHangRepositoryInterface;

class BanHangRepository implements BanHangRepositoryInterface
{
    // ===================== THỨC UỐNG =====================

    public function getProducts()
    {
        return Product::with(['shipment.arrange'])
            ->orderBy('created_at', 'desc');
    }

    public function storeProduct(array $data): Product
    {
        return Product::create($data);
    }

    public function updateProduct(int $id, array $data): bool
    {
        return Product::findOrFail($id)->update($data);
    }

    public function deleteProduct(int $id): bool
    {
        return Product::findOrFail($id)->delete();
    }

    // ===================== TỒN KHO =====================

    public function getTonKho()
    {
        return Product::with(['shipment.arrange'])
            ->orderByRaw('(number_in - number_out) ASC');
    }

    // ===================== GIAO DỊCH BÁN HÀNG =====================

    public function getSells()
    {
        return Sell::with(['shipment.arrange', 'sellProducts.drink'])
            ->orderBy('created_at', 'desc');
    }

    public function storeSell(array $data, array $items): Sell
    {
        // ===== KIỂM TRA TỒN KHO TRƯỚC KHI TẠO ĐƠN =====
        if (($data['status'] ?? 0) != 0) {
            $this->checkStock($items);
        }

        return DB::transaction(function () use ($data, $items) {
            // 1. Tạo đơn bán
            $sell = Sell::create([
                'branch_id'        => auth()->user()?->branch_id ?? null,
                'name'             => $data['name'] ?? null,
                'status'           => $data['status'] ?? 0,
                'storage'          => $data['storage'] ?? 0,
                'sell_day'         => $data['sell_day'] ?? now()->format('Y-m-d'),
                'payment_method'   => $data['payment_method'] ?? null,
                'paid_amount'      => $data['paid_amount'] ?? 0,
                'shipment_revenue' => 0,
                'profit'           => 0,
                'note'             => $data['note'] ?? null,
            ]);

            // 2. Tạo chi tiết + trừ tồn kho + tính lợi nhuận
            $totalRevenue = 0;
            $totalCost    = 0;

            foreach ($items as $item) {
                if (empty($item['drink_id']) || empty($item['number_sell'])) continue;

                $qty       = (int) $item['number_sell'];
                $priceSell = (int) ($item['price_sell'] ?? 0);
                $drink     = \App\Models\Drink::with('recipes.product')->find($item['drink_id']);

                $priceImport  = 0;
                // Chỉ trừ kho khi đã bán (status != 0)
                if ($drink && $sell->status != 0) {
                    $noteStr = mb_strtolower($item['note'] ?? '');
                    foreach ($drink->recipes as $rc) {
                        $p = $rc->product;
                        if (!$p) continue;

                        $multiplier = 1.0;
                        $pName = mb_strtolower($p->name);

                        // Đường
                        if (str_contains($pName, 'đường')) {
                            if (str_contains($noteStr, 'ít đường'))   $multiplier -= 0.5;
                            if (str_contains($noteStr, 'thêm đường') || str_contains($noteStr, 'nhiều đường')) $multiplier += 0.5;
                            if (str_contains($noteStr, 'không đường')) $multiplier = 0;
                        }
                        // Đá
                        if (str_contains($pName, 'đá')) {
                            if (str_contains($noteStr, 'ít đá'))   $multiplier -= 0.5;
                            if (str_contains($noteStr, 'thêm đá') || str_contains($noteStr, 'nhiều đá')) $multiplier += 0.5;
                            if (str_contains($noteStr, 'không đá') || str_contains($noteStr, 'nóng')) $multiplier = 0;
                        }
                        // Cà phê
                        if (str_contains($pName, 'cà phê') || str_contains($pName, 'caffe') || str_contains($pName, 'cafe')) {
                            if (str_contains($noteStr, 'thêm cafe') || str_contains($noteStr, 'thêm cà phê')) $multiplier += 0.5;
                        }
                        if ($multiplier < 0) $multiplier = 0;

                        $finalQty = (int) round($rc->quantity * $qty * $multiplier);
                        if ($finalQty > 0) {
                            $priceImport += ($p->cost_price ?? 0) * $finalQty;
                            $p->increment('number_out', $finalQty);
                        }
                    }
                }

                SellProduct::create([
                    'sell_id'           => $sell->id,
                    'drink_id'          => $item['drink_id'],
                    'sell_day'          => $data['sell_day'] ?? now()->format('Y-m-d'),
                    'fullname_customer' => $item['fullname_customer'] ?? null,
                    'number_sell'       => $qty,
                    'price_sell'        => $priceSell,
                    'revenue'           => $qty * $priceSell,
                    'number_produts'    => $item['number_produts'] ?? null,
                    'note'              => $item['note'] ?? null,
                    'transport'         => $item['transport'] ?? null,
                ]);

                if ($sell->status != 0) {
                    $totalRevenue += $qty * $priceSell;
                    $totalCost    += $qty * $priceImport;
                }
            }

            // 3. Lợi nhuận
            $sell->update([
                'shipment_revenue' => $totalRevenue,
                'profit'           => $totalRevenue - $totalCost,
            ]);

            return $sell;
        });
    }

    public function updateSell(int $id, array $data, array $items): Sell
    {
        // ===== KIỂM TRA TỒN KHO TRƯỚC KHI CẬP NHẬT =====
        // Lấy sell cũ để biết status cũ, chỉ check nếu status mới là "đã bán"
        if (($data['status'] ?? 0) != 0) {
            $oldSell = Sell::with('sellProducts.drink.recipes.product')->findOrFail($id);
            // Tính lại stock đã trả về (items cũ) để check đúng
            $this->checkStock($items, $oldSell);
        }

        return DB::transaction(function () use ($id, $data, $items) {
            $sell = Sell::with('sellProducts')->findOrFail($id);

            // 1. Hoàn trả tồn kho cho các sản phẩm cũ
            foreach ($sell->sellProducts as $sp) {
                if ($sp->drink_id) {
                    $drink = \App\Models\Drink::with('recipes.product')->find($sp->drink_id);
                    if ($drink) {
                        foreach($drink->recipes as $rc) {
                            if ($rc->product) {
                                $rc->product->decrement('number_out', $rc->quantity * ($sp->number_sell ?? 0));
                            }
                        }
                    }
                }
            }
            
            // Xóa chi tiết cũ
            $sell->sellProducts()->delete();

            // 2. Cập nhật thông tin đơn bán
            $sell->update([
                'name'             => $data['name'] ?? null,
                'status'           => $data['status'] ?? 0,
                'storage'          => $data['storage'] ?? 0,
                'sell_day'         => $data['sell_day'] ?? now()->format('Y-m-d'),
                'payment_method'   => $data['payment_method'] ?? null,
                'paid_amount'      => $data['paid_amount'] ?? 0,
                'note'             => $data['note'] ?? null,
            ]);

            // 3. Tạo lại chi tiết + trừ tồn kho + tính lợi nhuận
            $totalRevenue = 0;
            $totalCost    = 0;

            foreach ($items as $item) {
                if (empty($item['drink_id']) || empty($item['number_sell'])) continue;

                $qty        = (int) $item['number_sell'];
                $priceSell  = (int) ($item['price_sell'] ?? 0);
                $drink      = \App\Models\Drink::with('recipes.product')->find($item['drink_id']);
                
                $priceImport = 0; // Giá vốn tính từ tổng giá trị nguyên liệu tiêu hao
                if ($drink && $sell->status != 0) { // status == 0 là chưa bán
                    $noteStr = mb_strtolower($item['note'] ?? '');
                    foreach($drink->recipes as $rc) {
                        $p = $rc->product;
                        if ($p) {
                            $multiplier = 1.0;
                            $pName = mb_strtolower($p->name);
                            
                            // Phân tích "Ít/Thêm/Không" cho Đường
                            if (str_contains($pName, 'đường')) {
                                if (str_contains($noteStr, 'ít đường')) $multiplier -= 0.5;
                                if (str_contains($noteStr, 'thêm đường') || str_contains($noteStr, 'nhiều đường')) $multiplier += 0.5;
                                if (str_contains($noteStr, 'không đường')) $multiplier = 0;
                            }
                            // Phân tích "Ít/Thêm/Không" cho Đá
                            if (str_contains($pName, 'đá')) {
                                if (str_contains($noteStr, 'ít đá')) $multiplier -= 0.5;
                                if (str_contains($noteStr, 'thêm đá') || str_contains($noteStr, 'nhiều đá')) $multiplier += 0.5;
                                if (str_contains($noteStr, 'không đá') || str_contains($noteStr, 'nóng')) $multiplier = 0;
                            }
                            // Phân tích "Ít/Thêm" cho Cà phê
                            if (str_contains($pName, 'cà phê') || str_contains($pName, 'caffe') || str_contains($pName, 'cafe')) {
                                if (str_contains($noteStr, 'thêm cafe') || str_contains($noteStr, 'thêm cà phê')) $multiplier += 0.5;
                            }
                            // Đảm bảo không âm
                            if ($multiplier < 0) $multiplier = 0;

                            $finalQty = round($rc->quantity * $qty * $multiplier);
                            
                            if ($finalQty > 0) {
                                $priceImport += ($p->cost_price ?? 0) * $finalQty;
                                // Trừ tồn kho nguyên liệu (tăng number_out)
                                $p->increment('number_out', $finalQty);
                            }
                        }
                    }
                }

                SellProduct::create([
                    'sell_id'           => $sell->id,
                    'drink_id'          => $item['drink_id'],
                    'sell_day'          => $data['sell_day'] ?? now()->format('Y-m-d'),
                    'fullname_customer' => $item['fullname_customer'] ?? null,
                    'number_sell'       => $qty,
                    'price_sell'        => $priceSell,
                    'revenue'           => $qty * $priceSell,
                    'number_produts'    => $item['number_produts'] ?? null,
                    'note'              => $item['note'] ?? null,
                    'transport'         => $item['transport'] ?? null,
                ]);

                if ($sell->status != 0) {
                    $totalRevenue += $qty * $priceSell;
                    $totalCost    += $qty * $priceImport;
                }
            }

            // 4. Lợi nhuận
            $sell->update([
                'shipment_revenue' => $totalRevenue,
                'profit'           => $totalRevenue - $totalCost,
            ]);

            return $sell;
        });
    }

    public function deleteSell(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $sell = Sell::with('sellProducts')->findOrFail($id);

            // Hoàn trả tồn kho
            foreach ($sell->sellProducts as $sp) {
                if ($sp->drink_id) {
                    $drink = \App\Models\Drink::with('recipes.product')->find($sp->drink_id);
                    if ($drink) {
                        foreach($drink->recipes as $rc) {
                            if ($rc->product) {
                                $rc->product->decrement('number_out', $rc->quantity * ($sp->number_sell ?? 0));
                            }
                        }
                    }
                }
            }

            $sell->sellProducts()->delete();
            return $sell->delete();
        });
    }

    // ===================== HELPERS =====================

    public function getShipmentsForSelect(): array
    {
        return Shipment::with('arrange')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($s) {
                $typeStr = '';
                if ($s->arrange) {
                    $typeStr = $s->arrange->type_arrange === 0 ? '[Mới] ' : ($s->arrange->type_arrange === 1 ? '[Cũ] ' : '');
                }
                return [
                    'id'   => $s->id,
                    'name' => $typeStr . ($s->arrange?->name_arrange ?? 'Đơn #' . $s->id)
                            . ' — ' . ($s->arrange?->day ?? ''),
                    'type_arrange' => $s->arrange?->type_arrange
                ];
            })
            ->toArray();
    }

    /**
     * Kiểm tra tồn kho nguyên liệu trước khi tạo/cập nhật giao dịch.
     * Ném exception nếu bất kỳ nguyên liệu nào không đủ.
     *
     * @param array      $items   Danh sách món bán [{drink_id, number_sell, note}]
     * @param \App\Models\Sell|null $oldSell  Nếu là update, truyền vào để cộng lại stock cũ
     */
    private function checkStock(array $items, ?\App\Models\Sell $oldSell = null): void
    {
        // Tích lũy lượng tiêu hao nguyên liệu từ các items mới
        $needed = []; // [product_id => qty_needed]

        foreach ($items as $item) {
            if (empty($item['drink_id']) || empty($item['number_sell'])) continue;

            $qty     = (int) $item['number_sell'];
            $drink   = \App\Models\Drink::with('recipes.product')->find($item['drink_id']);
            if (!$drink) continue;

            $noteStr = mb_strtolower($item['note'] ?? '');

            foreach ($drink->recipes as $rc) {
                $p = $rc->product;
                if (!$p) continue;

                $multiplier = 1.0;
                $pName = mb_strtolower($p->name);

                if (str_contains($pName, 'đường')) {
                    if (str_contains($noteStr, 'ít đường'))   $multiplier -= 0.5;
                    if (str_contains($noteStr, 'thêm đường') || str_contains($noteStr, 'nhiều đường')) $multiplier += 0.5;
                    if (str_contains($noteStr, 'không đường')) $multiplier = 0;
                }
                if (str_contains($pName, 'đá')) {
                    if (str_contains($noteStr, 'ít đá'))   $multiplier -= 0.5;
                    if (str_contains($noteStr, 'thêm đá') || str_contains($noteStr, 'nhiều đá')) $multiplier += 0.5;
                    if (str_contains($noteStr, 'không đá') || str_contains($noteStr, 'nóng')) $multiplier = 0;
                }
                if (str_contains($pName, 'cà phê') || str_contains($pName, 'caffe') || str_contains($pName, 'cafe')) {
                    if (str_contains($noteStr, 'thêm cafe') || str_contains($noteStr, 'thêm cà phê')) $multiplier += 0.5;
                }
                if ($multiplier < 0) $multiplier = 0;

                $finalQty = (int) round($rc->quantity * $qty * $multiplier);
                if ($finalQty > 0) {
                    $needed[$p->id] = ($needed[$p->id] ?? 0) + $finalQty;
                }
            }
        }

        // Nếu là update, cộng lại stock từ đơn cũ (vì sắp hoàn trả)
        $returnedStock = []; // [product_id => qty_returned]
        if ($oldSell) {
            foreach ($oldSell->sellProducts as $sp) {
                if (!$sp->drink_id) continue;
                $drink = \App\Models\Drink::with('recipes.product')->find($sp->drink_id);
                if (!$drink) continue;
                foreach ($drink->recipes as $rc) {
                    if (!$rc->product) continue;
                    $q = (int) round($rc->quantity * ($sp->number_sell ?? 0));
                    if ($q > 0) {
                        $returnedStock[$rc->product->id] = ($returnedStock[$rc->product->id] ?? 0) + $q;
                    }
                }
            }
        }

        // Kiểm tra từng nguyên liệu
        $errors = [];
        foreach ($needed as $productId => $qtyNeeded) {
            $product  = \App\Models\Product::find($productId);
            if (!$product) continue;

            $available = ($product->number_in ?? 0) - ($product->number_out ?? 0);
            // Nếu là update thì cộng lại phần stock sẽ được hoàn trả
            $available += ($returnedStock[$productId] ?? 0);

            if ($available < $qtyNeeded) {
                $errors[] = sprintf(
                    'Nguyên liệu "%s" không đủ: cần %d, còn lại %d',
                    $product->name,
                    $qtyNeeded,
                    max(0, $available)
                );
            }
        }

        if (!empty($errors)) {
            throw new \Illuminate\Validation\ValidationException(
                \Illuminate\Support\Facades\Validator::make([], []),
                response()->json([
                    'message' => 'Không đủ nguyên liệu trong kho!',
                    'errors'  => ['stock' => $errors],
                ], 422)
            );
        }
    }
}
