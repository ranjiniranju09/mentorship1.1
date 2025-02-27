<?php

namespace App\Http\Controllers\Admin;

use App\GuestLecture;
use App\Guestspeaker;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyGuestLectureRequest;
use App\Http\Requests\StoreGuestLectureRequest;
use App\Http\Requests\UpdateGuestLectureRequest;
use App\Mentee;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Mail;
use App\Mail\GuestSessionNotification;

class GuestLecturesController extends Controller
{
    use CsvImportTrait;

//     public function index(Request $request)
// {
//     abort_if(Gate::denies('guest_lecture_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

//     if ($request->ajax()) {
//         $query = GuestLecture::with(['speaker', 'invitedMentees'])->select(sprintf('%s.*', (new GuestLecture)->table));
//         $table = Datatables::of($query);

//         $table->addColumn('placeholder', '&nbsp;');
//         $table->addColumn('actions', '&nbsp;');

//         $table->editColumn('actions', function ($row) {
//             $viewGate      = 'guest_lecture_show';
//             $editGate      = 'guest_lecture_edit';
//             $deleteGate    = 'guest_lecture_delete';
//             $crudRoutePart = 'guest-lectures';

//             return view('partials.datatablesActions', compact(
//                 'viewGate',
//                 'editGate',
//                 'deleteGate',
//                 'crudRoutePart',
//                 'row'
//             ));
//         });

//         $table->editColumn('id', function ($row) {
//             return $row->id ? $row->id : '';
//         });
//         $table->editColumn('guessionsession_title', function ($row) {
//             return $row->guessionsession_title ? $row->guessionsession_title : '';
//         });
//         $table->addColumn('speaker_speakername', function ($row) {
//             return $row->speaker ? $row->speaker->speakername : '';
//         });

//         $table->editColumn('invited_mentees', function ($row) {
//             $labels = [];
//             foreach ($row->invited_mentees as $invited_mentee) {
//                 $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $invited_mentee->name);
//             }

//             return implode(' ', $labels);
//         });

//         $table->editColumn('guest_session_duration', function ($row) {
//             return $row->guest_session_duration ? GuestLecture::GUEST_SESSION_DURATION_RADIO[$row->guest_session_duration] : '';
//         });
//         $table->editColumn('platform', function ($row) {
//             return $row->platform ? GuestLecture::PLATFORM_SELECT[$row->platform] : '';
//         });

//         $table->rawColumns(['actions', 'placeholder', 'speaker_speakername', 'invited_mentees']);

//         return $table->make(true);
//     }

//     $guestspeakers = Guestspeaker::get();
//     $mentees       = Mentee::get();

//     // Now we only fetch guestLectures once for both DataTables and view
//     $guestLectures = GuestLecture::with(['speaker', 'invitedMentees'])->get();

//     return view('admin.guestLectures.index', compact('guestspeakers', 'mentees', 'guestLectures'));
// }

public function index(Request $request)
{
    abort_if(Gate::denies('guest_lecture_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    if ($request->ajax()) {
        // Eager load the relationships
        $guestLectures = GuestLecture::with(['speaker', 'invitedMentees'])->select('guest_lectures.*');

        return DataTables::of($guestLectures)
            ->addColumn('actions', function ($row) {
                $viewGate = 'guest_lecture_show';
                $editGate = 'guest_lecture_edit';
                $deleteGate = 'guest_lecture_delete';
                $crudRoutePart = 'guest-lectures';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            })
            // Update the invited_mentees column to return a formatted string
            // ->addColumn('invited_mentees', function ($row) {
            //     return $row->invitedMentees->map(function ($mentee) {
            //         return sprintf('<span class="label label-info label-many">%s</span>', $mentee->name);
            //     })->implode(' ');
            // })
            ->addColumn('invited_mentees', function ($row) {
                if ($row->invitedMentees && $row->invitedMentees->count()) {
                    return $row->invitedMentees->map(function ($mentee) {
                        return sprintf('<span class="badge badge-info">%s</span>', $mentee->name);
                    })->implode(' ');
                }
                return 'No Mentees Invited';
            })
            
            ->addColumn('speaker_name', function ($row) {
                return $row->speaker ? $row->speaker->speakername : '';
            })
            ->editColumn('guestsession_date_time', function ($row) {
                return $row->guestsession_date_time 
                    ? \Carbon\Carbon::parse($row->guestsession_date_time)->format('d-m-Y H:i') 
                    : '';
            })
            ->editColumn('guest_session_duration', function ($row) {
                return $row->guest_session_duration 
                    ? \App\GuestLecture::GUEST_SESSION_DURATION_RADIO[$row->guest_session_duration] 
                    : '';
            })
            ->editColumn('platform', function ($row) {
                return $row->platform 
                    ? \App\GuestLecture::PLATFORM_SELECT[$row->platform] 
                    : '';
            })
            ->rawColumns(['actions', 'invited_mentees']) // Allow raw HTML
            ->make(true);
    }

    // Fetch guest speakers and mentees for the filter dropdowns
    $guestspeakers = Guestspeaker::all();
    $mentees = Mentee::all();

    return view('admin.guestLectures.index', compact('guestspeakers', 'mentees'));
}




    public function create()
    {
        abort_if(Gate::denies('guest_lecture_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $speaker_names = Guestspeaker::pluck('speakername', 'id')->prepend(trans('global.pleaseSelect'), '');

        $invited_mentees = Mentee::pluck('name', 'id');

        return view('admin.guestLectures.create', compact('invited_mentees', 'speaker_names'));
    }

    public function store(StoreGuestLectureRequest $request)
    {
       $guestLecture = GuestLecture::create($request->all());

    // Attach invited mentees
         $guestLecture->invitedMentees()->sync($request->input('invited_mentees', []));

    // Load invited mentees and speaker
        $guestLecture->load('invitedMentees', 'speaker');

    // Send email to invited mentees
        foreach ($guestLecture->invitedMentees as $mentee) {
            Mail::to($mentee->email)->send(new GuestSessionNotification($guestLecture));
        }

        return redirect()->route('admin.guest-lectures.index');
    }

    public function edit(GuestLecture $guestLecture)
    {
        abort_if(Gate::denies('guest_lecture_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    
        // Fetch all speaker names
        $speaker_names = Guestspeaker::pluck('speakername', 'id')->prepend(trans('global.pleaseSelect'), '');
    
        // Fetch all mentees
        $invited_mentees = Mentee::pluck('name', 'id');
    
        // Load the 'speaker_name' and 'invitedMentees' relationship
        $guestLecture->load('speaker_name', 'invitedMentees');
    
        // Pass the loaded data to the view
        return view('admin.guestLectures.edit', compact('guestLecture', 'invited_mentees', 'speaker_names'));
    }
    
    
    public function update(UpdateGuestLectureRequest $request, GuestLecture $guestLecture)
    {
        // Update the guest lecture
        $guestLecture->update($request->all());
    
        // Sync the mentees
        $guestLecture->invitedMentees()->sync($request->input('invited_mentees', []));
    
        return redirect()->route('admin.guest-lectures.index');
    }
    
    


public function show(GuestLecture $guestLecture)
{
    abort_if(Gate::denies('guest_lecture_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    // Load the related speaker and invited mentees relationships
    $guestLecture->load('speaker', 'invitedMentees'); // Corrected relationship names

    return view('admin.guestLectures.show', compact('guestLecture'));
}




    public function destroy(GuestLecture $guestLecture)
    {
        abort_if(Gate::denies('guest_lecture_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guestLecture->delete();

        return back();
    }

    public function massDestroy(MassDestroyGuestLectureRequest $request)
    {
        $guestLectures = GuestLecture::find(request('ids'));

        foreach ($guestLectures as $guestLecture) {
            $guestLecture->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function uploadRecording(Request $request)
    {

            // Fetch session titles
        $guessionsession_title = DB::table('guest_lectures')->pluck('guessionsession_title', 'id');

        return view('admin.guestLectures.uploadrecording',compact('guessionsession_title'));
    }

    public function storeRecording(Request $request){

        // Validate the request
        $request->validate([
            'selectSession' => 'required',
            'recordingFile' => 'required|file|mimes:mp3,mp4,wav,m4a|max:25600',
        ]);

        // Handle the uploaded file
        $file = $request->file('recordingFile');
        $filePath = 'recordings/' . uniqid() . '_' . $file->getClientOriginalName();

        // Upload the file to S3
        $path = Storage::disk('s3')->put($filePath, file_get_contents($file));

        if ($path) {
            $bucket = env('AWS_BUCKET');
            $region = env('AWS_DEFAULT_REGION');
            $baseUrl = "https://{$bucket}.s3.{$region}.amazonaws.com/";
            $fileUrl = $baseUrl . $filePath;

            
        // Update the file_path in the sessions table
        DB::table('guest_lectures')
            ->where('id', $request->input('selectSession'))
            ->update(['file_path' => $fileUrl]);

            return redirect()->back()->with('success', 'Recording uploaded and saved successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to upload recording.');
        }

    }
}
