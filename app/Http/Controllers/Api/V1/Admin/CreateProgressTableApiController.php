<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\CreateProgressTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCreateProgressTableRequest;
use App\Http\Requests\UpdateCreateProgressTableRequest;
use App\Http\Resources\Admin\CreateProgressTableResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateProgressTableApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('create_progress_table_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CreateProgressTableResource(CreateProgressTable::with(['user', 'lesson'])->get());
    }

    public function store(StoreCreateProgressTableRequest $request)
    {
        $createProgressTable = CreateProgressTable::create($request->all());

        return (new CreateProgressTableResource($createProgressTable))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CreateProgressTable $createProgressTable)
    {
        abort_if(Gate::denies('create_progress_table_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CreateProgressTableResource($createProgressTable->load(['user', 'lesson']));
    }

    public function update(UpdateCreateProgressTableRequest $request, CreateProgressTable $createProgressTable)
    {
        $createProgressTable->update($request->all());

        return (new CreateProgressTableResource($createProgressTable))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CreateProgressTable $createProgressTable)
    {
        abort_if(Gate::denies('create_progress_table_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $createProgressTable->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
