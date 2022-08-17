<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('employeeLists');
})->name('employee.lists');
Route::get('/create', function () {
    return view('employeeCreate');
})->name('employee.create');
Route::get('/edit/{id}', function () {
    return view('employeeEdit');
})->name('employee.edit');