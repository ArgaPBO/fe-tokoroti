<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Frontend-only routes that render blade views
| All data fetching via JavaScript calling API endpoints
|--------------------------------------------------------------------------
*/

// --- ADMIN ROUTES ---
Route::prefix("admin")
  ->name("admin.")
  ->group(function () {
    Route::view("/", "content.pages.admin.dashboard")->name("dashboard");
    Route::view("/branches", "content.pages.admin.branches")->name("branches");
    Route::view("/products", "content.pages.admin.products")->name("products");
    Route::view("/expenses", "content.pages.admin.expenses")->name("expenses");
  });

// --- BRANCH ROUTES ---
Route::prefix("branch")
  ->name("branch.")
  ->group(function () {
    Route::view("/", "content.pages.branch.dashboard")->name("dashboard");
    Route::view("/products", "content.pages.branch.products")->name("products");
    Route::view("/history/product", "content.pages.branch.producthistory")->name("producthistory");
  });

Route::view("/login", "content.authentications.auth-login-basic")->name("login");
