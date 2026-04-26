<?php

namespace Modules\BanHang\Repositories\Interfaces;

interface BanHangRepositoryInterface
{
    // Thức uống (Products)
    public function getProducts();
    public function storeProduct(array $data);
    public function updateProduct(int $id, array $data);
    public function deleteProduct(int $id);

    // Tồn kho
    public function getTonKho();

    // Giao dịch bán hàng (Sells)
    public function getSells();
    public function storeSell(array $data, array $items);
    public function updateSell(int $id, array $data, array $items);
    public function deleteSell(int $id);

    // Helpers
    public function getShipmentsForSelect();
}
