<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyMappingRequest;
use App\Http\Requests\StoreMappingRequest;
use App\Http\Requests\UpdateMappingRequest;
use App\Mapping;
use App\Mentee;
use App\Mentor;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Mail\MentorMappingNotification;
use App\Mail\MenteeMappingNotification;
use Illuminate\Support\Facades\Mail;


class MappingController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('mapping_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Mapping::with(['mentorname', 'menteename'])->select(sprintf('%s.*', (new Mapping)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'mapping_show';
                $editGate      = 'mapping_edit';
                $deleteGate    = 'mapping_delete';
                $crudRoutePart = 'mappings';

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
            $table->addColumn('mentorname_name', function ($row) {
                return $row->mentorname ? $row->mentorname->name : '';
            });

            $table->addColumn('menteename_name', function ($row) {
                return $row->menteename ? $row->menteename->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'mentorname', 'menteename']);

            return $table->make(true);
        }

        $mentors = Mentor::get();
        $mentees = Mentee::get();

        return view('admin.mappings.index', compact('mentors', 'mentees'));
    }

    public function create()
    {
        abort_if(Gate::denies('mapping_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mentornames = Mentor::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $menteenames = Mentee::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.mappings.create', compact('menteenames', 'mentornames'));
    }

    public function store(StoreMappingRequest $request)
    {

            $mapping = Mapping::create($request->all());

            // Load mentor and mentee relationships
            $mapping->load('mentor', 'mentee');

            // Send email to mentor
            if ($mapping->mentor) {
                Mail::to($mapping->mentor->email)->send(new MentorMappingNotification($mapping));
                }

            // Send email to mentee
            if ($mapping->mentee) {
                Mail::to($mapping->mentee->email)->send(new MenteeMappingNotification($mapping));
            }

            return redirect()->route('admin.mappings.index');
    }

    public function edit(Mapping $mapping)
    {
        abort_if(Gate::denies('mapping_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mentornames = Mentor::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $menteenames = Mentee::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mapping->load('mentorname', 'menteename');

        return view('admin.mappings.edit', compact('mapping', 'menteenames', 'mentornames'));
    }

    public function update(UpdateMappingRequest $request, Mapping $mapping)
    {
        $mapping->update($request->all());

        return redirect()->route('admin.mappings.index');
    }

    public function show(Mapping $mapping)
    {
        abort_if(Gate::denies('mapping_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mapping->load('mentorname', 'menteename');

        return view('admin.mappings.show', compact('mapping'));
    }

    public function destroy(Mapping $mapping)
    {
        abort_if(Gate::denies('mapping_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mapping->delete();

        return back();
    }

    public function massDestroy(MassDestroyMappingRequest $request)
    {
        $mappings = Mapping::find(request('ids'));

        foreach ($mappings as $mapping) {
            $mapping->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
