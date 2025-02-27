<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenteeRequest;
use App\Http\Requests\UpdateMenteeRequest;
use App\Http\Resources\Admin\MenteeResource;
use App\Mentee;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MenteesApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('mentee_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MenteeResource(Mentee::with(['languagesspokens'])->get());
    }

    public function store(StoreMenteeRequest $request)
    {
        $mentee = Mentee::create($request->all());
        $mentee->languagesspokens()->sync($request->input('languagesspokens', []));

        return (new MenteeResource($mentee))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Mentee $mentee)
    {
        abort_if(Gate::denies('mentee_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MenteeResource($mentee->load(['languagesspokens']));
    }

    public function update(UpdateMenteeRequest $request, Mentee $mentee)
    {
        $mentee->update($request->all());
        $mentee->languagesspokens()->sync($request->input('languagesspokens', []));

        return (new MenteeResource($mentee))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Mentee $mentee)
    {
        abort_if(Gate::denies('mentee_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mentee->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
