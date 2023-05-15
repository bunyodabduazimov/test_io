<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['поступил', 'водитель назначен', 'водитель на месте', 'исполняется', 'выполнен'];
        $centerLatitude = 40.2862; // Широта центра Худжанда
        $centerLongitude = 69.6288; // Долгота центра Худжанда
        $radius = 10; // Радиус в километрах

        for ($i = 1; $i <= 10; $i++) {
            $order = new Order();
            $order->longitude = $this->generateRandomCoordinate($centerLongitude, $radius);
            $order->latitude = $this->generateRandomCoordinate($centerLatitude, $radius);
            $order->amount = rand(10, 20);
            $order->status = $statuses[0];
            $order->save();
        }
    }
    private function generateRandomCoordinate($center, $radius)
    {
        $randomCoordinate = $center + (mt_rand(-$radius * 1000000, $radius * 1000000) / 1000000);
        return round($randomCoordinate, 6); // Округляем до 6 знаков после запятой
    }
}
