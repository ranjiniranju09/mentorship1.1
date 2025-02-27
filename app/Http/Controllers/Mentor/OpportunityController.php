<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class OpportunityController extends Controller
{
    public function index()
    {
        // Fetch all opportunities from the database
        $opportunities = DB::table('opportunities')->get();

        

        // Pass the data to the view
        // return view('mentor.jobs.index', ['opportunities' => $opportunities]);
        return view('mentor.jobs.index', compact('opportunities'));

    }
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'opportunity_type' => 'required|string|max:255',
            'link' => 'required|url|max:255',
        ]);

        // Insert the new opportunity using the query builder
        DB::table('opportunities')->insert([
            'title' => $validatedData['title'],
            'opportunity_type' => $validatedData['opportunity_type'],
            'link' => $validatedData['link'],
            'added_by' => Auth::id(), // Store the logged-in user's ID in the added_by column
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $opportunities = DB::table('opportunities')->get();

        // Return a success response (redirect, JSON, etc.)
        return view('mentor.jobs.index',compact('opportunities'));
    }
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'opportunity_type' => 'required|string|max:255',
            'link' => 'required|url|max:255',
        ]);

        // Update the opportunity in the database
        DB::table('opportunities')->where('id', $id)->update([
            'title' => $validatedData['title'],
            'opportunity_type' => $validatedData['opportunity_type'],
            'link' => $validatedData['link'],
            'updated_at' => now(),
        ]);

        // Redirect back with success message
        return redirect()->route('opportunity.index')->with('success', 'Opportunity updated successfully.');
    }


    public function destroy($id)
    {
        // Delete the opportunity from the database
        DB::table('opportunities')->where('id', $id)->delete();

        // Redirect back to the opportunity list with a success message
        return redirect()->route('opportunity.index')->with('success', 'Opportunity deleted successfully');
    }


}
