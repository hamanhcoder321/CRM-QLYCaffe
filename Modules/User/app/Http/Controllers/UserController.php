<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Modules\User\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Validation\Rule;
class UserController extends Controller
{
    protected $userRepositroy;

    public function __construct(UserRepositoryInterface $userRepositroy){
        $this->userRepositroy = $userRepositroy;
    }

    public function index()
    {
        return view('users::users.list');
    }


    public function getFilters()
    {
        return response()->json($this->userRepositroy->getFilters());
    }

    public function getUsersData(Request $request)
    {
        if ($request->ajax()) {
            return $this->userRepositroy->getUsersData($request);
        }
    }

    // public function formOptions()
    // {
        
    // }

    public function created()
    {
        $option = $this->userRepositroy->formOptions();
        return view('users::users.created', [
            'option' => $option,
            'mode' => 'create',
            'user' => new User() // rỗng chưa có gì
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:6'],
            'birthday' => ['date', 'nullable'],
            'sex' => ['required'],
            'part' => ['integer', 'exists:parts,id'],
            'position' => ['integer', 'exists:positions,id'],
            'type_work' => ['required'],
            'team' => ['integer', 'exists:teams,id'],
            'phone' => ['nullable', 'max:20'],
            'address' => ['nullable', 'max:255'],
            'status' => ['required'],
            'start_day' => ['date', 'nullable'],
            'end_day' => ['date', 'nullable'],
            'type_account' => ['required'],
            'branch_id' => ['integer', 'nullable', 'exists:branches,id'],
        ], [
            'name.required' => 'Họ tên bắt buộc phải nhập',
            'email.required' => 'Email bắt buộc phải nhập',
            'email.unique' => 'Email đã tồn tại',
            'password.required' => 'password bắt buộc phải nhập',
            'sex.required' => 'Giới tính bắt buộc phải nhập',
            'type_work.required' => 'Hình thức làm bắt buộc phải nhập',
            'status.required' => 'Trạng thái bắt buộc phải nhập',
            'type_account.required' => 'Loại tài khoản bắt buộc phải nhập'
        ]);

        $this->userRepositroy->store($validated);

        return redirect()->route('users.list')->with('success', 'thêm mới tài khoản thành công');
    }

    public function edit(User $user)
    {
        $option = $this->userRepositroy->formOptions();
        return view('users::users.edit', [
            'option' => $option,
            'mode' => 'edit',
            'user' => $user // load data
        ]);
    }

    public function update(Request $request, User $user)
    {
        // validate form
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'birthday' => ['date', 'nullable'],
            'sex' => ['required'],
            'part' => ['integer', 'exists:parts,id'],
            'position' => ['integer', 'exists:positions,id'],
            'type_work' => ['required'],
            'team' => ['integer', 'exists:teams,id'],
            'phone' => ['nullable', 'max:20'],
            'address' => ['nullable', 'max:255'],
            'status' => ['required'],
            'start_day' => ['date', 'nullable'],
            'end_day' => ['date', 'nullable'],
            'type_account' => ['required'],
            'branch_id' => ['integer', 'nullable', 'exists:branches,id'],
        ], [
            'name.required' => 'Họ tên bắt buộc phải nhập',
            'email.required' => 'Email bắt buộc phải nhập',
            'email.unique' => 'Email đã tồn tại',
            'password.required' => 'password bắt buộc phải nhập',
            'sex.required' => 'Giới tính bắt buộc phải nhập',
            'type_work.required' => 'Hình thức làm bắt buộc phải nhập',
            'status.required' => 'Trạng thái bắt buộc phải nhập',
            'type_account.required' => 'Loại tài khoản bắt buộc phải nhập'
        ]);

        $this->userRepositroy->update($validated, $user);

        return redirect()->route('users.list')->with('success', 'Chỉnh sửa tài khoản thành công');

    }

    public function destroy(User $user){
        $this->userRepositroy->destroy($user);
        return redirect()->route('users.list')->with('success', 'xóa tài khoản thành công');
    }
}

