<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySessionRequest;
use App\Http\Requests\StoreSessionRequest;
use App\Http\Requests\UpdateSessionRequest;
use App\Mentee;
use App\Mentor;
use App\Module;
use App\Session;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SessionsController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('session_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sessions = Session::with(['modulename', 'mentorname', 'menteename'])->get();

        $modules = Module::get();

        $mentors = Mentor::get();

        $mentees = Mentee::get();

        return view('frontend.sessions.index', compact('mentees', 'mentors', 'modules', 'sessions'));
    }

    public function create()
    {
        abort_if(Gate::denies('session_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $modulenames = Module::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mentornames = Mentor::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $menteenames = Mentee::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.sessions.create', compact('menteenames', 'mentornames', 'modulenames'));
    }

    public function store(StoreSessionRequest $request)
    {
        $session = Session::create($request->all());

        return redirect()->route('frontend.sessions.index');
    }

    public function edit(Session $session)
    {
        abort_if(Gate::denies('session_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $modulenames = Module::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mentornames = Mentor::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $menteenames = Mentee::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $session->load('modulename', 'mentorname', 'menteename');

        return view('frontend.sessions.edit', compact('menteenames', 'mentornames', 'modulenames', 'session'));
    }

    public function update(UpdateSessionRequest $request, Session $session)
    {
        $session->update($request->all());

        return redirect()->route('frontend.sessions.index');
    }

    public function show(Session $session)
    {
        abort_if(Gate::denies('session_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $session->load('modulename', 'mentorname', 'menteename', 'sessionTitleSessionRecordings', 'sessionAttendanceOneOneAttendances', 'sessionTitleGuestSessionAttendances');

        return view('frontend.sessions.show', compact('session'));
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
