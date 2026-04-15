<?php

namespace Modules\NhapHang\Repositories\Interfaces;

use App\Models\Arrange;
use Illuminate\Http\Request;

interface NhapHangRepositoryInterface
{
    public function getFilters(): array;

    public function getData(Request $request);

    public function store(array $validated): Arrange;

    public function update(array $validated, Arrange $arrange): bool;

    public function destroy(Arrange $arrange): bool;
}
