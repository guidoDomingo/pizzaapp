<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;

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

Route::get('/', function () {
    return redirect('/admin');
});

// Rutas para manejo de tickets
Route::middleware(['auth'])->group(function () {
    Route::post('/tickets/regenerate/{order}', [TicketController::class, 'regenerateTicket'])->name('tickets.regenerate');
    Route::get('/tickets/download/{order}', [TicketController::class, 'downloadTicket'])->name('tickets.download');
});
