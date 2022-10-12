<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\CustomPermission;
use App\Models\User;
use App\Services\PermissionService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $users = User::paginate(10);


            return response()->json(new UserResource($users));

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        try {

            $user = UserService::store($request->all());

            return response()->json($user);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        try {

            return response()->json($user, 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        try {

            $user = UserService::update($request->all(), $user);

            return response()->json($user);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {

            $user->delete();

            return response()->json($user);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function restore(User $user) 
    {
        try {

            $user->restore();

            return response()->json($user);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function assignPermissions(Request $request, User $user)
    {
        try {
            $data = $request->all();

            $existingPermissions = CustomPermission::all()->pluck('name')->toArray();

            PermissionService::forgetCachedPermissions();

            foreach ($data['permissions'] as $permission) {
                if (!in_array($permission, $existingPermissions)) {
                    CustomPermission::findOrCreate($permission);
                }
            }

            $user->syncPermissions($data['permissions']);

            $user['permissions'] = $user->getPermissionNames();

            return response()->json($user);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        } 
    }
}
