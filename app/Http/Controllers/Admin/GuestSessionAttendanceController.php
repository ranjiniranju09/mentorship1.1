<?php

namespace App\Http\Controllers\Admin;

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
use Yajra\DataTables\Facades\DataTables;

class GuestSessionAttendanceController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('guest_session_attendance_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = GuestSessionAttendance::with(['session_title'])->select(sprintf('%s.*', (new GuestSessionAttendance)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'guest_session_attendance_show';
                $editGate      = 'guest_session_attendance_edit';
                $deleteGate    = 'guest_session_attendance_delete';
                $crudRoutePart = 'guest-session-attendances';

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
            $table->addColumn('session_title_session_title', function ($row) {
                return $row->session_title ? $row->session_title->session_title : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'session_title']);

            return $table->make(true);
        }

        $sessions = Session::get();

        return view('admin.guestSessionAttendances.index', compact('sessions'));
    }

    public function create()
    {
        abort_if(Gate::denies('guest_session_attendance_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $session_titles = Session::pluck('session_title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.guestSessionAttendances.create', compact('session_titles'));
    }

    public function store(StoreGuestSessionAttendanceRequest $request)
    {
        $guestSessionAttendance = GuestSessionAttendance::create($request->all());

        return redirect()->route('admin.guest-session-attendances.index');
    }

    public function edit(GuestSessionAttendance $guestSessionAttendance)
    {
        abort_if(Gate::denies('guest_session_attendance_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $session_titles = Session::pluck('session_title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $guestSessionAttendance->load('session_title');

        return view('admin.guestSessionAttendances.edit', compact('guestSessionAttendance', 'session_titles'));
    }

    public function update(UpdateGuestSessionAttendanceRequest $request, GuestSessionAttendance $guestSessionAttendance)
    {
        $guestSessionAttendance->update($request->all());

        return redirect()->route('admin.guest-session-attendances.index');
    }

    public function show(GuestSessionAttendance $guestSessionAttendance)
    {
        abort_if(Gate::denies('guest_session_attendance_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guestSessionAttendance->load('session_title');

        return view('admin.guestSessionAttendances.show', compact('guestSessionAttendance'));
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
