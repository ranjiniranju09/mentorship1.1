<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyMentorRequest;
use App\Http\Requests\StoreMentorRequest;
use App\Http\Requests\UpdateMentorRequest;
use App\Languagespoken;
use App\Mentor;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MentorsController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('mentor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Mentor::with(['langspokens'])->select(sprintf('%s.*', (new Mentor)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'mentor_show';
                $editGate      = 'mentor_edit';
                $deleteGate    = 'mentor_delete';
                $crudRoutePart = 'mentors';

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
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            $table->editColumn('mobile', function ($row) {
                return $row->mobile ? $row->mobile : '';
            });
            $table->editColumn('companyname', function ($row) {
                return $row->companyname ? $row->companyname : '';
            });
            $table->editColumn('skills', function ($row) {
                return $row->skills ? $row->skills : '';
            });
            $table->editColumn('langspoken', function ($row) {
                $labels = [];
                foreach ($row->langspokens as $langspoken) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $langspoken->langname);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'langspoken']);

            return $table->make(true);
        }

        $languagespokens = Languagespoken::get();

        return view('admin.mentors.index', compact('languagespokens'));
    }

    public function create()
    {
        abort_if(Gate::denies('mentor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $langspokens = Languagespoken::pluck('langname', 'id');

        return view('admin.mentors.create', compact('langspokens'));
    }

    public function store(StoreMentorRequest $request)
    {
        $mentor = Mentor::create($request->all());
        $mentor->langspokens()->sync($request->input('langspokens', []));

        return redirect()->route('admin.mentors.index');
    }

    public function edit(Mentor $mentor)
    {
        abort_if(Gate::denies('mentor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $langspokens = Languagespoken::pluck('langname', 'id');

        $mentor->load('langspokens');

        return view('admin.mentors.edit', compact('langspokens', 'mentor'));
    }

    public function update(UpdateMentorRequest $request, Mentor $mentor)
    {
        $mentor->update($request->all());
        $mentor->langspokens()->sync($request->input('langspokens', []));

        return redirect()->route('admin.mentors.index');
    }

    public function show(Mentor $mentor)
    {
        abort_if(Gate::denies('mentor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mentor->load('langspokens', 'mentornameSessions', 'mentornameMappings');

        return view('admin.mentors.show', compact('mentor'));
    }

    public function destroy(Mentor $mentor)
    {
        abort_if(Gate::denies('mentor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mentor->delete();

        return back();
    }

    public function massDestroy(MassDestroyMentorRequest $request)
    {
        $mentors = Mentor::find(request('ids'));

        foreach ($mentors as $mentor) {
            $mentor->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
