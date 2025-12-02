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

// --- ROOT ROUTE ---
Route::get('/', function () {
  // Check if user has token cookie; if so, redirect based on role
  $token = request()->cookie('token');
  if ($token) {
    $isAdmin = request()->cookie('is_admin') === '1';
    return redirect($isAdmin ? '/admin' : '/branch');
  }
  // No token; redirect to login
  return redirect('/login');
});

// --- ADMIN ROUTES ---
Route::prefix("admin")
  ->name("admin.")
  ->group(function () {
    Route::view("/", "content.pages.admin.dashboard")->name("dashboard");
    Route::view("/branches", "content.pages.admin.branches")->name("branches");
    Route::view("/products", "content.pages.admin.products")->name("products");
    Route::view("/expenses", "content.pages.admin.expenses")->name("expenses");
    Route::view("/users", "content.pages.admin.users")->name("users");
    Route::view("/branch", "content.pages.admin.branchdetail")->name("branchdetail");
    Route::view("/export/labarugi", "content.pages.labarugiadmin")->name("labarugiadmin");
  });

// --- BRANCH ROUTES ---
Route::prefix("branch")
  ->name("branch.")
  ->group(function () {
    Route::view("/", "content.pages.branch.branchdetail")->name("dashboard");
    Route::view("/products", "content.pages.branch.products")->name("products");
    Route::view("/history/products", "content.pages.branch.producthistory")->name("producthistory");
    Route::view("/history/expenses", "content.pages.branch.expensehistory")->name("expensehistory");
    Route::view("/export/labarugi", "content.pages.labarugi")->name("labarugi");
    // Route::view("/branch", "content.pages.branch.branchdetail")->name("branchdetail1");
  });

  

Route::view("/login", "content.authentications.auth-login-basic")->name("login");
