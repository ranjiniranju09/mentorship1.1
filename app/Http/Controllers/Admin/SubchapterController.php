<?php

namespace App\Http\Controllers\Admin;

use App\Chapter;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySubchapterRequest;
use App\Http\Requests\StoreSubchapterRequest;
use App\Http\Requests\UpdateSubchapterRequest;
use App\Subchapter;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SubchapterController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('subchapter_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Subchapter::with(['chapter'])->select(sprintf('%s.*', (new Subchapter)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'subchapter_show';
                $editGate      = 'subchapter_edit';
                $deleteGate    = 'subchapter_delete';
                $crudRoutePart = 'subchapters';

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
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->addColumn('chapter_chaptername', function ($row) {
                return $row->chapter ? $row->chapter->chaptername : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'chapter']);

            return $table->make(true);
        }

        $chapters = Chapter::get();

        return view('admin.subchapters.index', compact('chapters'));
    }

    public function create()
    {
        abort_if(Gate::denies('subchapter_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $chapters = Chapter::pluck('chaptername', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.subchapters.create', compact('chapters'));
    }

    public function store(StoreSubchapterRequest $request)
    {
        $subchapter = Subchapter::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $subchapter->id]);
        }

        return redirect()->route('admin.subchapters.index');
    }

    public function edit(Subchapter $subchapter)
    {
        abort_if(Gate::denies('subchapter_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $chapters = Chapter::pluck('chaptername', 'id')->prepend(trans('global.pleaseSelect'), '');

        $subchapter->load('chapter');

        return view('admin.subchapters.edit', compact('chapters', 'subchapter'));
    }

    public function update(UpdateSubchapterRequest $request, Subchapter $subchapter)
    {
        $subchapter->update($request->all());

        return redirect()->route('admin.subchapters.index');
    }

    public function show(Subchapter $subchapter)
    {
        abort_if(Gate::denies('subchapter_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subchapter->load('chapter');

        return view('admin.subchapters.show', compact('subchapter'));
    }

    public function destroy(Subchapter $subchapter)
    {
        abort_if(Gate::denies('subchapter_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subchapter->delete();

        return back();
    }

    public function massDestroy(MassDestroySubchapterRequest $request)
    {
        $subchapters = Subchapter::find(request('ids'));

        foreach ($subchapters as $subchapter) {
            $subchapter->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('subchapter_create') && Gate::denies('subchapter_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Subchapter();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
