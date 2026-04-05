<?php

namespace Modules\User\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Http\Request;

interface UserRepositoryInterface{
    public function getFilters(): array;

    public function getUsersData(Request $request);

    public function formOptions(): array;

    public function store(array $validated): User;

    public function update(array $validated, User $user): bool;

    public function destroy(User $user): bool;
}
