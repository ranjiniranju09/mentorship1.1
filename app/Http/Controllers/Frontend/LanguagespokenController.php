<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyLanguagespokenRequest;
use App\Http\Requests\StoreLanguagespokenRequest;
use App\Http\Requests\UpdateLanguagespokenRequest;
use App\Languagespoken;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LanguagespokenController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('languagespoken_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $languagespokens = Languagespoken::all();

        return view('frontend.languagespokens.index', compact('languagespokens'));
    }

    public function create()
    {
        abort_if(Gate::denies('languagespoken_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.languagespokens.create');
    }

    public function store(StoreLanguagespokenRequest $request)
    {
        $languagespoken = Languagespoken::create($request->all());

        return redirect()->route('frontend.languagespokens.index');
    }

    public function edit(Languagespoken $languagespoken)
    {
        abort_if(Gate::denies('languagespoken_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.languagespokens.edit', compact('languagespoken'));
    }

    public function update(UpdateLanguagespokenRequest $request, Languagespoken $languagespoken)
    {
        $languagespoken->update($request->all());

        return redirect()->route('frontend.languagespokens.index');
    }

    public function show(Languagespoken $languagespoken)
    {
        abort_if(Gate::denies('languagespoken_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $languagespoken->load('langspokenMentors', 'languagesspokenMentees');

        return view('frontend.languagespokens.show', compact('languagespoken'));
    }

    public function destroy(Languagespoken $languagespoken)
    {
        abort_if(Gate::denies('languagespoken_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $languagespoken->delete();

        return back();
    }

    public function massDestroy(MassDestroyLanguagespokenRequest $request)
    {
        $languagespokens = Languagespoken::find(request('ids'));

        foreach ($languagespokens as $languagespoken) {
            $languagespoken->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
