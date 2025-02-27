<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreTicketDescriptionRequest;
use App\Http\Requests\UpdateTicketDescriptionRequest;
use App\Http\Resources\Admin\TicketDescriptionResource;
use App\TicketDescription;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TicketDescriptionApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('ticket_description_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TicketDescriptionResource(TicketDescription::with(['ticket_category'])->get());
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

        return (new TicketDescriptionResource($ticketDescription))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(TicketDescription $ticketDescription)
    {
        abort_if(Gate::denies('ticket_description_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TicketDescriptionResource($ticketDescription->load(['ticket_category']));
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

        return (new TicketDescriptionResource($ticketDescription))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(TicketDescription $ticketDescription)
    {
        abort_if(Gate::denies('ticket_description_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticketDescription->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
