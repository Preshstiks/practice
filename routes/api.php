<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);
Route::middleware('auth:sanctum')->group(function (){
    Route::post("/posts", [PostController::class, "createPost"]);
    Route::put('/posts/{post}', [PostController::class, "updatePost"]);
    Route::delete('/posts/{post}', [PostController::class, "deletePost"]);
});
Route::get("/posts", [PostController::class, "getPost"]);

