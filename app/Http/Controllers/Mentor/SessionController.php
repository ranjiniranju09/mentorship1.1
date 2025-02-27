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


use App\Mail\SessionCreatedMentor;
use App\Mail\SessionCreatedMentee;

class SessionController extends Controller
{
    //
    public function index()
{
    // Fetch module names
    $modulenames = Module::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

    // Fetch assignments from mappings
    $assignments = DB::table('mappings')->get();
    $mentorIds = $assignments->pluck('mentorname_id')->toArray();
    $menteeIds = $assignments->pluck('menteename_id')->toArray();

    // Fetch mentor and mentee names based on mappings
    $mentornames = Mentor::whereIn('id', $mentorIds)
        ->pluck('name', 'id')
        ->prepend(trans('global.pleaseSelect'), '');

    $menteenames = Mentee::whereIn('id', $menteeIds)
        ->pluck('name', 'id')
        ->prepend(trans('global.pleaseSelect'), '');

    // Fetch sessions for mentors in mappings
    $sessions = DB::table('sessions')
        ->whereIn('mentorname_id', $mentorIds)
        ->get();

    // Fetch session titles
    $sessionTitles = DB::table('sessions')->pluck('session_title', 'id');

    // Pass all data to the view
    return view('mentor.sessions.index', compact(
        'menteenames',
        'mentornames',
        'modulenames',
        'sessions',
        'assignments',
        'sessionTitles'
    ));
}

    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'sessiondatetime' => 'required|date',
            'sessionlink' => 'required|string',
            'session_title' => 'required|string',
            'session_duration_minutes' => 'required|integer',
            'modulename_id' => 'required|integer',
            'mentorname_id' => 'required|integer',
            'menteename_id' => 'required|integer',
        ]);
    
        // Insert session record into the database
        $sessionId = DB::table('sessions')->insertGetId([
            'sessiondatetime' => $request->sessiondatetime, // Save as it is
            'sessionlink' => $request->sessionlink,
            'session_title' => $request->session_title,
            'session_duration_minutes' => $request->session_duration_minutes,
            'modulename_id' => $request->modulename_id,
            'mentorname_id' => $request->mentorname_id,
            'menteename_id' => $request->menteename_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        // Fetch mentor and mentee details
        $mentor = DB::table('mentors')->where('id', $request->mentorname_id)->first();
        $mentee = DB::table('mentees')->where('id', $request->menteename_id)->first();
        $module = DB::table('modules')->where('id', $request->modulename_id)->first();
    
        if ($mentor && $mentee && $module) {
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
    
        // Fetch module names
        $modulenames = DB::table('modules')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
    
        // Fetch mentor and mentee names from mappings
        $assignments = DB::table('mappings')->get();
        $mentorIds = $assignments->pluck('mentorname_id')->toArray();
        $menteeIds = $assignments->pluck('menteename_id')->toArray();
    
        $mentornames = DB::table('mentors')
            ->whereIn('id', $mentorIds)
            ->pluck('name', 'id')
            ->prepend(trans('global.pleaseSelect'), '');
    
        $menteenames = DB::table('mentees')
            ->whereIn('id', $menteeIds)
            ->pluck('name', 'id')
            ->prepend(trans('global.pleaseSelect'), '');
    
        // Retrieve sessions based on mentor IDs
        $sessions = DB::table('sessions')->whereIn('mentorname_id', $mentorIds)->get();
    
        // Fetch session titles for the view
        $sessionTitles = DB::table('sessions')->pluck('session_title', 'id');
    
        // Return view with necessary data
        return view('mentor.sessions.index', compact(
            'menteenames',
            'mentornames',
            'modulenames',
            'sessions',
            'assignments',
            'sessionTitles'
        ));
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

            
            $modulenames = Module::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
            $mentornames = Mentor::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

            $menteenames = Mentee::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
            $assignments = DB::table('mappings')->get();
            $mentorIds = $assignments->pluck('mentorname_id')->toArray();
            $menteeIds = $assignments->pluck('menteename_id')->toArray();
            $sessions = Session::where('mentorname_id', $mentorIds)->get();
            $mentornames = Mentor::whereIn('id', $mentorIds)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
            $menteenames = Mentee::whereIn('id', $menteeIds)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
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


