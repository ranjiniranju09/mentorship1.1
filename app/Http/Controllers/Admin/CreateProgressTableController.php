<?php

namespace App\Http\Controllers\Admin;

use App\Chapter;
use App\CreateProgressTable;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCreateProgressTableRequest;
use App\Http\Requests\StoreCreateProgressTableRequest;
use App\Http\Requests\UpdateCreateProgressTableRequest;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CreateProgressTableController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('create_progress_table_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = CreateProgressTable::with(['user', 'lesson'])->select(sprintf('%s.*', (new CreateProgressTable)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'create_progress_table_show';
                $editGate      = 'create_progress_table_edit';
                $deleteGate    = 'create_progress_table_delete';
                $crudRoutePart = 'create-progress-tables';

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
            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->addColumn('lesson_chaptername', function ($row) {
                return $row->lesson ? $row->lesson->chaptername : '';
            });

            $table->editColumn('status', function ($row) {
                return $row->status ? CreateProgressTable::STATUS_SELECT[$row->status] : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user', 'lesson']);

            return $table->make(true);
        }

        $users    = User::get();
        $chapters = Chapter::get();

        return view('admin.createProgressTables.index', compact('users', 'chapters'));
    }

    public function create()
    {
        abort_if(Gate::denies('create_progress_table_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $lessons = Chapter::pluck('chaptername', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.createProgressTables.create', compact('lessons', 'users'));
    }

    public function store(StoreCreateProgressTableRequest $request)
    {
        $createProgressTable = CreateProgressTable::create($request->all());

        return redirect()->route('admin.create-progress-tables.index');
    }

    public function edit(CreateProgressTable $createProgressTable)
    {
        abort_if(Gate::denies('create_progress_table_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $lessons = Chapter::pluck('chaptername', 'id')->prepend(trans('global.pleaseSelect'), '');

        $createProgressTable->load('user', 'lesson');

        return view('admin.createProgressTables.edit', compact('createProgressTable', 'lessons', 'users'));
    }

    public function update(UpdateCreateProgressTableRequest $request, CreateProgressTable $createProgressTable)
    {
        $createProgressTable->update($request->all());

        return redirect()->route('admin.create-progress-tables.index');
    }

    public function show(CreateProgressTable $createProgressTable)
    {
        abort_if(Gate::denies('create_progress_table_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $createProgressTable->load('user', 'lesson');

        return view('admin.createProgressTables.show', compact('createProgressTable'));
    }

    public function destroy(CreateProgressTable $createProgressTable)
    {
        abort_if(Gate::denies('create_progress_table_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $createProgressTable->delete();

        return back();
    }

    public function massDestroy(MassDestroyCreateProgressTableRequest $request)
    {
        $createProgressTables = CreateProgressTable::find(request('ids'));

        foreach ($createProgressTables as $createProgressTable) {
            $createProgressTable->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
