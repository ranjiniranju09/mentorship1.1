<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyOneOneAttendanceRequest;
use App\Http\Requests\StoreOneOneAttendanceRequest;
use App\Http\Requests\UpdateOneOneAttendanceRequest;
use App\OneOneAttendance;
use App\Session;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OneOneAttendanceController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('one_one_attendance_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $oneOneAttendances = OneOneAttendance::with(['session_attendance'])->get();

        $sessions = Session::get();

        return view('frontend.oneOneAttendances.index', compact('oneOneAttendances', 'sessions'));
    }

    public function create()
    {
        abort_if(Gate::denies('one_one_attendance_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $session_attendances = Session::pluck('session_title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.oneOneAttendances.create', compact('session_attendances'));
    }

    public function store(StoreOneOneAttendanceRequest $request)
    {
        $oneOneAttendance = OneOneAttendance::create($request->all());

        return redirect()->route('frontend.one-one-attendances.index');
    }

    public function edit(OneOneAttendance $oneOneAttendance)
    {
        abort_if(Gate::denies('one_one_attendance_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $session_attendances = Session::pluck('session_title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $oneOneAttendance->load('session_attendance');

        return view('frontend.oneOneAttendances.edit', compact('oneOneAttendance', 'session_attendances'));
    }

    public function update(UpdateOneOneAttendanceRequest $request, OneOneAttendance $oneOneAttendance)
    {
        $oneOneAttendance->update($request->all());

        return redirect()->route('frontend.one-one-attendances.index');
    }

    public function show(OneOneAttendance $oneOneAttendance)
    {
        abort_if(Gate::denies('one_one_attendance_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $oneOneAttendance->load('session_attendance');

        return view('frontend.oneOneAttendances.show', compact('oneOneAttendance'));
    }

    public function destroy(OneOneAttendance $oneOneAttendance)
    {
        abort_if(Gate::denies('one_one_attendance_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $oneOneAttendance->delete();

        return back();
    }

    public function massDestroy(MassDestroyOneOneAttendanceRequest $request)
    {
        $oneOneAttendances = OneOneAttendance::find(request('ids'));

        foreach ($oneOneAttendances as $oneOneAttendance) {
            $oneOneAttendance->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
