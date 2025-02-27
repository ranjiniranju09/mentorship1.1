<?php

namespace App\Http\Controllers\Admin;

use App\Guestspeaker;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyGuestspeakerRequest;
use App\Http\Requests\StoreGuestspeakerRequest;
use App\Http\Requests\UpdateGuestspeakerRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class GuestspeakersController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('guestspeaker_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Guestspeaker::query()->select(sprintf('%s.*', (new Guestspeaker)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'guestspeaker_show';
                $editGate      = 'guestspeaker_edit';
                $deleteGate    = 'guestspeaker_delete';
                $crudRoutePart = 'guestspeakers';

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
            $table->editColumn('speakername', function ($row) {
                return $row->speakername ? $row->speakername : '';
            });
            $table->editColumn('speaker_name', function ($row) {
                return $row->speaker_name ? $row->speaker_name : '';
            });
            $table->editColumn('speakermobile', function ($row) {
                return $row->speakermobile ? $row->speakermobile : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.guestspeakers.index');
    }

    public function create()
    {
        abort_if(Gate::denies('guestspeaker_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.guestspeakers.create');
    }

    public function store(StoreGuestspeakerRequest $request)
    {
        $guestspeaker = Guestspeaker::create($request->all());

        return redirect()->route('admin.guestspeakers.index');
    }

    public function edit(Guestspeaker $guestspeaker)
    {
        abort_if(Gate::denies('guestspeaker_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.guestspeakers.edit', compact('guestspeaker'));
    }

    public function update(UpdateGuestspeakerRequest $request, Guestspeaker $guestspeaker)
    {
        $guestspeaker->update($request->all());

        return redirect()->route('admin.guestspeakers.index');
    }

    public function show(Guestspeaker $guestspeaker)
    {
        abort_if(Gate::denies('guestspeaker_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guestspeaker->load('speakerNameGuestLectures');

        return view('admin.guestspeakers.show', compact('guestspeaker'));
    }

    public function destroy(Guestspeaker $guestspeaker)
    {
        abort_if(Gate::denies('guestspeaker_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guestspeaker->delete();

        return back();
    }

    public function massDestroy(MassDestroyGuestspeakerRequest $request)
    {
        $guestspeakers = Guestspeaker::find(request('ids'));

        foreach ($guestspeakers as $guestspeaker) {
            $guestspeaker->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
