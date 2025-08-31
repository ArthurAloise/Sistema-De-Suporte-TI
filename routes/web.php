<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SetorController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\TypesController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\User\ChangePasswordController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserProfileController;
use Illuminate\Support\Facades\Route;


//
//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');
//
//Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});

Route::get('/', function () {
    return view('welcome');
});

//DASHBOARD DO USUÁRIO PADRÃO
Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
});

//TELAS DO PERFIL DE USUÁRIO
Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::get('/profile', [UserProfileController::class, 'edit'])->name('user.profile');
    Route::post('/profile/update', [UserProfileController::class, 'update'])->name('user.profile.update');
});

//TELA PARA O USUÁRIO ALTERAR SUA PRÓPRIA SENHA
Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::get('/change-password', [ChangePasswordController::class, 'edit'])->name('user.change-password');
    Route::post('/change-password', [ChangePasswordController::class, 'update'])->name('user.change-password.update');
});

Route::middleware(['auth', 'permission:acessar_admin'])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    Route::get('/logs', [App\Http\Controllers\Admin\LogController::class, 'index'])->name('admin.logs');

    Route::resource('types', TypesController::class)->except(['show']);
    Route::post('types/{type}/recalculate-sla', [TypesController::class, 'recalculateSla'])->name('types.recalculateSla');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::post('categories/{category}/recalculate-sla', [CategoryController::class, 'recalculateSla'])->name('categories.recalculateSla');

    Route::resource('setores', SetorController::class)->except(['show']);
});

Route::resource('tickets', TicketController::class)->middleware('auth');
Route::post('/tickets/{id}/assign', [TicketController::class, 'assignTechnician'])->name('tickets.assign');
Route::post('/tickets/{id}/mark-as-pending', [TicketController::class, 'markAsPending'])->name('tickets.markAsPending');
Route::post('/tickets/{id}/mark-as-completed', [TicketController::class, 'markAsCompleted'])->name('tickets.markAsCompleted');
Route::post('/tickets/{id}/update-technician', [TicketController::class, 'updateTechnician'])->name('tickets.updateTechnician');

require __DIR__.'/auth.php';
