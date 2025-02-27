<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySurveyFormRequest;
use App\Http\Requests\StoreSurveyFormRequest;
use App\Http\Requests\UpdateSurveyFormRequest;
use App\SurveyForm;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SurveyFormController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('survey_form_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SurveyForm::query()->select(sprintf('%s.*', (new SurveyForm)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'survey_form_show';
                $editGate      = 'survey_form_edit';
                $deleteGate    = 'survey_form_delete';
                $crudRoutePart = 'survey-forms';

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
            $table->editColumn('surveytopic', function ($row) {
                return $row->surveytopic ? $row->surveytopic : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.surveyForms.index');
    }

    public function create()
    {
        abort_if(Gate::denies('survey_form_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.surveyForms.create');
    }

    public function store(StoreSurveyFormRequest $request)
    {
        $surveyForm = SurveyForm::create($request->all());

        return redirect()->route('admin.survey-forms.index');
    }

    public function edit(SurveyForm $surveyForm)
    {
        abort_if(Gate::denies('survey_form_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.surveyForms.edit', compact('surveyForm'));
    }

    public function update(UpdateSurveyFormRequest $request, SurveyForm $surveyForm)
    {
        $surveyForm->update($request->all());

        return redirect()->route('admin.survey-forms.index');
    }

    public function show(SurveyForm $surveyForm)
    {
        abort_if(Gate::denies('survey_form_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.surveyForms.show', compact('surveyForm'));
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
