<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardBranchController extends Controller
{
  public function index()
  {
    return view('content.pages.branch.dashboard');
  }
}
