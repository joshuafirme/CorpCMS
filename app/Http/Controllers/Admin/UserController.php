<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use Auth;
use Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(UserRole $roles)
    {
        $query = User::select('users.*', 'ur.name as role', 'permissions')
            ->leftJoin('user_roles as ur', 'ur.id', '=', 'users.role_id');

        if (request()->key) {
            $query->orWhere('users.name', 'LIKE', '%' . request()->key . '%');
            $query->orWhere('users.email', 'LIKE', '%' . request()->key . '%');
        }

        $users = $query->paginate(10);

        $roleModel = $roles;
        $roles = UserRole::where('status', 1)->get();

        return view('admin.users.list', compact('users', 'roles', 'roleModel'));
    }


    public function store(Request $request)
    {
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        User::create($data);
        return redirect()->back()->with('success', 'User was added.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->except(['_token', 'verified']);

        if ($request->password && !empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $user = User::find($id);

        if ($user->update($data)) {
            return redirect()->back()->with('success', 'User was updated.');
        }
    }

    public function changePassword()
    {
        return view('admin.users.change-password');
    }

    public function resetPassword($token)
    {
        $user = User::where('email', request()->email)
            ->where('mail_token', $token)
            ->first();
        
        return view('app.reset-password', compact('user', 'token'));

    }


    public function updatePassword(Request $request)
    {
        $password = Hash::make($request->password);

        $user = User::find(Auth::id());
        $user->password = $password;

        if ($user->save()) {
            return redirect()->back()->with('success', 'Password was updated successfully.');
        }
        return redirect()->back()->with('danger', 'Unable to update password, please try again.');

    }

    public function destroy($id)
    {

        $query = User::where('id', $id);
        if ($query->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Data was deleted.'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Deletion failed'
        ]);
    }

    public function login()
    {
        if (auth()->check()) {
            return redirect('/');
        }
        return view('admin.login');
    }


    public function doLogin(Request $request)
    {
        $authenticate = Auth::attempt(['email' => $request->email, 'password' => $request->password]);

        if ($authenticate) {
            return redirect('admin/users');
        }

        return redirect()->back()->with('danger', 'These credentials do not match our records.');
    }
    public function verifyEmail($user_id, $mail_token)
    {
        $verified_user = User::where('id', $user_id)->where('mail_token', $mail_token)->first();

        if ($verified_user) {
            $already_verified = false;
            if (!$verified_user->email_verified_at) {
                $verified_user->email_verified_at = date('Y-m-d H:i:s');
                $verified_user->save();
            } else {
                $already_verified = true;
            }
            return view('app.account.mail-verification', compact('verified_user', 'already_verified'));
        }
        return view('app.account.mail-verification');
    }

    public function logout()
    {
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    }
}
