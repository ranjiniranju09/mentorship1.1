<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use App\Mentor;
use App\Mentee;
use App\Module;
use App\Http\Requests\StoreSessionRequest;
use App\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Mail\SessionCreatedMentor;
use App\Mail\SessionCreatedMentee;

class SessionController extends Controller
{
    //
    public function index()
    {
        // Get the logged-in user ID
        $userId = Auth::id();
    
        // Check if the user is a mentor
        $mentor = DB::table('mentors')->where('user_id', $userId)->first();
    
        // Check if the user is a mentee
        $mentee = DB::table('mentees')->where('user_id', $userId)->first();
    
        // Initialize variables
        $mentorId = null;
        $menteeId = null;
    
        if ($mentor) {
            $mentorId = $mentor->id;
        } elseif ($mentee) {
            $menteeId = $mentee->id;
        } else {
            return abort(403, 'Unauthorized access'); // If user is neither a mentor nor a mentee
        }
            
        // Fetch module names
        $modulenames = Module::pluck('name', 'id');
    
        // Fetch mappings if the user is a mentor
        $mappings = $mentor ? DB::table('mappings')->where('mentorname_id', $mentor->id)->get() : collect();

        // Fetch mentee details
        $menteeIds = $mappings->pluck('menteename_id')->toArray();
        $menteenames = !empty($menteeIds) ? DB::table('mentees')->whereIn('id', $menteeIds)->pluck('name', 'id') : collect();
    
        $mentorIds = $mappings->pluck('mentorname_id')->toArray();
        // $menteeIds = $mappings->pluck('menteename_id')->toArray();
    
        // Fetch mentor names based on mappings
        $mentornames = Mentor::whereIn('id', $mentorIds)->pluck('name', 'id');
            
    
        // Fetch mentee names based on mappings
        $menteenames = Mentee::whereIn('id', $menteeIds)->pluck('name', 'id');

    
        // Fetch sessions if the user is a mentor
        $sessions = $mentorId ? DB::table('sessions')->where('mentorname_id', $mentorId)->get() : collect();

    
        // Fetch session titles
        $sessionTitles = DB::table('sessions')->pluck('session_title', 'id');
    
        // Pass all data to the view
        return view('mentor.sessions.index', compact(
            'mentornames',
            'menteenames',
            'modulenames',
            'sessions',
            'mappings',
            'sessionTitles'
        ));
    }
    


    public function store(Request $request)
    {
        // Validate request data (removed 'mentorname_id' validation)
        $request->validate([
            'sessiondatetime' => 'required|date',
            'sessionlink' => 'required|string',
            'session_title' => 'required|string',
            'session_duration_minutes' => 'required|integer',
            'modulename_id' => 'required|integer',
        ]);


        // Get the logged-in user ID
        $userId = Auth::id();

        // Fetch the mentor ID of the logged-in user
        $mentor = DB::table('mentors')->where('user_id', $userId)->first();

        // If the user is not a mentor, return an error
        if (!$mentor) {
            return abort(403, 'Unauthorized access');
        }

        // Fetch the mapped mentee ID from the mappings table
        $mapping = DB::table('mappings')->where('mentorname_id', $mentor->id)->first();


        // If no mentee is mapped, return an error
        if (!$mapping) {
            return back()->with('error', 'No mentee is mapped to this mentor.');
        }

        // Insert session record into the database with the logged-in mentor ID and mapped mentee ID
        $sessionId = DB::table('sessions')->insertGetId([
            'sessiondatetime' => $request->sessiondatetime,
            'sessionlink' => $request->sessionlink,
            'session_title' => $request->session_title,
            'session_duration_minutes' => $request->session_duration_minutes,
            'modulename_id' => $request->modulename_id,
            'mentorname_id' => $mentor->id, // Set mentor ID from logged-in user
            'menteename_id' => $mapping->menteename_id, // Use mapped mentee ID
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Fetch mentor, mentee, and module details
        $mentee = DB::table('mentees')->where('id', $mapping->menteename_id)->first();
        $module = DB::table('modules')->where('id', $request->modulename_id)->first();


        if ($mentee && $module) {
            // Create a session object with necessary details
            $sessionData = (object) [
                'id' => $sessionId,
                'mentorname' => $mentor->name,
                'menteename' => $mentee->name,
                'modulename' => $module->name,
                'sessiondatetime' => $request->sessiondatetime,
                'sessionlink' => $request->sessionlink,
                'session_title' => $request->session_title,
                'session_duration_minutes' => $request->session_duration_minutes,
            ];

            // Send emails to mentor and mentee
            Mail::to($mentor->email)->send(new SessionCreatedMentor($sessionData));
            Mail::to($mentee->email)->send(new SessionCreatedMentee($sessionData));
        }

        // Redirect back with success message
        return redirect()->route('sessions.index')->with('success', 'Session created successfully.');
    }

    
    
    public function edit($id)
    {
        // Fetch a single session record
        $session = DB::table('sessions')->where('id', $id)->first();
        
        // Check if the session exists
        if (!$session) {
            return redirect()->route('sessions.index')->with('error', 'Session not found');
        }

        // Return the view with the session data
        return view('sessions.index', ['session' => $session]);
    }


    // Method to update the session details
    public function update(Request $request, $id)
    {
        // Extract the request data directly without validation
        $sessiondatetime = $request->input('sessiondatetime');
        $sessionlink = $request->input('sessionlink');
        $session_title = $request->input('session_title');
        $session_duration_minutes = $request->input('session_duration_minutes');

        $done = $request->input('done') === 'yes' ? 1 : 0; // Assuming 1 is for done and 0 is for not done

        // Update the session in the database
        DB::table('sessions')
            ->where('id', $id)
            ->update([
                'sessiondatetime' => $sessiondatetime,
                'sessionlink' => $sessionlink,
                'session_title' => $session_title,
                'session_duration_minutes' => $session_duration_minutes,
                'done' => $done,
                'updated_at' => Carbon::now(), // Update timestamp
            ]);

            $session = DB::table('sessions')->where('id', $id)->first();

            
            $modulenames = Module::pluck('name', 'id');
            $mentornames = Mentor::pluck('name', 'id');

            $menteenames = Mentee::pluck('name', 'id');
            $assignments = DB::table('mappings')->get();
            $mentorIds = $assignments->pluck('mentorname_id')->toArray();
            $menteeIds = $assignments->pluck('menteename_id')->toArray();
            $sessions = Session::where('mentorname_id', $mentorIds)->get();
            $mentornames = Mentor::whereIn('id', $mentorIds)->pluck('name', 'id');
            $menteenames = Mentee::whereIn('id', $menteeIds)->pluck('name', 'id');
            $sessionTitles = DB::table('sessions')->pluck('session_title', 'id');

        // Redirect back to the session list or another route
        // return redirect()->route('sessions.index')->with('success', 'Session updated successfully');
        return view('mentor.sessions.index', compact('menteenames', 'mentornames', 'modulenames','sessions','session','assignments','sessionTitles'));
    }


    public function destroy($id)
    {
        // Delete the session from the database
        DB::table('sessions')->where('id', $id)->delete();

        // Redirect back to the session list or another route
        return redirect()->route('sessions.index')->with('success', 'Session deleted successfully');
    }

    public function uploadRecording(Request $request)
    {

        
        // Validate the request
        $request->validate([
            'selectSession' => 'required',
            'recordingFile' => 'required|file|mimes:mp3,mp4,wav,m4a|max:5120000', // 5GB
        ]);
        

        // Handle the uploaded file
        $file = $request->file('recordingFile');
        $filePath = 'recordings/' . uniqid() . '_' . $file->getClientOriginalName();

        // Upload the file to S3
        $path = Storage::disk('s3')->put($filePath, file_get_contents($file));

        if ($path) {
            // Correctly construct the S3 URL based on the bucket's region and endpoint
            //$s3Url = 'https://' . env('AWS_BUCKET') . '.s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . $filePath;

            //$s3Url = Storage::disk('s3')->url($filePath);
            $bucket = env('AWS_BUCKET');
            $region = env('AWS_DEFAULT_REGION');
            $baseUrl = "https://{$bucket}.s3.{$region}.amazonaws.com/";
            $fileUrl = $baseUrl . $filePath;

            
        // Update the file_path in the sessions table
        DB::table('sessions')
            ->where('id', $request->input('selectSession'))
            ->update(['file_path' => $fileUrl]);

            return redirect()->back()->with('success', 'Recording uploaded and saved successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to upload recording.');
        }

        }
    }


