<?php

declare(strict_types=1);

use App\Models\Country;
use App\Models\Developer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/login');

Route::get('/user/image', App\Http\Controllers\UserImageController::class)
    ->name('user.image');

Route::middleware(['auth', 'verified'])
    ->group(function (): void {

        /* Route::get('/home', App\Livewire\Home::class)
            ->name('home'); */

        Route::redirect('/home', '/resume')
            ->name('home');

        Route::get('/resume', App\Livewire\Resume::class)
            ->name('resume')
            ->middleware('password.confirm');
    });
