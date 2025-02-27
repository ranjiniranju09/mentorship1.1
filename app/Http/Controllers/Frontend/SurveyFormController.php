<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySurveyFormRequest;
use App\Http\Requests\StoreSurveyFormRequest;
use App\Http\Requests\UpdateSurveyFormRequest;
use App\SurveyForm;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SurveyFormController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('survey_form_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $surveyForms = SurveyForm::all();

        return view('frontend.surveyForms.index', compact('surveyForms'));
    }

    public function create()
    {
        abort_if(Gate::denies('survey_form_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.surveyForms.create');
    }

    public function store(StoreSurveyFormRequest $request)
    {
        $surveyForm = SurveyForm::create($request->all());

        return redirect()->route('frontend.survey-forms.index');
    }

    public function edit(SurveyForm $surveyForm)
    {
        abort_if(Gate::denies('survey_form_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.surveyForms.edit', compact('surveyForm'));
    }

    public function update(UpdateSurveyFormRequest $request, SurveyForm $surveyForm)
    {
        $surveyForm->update($request->all());

        return redirect()->route('frontend.survey-forms.index');
    }

    public function show(SurveyForm $surveyForm)
    {
        abort_if(Gate::denies('survey_form_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.surveyForms.show', compact('surveyForm'));
    }

    public function destroy(SurveyForm $surveyForm)
    {
        abort_if(Gate::denies('survey_form_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $surveyForm->delete();

        return back();
    }

    public function massDestroy(MassDestroySurveyFormRequest $request)
    {
        $surveyForms = SurveyForm::find(request('ids'));

        foreach ($surveyForms as $surveyForm) {
            $surveyForm->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
