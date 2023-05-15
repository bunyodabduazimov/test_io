<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Order;
use App\Models\User;
use Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Company::create([
            'name' => "Maxim taxi",
            'balance' => 1000,
        ]);

        User::create([
            'first_name' => 'first_name User',
            'last_name' => 'last_name User',
            'email' => 'test@example.com',
            'password' => Hash::make('123456'),
        ]);

        $statuses = ['активен', 'не активен'];
        $centerLatitude = 40.2862; // Широта центра Худжанда
        $centerLongitude = 69.6288; // Долгота центра Худжанда
        $radius = 10; // Радиус в километрах

        for ($i = 1; $i <= 10; $i++) {
            $driver = new Driver();
            $driver->first_name = 'Driver' . $i;
            $driver->last_name = 'Lastname' . $i;
            $driver->longitude = $this->generateRandomCoordinate($centerLongitude, $radius);
            $driver->latitude = $this->generateRandomCoordinate($centerLatitude, $radius);
            $driver->balance = rand(0, 100); 
            $driver->rating = rand(0, 99) / 100; 
            $driver->status = $statuses[array_rand($statuses)];
            $driver->login = 'driver' . $i;
            $driver->password = Hash::make('pass_driver'. $i);
            $driver->save();
        }
        

        $statuses = ['поступил', 'водитель назначен', 'водитель на месте', 'исполняется', 'выполнен'];
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
        return round($randomCoordinate, 6);
    }
}
