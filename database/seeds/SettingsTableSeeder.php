<?php

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $model = new Setting();
        $model->id = 'company';
        $model->name = '<span class="headline font-weight-light">WEB</span><span class="headline font-weight-bold">SOCKET</span>';
        $model->title = '<div class="d-block display-1 text-uppercase"><span class="font-weight-light">let\'s build</span><span class="font-weight-bold"> realtime apps</span></div>';
        $model->quote = '<span class="d-block">WebSocket adalah standar baru untuk komunikasi realtime pada Web dan aplikasi mobile. WebSocket dirancang untuk diterapkan di browser web dan server web, tetapi dapat digunakan oleh aplikasi client atau server. WebSocket adalah protokol yang menyediakan saluran komunikasi full-duplex melalui koneksi TCP tunggal.</span>';
        $model->logo = '/images/logo-holder.svg';
        $model->background = '/images/back-holder.jpg';
        $model->height = '120px';
        $model->width = '120px';
        $model->save();
    }
}
