<?php

namespace Modules\User\Repositories;

use App\Models\Branch;
use App\Models\Part;
use App\Models\Position;
use App\Models\Team;
use App\Models\Type_account;
use App\Models\User;
use Modules\User\Repositories\Interfaces\UserRepositoryInterface;
use Hash;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserRepository implements UserRepositoryInterface{
    public function getFilters(): array{
        $status_f = collect([
            ['id' => 1, 'text' => 'Đã nghỉ'],
            ['id' => 0, 'text' => 'Đang làm']
        ]);
        $part_f = \App\Models\Part::select('id', 'name as text')->orderBy('name')->get();
        if (!\App\Models\Team::where('name', 'Ca Tối')->exists()) {
            \App\Models\Team::create(['name' => 'Ca Tối']);
        }
        $team_f = \App\Models\Team::select('id', 'name as text')->where('name', '!=', 'Ca Kho')->orderBy('name')->get();
        $role_f = \App\Models\Type_account::select('id', 'name as text')->orderBy('name')->get();
        $branch_f = \App\Models\Branch::select('id', 'name as text')->orderBy('name')->get();

        return [
            'status_f' => $status_f,
            'part_f'   => $part_f,
            'team_f'   => $team_f,
            'role_f'   => $role_f,
            'branch_f' => $branch_f
        ];
    }

    public function getUsersData(Request $request){
        $users = \App\Models\User::leftJoin('branches', 'users.branch_id', '=', 'branches.id')
                ->with('part', 'position', 'team', 'typeAccount')
                ->select('users.*', 'branches.name as branch_name');

        if ($request->input('is_account_page') === 'true') {
            $users->whereDoesntHave('typeAccount', function($q) {
                $q->whereIn('name', ['Admin', 'Super Admin', 'Giám đốc']);
            });
        }

        $users->orderBy('created_at', 'desc');

            // check filter
            if ($request->filled('part_id')) {
                $users->where('part_id', $request->part_id);
            }
            if ($request->filled('status')) {
                $users->where('users.status', $request->status);
            }
            if ($request->filled('team_id')) {
                $users->where('team_id', $request->team_id);
            }
            if ($request->filled('type_accounts_id')) {
                $users->where('users.type_accounts_id', $request->type_accounts_id);
            }
            if ($request->filled('branch_id')) {
                $users->where('users.branch_id', $request->branch_id);
            }

            return DataTables::of($users)
                ->addIndexColumn()

                ->editColumn('sex', function ($row) {
                    return $row->sex == 0 ? 'nam' : 'nữ';
                })
                ->editColumn('part_id', function ($row) {
                    return $row->part->name ?? '';
                })
                ->editColumn('position_id', function ($row) {
                    return $row->position->name ?? '';
                })
                ->editColumn('type_work', function ($row) {
                    return $row->type_work == 0 ? 'Fulltime' : 'Partime';
                })
                ->editColumn('team_id', function ($row) {
                    return $row->team->name ?? '';
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 0 ? 'Đang làm' : 'nghỉ việc';
                })
                ->editColumn('type_accounts_id', function ($row) {
                    return $row->typeAccount->name ?? '';
                })

                ->addColumn('action', function ($row) {
                    return '
                        <div class="btn-user">
                            <a href="'.route('users.edit', $row->id).'" class="btn btn-edit btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action=" '. route('users.delete', $row->id).'" method="POST">
                                '.csrf_field().'
                                '.method_field('DELETE').'
                                <button type="button" class="btn btn-danger btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    ';
                })
                ->rawColumns(['action'])
                ->filter(function ($query) use ($request) {
                    $search = $request->input('search.value');
                    if ($search !== null && $search !== '') {
                        $like = '%' . trim($search) . '%';

                        $query->where(function ($q) use ($like) {
                            $q->orwhere('users.name', 'like', $like)->orwhere('users.email', 'like', $like)->orwhere('users.phone', 'like', $like);
                        });
                    }
                })
                ->make(true);
    }

    public function formOptions(): array{
        // Đảm bảo 3 ca làm việc luôn tồn tại trong DB
        foreach (['Ca Sáng', 'Ca Chiều', 'Ca Tối'] as $caName) {
            \App\Models\Team::firstOrCreate(['name' => $caName]);
        }

        return [
            'part'   => \App\Models\Part::select('id', 'name as text')->orderBy('name')->get(),
            'position' => \App\Models\Position::select('id', 'name as text')->orderBy('name')->get(),
            'ca'     => \App\Models\Team::select('id', 'name as text')->whereIn('name', ['Ca Sáng', 'Ca Chiều', 'Ca Tối'])->orderBy('name')->get(),
            'type_account' => \App\Models\Type_account::select('id', 'name as text')->orderBy('name')->get(),
            'branch' => \App\Models\Branch::select('id', 'name as text')->orderBy('name')->get(),
            'genders' => [
                ['id' => 0, 'text' => 'nam'],
                ['id' => 1, 'text' => 'nữ'],
            ],
            'status' => [
                ['id' => 0, 'text' => 'Đang làm'],
                ['id' => 1, 'text' => 'Đã nghỉ']
            ],
        ];
    }

    public function store(array $validated): User{
        return \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'birthday' => $validated['birthday'],
            'sex' => $validated['sex'],
            'part_id' => !empty($validated['part']) ? $validated['part'] : null,
            'position_id' => !empty($validated['position']) ? $validated['position'] : null,
            'type_work' => isset($validated['type_work']) && $validated['type_work'] !== '' && $validated['type_work'] !== null ? $validated['type_work'] : 0,
            'team_id' => !empty($validated['team']) ? $validated['team'] : null,
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'status' => $validated['status'],
            'start_day' => $validated['start_day'],
            'end_day' => $validated['end_day'],
            'type_accounts_id' => $validated['type_account'],
            'branch_id' => $validated['branch_id'],
        ]);
    }

    public function update(array $validated, User $user): bool{
        $dataUpdate = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'birthday' => $validated['birthday'],
            'sex' => $validated['sex'],
            'part_id' => !empty($validated['part']) ? $validated['part'] : null,
            'position_id' => !empty($validated['position']) ? $validated['position'] : null,
            'type_work' => isset($validated['type_work']) && $validated['type_work'] !== '' && $validated['type_work'] !== null ? $validated['type_work'] : 0,
            'team_id' => !empty($validated['team']) ? $validated['team'] : null,
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'status' => $validated['status'],
            'start_day' => $validated['start_day'],
            'end_day' => $validated['end_day'],
            'type_accounts_id' => $validated['type_account'],
            'branch_id' => $validated['branch_id'],
        ];
        if(!empty($validated['password'])){
            $dataUpdate['password'] = Hash::make($validated['password']);
        }

        return $user->update($dataUpdate);
    }

    public function destroy(User $user): bool{
        return $user->delete();
    }
}