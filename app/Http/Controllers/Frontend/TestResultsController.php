<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTestResultRequest;
use App\Http\Requests\StoreTestResultRequest;
use App\Http\Requests\UpdateTestResultRequest;
use App\Test;
use App\TestResult;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TestResultsController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('test_result_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $testResults = TestResult::with(['test', 'student'])->get();

        $tests = Test::get();

        $users = User::get();

        return view('frontend.testResults.index', compact('testResults', 'tests', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('test_result_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tests = Test::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $students = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.testResults.create', compact('students', 'tests'));
    }

    public function store(StoreTestResultRequest $request)
    {
        $testResult = TestResult::create($request->all());

        return redirect()->route('frontend.test-results.index');
    }

    public function edit(TestResult $testResult)
    {
        abort_if(Gate::denies('test_result_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tests = Test::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $students = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $testResult->load('test', 'student');

        return view('frontend.testResults.edit', compact('students', 'testResult', 'tests'));
    }

    public function update(UpdateTestResultRequest $request, TestResult $testResult)
    {
        $testResult->update($request->all());

        return redirect()->route('frontend.test-results.index');
    }

    public function show(TestResult $testResult)
    {
        abort_if(Gate::denies('test_result_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $testResult->load('test', 'student');

        return view('frontend.testResults.show', compact('testResult'));
    }

    public function destroy(TestResult $testResult)
    {
        abort_if(Gate::denies('test_result_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $testResult->delete();

        return back();
    }

    public function massDestroy(MassDestroyTestResultRequest $request)
    {
        $testResults = TestResult::find(request('ids'));

        foreach ($testResults as $testResult) {
            $testResult->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
