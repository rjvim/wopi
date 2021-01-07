<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WOPIController;
use App\Http\Controllers\FileController;

Route::get('/wopi/host', [FileController::class,'host']);

Route::get('/wopi/files/{fileId}', [WOPIController::class,'checkFileInfo']);
Route::post('/wopi/files/{fileId}', [WOPIController::class,'lock']);
Route::get('/wopi/files/{fileId}/contents', [WOPIController::class,'GetFile']);