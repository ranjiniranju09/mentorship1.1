<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTestAnswerRequest;
use App\Http\Requests\StoreTestAnswerRequest;
use App\Http\Requests\UpdateTestAnswerRequest;
use App\Question;
use App\QuestionOption;
use App\TestAnswer;
use App\TestResult;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TestAnswersController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('test_answer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $testAnswers = TestAnswer::with(['test_result', 'question', 'option'])->get();

        $test_results = TestResult::get();

        $questions = Question::get();

        $question_options = QuestionOption::get();

        return view('frontend.testAnswers.index', compact('question_options', 'questions', 'testAnswers', 'test_results'));
    }

    public function create()
    {
        abort_if(Gate::denies('test_answer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $test_results = TestResult::pluck('score', 'id')->prepend(trans('global.pleaseSelect'), '');

        $questions = Question::pluck('question_text', 'id')->prepend(trans('global.pleaseSelect'), '');

        $options = QuestionOption::pluck('option_text', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.testAnswers.create', compact('options', 'questions', 'test_results'));
    }

    public function store(StoreTestAnswerRequest $request)
    {
        $testAnswer = TestAnswer::create($request->all());

        return redirect()->route('frontend.test-answers.index');
    }

    public function edit(TestAnswer $testAnswer)
    {
        abort_if(Gate::denies('test_answer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $test_results = TestResult::pluck('score', 'id')->prepend(trans('global.pleaseSelect'), '');

        $questions = Question::pluck('question_text', 'id')->prepend(trans('global.pleaseSelect'), '');

        $options = QuestionOption::pluck('option_text', 'id')->prepend(trans('global.pleaseSelect'), '');

        $testAnswer->load('test_result', 'question', 'option');

        return view('frontend.testAnswers.edit', compact('options', 'questions', 'testAnswer', 'test_results'));
    }

    public function update(UpdateTestAnswerRequest $request, TestAnswer $testAnswer)
    {
        $testAnswer->update($request->all());

        return redirect()->route('frontend.test-answers.index');
    }

    public function show(TestAnswer $testAnswer)
    {
        abort_if(Gate::denies('test_answer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $testAnswer->load('test_result', 'question', 'option');

        return view('frontend.testAnswers.show', compact('testAnswer'));
    }

    public function destroy(TestAnswer $testAnswer)
    {
        abort_if(Gate::denies('test_answer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $testAnswer->delete();

        return back();
    }

    public function massDestroy(MassDestroyTestAnswerRequest $request)
    {
        $testAnswers = TestAnswer::find(request('ids'));

        foreach ($testAnswers as $testAnswer) {
            $testAnswer->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
