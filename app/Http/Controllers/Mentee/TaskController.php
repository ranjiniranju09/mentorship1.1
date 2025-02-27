<?php

namespace App\Http\Controllers\Mentee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mentor;
use App\Mentee;
use App\AssignTask;
use App\Http\Requests\StoreAssignTaskRequest;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\Traits\MediaUploadingTrait;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class TaskController extends Controller
{
    //
    use MediaUploadingTrait;
    public function index()
    {

        $menteeEmail = Auth::user()->email;
        $mentee = Mentee::where('email', $menteeEmail)->first();
        if (!$mentee) {
            return  'Mentee not found.';
        }
        //return $mentee;

        $tasks = AssignTask::where('mentee_id', $mentee->id)->get();
        $unsubmittedTasks = AssignTask::where('mentee_id', $mentee->id)
                             ->whereNull('task_response')
                             ->get();
        $submittedTasks = AssignTask::where('mentee_id', $mentee->id)
                            ->whereNotNull('task_response')
                            ->get();
        
        //return $unsubmittedTasks;
        return view('mentee.tasks.index',compact('mentee','tasks','unsubmittedTasks','submittedTasks'));
    }
    

    public function submit(Request $request)
{
    try {
        // Validate the incoming request
        $request->validate([
            'task_response' => 'required|string',  // Ensure the response is not empty
            'submitted_files.*' => 'file|mimes:jpeg,png,pdf,doc,docx|max:5120',  // Validation for file types and size
        ]);
    
        // Retrieve the task by ID
        $task = AssignTask::findOrFail($request->task_id);
    
        // Update the task response
        $task->task_response = $request->task_response;
    
        // Handle file upload for submitted files
        $fileUrls = [];
        if ($request->hasFile('submitted_files')) {
            foreach ($request->file('submitted_files') as $file) {
                // Generate a unique file path for each file
                $filePath = 'tasksattachments/' . uniqid() . '_' . $file->getClientOriginalName();
    
                // Upload the file to S3
                $uploaded = Storage::disk('s3')->put($filePath, file_get_contents($file));
    
                if ($uploaded) {
                    // Construct the S3 URL for each file
                    $bucket = env('AWS_BUCKET');
                    $region = env('AWS_DEFAULT_REGION');
                    $baseUrl = "https://{$bucket}.s3.{$region}.amazonaws.com/";
                    $fileUrls[] = $baseUrl . $filePath;  // Add the file URL to the array
                } else {
                    // Return an error if any file failed to upload
                    return redirect()->back()->with('error', 'Failed to upload one or more files to S3.');
                }
            }
        }
    
        // Save the file URLs to the submitted_file column as a comma-separated list
        if (!empty($fileUrls)) {
            $task->submitted_file = implode(',', $fileUrls);  // Save the URLs in the database
        } else {
            $task->submitted_file = null;  // No files were uploaded
        }
    
        // Save the task with the updated data
        $task->save();
    
        // Retrieve the mentee information (assuming the user is the mentee)
        $menteeEmail = Auth::user()->email;
        $mentee = Mentee::where('email', $menteeEmail)->first();
    
        if (!$mentee) {
            return response()->json(['success' => false, 'message' => 'Mentee not found.'], 404);
        }
    
        // Prepare data for response, including task and file URLs
        $data = [
            'mentee' => $mentee,
            'task' => $task,
            'file_urls' => $fileUrls  // Include file URLs in the response
        ];
    
        // Return a success response with updated data
        return redirect()->route('menteetasks.index')->with(compact('mentee', 'data'));
    
    } catch (\Exception $e) {
        // Handle any unexpected errors here
        Log::error('Error submitting task:', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => 'An unexpected error occurred. Please try again.'], 500);
    }
}

    

    
public function storeCKEditorImages(Request $request)
    {
        

        $model         = new AssignTask();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    
}

    
