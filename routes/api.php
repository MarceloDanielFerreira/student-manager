<?php
use App\Http\Controllers\studentController as studentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/students', [studentController::class,'index'] );
Route::post('/students', [studentController::class,'store'] );
Route::get('/students/{id}', [studentController::class,'show'] );
Route::put('/students/{id}', [studentController::class,'update'] );
Route::delete('/students/{id}', [studentController::class,'destroy'] );
Route::get('/students/search', [studentController::class,'search'] );
