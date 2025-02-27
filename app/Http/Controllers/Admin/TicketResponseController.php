<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyTicketResponseRequest;
use App\Http\Requests\StoreTicketResponseRequest;
use App\Http\Requests\UpdateTicketResponseRequest;
use App\TicketDescription;
use App\TicketResponse;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;


class TicketResponseController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
{
    abort_if(Gate::denies('ticket_response_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    // Fetch all ticket categories
    $ticketcategories = DB::table('ticketcategories')->get();

    // $ticketDescriptions = DB::table('ticket_descriptions')
    //     ->leftJoin('ticketcategories', 'ticket_descriptions.ticket_category_id', '=', 'ticketcategories.id')
    //     ->select('ticket_descriptions.*', 'ticketcategories.category_description')
    //     ->get();


    if ($request->ajax()) {
        // Manually join ticketcategories table with ticket_descriptions
        $query = TicketDescription::join('ticketcategories', 'ticketcategories.id', '=', 'ticket_descriptions.ticket_category_id')
            ->select('ticket_descriptions.*', 'ticketcategories.category_description')  // Select necessary columns
            ->with(['ticket_category']); // Optionally keep the relationship loaded

        $table = Datatables::of($query);

        $table->addColumn('placeholder', '&nbsp;');
        $table->addColumn('actions', '&nbsp;');

        $table->editColumn('actions', function ($row) {
            $viewGate      = 'ticket_response_show';
            $editGate      = 'ticket_response_edit';
            $deleteGate    = 'ticket_response_delete';
            $crudRoutePart = 'ticket-responses';

            return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
        });

        $table->editColumn('id', function ($row) {
            return $row->id ? $row->id : '';
        });

        $table->editColumn('ticket_description', function ($row) {
            return $row->ticket_description ? $row->ticket_description : '';
        });

        // Modify ticket_category to display category_description after the join
        $table->addColumn('ticket_category', function ($row) {
            return $row->category_description;  // Now using the joined category_description
        });

        
        // Remove the attachment_url column if you no longer need it in the table
        $table->editColumn('attachment_url', function ($row) {
            if (!$row->attachment_url) {
                return '';
            }
            return '<a href="' . $row->attachment_url . '" target="_blank">View Attachment</a>';
        });

        $table->editColumn('response', function ($row) {
            return $row->response ? $row->response : '';
        });

        $table->rawColumns(['actions', 'placeholder', 'ticket_description', 'ticket_category', 'attachment_url', 'response']);

        return $table->make(true);
    }

    // Pass ticket categories to the view
    return view('admin.ticketResponses.index', compact('ticketcategories'));
}



    public function create()
    {
        abort_if(Gate::denies('ticket_response_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticket_descriptions = TicketDescription::pluck('ticket_title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.ticketResponses.create', compact('ticket_descriptions'));
    }

    public function store(StoreTicketResponseRequest $request)
    {
        $ticketResponse = TicketResponse::create($request->all());

        foreach ($request->input('supporting_files', []) as $file) {
            $ticketResponse->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('supporting_files');
        }

        foreach ($request->input('supporting_photo', []) as $file) {
            $ticketResponse->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('supporting_photo');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $ticketResponse->id]);
        }

        return redirect()->route('admin.ticket-responses.index');
    }

    public function edit(TicketResponse $ticketResponse)
    {
        abort_if(Gate::denies('ticket_response_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticket_descriptions = TicketDescription::pluck('ticket_title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ticketResponse->load('ticket_description');

        return view('admin.ticketResponses.edit', compact('ticketResponse', 'ticket_descriptions'));
    }

    public function update(UpdateTicketResponseRequest $request, TicketResponse $ticketResponse)
    {
        $ticketResponse->update($request->all());

        if (count($ticketResponse->supporting_files) > 0) {
            foreach ($ticketResponse->supporting_files as $media) {
                if (! in_array($media->file_name, $request->input('supporting_files', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ticketResponse->supporting_files->pluck('file_name')->toArray();
        foreach ($request->input('supporting_files', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $ticketResponse->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('supporting_files');
            }
        }

        if (count($ticketResponse->supporting_photo) > 0) {
            foreach ($ticketResponse->supporting_photo as $media) {
                if (! in_array($media->file_name, $request->input('supporting_photo', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ticketResponse->supporting_photo->pluck('file_name')->toArray();
        foreach ($request->input('supporting_photo', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $ticketResponse->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('supporting_photo');
            }
        }

        return redirect()->route('admin.ticket-responses.index');
    }

    public function show(TicketResponse $ticketResponse)
    {
        abort_if(Gate::denies('ticket_response_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticketResponse->load('ticket_description');

        return view('admin.ticketResponses.show', compact('ticketResponse'));
    }

    public function destroy(TicketResponse $ticketResponse)
    {
        abort_if(Gate::denies('ticket_response_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticketResponse->delete();

        return back();
    }

    public function massDestroy(MassDestroyTicketResponseRequest $request)
    {
        $ticketResponses = TicketResponse::find(request('ids'));

        foreach ($ticketResponses as $ticketResponse) {
            $ticketResponse->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('ticket_response_create') && Gate::denies('ticket_response_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new TicketResponse();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
