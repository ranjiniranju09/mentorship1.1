<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTicketcategoryRequest;
use App\Http\Requests\StoreTicketcategoryRequest;
use App\Http\Requests\UpdateTicketcategoryRequest;
use App\Ticketcategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TicketcategoryController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('ticketcategory_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Ticketcategory::query()->select(sprintf('%s.*', (new Ticketcategory)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'ticketcategory_show';
                $editGate      = 'ticketcategory_edit';
                $deleteGate    = 'ticketcategory_delete';
                $crudRoutePart = 'ticketcategories';

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
            $table->editColumn('category_description', function ($row) {
                return $row->category_description ? $row->category_description : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.ticketcategories.index');
    }

    public function create()
    {
        abort_if(Gate::denies('ticketcategory_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.ticketcategories.create');
    }

    public function store(StoreTicketcategoryRequest $request)
    {
        $ticketcategory = Ticketcategory::create($request->all());

        return redirect()->route('admin.ticketcategories.index');
    }

    public function edit(Ticketcategory $ticketcategory)
    {
        abort_if(Gate::denies('ticketcategory_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.ticketcategories.edit', compact('ticketcategory'));
    }

    public function update(UpdateTicketcategoryRequest $request, Ticketcategory $ticketcategory)
    {
        $ticketcategory->update($request->all());

        return redirect()->route('admin.ticketcategories.index');
    }

    public function show(Ticketcategory $ticketcategory)
    {
        abort_if(Gate::denies('ticketcategory_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticketcategory->load('ticketCategoryTicketDescriptions');

        return view('admin.ticketcategories.show', compact('ticketcategory'));
    }

    public function destroy(Ticketcategory $ticketcategory)
    {
        abort_if(Gate::denies('ticketcategory_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticketcategory->delete();

        return back();
    }

    public function massDestroy(MassDestroyTicketcategoryRequest $request)
    {
        $ticketcategories = Ticketcategory::find(request('ids'));

        foreach ($ticketcategories as $ticketcategory) {
            $ticketcategory->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
