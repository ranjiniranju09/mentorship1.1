<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyOpportunityRequest;
use App\Http\Requests\StoreOpportunityRequest;
use App\Http\Requests\UpdateOpportunityRequest;
use App\Opportunity;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class OpportunitiesController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('opportunity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Opportunity::query()->select(sprintf('%s.*', (new Opportunity)->table));
            $table = Datatables::of($query);
            
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'opportunity_show';
                $editGate      = 'opportunity_edit';
                $deleteGate    = 'opportunity_delete';
                $crudRoutePart = 'opportunities';

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
            $table->editColumn('link', function ($row) {
                return $row->link ? $row->link : '';
            });
            $table->editColumn('opportunity_type', function ($row) {
                return $row->opportunity_type ? Opportunity::OPPORTUNITY_TYPE_SELECT[$row->opportunity_type] : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.opportunities.index');
    }

    public function create()
    {
        abort_if(Gate::denies('opportunity_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.opportunities.create');
    }

    public function store(StoreOpportunityRequest $request)
    {
        $opportunity = Opportunity::create($request->all());

        return redirect()->route('admin.opportunities.index');
    }

    public function edit(Opportunity $opportunity)
    {
        abort_if(Gate::denies('opportunity_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.opportunities.edit', compact('opportunity'));
    }

    public function update(UpdateOpportunityRequest $request, Opportunity $opportunity)
    {
        $opportunity->update($request->all());

        return redirect()->route('admin.opportunities.index');
    }

    public function show(Opportunity $opportunity)
    {
        abort_if(Gate::denies('opportunity_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.opportunities.show', compact('opportunity'));
    }

    public function destroy(Opportunity $opportunity)
    {
        abort_if(Gate::denies('opportunity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opportunity->delete();

        return back();
    }

    public function massDestroy(MassDestroyOpportunityRequest $request)
    {
        $opportunities = Opportunity::find(request('ids'));

        foreach ($opportunities as $opportunity) {
            $opportunity->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
