<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Hash;

class AuthController extends Controller
{

    /**
    * @OA\Post(
    * path="/api/user-login",
    * summary="Авторизации пользователя",
    * description="Вход по электронной почте, пароль",
    * operationId="login",
    * security={{"sanctum":{}}},
    * tags={"User"},
    *     @OA\RequestBody(
    *         @OA\JsonContent(),
    *         @OA\MediaType(
    *            mediaType="multipart/form-data",
    *            @OA\Schema(
    *               type="object",
    *               required={"email", "password"},
    *               @OA\Property(property="email", type="string", format="email", example="test@example.com"),
    *               @OA\Property(property="password", type="string", format="password", example="123456"),
    *            ),
    *        ),
    *    ),
    *      @OA\Response(
    *          response=201,
    *          description="Успешная операция",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(
    *          response=200,
    *          description="Успешная операция",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(
    *          response=422,
    *          description="Необрабатываемый объект",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(response=400, description="Неверный запрос"),
    *      @OA\Response(response=404, description="Ресурс не найден"),
    * )
    */
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($validatedData)) {
            $user = Auth::user();
            $token = $user->createToken('access_token')->plainTextToken;
            return response()->json(['message' => 'Аутентификация успешна', 'access_token' => $token]);
        } else {
            return response()->json(['message' => 'Неверные учетные данные'], 401);
        }
    }


    /**
    * @OA\Post(
    * path="/api/user-register",
    * operationId="register",
    * tags={"User"},
    * security={{"sanctum":{}}},
    * summary="Регистрация пользователя",
    * description="Регистрация пользователя",
    *     @OA\RequestBody(
    *         @OA\JsonContent(),
    *         @OA\MediaType(
    *            mediaType="multipart/form-data",
    *            @OA\Schema(
    *               type="object",
    *               required={"first_name", "last_name","email", "password", "password_confirmation"},
    *               @OA\Property(property="first_name", type="text"),
    *               @OA\Property(property="last_name", type="text"),
    *               @OA\Property(property="email", type="text"),
    *               @OA\Property(property="password", type="password"),
    *               @OA\Property(property="password_confirmation", type="password")
    *            ),
    *        ),
    *    ),
   *      @OA\Response(
    *          response=201,
    *          description="Успешная операция",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(
    *          response=200,
    *          description="Успешная операция",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(
    *          response=422,
    *          description="Необрабатываемый объект",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(response=400, description="Неверный запрос"),
    *      @OA\Response(response=404, description="Ресурс не найден"),
    * )
    */
    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [  
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
    
        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
    
        $token = $user->createToken('API Token')->accessToken;
    
        return response()->json(['message' => 'Пользователь успешно зарегистрирован', 'user' => $user], 201);
    }
}
