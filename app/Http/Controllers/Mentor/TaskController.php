<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskAddedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function index(Request $request)
{
    $mentorEmail = Auth::user()->email;
    $mentor = DB::table('mentors')->where('email', $mentorEmail)->first();

    if (!$mentor) {
        return 'Mentor not found.';
    }

    $tasks = DB::table('assign_tasks')
    ->select('id', 'title', 'description', 'files', 'start_date_time', 'end_date_time', 'completed', 'task_response', 'submitted_file', 'mentors_reply')  // Use mentors_reply
    ->where('mentor_id', $mentor->id)
    ->where('completed', 'open')
    ->get();

    $taskscomplete = DB::table('assign_tasks')
    ->select('id', 'title', 'description', 'files', 'start_date_time', 'end_date_time', 'completed', 'task_response', 'submitted_file', 'mentors_reply')  // Use mentors_reply
    ->where('mentor_id', $mentor->id)
    ->where('completed', 'close')
    ->get();




    // Return view with both open and closed tasks
    return view('mentor.tasks.index', compact('tasks', 'taskscomplete'));
}


    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date_time' => 'required|date',
            'end_date_time' => 'required|date',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,zip,jpg,jpeg,png|max:20480',
        ]);

        // Retrieve the logged-in mentor's email
        $userEmail = Auth::user()->email;

        // Retrieve the logged-in mentor by their email
        $mentor = DB::table('mentors')->where('email', $userEmail)->first();

        if (!$mentor) {
            return redirect()->back()->with('error', 'Mentor not found.');
        }

        // Get the mentee mapped to the mentor
        $mapping = DB::table('mappings')->where('mentorname_id', $mentor->id)->first();

        // return $mapping;

        if (!$mapping) {
            return redirect()->back()->with('error', 'No mentee mapped to this mentor.');
        }

        $menteeId = $mapping->menteename_id;

        $mentee = DB::table('mentees')->where('id', $menteeId)->first();

    
        // Handle file upload
        $fileUrl = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filePath = 'tasksattachments/' . uniqid() . '_' . $file->getClientOriginalName();
    
            // Upload to S3
            $uploaded = Storage::disk('s3')->put($filePath, file_get_contents($file));
    
            if ($uploaded) {
                // Construct the S3 URL based on the bucket's region and endpoint
                $bucket = env('AWS_BUCKET');
                $region = env('AWS_DEFAULT_REGION');
                $baseUrl = "https://{$bucket}.s3.{$region}.amazonaws.com/";
                $fileUrl = $baseUrl . $filePath;
            } else {
                return redirect()->back()->with('error', 'Failed to upload file to S3.');
            }
        }
    
        // Insert the new task into the assign_tasks table
        $taskId = DB::table('assign_tasks')->insertGetId([
            'title' => $request->title,
            'description' => $request->description,
            'start_date_time' => $request->start_date_time,
            'end_date_time' => $request->end_date_time,
            'mentor_id' => $mentor->id,
            'mentee_id' => $menteeId,
            'submitted' => 0,
            'completed' => 'Open',
            'files' => $fileUrl,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
    
        $menteeEmail = $mentee->email;

        if ($menteeId) {
            // Retrieve the task from the database
            $task = DB::table('assign_tasks')->where('id', $taskId)->first();
        
            
            // Send the email notification
            Mail::to($menteeEmail)->send(new TaskAddedNotification($task, $mentor->name,$mentee->name));
        

            // return [
            //     'menteeId' => $menteeId,
            //     'menteeEmail' => $menteeEmail,
            //     'mentor' => $mentor,
            //     'task' => $task,
            //     'mentee' => $mentee,
            // ];
            
            // Check if there were any failures in sending the email
            if (count(Mail::failures()) > 0) {
                // Redirect back with a failure message if the email was not sent
                return redirect()->back()->with('error', 'Task added, but failed to send email.');
            }
        
            // Redirect back with a success message if the email was sent successfully
            return redirect()->back()->with('success', 'Task added and email sent successfully.');
        }
    
        return redirect()->back()->with('error', 'Task added, but no mentee found.');
    }
    
    public function show($id)
    {
        $task = DB::table('assign_tasks')->where('id', $id)->first();

        if (!$task) {
            return response()->json(['error' => 'Task not found.'], 404);
        }

        return view('mentor.tasks.index', compact('task'));
    }


    public function update(Request $request, $id)
    {
        // Initialize an array to hold the update data
        $updateData = [
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'start_date_time' => $request->input('start_date_time'),
            'end_date_time' => $request->input('end_date_time'),
            'mentors_reply' => $request->input('mentors_reply'),
            'completed' => $request->input('completed'),
        ];

        // Handle file upload if a file is provided
        if ($request->hasFile('file')) {
            $file = $request->file('file');
        
            // Check if the file is valid
            if ($file->isValid()) {
                $fileName = uniqid() . '_' . $file->getClientOriginalName();
                $filePath = 'tasksattachments/' . $fileName;
        
                try {
                    // Upload to S3
                    $uploaded = Storage::disk('s3')->put($filePath, file_get_contents($file));
        
                    if ($uploaded) {
                        // Construct the S3 URL based on the bucket's region and endpoint
                        $bucket = env('AWS_BUCKET');
                        $region = env('AWS_DEFAULT_REGION');
                        $baseUrl = "https://{$bucket}.s3.{$region}.amazonaws.com/";
                        $fileUrl = $baseUrl . $filePath;
                        $fileUploaded = true;
                    } else {
                        return redirect()->back()->with('error', 'Failed to upload file to S3.');
                    }
                    /* if ($uploaded) {
                        // Construct the S3 URL
                        $fileUrl = Storage::disk('s3')->url($filePath);
                        $updateData['files'] = $fileUrl; // Use 'files' column if that's the correct one
                    } else {
                        return redirect()->back()->with('error', 'Failed to upload file to S3.');
                    } */
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Error uploading file to S3: ' . $e->getMessage());
                }
            } else {
                return redirect()->back()->with('error', 'Uploaded file is not valid.');
            }
        }
        
        // Update the task in the database
        $updated = DB::table('assign_tasks')
            ->where('id', $id)
            ->update($updateData);

        if (!$updated) {
            return redirect()->back()->with('error', 'Failed to update task.');
        }

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully');
    }


    public function destroy($id)
    {
        try {
            DB::table('assign_tasks')->where('id', $id)->delete();
            return redirect()->route('tasks.index')->with('success', 'Task deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('tasks.index')->with('error', 'Failed to delete task');
        }
    }
}
