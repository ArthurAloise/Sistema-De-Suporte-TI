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
use App\Http\Controllers\Admin\ReportsController;
use Illuminate\Support\Facades\Gate;


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

    Route::resource('setores', SetorController::class)
        ->parameters(['setores' => 'setor'])
        ->except(['show']);
    // === RELATÓRIOS ===
    Route::prefix('reports')->group(function () {
        // View principal
        Route::get('/', [ReportsController::class, 'index'])->name('reports.index');

        // Tickets – APIs
        Route::get('/api/tickets/kpis',          [ReportsController::class, 'apiTicketsKpis'])->name('reports.api.tickets.kpis');
        Route::get('/api/tickets/by-status',     [ReportsController::class, 'apiTicketsByStatus'])->name('reports.api.tickets.by_status');
        Route::get('/api/tickets/by-priority',   [ReportsController::class, 'apiTicketsByPriority'])->name('reports.api.tickets.by_priority');
        Route::get('/api/tickets/by-category',   [ReportsController::class, 'apiTicketsByCategory'])->name('reports.api.tickets.by_category');
        Route::get('/api/tickets/by-type',       [ReportsController::class, 'apiTicketsByType'])->name('reports.api.tickets.by_type');
        Route::get('/api/tickets/created-daily', [ReportsController::class, 'apiTicketsCreatedDaily'])->name('reports.api.tickets.created_daily');
        Route::get('/api/tickets/resolved-daily', [ReportsController::class, 'apiTicketsResolvedDaily'])->name('reports.api.tickets.resolved_daily');
        Route::get('/api/tickets/aging',         [ReportsController::class, 'apiTicketsAging'])->name('reports.api.tickets.aging');
        Route::get('/api/tickets/sla-monthly',   [ReportsController::class, 'apiSlaHitRateMonthly'])->name('reports.api.tickets.sla_monthly');

        // Logs – APIs
        Route::get('/api/logs/actions',  [ReportsController::class, 'apiLogsTopActions'])->name('reports.api.logs.actions');
        Route::get('/api/logs/by-day',   [ReportsController::class, 'apiLogsByDay'])->name('reports.api.logs.by_day');
        Route::get('/api/logs/top-users', [ReportsController::class, 'apiLogsTopUsers'])->name('reports.api.logs.top_users');
        Route::get('/api/logs/top-routes', [ReportsController::class, 'apiLogsTopRoutes'])->name('reports.api.logs.top_routes');
        Route::get('/api/logs/methods',  [ReportsController::class, 'apiLogsMethods'])->name('reports.api.logs.methods');

        // Exports
        Route::get('/export/tickets.csv', [ReportsController::class, 'exportTicketsCsv'])->name('reports.export.tickets.csv');
        Route::get('/export/logs.csv',    [ReportsController::class, 'exportLogsCsv'])->name('reports.export.logs.csv');
    });
});

Route::resource('tickets', TicketController::class)->middleware('auth');
Route::post('/tickets/{id}/assign', [TicketController::class, 'assignTechnician'])->name('tickets.assign');
Route::post('/tickets/{id}/mark-as-pending', [TicketController::class, 'markAsPending'])->name('tickets.markAsPending');
Route::post('/tickets/{id}/mark-as-completed', [TicketController::class, 'markAsCompleted'])->name('tickets.markAsCompleted');
Route::post('/tickets/{id}/update-technician', [TicketController::class, 'updateTechnician'])->name('tickets.updateTechnician');

require __DIR__ . '/auth.php';

// Aliases p/ templates do Breeze
Route::middleware('auth')->get('/dashboard', function () {
    $home = Gate::allows('acessar_admin') ? 'admin.dashboard' : 'user.dashboard';
    return redirect()->route($home);
})->name('dashboard');

Route::middleware('auth')->get('/profile', function () {
    return redirect()->route('user.profile');
})->name('profile.edit');
