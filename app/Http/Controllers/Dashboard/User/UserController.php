<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\User\RegisterRequest;
use App\Http\Resources\Dashboard\User\UserResource;
use App\Mail\Client\User\PasswordMail;
use App\Mail\Client\User\VerifyMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function index(): \Illuminate\Http\JsonResponse
    {
        $users = User::all();
        return response()->json(['users' => UserResource::collection($users)]);
    }

    protected function show(User $user)
    {
        return response()->json(['user' => new UserResource($user)]);
    }

    protected function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $password = Str::random(12);
        $data['password'] = Hash::make($password);
        $user = User::firstOrCreate(['login' => $data['login']], $data);
        Mail::to($user->email)->send(new VerifyMail($user->id, $user->login, sha1($user->getEmailForVerification())));
        Mail::to($user->email)->send(new PasswordMail($password, $user->getEmailForVerification()));
        return response()->json([
            'message' => 'New user register successfully!',
        ], 201);
    }
}