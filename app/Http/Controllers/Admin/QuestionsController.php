<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyQuestionRequest;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Question;
use App\Test;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class QuestionsController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('question_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Question::with(['test'])->select(sprintf('%s.*', (new Question)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'question_show';
                $editGate      = 'question_edit';
                $deleteGate    = 'question_delete';
                $crudRoutePart = 'questions';

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
            $table->addColumn('test_title', function ($row) {
                return $row->test ? $row->test->title : '';
            });

            $table->editColumn('question_text', function ($row) {
                return $row->question_text ? $row->question_text : '';
            });
            $table->editColumn('question_image', function ($row) {
                if ($photo = $row->question_image) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="50px" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                }

                return '';
            });
            $table->editColumn('points', function ($row) {
                return $row->points ? $row->points : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'test', 'question_image']);

            return $table->make(true);
        }

        $tests = Test::get();

        return view('admin.questions.index', compact('tests'));
    }

    public function create()
    {
        abort_if(Gate::denies('question_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tests = Test::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.questions.create', compact('tests'));
    }

    public function store(StoreQuestionRequest $request)
    {
        // Ensure `mcq` is included in the request
        $data = $request->all();
        $data['mcq'] = $request->input('mcq','No'); // Default to 0 (No) if not provided

        // Create the question with `mcq` included
        $question = Question::create($data);

        // Handle question image upload
        if ($request->input('question_image', false)) {
            $question->addMedia(storage_path('tmp/uploads/' . basename($request->input('question_image'))))->toMediaCollection('question_image');
        }

        // Handle CKEditor media if any
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $question->id]);
        }

        return redirect()->route('admin.questions.index');
    }


    public function edit(Question $question)
    {
        abort_if(Gate::denies('question_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tests = Test::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $question->load('test');

        return view('admin.questions.edit', compact('question', 'tests'));
    }

    public function update(UpdateQuestionRequest $request, Question $question)
    {
        // Ensure `mcq` is included in the request
        $data = $request->all();
        $data['mcq'] = $request->input('mcq', $question->mcq); // Retain the existing value if not provided

        // Update the question with `mcq` included
        $question->update($data);

        // Handle question image upload
        if ($request->input('question_image', false)) {
            if (! $question->question_image || $request->input('question_image') !== $question->question_image->file_name) {
                if ($question->question_image) {
                    $question->question_image->delete();
                }
                $question->addMedia(storage_path('tmp/uploads/' . basename($request->input('question_image'))))->toMediaCollection('question_image');
            }
        } elseif ($question->question_image) {
            $question->question_image->delete();
        }

        return redirect()->route('admin.questions.index');
    }


    public function show(Question $question)
    {
        abort_if(Gate::denies('question_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $question->load('test');

        return view('admin.questions.show', compact('question'));
    }

    public function destroy(Question $question)
    {
        abort_if(Gate::denies('question_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $question->delete();

        return back();
    }

    public function massDestroy(MassDestroyQuestionRequest $request)
    {
        $questions = Question::find(request('ids'));

        foreach ($questions as $question) {
            $question->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('question_create') && Gate::denies('question_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Question();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
