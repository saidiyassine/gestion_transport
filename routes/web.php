<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

Route::get("/",[AuthController::class,"authForm"])->name("login");
Route::post("/auth",[AuthController::class,"auth"]);
Route::get("/registerForm",[AuthController::class,"registrerForm"]);
Route::post("/registrer",[AuthController::class,"register"]);
Route::get("/logout",[AuthController::class,"logout"]);


Route::middleware(['auth'])->group(function () {
    Route::get("/admin/dashboard",[AdminController::class,"dashboard"]);
    Route::get("/admin/employes/lister",[AdminController::class,"lister"]);
    Route::get("/admin/employe/add",[AdminController::class,"addForm"]);
    Route::post("/admin/employe/add",[AdminController::class,"addE"]);
});
