<?php
namespace App\Http\Controllers\Mentee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;
use App\Mentor;
use App\Mentee;
use App\Moduleresourcebank;

use Illuminate\Support\Facades\Redirect;


class KnowledgebankController extends Controller
{
	public function index()
{
    // Use query builder to fetch resources with their related module
    $resources = DB::table('resources')
        ->leftJoin('modules', 'resources.module_id', '=', 'modules.id')
        ->select('resources.*', 'modules.name as module_name', 'modules.id as module_id')
        ->get();

    return view('mentee.knowledgebank.index', compact('resources'));
}


}
