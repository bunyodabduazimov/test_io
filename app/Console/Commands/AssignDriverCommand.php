<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Driver;

class AssignDriverCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:assign-driver-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'С помощью консоли создать artisan команду, которая для заказа назначит водителя';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $order = Order::where('status', 'поступил')->first();
        if (!$order) {
            $this->info('Нет заказов со статусом "поступил"');
            return;
        }

        $drivers = Driver::where('status', 'активен')->where('balance', '>=', $order->amount * 0.1)->orderBy('rating', 'desc')->get();
        if ($drivers->isEmpty()) {
            $this->info('Нет доступных водителей для заказа');
            return;
        }

        $assignedDriver = null;
        $minDistance = INF;

        foreach ($drivers as $driver) {
            $distance = self::distance($order->latitude, $order->longitude, $driver->latitude, $driver->longitude);
            if ($distance < $minDistance) {
                $minDistance = $distance;
                $assignedDriver = $driver;
            }
        }

        if (!$assignedDriver) {
            $this->info('Нет доступных водителей для заказа');
            return;
        }

        $order->driver_id = $assignedDriver->id;
        $order->status = 'водитель назначен';
        $order->save();

        $this->info('Заказ ' . $order->id . ' назначен водителю ' . $assignedDriver->id);
        $this->info('Информация о назначенном водителе:');
        $this->table(['ID', 'Имя', 'Фамилия', 'Рейтинг', 'Баланс', 'Дистанция'], [
            [$assignedDriver->id, $assignedDriver->first_name, $assignedDriver->last_name, $assignedDriver->rating, $assignedDriver->balance, $minDistance]
        ]);
    }

    public static function distance($latitude1, $longitude1, $latitude2, $longitude2)
    {
        $earthRadius = 6371;

        $latDifference = deg2rad($latitude2 - $latitude1);
        $lonDifference = deg2rad($longitude2 - $longitude1);

        $a = sin($latDifference / 2) * sin($latDifference / 2)
            + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2))
            * sin($lonDifference / 2) * sin($lonDifference / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;
        return $distance;
    }

}
