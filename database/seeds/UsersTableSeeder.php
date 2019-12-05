<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\ClientRepository;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'name' => 'administrator',
            'email' => 'kominfo@bantenprov.go.id',
            'password' => Hash::make('rahasia'),
            'authent_id' => 1,
        ]);

        dd(((new ClientRepository())->createPasswordGrantClient(null, 'clientside', null))->secret);
    }
}