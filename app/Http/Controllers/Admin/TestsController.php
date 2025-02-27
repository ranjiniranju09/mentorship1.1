<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTestRequest;
use App\Http\Requests\StoreTestRequest;
use App\Http\Requests\UpdateTestRequest;
use Illuminate\Support\Facades\DB;
use App\Lesson;
use App\Test;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Chapter;
use App\Module;

class TestsController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('test_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            // Start building the query
            $query = Test::leftJoin('chapters', 'tests.chapter_id', '=', 'chapters.id')
                ->select(
                    'tests.id',
                    'tests.title',
                    'tests.is_published',
                    'chapters.chaptername'
                )
                ->groupBy('tests.id');
            
            // Filter by test ID
            if ($request->has('search_id') && $request->search_id) {
                $query->where('tests.id', 'like', '%' . $request->search_id . '%');
            }

            // Filter by chapter name
            if ($request->has('search_chapter') && $request->search_chapter) {
                $query->where('chapters.chaptername', 'like', '%' . $request->search_chapter . '%');
            }

            // Filter by test title
            if ($request->has('search_title') && $request->search_title) {
                $query->where('tests.title', 'like', '%' . $request->search_title . '%');
            }

            // Filter by published status
            if ($request->has('search_is_published') && $request->search_is_published) {
                $query->where('tests.is_published', 'like', '%' . $request->search_is_published . '%');
            }

            // Process the query using DataTables
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            // Add action buttons
            $table->editColumn('actions', function ($row) {
                $viewGate      = 'test_show';
                $editGate      = 'test_edit';
                $deleteGate    = 'test_delete';
                $crudRoutePart = 'tests';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            // Display ID
            $table->editColumn('id', function ($row) {
                return $row->id ?? '';
            });

            // Add chapter name column
            $table->addColumn('chaptername', function ($row) {
                return $row->chaptername ?? '';
            });

            // Display title
            $table->editColumn('title', function ($row) {
                return $row->title ?? '';
            });

            // Display is_published checkbox
            $table->editColumn('is_published', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->is_published ? 'checked' : null) . '>';
            });

            $table->rawColumns(['actions', 'placeholder', 'is_published']);

            return $table->make(true);
        }

        // Fetch chapters for the dropdown in the view
        $chapters = Chapter::get();

        // Pass data to the view
        return view('admin.tests.index', compact('chapters'));
    }

    


    public function create()
    {
        abort_if(Gate::denies('test_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $modules = Module::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $chapters = Chapter::pluck('chaptername', 'id')->prepend(trans('global.pleaseSelect'), '');


        return view('admin.tests.create', compact('modules', 'chapters'));
    }

    public function store(StoreTestRequest $request)
    {
        $test = Test::create($request->all());

        return redirect()->route('admin.tests.index');
    }

    public function edit(Test $test)
    {
        abort_if(Gate::denies('test_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $modules = Module::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $chapters = Chapter::pluck('chaptername', 'id')->prepend(trans('global.pleaseSelect'), '');


        $test->load('course', 'lesson'); 

        return view('admin.tests.edit', compact('modules', 'chapters', 'test'));
    }

    // public function update(UpdateTestRequest $request, Test $test)
    // {
    //     $test->update($request->all());

    //     return redirect()->route('admin.tests.index');
    // }


    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'module_id' => 'required|exists:modules,id',
            'chapter_id' => 'required|exists:chapters,id',
            'title' => 'required|string|max:255',
            'is_published' => 'nullable|boolean',
        ]);

        // Prepare data for the update
        $updateData = [
            'module_id' => $request->input('module_id'),
            'chapter_id' => $request->input('chapter_id'),
            'title' => $request->input('title'),
            'is_published' => $request->has('is_published') ? 1 : 0,
            'updated_at' => now(), // Ensure timestamps are handled correctly
        ];

        // Update the test using Query Builder
        $affected = DB::table('tests')
            ->where('id', $id)
            ->update($updateData);

        // Check if the update was successful
        if ($affected) {
            return redirect()->route('admin.tests.index')
                ->with('success', 'Test updated successfully.');
        } else {
            return redirect()->route('admin.tests.index')
                ->with('error', 'Failed to update the test.');
        }
    }


    public function show(Test $test)
    {
        abort_if(Gate::denies('test_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $test->load('course', 'lesson');

        return view('admin.tests.show', compact('test'));
    }

    public function destroy(Test $test)
    {
        abort_if(Gate::denies('test_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $test->delete();

        return back();
    }

    public function massDestroy(MassDestroyTestRequest $request)
    {
        $tests = Test::find(request('ids'));

        foreach ($tests as $test) {
            $test->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
