<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTestResultRequest;
use App\Http\Requests\UpdateTestResultRequest;
use App\Http\Resources\Admin\TestResultResource;
use App\TestResult;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TestResultsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('test_result_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TestResultResource(TestResult::with(['test', 'student'])->get());
    }

    public function store(StoreTestResultRequest $request)
    {
        $testResult = TestResult::create($request->all());

        return (new TestResultResource($testResult))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(TestResult $testResult)
    {
        abort_if(Gate::denies('test_result_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TestResultResource($testResult->load(['test', 'student']));
    }

    public function update(UpdateTestResultRequest $request, TestResult $testResult)
    {
        $testResult->update($request->all());

        return (new TestResultResource($testResult))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(TestResult $testResult)
    {
        abort_if(Gate::denies('test_result_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $testResult->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
