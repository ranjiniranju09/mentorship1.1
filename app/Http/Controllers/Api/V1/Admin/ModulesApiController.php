<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreModuleRequest;
use App\Http\Requests\UpdateModuleRequest;
use App\Http\Resources\Admin\ModuleResource;
use App\Module;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModulesApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('module_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ModuleResource(Module::all());
    }

    public function store(StoreModuleRequest $request)
    {
        $module = Module::create($request->all());

        return (new ModuleResource($module))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Module $module)
    {
        abort_if(Gate::denies('module_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ModuleResource($module);
    }

    public function update(UpdateModuleRequest $request, Module $module)
    {
        $module->update($request->all());

        return (new ModuleResource($module))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Module $module)
    {
        abort_if(Gate::denies('module_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $module->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
