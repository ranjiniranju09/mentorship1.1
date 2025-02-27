<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketcategoryRequest;
use App\Http\Requests\UpdateTicketcategoryRequest;
use App\Http\Resources\Admin\TicketcategoryResource;
use App\Ticketcategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TicketcategoryApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('ticketcategory_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TicketcategoryResource(Ticketcategory::all());
    }

    public function store(StoreTicketcategoryRequest $request)
    {
        $ticketcategory = Ticketcategory::create($request->all());

        return (new TicketcategoryResource($ticketcategory))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Ticketcategory $ticketcategory)
    {
        abort_if(Gate::denies('ticketcategory_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TicketcategoryResource($ticketcategory);
    }

    public function update(UpdateTicketcategoryRequest $request, Ticketcategory $ticketcategory)
    {
        $ticketcategory->update($request->all());

        return (new TicketcategoryResource($ticketcategory))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Ticketcategory $ticketcategory)
    {
        abort_if(Gate::denies('ticketcategory_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticketcategory->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
