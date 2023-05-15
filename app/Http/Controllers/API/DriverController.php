<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Driver;

class DriverController extends Controller
{
    /**
    * @OA\Post(
    * path="/api/driver-register",
    * operationId="registerDriver",
    * tags={"Driver"},
    * security={{"bearer_token":{}}},
    * summary="Регистрация водителя",
    * description="Регистрация водителя",
    *     @OA\RequestBody(
    *         @OA\JsonContent(),
    *         @OA\MediaType(
    *            mediaType="multipart/form-data",
    *            @OA\Schema(
    *               type="object",
    *               required={"first_name", "last_name", "longitude", "latitude", "balance", "rating", "status", "login", "password", "password_confirmation"},
    *               @OA\Property(property="first_name", type="text"),
    *               @OA\Property(property="last_name", type="text"),
    *               @OA\Property(property="longitude", type="number", format="double", example="69.6288"),
    *               @OA\Property(property="latitude", type="number", format="double", example="40.2862"),
    *               @OA\Property(property="balance", type="number", format="double", example="0"),
    *               @OA\Property(property="rating", type="number", format="double", example="0", minimum=0, maximum=0.99),
    *               @OA\Property(property="status", type="text", enum={"активен", "не активен"}, example="активен"),
    *               @OA\Property(property="login", type="string"),
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

    public function registerDriver(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'balance' => 'required',
            'rating' => 'required',
            'status' => 'required',
            'login' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $driver = Driver::create($validatedData);

        return response()->json(['message' => 'Водитель успешно зарегистрирован',  'driver' => $driver]);

    }

    /**
     * @OA\Get(
     *     path="/api/driver-balance/{id}",
     *     summary="Получить баланс водителя",
     *     tags={"Driver"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Идентификатор водителя",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *         @OA\JsonContent(
     *             @OA\Property(property="balance", type="number", format="double", example=100.50)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Неавторизованный доступ"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Водитель не найден"
     *     )
     * )
     */
    public function balanceDriver(Request $request)
    {
        $driver = Driver::find($request->id);
        
        if (!empty($driver)) {
            return response()->json(['balance' => $driver->balance]);
        }
        
        return response()->json(['message' => 'Водитель не найден.']);
    }    
}
