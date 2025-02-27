<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOneOneAttendanceRequest;
use App\Http\Requests\UpdateOneOneAttendanceRequest;
use App\Http\Resources\Admin\OneOneAttendanceResource;
use App\OneOneAttendance;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OneOneAttendanceApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('one_one_attendance_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OneOneAttendanceResource(OneOneAttendance::with(['session_attendance'])->get());
    }

    public function store(StoreOneOneAttendanceRequest $request)
    {
        $oneOneAttendance = OneOneAttendance::create($request->all());

        return (new OneOneAttendanceResource($oneOneAttendance))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(OneOneAttendance $oneOneAttendance)
    {
        abort_if(Gate::denies('one_one_attendance_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OneOneAttendanceResource($oneOneAttendance->load(['session_attendance']));
    }

    public function update(UpdateOneOneAttendanceRequest $request, OneOneAttendance $oneOneAttendance)
    {
        $oneOneAttendance->update($request->all());

        return (new OneOneAttendanceResource($oneOneAttendance))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(OneOneAttendance $oneOneAttendance)
    {
        abort_if(Gate::denies('one_one_attendance_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $oneOneAttendance->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
