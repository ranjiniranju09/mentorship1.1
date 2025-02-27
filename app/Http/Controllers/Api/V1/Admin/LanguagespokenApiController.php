<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLanguagespokenRequest;
use App\Http\Requests\UpdateLanguagespokenRequest;
use App\Http\Resources\Admin\LanguagespokenResource;
use App\Languagespoken;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LanguagespokenApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('languagespoken_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LanguagespokenResource(Languagespoken::all());
    }

    public function store(StoreLanguagespokenRequest $request)
    {
        $languagespoken = Languagespoken::create($request->all());

        return (new LanguagespokenResource($languagespoken))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Languagespoken $languagespoken)
    {
        abort_if(Gate::denies('languagespoken_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LanguagespokenResource($languagespoken);
    }

    public function update(UpdateLanguagespokenRequest $request, Languagespoken $languagespoken)
    {
        $languagespoken->update($request->all());

        return (new LanguagespokenResource($languagespoken))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Languagespoken $languagespoken)
    {
        abort_if(Gate::denies('languagespoken_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $languagespoken->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
