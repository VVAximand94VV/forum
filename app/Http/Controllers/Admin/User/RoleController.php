<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Http\Resources\Admin\User\RoleResource;
use App\Models\Role;

class RoleController extends Controller
{

    public function index(){
        return response()->json(['roles' => RoleResource::collection(Role::all())]);
    }

    public function store(RoleRequest $request){
        $data = $request->validated();
        //dd($data);
        $role = Role::firstOrCreate($data);
        return response()->json(['message' => 'Role created!']);
    }

    public function show(Role $role){
        return response()->json(['role' => new RoleResource($role)]);
    }

    public function update(RoleRequest $request, Role $role){
        $data = $request->validated();
        //dd($data);
        $role->name = $data['name'];
        $role->save();
        return response()->json(['message' => 'Role updated!']);
    }

    public function delete(Role $role){
        $role->delete();
        return response()->json(['message' => 'Role deleted successfully!']);
    }
}