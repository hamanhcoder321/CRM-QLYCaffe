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
        return Sell::with(['shipment.arrange', 'sellProducts.product'])
            ->orderBy('created_at', 'desc');
    }

    public function storeSell(array $data, array $items): Sell
    {
        return DB::transaction(function () use ($data, $items) {
            // 1. Tạo đơn bán
            $sell = Sell::create([
                'shipment_id'      => $data['shipment_id'] ?? null,
                'name'             => $data['name'] ?? null,
                'status'           => $data['status'] ?? 0,
                'storage'          => $data['storage'] ?? 0,
                'shipment_revenue' => $data['shipment_revenue'] ?? 0,
                'profit'           => $data['profit'] ?? 0,
            ]);

            // 2. Tạo chi tiết + trừ tồn kho
            foreach ($items as $item) {
                if (empty($item['product_id']) || empty($item['number_sell'])) continue;

                $qty = (int) $item['number_sell'];
                $price = (int) ($item['price_sell'] ?? 0);

                SellProduct::create([
                    'sell_id'            => $sell->id,
                    'product_id'         => $item['product_id'],
                    'sell_day'           => $data['sell_day'] ?? now()->format('Y-m-d'),
                    'fullname_customer'  => $item['fullname_customer'] ?? null,
                    'number_sell'        => $qty,
                    'price_sell'         => $price,
                    'revenue'            => $qty * $price,
                    'number_produts'     => $item['number_produts'] ?? null,
                    'note'               => $item['note'] ?? null,
                    'transport'          => $item['transport'] ?? null,
                ]);

                // Trừ tồn kho (tăng number_out)
                Product::where('id', $item['product_id'])
                    ->increment('number_out', $qty);
            }

            // 3. Cập nhật doanh thu tổng
            $totalRevenue = SellProduct::where('sell_id', $sell->id)->sum('revenue');
            $sellCost     = optional($sell->shipment?->arrange)->total_arrange ?? 0;
            $sell->update([
                'shipment_revenue' => $totalRevenue,
                'profit'           => $totalRevenue - $sellCost,
            ]);

            return $sell;
        });
    }

    public function updateSell(int $id, array $data): bool
    {
        return Sell::findOrFail($id)->update($data);
    }

    public function deleteSell(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $sell = Sell::with('sellProducts')->findOrFail($id);

            // Hoàn trả tồn kho
            foreach ($sell->sellProducts as $sp) {
                Product::where('id', $sp->product_id)
                    ->decrement('number_out', $sp->number_sell ?? 0);
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
            ->map(fn($s) => [
                'id'   => $s->id,
                'name' => ($s->arrange?->name_arrange ?? 'Đơn #' . $s->id)
                        . ' — ' . ($s->arrange?->day ?? ''),
            ])
            ->toArray();
    }
}
