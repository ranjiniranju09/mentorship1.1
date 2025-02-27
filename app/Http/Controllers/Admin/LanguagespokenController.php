<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyLanguagespokenRequest;
use App\Http\Requests\StoreLanguagespokenRequest;
use App\Http\Requests\UpdateLanguagespokenRequest;
use App\Languagespoken;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class LanguagespokenController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('languagespoken_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Languagespoken::query()->select(sprintf('%s.*', (new Languagespoken)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'languagespoken_show';
                $editGate      = 'languagespoken_edit';
                $deleteGate    = 'languagespoken_delete';
                $crudRoutePart = 'languagespokens';

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
            $table->editColumn('langname', function ($row) {
                return $row->langname ? $row->langname : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.languagespokens.index');
    }

    public function create()
    {
        abort_if(Gate::denies('languagespoken_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.languagespokens.create');
    }

    public function store(StoreLanguagespokenRequest $request)
    {
        $languagespoken = Languagespoken::create($request->all());

        return redirect()->route('admin.languagespokens.index');
    }

    public function edit(Languagespoken $languagespoken)
    {
        abort_if(Gate::denies('languagespoken_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.languagespokens.edit', compact('languagespoken'));
    }

    public function update(UpdateLanguagespokenRequest $request, Languagespoken $languagespoken)
    {
        $languagespoken->update($request->all());

        return redirect()->route('admin.languagespokens.index');
    }

    public function show(Languagespoken $languagespoken)
    {
        abort_if(Gate::denies('languagespoken_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $languagespoken->load('langspokenMentors', 'languagesspokenMentees');

        return view('admin.languagespokens.show', compact('languagespoken'));
    }

    public function destroy(Languagespoken $languagespoken)
    {
        abort_if(Gate::denies('languagespoken_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $languagespoken->delete();

        return back();
    }

    public function massDestroy(MassDestroyLanguagespokenRequest $request)
    {
        $languagespokens = Languagespoken::find(request('ids'));

        foreach ($languagespokens as $languagespoken) {
            $languagespoken->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
