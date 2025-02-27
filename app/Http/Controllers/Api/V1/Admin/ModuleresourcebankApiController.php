<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreModuleresourcebankRequest;
use App\Http\Requests\UpdateModuleresourcebankRequest;
use App\Http\Resources\Admin\ModuleresourcebankResource;
use App\Moduleresourcebank;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModuleresourcebankApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('moduleresourcebank_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ModuleresourcebankResource(Moduleresourcebank::with(['module', 'chapterid'])->get());
    }

    public function store(StoreModuleresourcebankRequest $request)
    {
        $moduleresourcebank = Moduleresourcebank::create($request->all());

        foreach ($request->input('resourcefile', []) as $file) {
            $moduleresourcebank->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('resourcefile');
        }

        foreach ($request->input('resource_photos', []) as $file) {
            $moduleresourcebank->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('resource_photos');
        }

        return (new ModuleresourcebankResource($moduleresourcebank))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Moduleresourcebank $moduleresourcebank)
    {
        abort_if(Gate::denies('moduleresourcebank_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ModuleresourcebankResource($moduleresourcebank->load(['module', 'chapterid']));
    }

    public function update(UpdateModuleresourcebankRequest $request, Moduleresourcebank $moduleresourcebank)
    {
        $moduleresourcebank->update($request->all());

        if (count($moduleresourcebank->resourcefile) > 0) {
            foreach ($moduleresourcebank->resourcefile as $media) {
                if (! in_array($media->file_name, $request->input('resourcefile', []))) {
                    $media->delete();
                }
            }
        }
        $media = $moduleresourcebank->resourcefile->pluck('file_name')->toArray();
        foreach ($request->input('resourcefile', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $moduleresourcebank->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('resourcefile');
            }
        }

        if (count($moduleresourcebank->resource_photos) > 0) {
            foreach ($moduleresourcebank->resource_photos as $media) {
                if (! in_array($media->file_name, $request->input('resource_photos', []))) {
                    $media->delete();
                }
            }
        }
        $media = $moduleresourcebank->resource_photos->pluck('file_name')->toArray();
        foreach ($request->input('resource_photos', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $moduleresourcebank->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('resource_photos');
            }
        }

        return (new ModuleresourcebankResource($moduleresourcebank))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Moduleresourcebank $moduleresourcebank)
    {
        abort_if(Gate::denies('moduleresourcebank_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $moduleresourcebank->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
