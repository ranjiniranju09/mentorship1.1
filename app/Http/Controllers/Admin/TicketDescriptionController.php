<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyTicketDescriptionRequest;
use App\Http\Requests\StoreTicketDescriptionRequest;
use App\Http\Requests\UpdateTicketDescriptionRequest;
use App\Ticketcategory;
use App\TicketDescription;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TicketDescriptionController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('ticket_description_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = TicketDescription::with(['ticket_category'])->select(sprintf('%s.*', (new TicketDescription)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'ticket_description_show';
                $editGate      = 'ticket_description_edit';
                $deleteGate    = 'ticket_description_delete';
                $crudRoutePart = 'ticket-descriptions';

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
            $table->addColumn('ticket_category_category_description', function ($row) {
                return $row->ticket_category ? $row->ticket_category->category_description : '';
            });

            $table->editColumn('supporting_files', function ($row) {
                if (! $row->supporting_files) {
                    return '';
                }
                $links = [];
                foreach ($row->supporting_files as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('supporting_photo', function ($row) {
                if (! $row->supporting_photo) {
                    return '';
                }
                $links = [];
                foreach ($row->supporting_photo as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank"><img src="' . $media->getUrl('thumb') . '" width="50px" height="50px"></a>';
                }

                return implode(' ', $links);
            });
            $table->editColumn('ticket_title', function ($row) {
                return $row->ticket_title ? $row->ticket_title : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'ticket_category', 'supporting_files', 'supporting_photo']);

            return $table->make(true);
        }

        $ticketcategories = Ticketcategory::get();

        return view('admin.ticketDescriptions.index', compact('ticketcategories'));
    }

    public function create()
    {
        abort_if(Gate::denies('ticket_description_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticket_categories = Ticketcategory::pluck('category_description', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.ticketDescriptions.create', compact('ticket_categories'));
    }

    public function store(StoreTicketDescriptionRequest $request)
    {
        $ticketDescription = TicketDescription::create($request->all());

        foreach ($request->input('supporting_files', []) as $file) {
            $ticketDescription->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('supporting_files');
        }

        foreach ($request->input('supporting_photo', []) as $file) {
            $ticketDescription->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('supporting_photo');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $ticketDescription->id]);
        }

        return redirect()->route('admin.ticket-descriptions.index');
    }

    public function edit(TicketDescription $ticketDescription)
    {
        abort_if(Gate::denies('ticket_description_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticket_categories = Ticketcategory::pluck('category_description', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ticketDescription->load('ticket_category');

        return view('admin.ticketDescriptions.edit', compact('ticketDescription', 'ticket_categories'));
    }

    public function update(UpdateTicketDescriptionRequest $request, TicketDescription $ticketDescription)
    {
        $ticketDescription->update($request->all());

        if (count($ticketDescription->supporting_files) > 0) {
            foreach ($ticketDescription->supporting_files as $media) {
                if (! in_array($media->file_name, $request->input('supporting_files', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ticketDescription->supporting_files->pluck('file_name')->toArray();
        foreach ($request->input('supporting_files', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $ticketDescription->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('supporting_files');
            }
        }

        if (count($ticketDescription->supporting_photo) > 0) {
            foreach ($ticketDescription->supporting_photo as $media) {
                if (! in_array($media->file_name, $request->input('supporting_photo', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ticketDescription->supporting_photo->pluck('file_name')->toArray();
        foreach ($request->input('supporting_photo', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $ticketDescription->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('supporting_photo');
            }
        }

        return redirect()->route('admin.ticket-descriptions.index');
    }

    public function show(TicketDescription $ticketDescription)
    {
        abort_if(Gate::denies('ticket_description_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticketDescription->load('ticket_category', 'ticketDescriptionTicketResponses');

        return view('admin.ticketDescriptions.show', compact('ticketDescription'));
    }

    public function destroy(TicketDescription $ticketDescription)
    {
        abort_if(Gate::denies('ticket_description_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticketDescription->delete();

        return back();
    }

    public function massDestroy(MassDestroyTicketDescriptionRequest $request)
    {
        $ticketDescriptions = TicketDescription::find(request('ids'));

        foreach ($ticketDescriptions as $ticketDescription) {
            $ticketDescription->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('ticket_description_create') && Gate::denies('ticket_description_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new TicketDescription();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
