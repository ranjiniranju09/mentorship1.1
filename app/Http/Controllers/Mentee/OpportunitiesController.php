<?php

namespace App\Http\Controllers\Mentee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Mentor;
use App\Mentee;
use App\Opportunity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;


class OpportunitiesController extends Controller
{
  public function index()
    {
    
    	$opportunity = DB::table('opportunities')->get();
    	
    	return view('mentee.opportunities.index',compact('opportunity'));
    }
}