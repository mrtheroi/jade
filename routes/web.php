<?php

use App\Http\Controllers\DashboardExportController;
use App\Livewire\CategoryController;
use App\Livewire\CorteController;
use App\Livewire\ExpenseTypeController;
use App\Livewire\SalesDashboard;
use App\Livewire\SuppliesController;
use App\Livewire\UserController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('livewire.auth.login');
})->name('home');

//Route::view('dashboard', 'dashboard')
//    ->middleware(['auth', 'verified'])
//    ->name('dashboard');

Route::get('/dashboard/ventas/export/excel', [DashboardExportController::class, 'excel'])
    ->name('dashboard.ventas.export.excel')
    ->middleware(['auth']);

Route::get('/dashboard/ventas/export/pdf', [DashboardExportController::class, 'pdf'])
    ->name('dashboard.ventas.export.pdf')
    ->middleware(['auth']);

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', SalesDashboard::class)->name('dashboard');

    Route::get('users', UserController::class)->name('users');
    Route::get('corte', CorteController::class)->name('corte');
    Route::get('categories', CategoryController::class)->name('categories');
    Route::get('supplies', SuppliesController::class)->name('supplies');
    Route::get('expense-types', ExpenseTypeController::class)->name('expense-types');
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
