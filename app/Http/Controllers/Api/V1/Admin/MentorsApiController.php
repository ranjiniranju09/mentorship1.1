<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMentorRequest;
use App\Http\Requests\UpdateMentorRequest;
use App\Http\Resources\Admin\MentorResource;
use App\Mentor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MentorsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('mentor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MentorResource(Mentor::with(['langspokens'])->get());
    }

    public function store(StoreMentorRequest $request)
    {
        $mentor = Mentor::create($request->all());
        $mentor->langspokens()->sync($request->input('langspokens', []));

        return (new MentorResource($mentor))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Mentor $mentor)
    {
        abort_if(Gate::denies('mentor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MentorResource($mentor->load(['langspokens']));
    }

    public function update(UpdateMentorRequest $request, Mentor $mentor)
    {
        $mentor->update($request->all());
        $mentor->langspokens()->sync($request->input('langspokens', []));

        return (new MentorResource($mentor))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Mentor $mentor)
    {
        abort_if(Gate::denies('mentor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mentor->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
