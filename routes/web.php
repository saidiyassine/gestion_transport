<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TransportController;

Route::get("/",[AuthController::class,"authForm"])->name("login");
Route::post("/auth",[AuthController::class,"auth"]);
Route::get("/registerForm",[AuthController::class,"registrerForm"]);
Route::post("/registrer",[AuthController::class,"register"]);
Route::get("/logout",[AuthController::class,"logout"]);


Route::middleware(['auth'])->group(function () {
    Route::get("/admin/dashboard",[AdminController::class,"dashboard"]);
    Route::get("/admin/employes/lister",[AdminController::class,"lister"]);
    Route::get("/admin/employes/add",[AdminController::class,"addForm"]);
    Route::post("/admin/employes/add",[AdminController::class,"addE"]);

    Route::get("/admin/transports/lister",[TransportController::class,"lister"]);
    Route::get("/admin/transports/add",[TransportController::class,"addForm"]);
    Route::post("/admin/transports/add",[TransportController::class,"addT"]);
    Route::get("/admin/transports/editer/{id}",[TransportController::class,"editer"]);
    Route::post("/admin/transports/update/{id}",[TransportController::class,"modifier"]);
    Route::get("/admin/transports/supprimer/{id}",[TransportController::class,"supprimer"]);
    Route::get("/admin/transports/localisation/{id}",[TransportController::class,"showLocation"]);
    Route::get("/admin/transports/showAllZones/",[TransportController::class,"showAllZones"]);
});
