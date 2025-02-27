<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyQuestionOptionRequest;
use App\Http\Requests\StoreQuestionOptionRequest;
use App\Http\Requests\UpdateQuestionOptionRequest;
use App\Question;
use App\QuestionOption;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class QuestionOptionsController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('question_option_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = QuestionOption::with(['question'])->select(sprintf('%s.*', (new QuestionOption)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'question_option_show';
                $editGate      = 'question_option_edit';
                $deleteGate    = 'question_option_delete';
                $crudRoutePart = 'question-options';

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
            $table->addColumn('question_question_text', function ($row) {
                return $row->question ? $row->question->question_text : '';
            });

            $table->editColumn('option_text', function ($row) {
                return $row->option_text ? $row->option_text : '';
            });
            $table->editColumn('is_correct', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->is_correct ? 'checked' : null) . '>';
            });

            $table->rawColumns(['actions', 'placeholder', 'question', 'is_correct']);

            return $table->make(true);
        }

        $questions = Question::get();

        return view('admin.questionOptions.index', compact('questions'));
    }

    public function create()
    {
        abort_if(Gate::denies('question_option_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $questions = Question::pluck('question_text', 'id')->prepend(trans('global.pleaseSelect'), '');
        $questions = Question::where('mcq', 'yes')->get(['id', 'question_text', 'mcq']);

        return view('admin.questionOptions.create', compact('questions'));
    }

    public function store(StoreQuestionOptionRequest $request)
    {
        $questionOption = QuestionOption::create($request->all());

        return redirect()->route('admin.question-options.index');
    }

    public function edit(QuestionOption $questionOption)
    {
        abort_if(Gate::denies('question_option_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $questions = Question::pluck('question_text', 'id')->prepend(trans('global.pleaseSelect'), '');

        $questionOption->load('question');

        return view('admin.questionOptions.edit', compact('questionOption', 'questions'));
    }

    public function update(UpdateQuestionOptionRequest $request, QuestionOption $questionOption)
    {
        $questionOption->update($request->all());

        return redirect()->route('admin.question-options.index');
    }

    public function show(QuestionOption $questionOption)
    {
        abort_if(Gate::denies('question_option_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $questionOption->load('question');

        return view('admin.questionOptions.show', compact('questionOption'));
    }

    public function destroy(QuestionOption $questionOption)
    {
        abort_if(Gate::denies('question_option_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $questionOption->delete();

        return back();
    }

    public function massDestroy(MassDestroyQuestionOptionRequest $request)
    {
        $questionOptions = QuestionOption::find(request('ids'));

        foreach ($questionOptions as $questionOption) {
            $questionOption->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
