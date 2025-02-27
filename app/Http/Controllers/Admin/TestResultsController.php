<?php

namespace App\Http\Controllers\Admin;

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
use Yajra\DataTables\Facades\DataTables;

class TestResultsController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('test_result_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = TestResult::with(['test', 'student'])->select(sprintf('%s.*', (new TestResult)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'test_result_show';
                $editGate      = 'test_result_edit';
                $deleteGate    = 'test_result_delete';
                $crudRoutePart = 'test-results';

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
            $table->addColumn('test_title', function ($row) {
                return $row->test ? $row->test->title : '';
            });

            $table->addColumn('student_name', function ($row) {
                return $row->student ? $row->student->name : '';
            });

            $table->editColumn('score', function ($row) {
                return $row->score ? $row->score : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'test', 'student']);

            return $table->make(true);
        }

        $tests = Test::get();
        $users = User::get();

        return view('admin.testResults.index', compact('tests', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('test_result_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tests = Test::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $students = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.testResults.create', compact('students', 'tests'));
    }

    public function store(StoreTestResultRequest $request)
    {
        $testResult = TestResult::create($request->all());

        return redirect()->route('admin.test-results.index');
    }

    public function edit(TestResult $testResult)
    {
        abort_if(Gate::denies('test_result_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tests = Test::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $students = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $testResult->load('test', 'student');

        return view('admin.testResults.edit', compact('students', 'testResult', 'tests'));
    }

    public function update(UpdateTestResultRequest $request, TestResult $testResult)
    {
        $testResult->update($request->all());

        return redirect()->route('admin.test-results.index');
    }

    public function show(TestResult $testResult)
    {
        abort_if(Gate::denies('test_result_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $testResult->load('test', 'student');

        return view('admin.testResults.show', compact('testResult'));
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
