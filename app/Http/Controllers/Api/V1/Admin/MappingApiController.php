<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMappingRequest;
use App\Http\Requests\UpdateMappingRequest;
use App\Http\Resources\Admin\MappingResource;
use App\Mapping;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MappingApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('mapping_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MappingResource(Mapping::with(['mentorname', 'menteename'])->get());
    }

    public function store(StoreMappingRequest $request)
    {
        $mapping = Mapping::create($request->all());

        return (new MappingResource($mapping))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Mapping $mapping)
    {
        abort_if(Gate::denies('mapping_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MappingResource($mapping->load(['mentorname', 'menteename']));
    }

    public function update(UpdateMappingRequest $request, Mapping $mapping)
    {
        $mapping->update($request->all());

        return (new MappingResource($mapping))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Mapping $mapping)
    {
        abort_if(Gate::denies('mapping_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mapping->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
