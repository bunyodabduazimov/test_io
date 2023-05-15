<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    title="Тестовое задание",
 *    version="1.0.0",
 *    description="Developer: Bunyod Abduazimov.",
 * ),
 *   @OA\SecurityScheme(
 *       securityScheme="bearer_token",
 *       in="header",
 *       name="Authorization",
 *       type="http",
 *       scheme="bearer",
 *       bearerFormat="JWT",
 *    ),
 */

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
