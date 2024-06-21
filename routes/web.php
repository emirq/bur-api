<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

Route::get('/', fn() => view('swagger'));

Route::get('/api-docs/openapi.yml', function () {
    $path = base_path('openapi.yml');

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});
