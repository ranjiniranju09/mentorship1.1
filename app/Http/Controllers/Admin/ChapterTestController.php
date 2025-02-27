<?php

namespace App\Http\Controllers\Admin;

use App\Chapter;
use App\ChapterTest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyChapterTestRequest;
use App\Http\Requests\StoreChapterTestRequest;
use App\Http\Requests\UpdateChapterTestRequest;
use App\Module;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ChapterTestController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('chapter_test_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ChapterTest::with(['moduleid', 'chapter'])->select(sprintf('%s.*', (new ChapterTest)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'chapter_test_show';
                $editGate      = 'chapter_test_edit';
                $deleteGate    = 'chapter_test_delete';
                $crudRoutePart = 'chapter-tests';

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
            $table->addColumn('moduleid_name', function ($row) {
                return $row->moduleid ? $row->moduleid->name : '';
            });

            $table->addColumn('chapter_chaptername', function ($row) {
                return $row->chapter ? $row->chapter->chaptername : '';
            });

            $table->editColumn('test_title', function ($row) {
                return $row->test_title ? $row->test_title : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'moduleid', 'chapter']);

            return $table->make(true);
        }

        $modules  = Module::get();
        $chapters = Chapter::get();

        return view('admin.chapterTests.index', compact('modules', 'chapters'));
    }

    public function create()
    {
        abort_if(Gate::denies('chapter_test_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $moduleids = Module::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $chapters = Chapter::pluck('chaptername', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.chapterTests.create', compact('chapters', 'moduleids'));
    }

    public function store(StoreChapterTestRequest $request)
    {
        $chapterTest = ChapterTest::create($request->all());

        return redirect()->route('admin.chapter-tests.index');
    }

    public function edit(ChapterTest $chapterTest)
    {
        abort_if(Gate::denies('chapter_test_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $moduleids = Module::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $chapters = Chapter::pluck('chaptername', 'id')->prepend(trans('global.pleaseSelect'), '');

        $chapterTest->load('moduleid', 'chapter');

        return view('admin.chapterTests.edit', compact('chapterTest', 'chapters', 'moduleids'));
    }

    public function update(UpdateChapterTestRequest $request, ChapterTest $chapterTest)
    {
        $chapterTest->update($request->all());

        return redirect()->route('admin.chapter-tests.index');
    }

    public function show(ChapterTest $chapterTest)
    {
        abort_if(Gate::denies('chapter_test_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $chapterTest->load('moduleid', 'chapter');

        return view('admin.chapterTests.show', compact('chapterTest'));
    }

    public function destroy(ChapterTest $chapterTest)
    {
        abort_if(Gate::denies('chapter_test_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $chapterTest->delete();

        return back();
    }

    public function massDestroy(MassDestroyChapterTestRequest $request)
    {
        $chapterTests = ChapterTest::find(request('ids'));

        foreach ($chapterTests as $chapterTest) {
            $chapterTest->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
