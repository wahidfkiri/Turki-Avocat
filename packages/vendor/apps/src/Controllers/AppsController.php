<?php 

namespace Vendor\Apps\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppsController extends Controller
{
    public function index(Request $request)
    {
        return view('apps::index');
    }
}