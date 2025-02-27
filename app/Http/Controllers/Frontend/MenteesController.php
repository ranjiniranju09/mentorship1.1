<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyMenteeRequest;
use App\Http\Requests\StoreMenteeRequest;
use App\Http\Requests\UpdateMenteeRequest;
use App\Languagespoken;
use App\Mentee;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MenteesController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('mentee_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mentees = Mentee::with(['languagesspokens'])->get();

        $languagespokens = Languagespoken::get();

        return view('frontend.mentees.index', compact('languagespokens', 'mentees'));
    }

    public function create()
    {
        abort_if(Gate::denies('mentee_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $languagesspokens = Languagespoken::pluck('langname', 'id');

        return view('frontend.mentees.create', compact('languagesspokens'));
    }

    public function store(StoreMenteeRequest $request)
    {
        $mentee = Mentee::create($request->all());
        $mentee->languagesspokens()->sync($request->input('languagesspokens', []));

        return redirect()->route('frontend.mentees.index');
    }

    public function edit(Mentee $mentee)
    {
        abort_if(Gate::denies('mentee_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $languagesspokens = Languagespoken::pluck('langname', 'id');

        $mentee->load('languagesspokens');

        return view('frontend.mentees.edit', compact('languagesspokens', 'mentee'));
    }

    public function update(UpdateMenteeRequest $request, Mentee $mentee)
    {
        $mentee->update($request->all());
        $mentee->languagesspokens()->sync($request->input('languagesspokens', []));

        return redirect()->route('frontend.mentees.index');
    }

    public function show(Mentee $mentee)
    {
        abort_if(Gate::denies('mentee_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mentee->load('languagesspokens', 'menteenameSessions', 'menteenameMappings', 'invitedMenteesGuestLectures');

        return view('frontend.mentees.show', compact('mentee'));
    }

    public function destroy(Mentee $mentee)
    {
        abort_if(Gate::denies('mentee_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mentee->delete();

        return back();
    }

    public function massDestroy(MassDestroyMenteeRequest $request)
    {
        $mentees = Mentee::find(request('ids'));

        foreach ($mentees as $mentee) {
            $mentee->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
