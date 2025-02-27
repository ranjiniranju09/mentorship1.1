<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyModuleRequest;
use App\Http\Requests\StoreModuleRequest;
use App\Http\Requests\UpdateModuleRequest;
use App\Module;
use App\Models\Chapters;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ModulesController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('module_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Module::query()->select(sprintf('%s.*', (new Module)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'module_show';
                $editGate      = 'module_edit';
                $deleteGate    = 'module_delete';
                $crudRoutePart = 'modules';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.modules.index');
    }

    public function create()
    {
        abort_if(Gate::denies('module_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.modules.create');
    }
    
    // public function store(StoreModuleRequest $request)
    // {
    //     Module::create($request->validated()); // Uses only validated data

    //     return redirect()->route('admin.modules.index')->with('success', 'Module created successfully.');
    // }

    public function store(Request $request)
    {
        // Check if the user has permission to create a module
        abort_if(Gate::denies('module_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'objective' => 'required|string|max:1000', // Adjust max length if necessary
            'description' => 'required|string|max:1000', // Adjust max length if necessary
            'mentorsnote' => 'required|string|max:1000', // Add validation for mentorsnote

        ]);

        // Insert a new Module record using query builder
        DB::table('modules')->insert([
            'name' => $request->input('name'),
            'objective' => $request->input('objective'),
            'description' => $request->input('description'),
            'mentorsnote' => 'required|string|max:1000', // Add validation for mentorsnote
            'created_at' => now(),  // Include timestamps if needed
            'updated_at' => now(),
        ]);

        // Redirect to the index page with a success message
        return redirect()->route('admin.modules.index')->with('success', 'Module created successfully.');
    }


    public function edit(Module $module)
    {
        abort_if(Gate::denies('module_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.modules.edit', compact('module'));
    }

    public function update(Request $request, $id)
    {
        // Check if the user has permission to update a module
        abort_if(Gate::denies('module_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'objective' => 'required|string|max:1000', // Adjust max length if necessary
            'description' => 'required|string|max:1000', // Adjust max length if necessary
            'mentorsnote' => 'required|string|max:1000', // Add validation for mentorsnote
        ]);

        // Update the module in the database using query builder
        DB::table('modules')
            ->where('id', $id)
            ->update([
                'name' => $request->input('name'),
                'objective' => $request->input('objective'),
                'description' => $request->input('description'),
                'mentorsnote' => $request->input('mentorsnote'), // Add mentorsnote to the update
                'updated_at' => now(),
            ]);

        // Redirect to the modules index page with a success message
        return redirect()->route('admin.modules.index')->with('success', 'Module updated successfully.');
    }


    // public function show(Module $module)
    // {
    //     abort_if(Gate::denies('module_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     //$module->load('modulenameSessions');
    //     //$module->load('modulenameSessions', 'moduleChapters', 'moduleidChapterTests');
    //     $module->load('modulenameSessions', 'moduleChapters', 'moduleidChapterTests', 'moduleModuleresourcebanks');
    //     return view('admin.modules.show', compact('module'));
    // }
    // In your ModulesController or any relevant controller

    public function show($id)
    {
        // Retrieve the module by ID, or handle a "not found" scenario
        $module = Module::findOrFail($id);

        // Pass the module data to the view
        return view('admin.modules.show', compact('module'));
    }



    public function destroy(Module $module)
    {
        abort_if(Gate::denies('module_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $module->delete();

        return back();
    }

    public function massDestroy(MassDestroyModuleRequest $request)
    {
        $modules = Module::find(request('ids'));

        foreach ($modules as $module) {
            $module->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
