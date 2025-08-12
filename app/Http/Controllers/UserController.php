<?php

namespace App\Http\Controllers;

use App\Filters\User\UserIndexFilter;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class UserController extends Controller
{

    public function index(){
        $this->authorize(__FUNCTION__,User::class);
        $users = User::with('roles')->filter(new UserIndexFilter(request()))->paginate(30);
        $roles = Role::orderBy('name')->get(['id','name']);
        return view('user.index',[
            'items'=> $users,
            'roles'=> $roles
        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,User::class);
        return view('user.create',[
            'roles'=>Role::get()
        ]);
    }

    public function store(Request $request){
        $this->authorize(__FUNCTION__,User::class);
        $this->validate($request,$this->rules(),$this->messages());
        $data = array_merge($request->except('_token','password_confirmation','role_id'),[
            'password'=>Hash::make($request->input('password')),
            'is_active'=>$request->exists('is_active') && !is_null($request->input('role_id')),
            'avatar'=>$this->storeAvatar($request)
        ]);
        $user = User::create($data);
        if(!is_null($request->input('role_id'))){
            $user->attachRole($request->input('role_id'));
        }
        return redirect()->route('users.index',['user'=>$user->id])->with('success','User Created Successfully');
    }

    public function edit(User $user)
    {
        $this->authorize(__FUNCTION__,User::class);
        $this->protectSuperAdmin($user);
        $userRole = optional($user->roles->first())->id;
        return view('user.edit',[
            'roles'=>Role::get(),
            'user'=>$user,
            'userRole'=>$userRole
        ]);
    }

    public function update(Request $request,User $user)
    {
        $this->authorize(__FUNCTION__,User::class);
        $this->protectSuperAdmin($user);
        $this->validate($request,$this->rules(true,$user),$this->messages());
        $data = array_merge($request->except('_token','password_confirmation','role_id'),[
            'password'=>is_null($request->input('password')) ? $user->password : Hash::make($request->input('password')),
            'is_active'=>($request->exists('is_active') && !is_null($request->input('role_id'))) || $user->is_super_admin,
            'avatar'=>$this->storeAvatar($request,$user->getAvatarStorageUrl())
        ]);
        $user->update($data);
        $roles = is_null($request->input('role_id')) ? [] : [$request->input('role_id')];
        $user->syncRole($roles);
        return redirect()->route('users.index',['user'=>$user->id])->with('success','User Updated Successfully');
    }

    protected function rules($is_update = false,$user = null){
        $rules =  [
            'full_name'=>'required',
            'name'=>'required|alpha_dash|min:4|unique:users,name',
            'password'=>'required|min:6|confirmed',
            'email'=>'email|nullable|unique:users,email',
            'employee_no'=>'nullable|integer|unique:users,employee_no',
        ];
        if($is_update){
            $rules = array_merge($rules, [
                'name'=>'required|alpha_dash|min:4|unique:users,name,'.$user->id,
                'email'=>'email|nullable|unique:users,email,'.$user->id,
                'employee_no'=>'nullable|integer|unique:users,employee_no,'.$user->id,
                'password'=>'nullable|min:6|confirmed',
            ]);
        }
        return $rules;
    }

    protected function messages(){
        return  [
            'name.required'=>'User Name is Required',
            'name.unique'=>'User Name has already been taken'
        ];
    }

    protected function storeAvatar(Request $request,$url = null){
        return $request->hasFile('avatar') ? $request->file('avatar')->store('avatars',['disk' => 'public']) : $url;
    }

    protected function protectSuperAdmin($user){
        if($user->is_super_admin && !Auth::user()->is_super_admin){
            throw new UnauthorizedHttpException("Not allowed to edit Super admin user");
        }
    }
}
