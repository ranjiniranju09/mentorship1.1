<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreTicketResponseRequest;
use App\Http\Requests\UpdateTicketResponseRequest;
use App\Http\Resources\Admin\TicketResponseResource;
use App\TicketResponse;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TicketResponseApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('ticket_response_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TicketResponseResource(TicketResponse::with(['ticket_description'])->get());
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

        return (new TicketResponseResource($ticketResponse))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(TicketResponse $ticketResponse)
    {
        abort_if(Gate::denies('ticket_response_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TicketResponseResource($ticketResponse->load(['ticket_description']));
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

        return (new TicketResponseResource($ticketResponse))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(TicketResponse $ticketResponse)
    {
        abort_if(Gate::denies('ticket_response_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticketResponse->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
