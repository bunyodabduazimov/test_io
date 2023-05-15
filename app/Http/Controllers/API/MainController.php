<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;

class MainController extends Controller
{
   /**
     * @OA\Get(
     *     path="/api/company-balance/{id}",
     *     summary="Получить баланс компании",
     *     tags={"Company"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Идентификатор компании",
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
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Неавторизованный доступ"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Компания не найдена"
     *     )
     * )
     */

     public function companyBalance(Request $request)
     {
        $company = Company::find($request->id);

        if (!empty($company)) {
            return response()->json(['balance' => $company->balance]);
         }
        
        return response()->json(['message' => 'Компания не найдена.']);
     }
     
}
