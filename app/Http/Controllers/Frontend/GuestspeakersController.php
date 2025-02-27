<?php

namespace App\Http\Controllers\Frontend;

use App\Guestspeaker;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyGuestspeakerRequest;
use App\Http\Requests\StoreGuestspeakerRequest;
use App\Http\Requests\UpdateGuestspeakerRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestspeakersController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('guestspeaker_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guestspeakers = Guestspeaker::all();

        return view('frontend.guestspeakers.index', compact('guestspeakers'));
    }

    public function create()
    {
        abort_if(Gate::denies('guestspeaker_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.guestspeakers.create');
    }

    public function store(StoreGuestspeakerRequest $request)
    {
        $guestspeaker = Guestspeaker::create($request->all());

        return redirect()->route('frontend.guestspeakers.index');
    }

    public function edit(Guestspeaker $guestspeaker)
    {
        abort_if(Gate::denies('guestspeaker_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.guestspeakers.edit', compact('guestspeaker'));
    }

    public function update(UpdateGuestspeakerRequest $request, Guestspeaker $guestspeaker)
    {
        $guestspeaker->update($request->all());

        return redirect()->route('frontend.guestspeakers.index');
    }

    public function show(Guestspeaker $guestspeaker)
    {
        abort_if(Gate::denies('guestspeaker_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guestspeaker->load('speakerNameGuestLectures');

        return view('frontend.guestspeakers.show', compact('guestspeaker'));
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
