<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\GuestSessionAttendance;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGuestSessionAttendanceRequest;
use App\Http\Requests\UpdateGuestSessionAttendanceRequest;
use App\Http\Resources\Admin\GuestSessionAttendanceResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestSessionAttendanceApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('guest_session_attendance_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new GuestSessionAttendanceResource(GuestSessionAttendance::with(['session_title'])->get());
    }

    public function store(StoreGuestSessionAttendanceRequest $request)
    {
        $guestSessionAttendance = GuestSessionAttendance::create($request->all());

        return (new GuestSessionAttendanceResource($guestSessionAttendance))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(GuestSessionAttendance $guestSessionAttendance)
    {
        abort_if(Gate::denies('guest_session_attendance_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new GuestSessionAttendanceResource($guestSessionAttendance->load(['session_title']));
    }

    public function update(UpdateGuestSessionAttendanceRequest $request, GuestSessionAttendance $guestSessionAttendance)
    {
        $guestSessionAttendance->update($request->all());

        return (new GuestSessionAttendanceResource($guestSessionAttendance))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(GuestSessionAttendance $guestSessionAttendance)
    {
        abort_if(Gate::denies('guest_session_attendance_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guestSessionAttendance->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
