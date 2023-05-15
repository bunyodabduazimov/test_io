<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Order;
use App\Models\Driver;
use App\Models\Company;
use App\Models\Transaction;


class OrderController extends Controller
{
    /**
     * @OA\Put(
     *     path="/api/orders/{id}/status",
     *     tags={"Order"},
     *     security={{"sanctum":{}}},
     *     summary="Обновить статус заказа",
     *     operationId="updateOrderStatus ",
     *     description="Обновляет статус указанного заказа.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID заказа",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Статус заказа",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             enum={"водитель на месте", "исполняется"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешное обновление статуса заказа",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Статус заказа успешно обновлен")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Ошибка при обновлении статуса заказа",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Некорректный ID заказа или статус")
     *         )
     *     )
     * )
     */


    public function updateOrderStatus (Request $request)
    {
        $order = Order::find($request->id);
        if($request->status == 'водитель на месте'){
            $order->status = $request->status;
            // Дополнительные действия
        } else if($request->status == 'исполняется'){
            $order->status = $request->status;
            // Дополнительные действия
        }
        $order->save();
        return response()->json(['message' => 'Заказ успешно обновлен'], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/orders-complate/{id}",
     *     tags={"Order"},
     *     security={{"sanctum":{}}},
     *     summary="Завершить заказ",
     *     operationId="completeOrder",
     *     description="Завершает заказ и обновляет балансы водителя и компании.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID заказа",
     *         required=true,
     *         @OA\Schema(
     *             type="integer", format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Заказ успешно завершен",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Заказ успешно завершен"),
     *             @OA\Property(property="driver_balance", type="number", format="double", example="150.50")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Ошибка при завершении заказа",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Некорректный заказ или водитель не выбран")
     *         )
     *     )
     * )
     */

    public function completeOrder(Request $request)
    {
        try {
            DB::beginTransaction();
            $order = Order::find($request->id);
            if (!empty($order) && $order->status == 'исполняется') {
                $commission = $order->amount * 0.1;
                $driver = Driver::find($order->driver_id);
                $company = Company::find(1);
    
                if ($driver->balance >= $commission) {
                    $driver->balance -= $commission;
                    $company->balance += $commission;
                    $driver->save();
                    $company->save();
                    $order->status = 'выполнен';
                    $order->save();
    
                    $transaction = new Transaction();
                    $transaction->company_id = $company->id;
                    $transaction->driver_id = $driver->id;
                    $transaction->amount = $order->amount;
                    $transaction->commission = $commission;
                    $transaction->save();
    
                    DB::commit();
    
                    return response()->json(['message' => 'Заказ успешно выполнен', 'driver_balance' => $driver->balance], 200);
                } else {
                    DB::rollBack();
                    return response()->json(['message' => 'Недостаточно средств на балансе водителя'], 400);
                }
            } else {
                return response()->json(['message' => 'Некорректный ID заказа или статус'], 400);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    
        return response()->json(['message' => 'Error'], 400);
    }

    // public function completeOrder(Request $request)
    // {
    //     DB::transaction(function() use ($request) {
    //         $order = Order::find($request->id);
    //         if(!empty($order))
    //         if($order->status == 'исполняется'){
    //             $commission = $order->amount * 0.1;
    //             $driver = Driver::find($order->driver_id);
    //             $company = Company::find(1);
        
    //             if ($driver->balance >= $commission) {            
    //                 $driver->balance -= $commission;
    //                 $company->balance += $commission;
    //                 $driver->save();
    //                 $company->save();        
    //                 $order->status = 'выполнен';
    //                 $order->save();
        
    //                 $transaction = new Transaction();
    //                 $transaction->company_id = $company->id;
    //                 $transaction->driver_id = $driver->id;
    //                 $transaction->amount = $order->amount;
    //                 $transaction->commission = $commission;
    //                 $transaction->save();
        
    //                 return response()->json(['message' => 'Заказ успешно выполнен', 'driver_balance' => $driver->balance], 200);
    //             } else {
    //                 return response()->json(['message' => 'Недостаточно средств на балансе водителя'], 400);
    //             }
    //         }
    //         return response()->json(['message' => 'Заказ пустой или водитель еще не выбран'], 400);
    //     }, function (Exception $ex) {
    //         return response()->json(['message' => "Error", 400]);
    //     });

    //     return response()->json(['message' => 'Error'], 400);
    // }
}
