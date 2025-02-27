<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\ChapterTest;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChapterTestRequest;
use App\Http\Requests\UpdateChapterTestRequest;
use App\Http\Resources\Admin\ChapterTestResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChapterTestApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('chapter_test_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ChapterTestResource(ChapterTest::with(['moduleid', 'chapter'])->get());
    }

    public function store(StoreChapterTestRequest $request)
    {
        $chapterTest = ChapterTest::create($request->all());

        return (new ChapterTestResource($chapterTest))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ChapterTest $chapterTest)
    {
        abort_if(Gate::denies('chapter_test_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ChapterTestResource($chapterTest->load(['moduleid', 'chapter']));
    }

    public function update(UpdateChapterTestRequest $request, ChapterTest $chapterTest)
    {
        $chapterTest->update($request->all());

        return (new ChapterTestResource($chapterTest))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ChapterTest $chapterTest)
    {
        abort_if(Gate::denies('chapter_test_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $chapterTest->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
