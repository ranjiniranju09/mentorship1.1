<?php

namespace App\Http\Controllers\Frontend;

use App\GuestLecture;
use App\Guestspeaker;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyGuestLectureRequest;
use App\Http\Requests\StoreGuestLectureRequest;
use App\Http\Requests\UpdateGuestLectureRequest;
use App\Mentee;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestLecturesController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('guest_lecture_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guestLectures = GuestLecture::with(['speaker_name', 'invited_mentees'])->get();

        $guestspeakers = Guestspeaker::get();

        $mentees = Mentee::get();

        return view('frontend.guestLectures.index', compact('guestLectures', 'guestspeakers', 'mentees'));
    }

    public function create()
    {
        abort_if(Gate::denies('guest_lecture_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $speaker_names = Guestspeaker::pluck('speakername', 'id')->prepend(trans('global.pleaseSelect'), '');

        $invited_mentees = Mentee::pluck('name', 'id');

        return view('frontend.guestLectures.create', compact('invited_mentees', 'speaker_names'));
    }

    public function store(StoreGuestLectureRequest $request)
    {
        $guestLecture = GuestLecture::create($request->all());
        $guestLecture->invited_mentees()->sync($request->input('invited_mentees', []));

        return redirect()->route('frontend.guest-lectures.index');
    }

    public function edit(GuestLecture $guestLecture)
    {
        abort_if(Gate::denies('guest_lecture_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $speaker_names = Guestspeaker::pluck('speakername', 'id')->prepend(trans('global.pleaseSelect'), '');

        $invited_mentees = Mentee::pluck('name', 'id');

        $guestLecture->load('speaker_name', 'invited_mentees');

        return view('frontend.guestLectures.edit', compact('guestLecture', 'invited_mentees', 'speaker_names'));
    }

    public function update(UpdateGuestLectureRequest $request, GuestLecture $guestLecture)
    {
        $guestLecture->update($request->all());
        $guestLecture->invited_mentees()->sync($request->input('invited_mentees', []));

        return redirect()->route('frontend.guest-lectures.index');
    }

    public function show(GuestLecture $guestLecture)
    {
        abort_if(Gate::denies('guest_lecture_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guestLecture->load('speaker_name', 'invited_mentees');

        return view('frontend.guestLectures.show', compact('guestLecture'));
    }

    public function destroy(GuestLecture $guestLecture)
    {
        abort_if(Gate::denies('guest_lecture_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guestLecture->delete();

        return back();
    }

    public function massDestroy(MassDestroyGuestLectureRequest $request)
    {
        $guestLectures = GuestLecture::find(request('ids'));

        foreach ($guestLectures as $guestLecture) {
            $guestLecture->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
