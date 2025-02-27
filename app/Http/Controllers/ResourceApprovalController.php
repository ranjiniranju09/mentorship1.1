<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resource;
use App\ResourceApproval;
use Auth;

class ResourceApprovalController extends Controller
{
    public function index()
    {
        $approvals = ResourceApproval::where('is_approved', false)->get();
        return view('resource_approvals.index', compact('approvals'));
    }

    public function approve(Request $request, ResourceApproval $approval)
    {
        $approval->update([
            'is_approved' => true,
            'admin_id' => Auth::id(),
            'comments' => $request->comments,
        ]);

        $resource = $approval->resource;
        $resource->update(['is_approved' => true]);

        return redirect()->route('resource_approvals.index');
    }
}
