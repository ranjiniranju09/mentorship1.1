<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSurveyFormRequest;
use App\Http\Requests\UpdateSurveyFormRequest;
use App\Http\Resources\Admin\SurveyFormResource;
use App\SurveyForm;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SurveyFormApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('survey_form_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SurveyFormResource(SurveyForm::all());
    }

    public function store(StoreSurveyFormRequest $request)
    {
        $surveyForm = SurveyForm::create($request->all());

        return (new SurveyFormResource($surveyForm))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(SurveyForm $surveyForm)
    {
        abort_if(Gate::denies('survey_form_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SurveyFormResource($surveyForm);
    }

    public function update(UpdateSurveyFormRequest $request, SurveyForm $surveyForm)
    {
        $surveyForm->update($request->all());

        return (new SurveyFormResource($surveyForm))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(SurveyForm $surveyForm)
    {
        abort_if(Gate::denies('survey_form_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $surveyForm->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
