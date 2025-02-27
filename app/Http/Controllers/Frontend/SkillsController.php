<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySkillRequest;
use App\Http\Requests\StoreSkillRequest;
use App\Http\Requests\UpdateSkillRequest;
use App\Skill;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SkillsController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('skill_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $skills = Skill::all();

        return view('frontend.skills.index', compact('skills'));
    }

    public function create()
    {
        abort_if(Gate::denies('skill_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.skills.create');
    }

    public function store(StoreSkillRequest $request)
    {
        $skill = Skill::create($request->all());

        return redirect()->route('frontend.skills.index');
    }

    public function edit(Skill $skill)
    {
        abort_if(Gate::denies('skill_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.skills.edit', compact('skill'));
    }

    public function update(UpdateSkillRequest $request, Skill $skill)
    {
        $skill->update($request->all());

        return redirect()->route('frontend.skills.index');
    }

    public function show(Skill $skill)
    {
        abort_if(Gate::denies('skill_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.skills.show', compact('skill'));
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
