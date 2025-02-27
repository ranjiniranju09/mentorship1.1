<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreSubchapterRequest;
use App\Http\Requests\UpdateSubchapterRequest;
use App\Http\Resources\Admin\SubchapterResource;
use App\Subchapter;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubchapterApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('subchapter_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SubchapterResource(Subchapter::with(['chapter'])->get());
    }

    public function store(StoreSubchapterRequest $request)
    {
        $subchapter = Subchapter::create($request->all());

        return (new SubchapterResource($subchapter))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Subchapter $subchapter)
    {
        abort_if(Gate::denies('subchapter_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SubchapterResource($subchapter->load(['chapter']));
    }

    public function update(UpdateSubchapterRequest $request, Subchapter $subchapter)
    {
        $subchapter->update($request->all());

        return (new SubchapterResource($subchapter))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Subchapter $subchapter)
    {
        abort_if(Gate::denies('subchapter_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subchapter->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
