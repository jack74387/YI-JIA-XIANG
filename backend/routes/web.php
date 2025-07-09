<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// 這裡只放 web 相關路由，不要放 API 路由
// API 路由應該全部放在 routes/api.php 中
