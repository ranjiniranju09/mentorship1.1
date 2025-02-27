<?php

namespace App\Http\Controllers\Admin;

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
use Yajra\DataTables\Facades\DataTables;

class OneOneAttendanceController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('one_one_attendance_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = OneOneAttendance::with(['session_attendance'])->select(sprintf('%s.*', (new OneOneAttendance)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'one_one_attendance_show';
                $editGate      = 'one_one_attendance_edit';
                $deleteGate    = 'one_one_attendance_delete';
                $crudRoutePart = 'one-one-attendances';

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
            $table->addColumn('session_attendance_session_title', function ($row) {
                return $row->session_attendance ? $row->session_attendance->session_title : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'session_attendance']);

            return $table->make(true);
        }

        $sessions = Session::get();

        return view('admin.oneOneAttendances.index', compact('sessions'));
    }

    public function create()
    {
        abort_if(Gate::denies('one_one_attendance_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $session_attendances = Session::pluck('session_title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.oneOneAttendances.create', compact('session_attendances'));
    }

    public function store(StoreOneOneAttendanceRequest $request)
    {
        $oneOneAttendance = OneOneAttendance::create($request->all());

        return redirect()->route('admin.one-one-attendances.index');
    }

    public function edit(OneOneAttendance $oneOneAttendance)
    {
        abort_if(Gate::denies('one_one_attendance_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $session_attendances = Session::pluck('session_title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $oneOneAttendance->load('session_attendance');

        return view('admin.oneOneAttendances.edit', compact('oneOneAttendance', 'session_attendances'));
    }

    public function update(UpdateOneOneAttendanceRequest $request, OneOneAttendance $oneOneAttendance)
    {
        $oneOneAttendance->update($request->all());

        return redirect()->route('admin.one-one-attendances.index');
    }

    public function show(OneOneAttendance $oneOneAttendance)
    {
        abort_if(Gate::denies('one_one_attendance_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $oneOneAttendance->load('session_attendance');

        return view('admin.oneOneAttendances.show', compact('oneOneAttendance'));
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
