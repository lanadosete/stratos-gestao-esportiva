<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/cadastro', function () {
    return view('auth.register');
});

Route::get('/agendamento', function () {
    return view('pagamento');
});

Route::get('/proprietario', function () {
    return view('proprietario.dashboard');
});

Route::get('/admin', function () {
    return view('admin.dashboard');
});

Route::get('/pagamento', function () {
    return view('pagamento');
});