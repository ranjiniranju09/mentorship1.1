<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyMentorRequest;
use App\Http\Requests\StoreMentorRequest;
use App\Http\Requests\UpdateMentorRequest;
use App\Languagespoken;
use App\Mentor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MentorsController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('mentor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mentors = Mentor::with(['langspokens'])->get();

        $languagespokens = Languagespoken::get();

        return view('frontend.mentors.index', compact('languagespokens', 'mentors'));
    }

    public function create()
    {
        abort_if(Gate::denies('mentor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $langspokens = Languagespoken::pluck('langname', 'id');

        return view('frontend.mentors.create', compact('langspokens'));
    }

    public function store(StoreMentorRequest $request)
    {
        $mentor = Mentor::create($request->all());
        $mentor->langspokens()->sync($request->input('langspokens', []));

        return redirect()->route('frontend.mentors.index');
    }

    public function edit(Mentor $mentor)
    {
        abort_if(Gate::denies('mentor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $langspokens = Languagespoken::pluck('langname', 'id');

        $mentor->load('langspokens');

        return view('frontend.mentors.edit', compact('langspokens', 'mentor'));
    }

    public function update(UpdateMentorRequest $request, Mentor $mentor)
    {
        $mentor->update($request->all());
        $mentor->langspokens()->sync($request->input('langspokens', []));

        return redirect()->route('frontend.mentors.index');
    }

    public function show(Mentor $mentor)
    {
        abort_if(Gate::denies('mentor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mentor->load('langspokens', 'mentornameSessions', 'mentornameMappings');

        return view('frontend.mentors.show', compact('mentor'));
    }

    public function destroy(Mentor $mentor)
    {
        abort_if(Gate::denies('mentor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mentor->delete();

        return back();
    }

    public function massDestroy(MassDestroyMentorRequest $request)
    {
        $mentors = Mentor::find(request('ids'));

        foreach ($mentors as $mentor) {
            $mentor->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
