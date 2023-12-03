<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class RegisterNewUserController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository,
    ) {}

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users|email'
        ]);
        $validated = $validator->safe()->collect();
 
        $user = $this->userRepository->register($validated['email']);
 
        return response()->json(['personal_code' => $user->ulid_token], Response::HTTP_CREATED);
    }
}
