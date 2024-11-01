<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\TransportController;

Route::get("/",[AuthController::class,"authForm"])->name("login");
Route::post("/auth",[AuthController::class,"auth"]);
Route::get("/registerForm",[AuthController::class,"registrerForm"]);
Route::post("/registrer",[AuthController::class,"register"]);
Route::get("/logout",[AuthController::class,"logout"]);


Route::middleware(['auth'])->group(function () {
    Route::get("/admin/dashboard",[EmployeController::class,"dashboard"]);
    Route::get("/admin/employes/lister",[EmployeController::class,"lister"]);
    Route::get("/admin/employes/add",[EmployeController::class,"addForm"]);
    Route::post("/admin/employes/add",[EmployeController::class,"addE"]);
    Route::get("/admin/employes/editer/{id}",[EmployeController::class,"editer"]);
    Route::post("/admin/employes/update/{id}",[EmployeController::class,"update"]);
    Route::get("/admin/employes/supprimer/{id}",[EmployeController::class,"delete"]);
    Route::get("/admin/employes/localisation/{id}",[EmployeController::class,"showLocation"]);
    Route::get("/admin/employes/zone/{id}",[EmployeController::class,"getClosestTransportZoneAndDistancesByEmployeId"]);
    Route::get("/admin/employes/affecter/{id}",[EmployeController::class,"affecter"]);
    Route::post("/admin/employes/affecter/{id}",[EmployeController::class,"affecterSave"]);
    Route::get("/admin/employes/points",[EmployeController::class,"afficherPoints"]);

    Route::get("/admin/transports/lister",[TransportController::class,"lister"]);
    Route::get("/admin/transports/add",[TransportController::class,"addForm"]);
    Route::post("/admin/transports/add",[TransportController::class,"addT"]);
    Route::get("/admin/transports/editer/{id}",[TransportController::class,"editer"]);
    Route::post("/admin/transports/update/{id}",[TransportController::class,"modifier"]);
    Route::get("/admin/transports/supprimer/{id}",[TransportController::class,"supprimer"]);
    Route::get("/admin/transports/localisation/{id}",[TransportController::class,"showLocation"]);
    Route::get("/admin/transports/showAllZones/",[TransportController::class,"showAllZones"]);
    Route::get("/admin/transports/showTraject/{id}",[TransportController::class,"showTraject"]);
});
