<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySessionRequest;
use App\Http\Requests\StoreSessionRequest;
use App\Http\Requests\UpdateSessionRequest;
use App\Mentee;
use App\Mentor;
use App\Module;
use App\Session;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SessionsController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('session_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Session::with(['modulename', 'mentorname', 'menteename'])->select(sprintf('%s.*', (new Session)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'session_show';
                $editGate      = 'session_edit';
                $deleteGate    = 'session_delete';
                $crudRoutePart = 'sessions';

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
            $table->addColumn('modulename_name', function ($row) {
                return $row->modulename ? $row->modulename->name : '';
            });

            $table->addColumn('mentorname_name', function ($row) {
                return $row->mentorname ? $row->mentorname->name : '';
            });

            $table->addColumn('menteename_name', function ($row) {
                return $row->menteename ? $row->menteename->name : '';
            });

            $table->editColumn('sessionlink', function ($row) {
                return $row->sessionlink ? $row->sessionlink : '';
            });
            $table->editColumn('session_title', function ($row) {
                return $row->session_title ? $row->session_title : '';
            });
            $table->editColumn('session_duration_minutes', function ($row) {
                return $row->session_duration_minutes ? Session::SESSION_DURATION_MINUTES_RADIO[$row->session_duration_minutes] : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'modulename', 'mentorname', 'menteename']);

            return $table->make(true);
        }

        $modules = Module::get();
        $mentors = Mentor::get();
        $mentees = Mentee::get();

        return view('admin.sessions.index', compact('modules', 'mentors', 'mentees'));
    }

    public function create()
    {
        abort_if(Gate::denies('session_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $modulenames = Module::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mentornames = Mentor::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $menteenames = Mentee::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.sessions.create', compact('menteenames', 'mentornames', 'modulenames'));
    }

    public function store(StoreSessionRequest $request)
    {
        $session = Session::create($request->all());

        return redirect()->route('admin.sessions.index');
    }

    public function edit(Session $session)
    {
        abort_if(Gate::denies('session_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $modulenames = Module::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mentornames = Mentor::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $menteenames = Mentee::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $session->load('modulename', 'mentorname', 'menteename');

        return view('admin.sessions.edit', compact('menteenames', 'mentornames', 'modulenames', 'session'));
    }

    public function update(UpdateSessionRequest $request, Session $session)
    {
        $session->update($request->all());

        return redirect()->route('admin.sessions.index');
    }

    public function show(Session $session)
    {
        abort_if(Gate::denies('session_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $session->load('modulename', 'mentorname', 'menteename', 'sessionTitleSessionRecordings', 'sessionAttendanceOneOneAttendances', 'sessionTitleGuestSessionAttendances');

        return view('admin.sessions.show', compact('session'));
    }

    public function destroy(Session $session)
    {
        abort_if(Gate::denies('session_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $session->delete();

        return back();
    }

    public function massDestroy(MassDestroySessionRequest $request)
    {
        $sessions = Session::find(request('ids'));

        foreach ($sessions as $session) {
            $session->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
