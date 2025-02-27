<?php

namespace App\Http\Controllers\Admin;

use App\Chapter;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyModuleresourcebankRequest;
use App\Http\Requests\StoreModuleresourcebankRequest;
use App\Http\Requests\UpdateModuleresourcebankRequest;
use App\Module;
use App\Moduleresourcebank;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ModuleresourcebankController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('moduleresourcebank_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Moduleresourcebank::with(['module', 'chapterid'])->select(sprintf('%s.*', (new Moduleresourcebank)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'moduleresourcebank_show';
                $editGate      = 'moduleresourcebank_edit';
                $deleteGate    = 'moduleresourcebank_delete';
                $crudRoutePart = 'moduleresourcebanks';

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
            $table->addColumn('module_name', function ($row) {
                return $row->module ? $row->module->name : '';
            });

            $table->addColumn('chapterid_chaptername', function ($row) {
                return $row->chapterid ? $row->chapterid->chaptername : '';
            });

            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('link', function ($row) {
                return $row->link ? $row->link : '';
            });
            $table->editColumn('resourcefile', function ($row) {
                if (! $row->resourcefile) {
                    return '';
                }
                $links = [];
                foreach ($row->resourcefile as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('resource_photos', function ($row) {
                if (! $row->resource_photos) {
                    return '';
                }
                $links = [];
                foreach ($row->resource_photos as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank"><img src="' . $media->getUrl('thumb') . '" width="50px" height="50px"></a>';
                }

                return implode(' ', $links);
            });

            $table->rawColumns(['actions', 'placeholder', 'module', 'chapterid', 'resourcefile', 'resource_photos']);

            return $table->make(true);
        }

        $modules  = Module::get();
        $chapters = Chapter::get();

        return view('admin.moduleresourcebanks.index', compact('modules', 'chapters'));
    }

    public function create()
    {
        abort_if(Gate::denies('moduleresourcebank_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $modules = Module::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $chapterids = Chapter::pluck('chaptername', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.moduleresourcebanks.create', compact('chapterids', 'modules'));
    }

    public function store(StoreModuleresourcebankRequest $request)
    {
        $moduleresourcebank = Moduleresourcebank::create($request->all());

        foreach ($request->input('resourcefile', []) as $file) {
            $moduleresourcebank->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('resourcefile');
        }

        foreach ($request->input('resource_photos', []) as $file) {
            $moduleresourcebank->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('resource_photos');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $moduleresourcebank->id]);
        }

        return redirect()->route('admin.moduleresourcebanks.index');
    }

    public function edit(Moduleresourcebank $moduleresourcebank)
    {
        abort_if(Gate::denies('moduleresourcebank_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $modules = Module::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $chapterids = Chapter::pluck('chaptername', 'id')->prepend(trans('global.pleaseSelect'), '');

        $moduleresourcebank->load('module', 'chapterid');

        return view('admin.moduleresourcebanks.edit', compact('chapterids', 'moduleresourcebank', 'modules'));
    }

    public function update(UpdateModuleresourcebankRequest $request, Moduleresourcebank $moduleresourcebank)
    {
        $moduleresourcebank->update($request->all());

        if (count($moduleresourcebank->resourcefile) > 0) {
            foreach ($moduleresourcebank->resourcefile as $media) {
                if (! in_array($media->file_name, $request->input('resourcefile', []))) {
                    $media->delete();
                }
            }
        }
        $media = $moduleresourcebank->resourcefile->pluck('file_name')->toArray();
        foreach ($request->input('resourcefile', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $moduleresourcebank->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('resourcefile');
            }
        }

        if (count($moduleresourcebank->resource_photos) > 0) {
            foreach ($moduleresourcebank->resource_photos as $media) {
                if (! in_array($media->file_name, $request->input('resource_photos', []))) {
                    $media->delete();
                }
            }
        }
        $media = $moduleresourcebank->resource_photos->pluck('file_name')->toArray();
        foreach ($request->input('resource_photos', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $moduleresourcebank->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('resource_photos');
            }
        }

        return redirect()->route('admin.moduleresourcebanks.index');
    }

    public function show(Moduleresourcebank $moduleresourcebank)
    {
        abort_if(Gate::denies('moduleresourcebank_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $moduleresourcebank->load('module', 'chapterid');

        return view('admin.moduleresourcebanks.show', compact('moduleresourcebank'));
    }

    public function destroy(Moduleresourcebank $moduleresourcebank)
    {
        abort_if(Gate::denies('moduleresourcebank_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $moduleresourcebank->delete();

        return back();
    }

    public function massDestroy(MassDestroyModuleresourcebankRequest $request)
    {
        $moduleresourcebanks = Moduleresourcebank::find(request('ids'));

        foreach ($moduleresourcebanks as $moduleresourcebank) {
            $moduleresourcebank->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('moduleresourcebank_create') && Gate::denies('moduleresourcebank_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Moduleresourcebank();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
