<?php

namespace App\Http\Controllers\Frontend;

use App\GuestSessionAttendance;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyGuestSessionAttendanceRequest;
use App\Http\Requests\StoreGuestSessionAttendanceRequest;
use App\Http\Requests\UpdateGuestSessionAttendanceRequest;
use App\Session;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestSessionAttendanceController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('guest_session_attendance_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guestSessionAttendances = GuestSessionAttendance::with(['session_title'])->get();

        $sessions = Session::get();

        return view('frontend.guestSessionAttendances.index', compact('guestSessionAttendances', 'sessions'));
    }

    public function create()
    {
        abort_if(Gate::denies('guest_session_attendance_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $session_titles = Session::pluck('session_title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.guestSessionAttendances.create', compact('session_titles'));
    }

    public function store(StoreGuestSessionAttendanceRequest $request)
    {
        $guestSessionAttendance = GuestSessionAttendance::create($request->all());

        return redirect()->route('frontend.guest-session-attendances.index');
    }

    public function edit(GuestSessionAttendance $guestSessionAttendance)
    {
        abort_if(Gate::denies('guest_session_attendance_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $session_titles = Session::pluck('session_title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $guestSessionAttendance->load('session_title');

        return view('frontend.guestSessionAttendances.edit', compact('guestSessionAttendance', 'session_titles'));
    }

    public function update(UpdateGuestSessionAttendanceRequest $request, GuestSessionAttendance $guestSessionAttendance)
    {
        $guestSessionAttendance->update($request->all());

        return redirect()->route('frontend.guest-session-attendances.index');
    }

    public function show(GuestSessionAttendance $guestSessionAttendance)
    {
        abort_if(Gate::denies('guest_session_attendance_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guestSessionAttendance->load('session_title');

        return view('frontend.guestSessionAttendances.show', compact('guestSessionAttendance'));
    }

    public function destroy(GuestSessionAttendance $guestSessionAttendance)
    {
        abort_if(Gate::denies('guest_session_attendance_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guestSessionAttendance->delete();

        return back();
    }

    public function massDestroy(MassDestroyGuestSessionAttendanceRequest $request)
    {
        $guestSessionAttendances = GuestSessionAttendance::find(request('ids'));

        foreach ($guestSessionAttendances as $guestSessionAttendance) {
            $guestSessionAttendance->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
