<?php

namespace App\Http\Controllers\Admin;

use App\Chapter;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyChapterRequest;
use App\Http\Requests\StoreChapterRequest;
use App\Http\Requests\UpdateChapterRequest;
use App\Module;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ChaptersController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
{
    abort_if(Gate::denies('chapter_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    if ($request->ajax()) {
        $query = Chapter::with(['module'])->select(sprintf('%s.*', (new Chapter)->table));
        $table = Datatables::of($query);

        $table->addColumn('placeholder', '&nbsp;');
        $table->addColumn('actions', '&nbsp;');

        $table->editColumn('actions', function ($row) {
            $viewGate      = 'chapter_show';
            $editGate      = 'chapter_edit';
            $deleteGate    = 'chapter_delete';
            $crudRoutePart = 'chapters';

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
        $table->editColumn('chaptername', function ($row) {
            return $row->chaptername ? $row->chaptername : '';
        });
        $table->addColumn('module_name', function ($row) {
            return $row->module ? $row->module->name : '';
        });

        $table->editColumn('description', function ($row) {
            return $row->description ? $row->description : '';
        });

        $table->editColumn('mentorsnote', function ($row) {
            return $row->mentorsnote ? $row->mentorsnote : '';
        });

        $table->editColumn('published', function ($row) {
            return isset(Chapter::PUBLISHED_SELECT[$row->published]) ? Chapter::PUBLISHED_SELECT[$row->published] : '';
        });

        // Add the 'objective' column
        $table->editColumn('objective', function ($row) {
            return $row->objective ? $row->objective : '';
        });

        $table->rawColumns(['actions', 'placeholder', 'module']);

        return $table->make(true);
    }

    $modules = Module::get();

    return view('admin.chapters.index', compact('modules'));
}




    public function create()
    {
        abort_if(Gate::denies('chapter_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $modules = Module::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.chapters.create', compact('modules'));
    }

    // public function store(StoreChapterRequest $request)
    // {
    //     $chapter = Chapter::create($request->all());

    //     return redirect()->route('admin.chapters.index');
    // }


    public function store(Request $request)
    {
        // Validate the form data
        $request->validate([
            'chaptername' => 'required|string|max:255',
            'module_id' => 'required|exists:modules,id',
            'description' => 'required|string',
            'mentorsnote' => 'required|string|max:1000', // Add validation for mentorsnote
            'objective' => 'required|string',
            'published' => 'required|in:yes,no', // Validate as 'yes' or 'no'
        ]);


        // Insert the data into the chapters table
        DB::table('chapters')->insert([
            'chaptername' => $request->input('chaptername'),
            'module_id' => $request->input('module_id'),
            'description' => $request->input('description'),
            'mentorsnote' => $request->input('mentorsnote'), // Add mentorsnote to the insert
            'objective' => $request->input('objective'),
            'published' => $request->input('published'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // $data = [
        //     'chaptername' => $request->input('chaptername'),
        //     'module_id' => $request->input('module_id'),
        //     'description' => $request->input('description'),
        //     'objective' => $request->input('objective'),
        //     'published' => $request->input('published'),
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ];

        // return $data;

        // Redirect with success message
        return redirect()->route('admin.chapters.index')->with('success', 'Chapter created successfully.');
    }

    public function edit(Chapter $chapter)
    {
        abort_if(Gate::denies('chapter_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $modules = Module::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $chapter->load('module');

        return view('admin.chapters.edit', compact('chapter', 'modules'));
    }


    // public function update(UpdateChapterRequest $request, Chapter $chapter)
    // {
    //     $chapter->update($request->all());

    //     return redirect()->route('admin.chapters.index');
    // }
    public function update(Request $request, $id)
    {
        // Validate the form data
        $request->validate([
            'chaptername' => 'required|string|max:255',
            'module_id' => 'required|exists:modules,id',
            'description' => 'required|string',
            'objective' => 'required|string',
            'published' => 'required|in:Yes,No', // Validate as 'yes' or 'no'
            'mentorsnote' => 'nullable|string', // Optional mentorsnote field
        ]);

        // Update the chapter fields using the query builder
        DB::table('chapters')
            ->where('id', $id)
            ->update([
                'chaptername' => $request->input('chaptername'),
                'module_id' => $request->input('module_id'),
                'description' => $request->input('description'),
                'objective' => $request->input('objective'),
                'published' => $request->input('published'),
                'mentorsnote' => $request->input('mentorsnote'), // Update mentorsnote field
                'updated_at' => now(), // Update the timestamp
            ]);

        // Redirect with success message
        return redirect()->route('admin.chapters.index')->with('success', 'Chapter updated successfully.');
    }


    public function show(Chapter $chapter)
    {
        abort_if(Gate::denies('chapter_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $chapter->load('module', 'chapterChapterTests', 'chapterSubchapters', 'lessonCreateProgressTables', 'chapteridModuleresourcebanks');

        return view('admin.chapters.show', compact('chapter'));
    }

    public function destroy(Chapter $chapter)
    {
        abort_if(Gate::denies('chapter_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $chapter->delete();

        return back();
    }

    public function massDestroy(MassDestroyChapterRequest $request)
    {
        $chapters = Chapter::find(request('ids'));

        foreach ($chapters as $chapter) {
            $chapter->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
