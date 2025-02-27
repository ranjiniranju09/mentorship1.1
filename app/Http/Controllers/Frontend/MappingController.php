<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyMappingRequest;
use App\Http\Requests\StoreMappingRequest;
use App\Http\Requests\UpdateMappingRequest;
use App\Mapping;
use App\Mentee;
use App\Mentor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MappingController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('mapping_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mappings = Mapping::with(['mentorname', 'menteename'])->get();

        $mentors = Mentor::get();

        $mentees = Mentee::get();

        return view('frontend.mappings.index', compact('mappings', 'mentees', 'mentors'));
    }

    public function create()
    {
        abort_if(Gate::denies('mapping_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mentornames = Mentor::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $menteenames = Mentee::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.mappings.create', compact('menteenames', 'mentornames'));
    }

    public function store(StoreMappingRequest $request)
    {
        $mapping = Mapping::create($request->all());

        return redirect()->route('frontend.mappings.index');
    }

    public function edit(Mapping $mapping)
    {
        abort_if(Gate::denies('mapping_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mentornames = Mentor::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $menteenames = Mentee::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mapping->load('mentorname', 'menteename');

        return view('frontend.mappings.edit', compact('mapping', 'menteenames', 'mentornames'));
    }

    public function update(UpdateMappingRequest $request, Mapping $mapping)
    {
        $mapping->update($request->all());

        return redirect()->route('frontend.mappings.index');
    }

    public function show(Mapping $mapping)
    {
        abort_if(Gate::denies('mapping_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mapping->load('mentorname', 'menteename');

        return view('frontend.mappings.show', compact('mapping'));
    }

    public function destroy(Mapping $mapping)
    {
        abort_if(Gate::denies('mapping_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mapping->delete();

        return back();
    }

    public function massDestroy(MassDestroyMappingRequest $request)
    {
        $mappings = Mapping::find(request('ids'));

        foreach ($mappings as $mapping) {
            $mapping->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
