<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySkillRequest;
use App\Http\Requests\StoreSkillRequest;
use App\Http\Requests\UpdateSkillRequest;
use App\Skill;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SkillsController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('skill_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Skill::query()->select(sprintf('%s.*', (new Skill)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'skill_show';
                $editGate      = 'skill_edit';
                $deleteGate    = 'skill_delete';
                $crudRoutePart = 'skills';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('skillname', function ($row) {
                return $row->skillname ? $row->skillname : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.skills.index');
    }

    public function create()
    {
        abort_if(Gate::denies('skill_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.skills.create');
    }

    public function store(StoreSkillRequest $request)
    {
        $skill = Skill::create($request->all());

        return redirect()->route('admin.skills.index');
    }

    public function edit(Skill $skill)
    {
        abort_if(Gate::denies('skill_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.skills.edit', compact('skill'));
    }

    public function update(UpdateSkillRequest $request, Skill $skill)
    {
        $skill->update($request->all());

        return redirect()->route('admin.skills.index');
    }

    public function show(Skill $skill)
    {
        abort_if(Gate::denies('skill_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.skills.show', compact('skill'));
    }

    public function destroy(Skill $skill)
    {
        abort_if(Gate::denies('skill_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $skill->delete();

        return back();
    }

    public function massDestroy(MassDestroySkillRequest $request)
    {
        $skills = Skill::find(request('ids'));

        foreach ($skills as $skill) {
            $skill->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
