<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyMenteeRequest;
use App\Http\Requests\StoreMenteeRequest;
use App\Http\Requests\UpdateMenteeRequest;
use App\Languagespoken;
use App\Mentee;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MenteesController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('mentee_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Mentee::with(['languagesspokens'])->select(sprintf('%s.*', (new Mentee)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'mentee_show';
                $editGate      = 'mentee_edit';
                $deleteGate    = 'mentee_delete';
                $crudRoutePart = 'mentees';

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

            $table->editColumn('skills', function ($row) {
                return $row->skilss ? $row->skilss : '';
            });
            $table->editColumn('interestedskills', function ($row) {
                return $row->interestedskills ? $row->interestedskills : '';
            });
            $table->editColumn('languagesspoken', function ($row) {
                $labels = [];
                foreach ($row->languagesspokens as $languagesspoken) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $languagesspoken->langname);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'languagesspoken']);

            return $table->make(true);
        }

        $languagespokens = Languagespoken::get();

        return view('admin.mentees.index', compact('languagespokens'));
    }

    public function create()
    {
        abort_if(Gate::denies('mentee_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $languagesspokens = Languagespoken::pluck('langname', 'id');

        return view('admin.mentees.create', compact('languagesspokens'));
    }

    public function store(StoreMenteeRequest $request)
    {
        $mentee = Mentee::create($request->all());
        $mentee->languagesspokens()->sync($request->input('languagesspokens', []));

        return redirect()->route('admin.mentees.index');
    }

    public function edit(Mentee $mentee)
    {
        abort_if(Gate::denies('mentee_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $languagesspokens = Languagespoken::pluck('langname', 'id');

        $mentee->load('languagesspokens');

        return view('admin.mentees.edit', compact('languagesspokens', 'mentee'));
    }

    public function update(UpdateMenteeRequest $request, Mentee $mentee)
    {
        $mentee->update($request->all());
        $mentee->languagesspokens()->sync($request->input('languagesspokens', []));

        return redirect()->route('admin.mentees.index');
    }

    public function show(Mentee $mentee)
    {
        abort_if(Gate::denies('mentee_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mentee->load('languagesspokens', 'menteenameSessions', 'menteenameMappings', 'invitedMenteesGuestLectures');

        return view('admin.mentees.show', compact('mentee'));
    }

    public function destroy(Mentee $mentee)
    {
        abort_if(Gate::denies('mentee_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mentee->delete();

        return back();
    }

    public function massDestroy(MassDestroyMenteeRequest $request)
    {
        $mentees = Mentee::find(request('ids'));

        foreach ($mentees as $mentee) {
            $mentee->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
