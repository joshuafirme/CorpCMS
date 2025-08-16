<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    private $page = "roles";

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            return User::isPermitted($this->page) ? $next($request) : redirect('/403');
        });
    }  

    public function index()
    {
        $user_roles = UserRole::paginate(15);

        return view('admin.user-roles.list', compact('user_roles'));
    }    
    
    public function store(Request $request)
    {
        $data = $request->all();
        if (!$request->permissions) {
            return redirect()->back()->with('danger', 'No selected permission, please try again.');
        }
        $data['permissions'] = json_encode($request->permissions);
        UserRole::create($data);
        return redirect()->back()->with('success', 'Role was added.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->except([ '_token' ]);
        $data['permissions'] = json_encode($request->permissions);
        UserRole::where('id', $id)->update($data);
        return redirect()->back()->with('success', 'Role was updated.');
    }

    public function destroy($id)
    {

        $query = UserRole::where('id', $id);
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
}
