<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Guestspeaker;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGuestspeakerRequest;
use App\Http\Requests\UpdateGuestspeakerRequest;
use App\Http\Resources\Admin\GuestspeakerResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestspeakersApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('guestspeaker_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new GuestspeakerResource(Guestspeaker::all());
    }

    public function store(StoreGuestspeakerRequest $request)
    {
        $guestspeaker = Guestspeaker::create($request->all());

        return (new GuestspeakerResource($guestspeaker))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Guestspeaker $guestspeaker)
    {
        abort_if(Gate::denies('guestspeaker_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new GuestspeakerResource($guestspeaker);
    }

    public function update(UpdateGuestspeakerRequest $request, Guestspeaker $guestspeaker)
    {
        $guestspeaker->update($request->all());

        return (new GuestspeakerResource($guestspeaker))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Guestspeaker $guestspeaker)
    {
        abort_if(Gate::denies('guestspeaker_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guestspeaker->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
