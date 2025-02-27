@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.opportunity.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.opportunities.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.opportunity.fields.id') }}
                        </th>
                        <td>
                            {{ $opportunity->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opportunity.fields.title') }}
                        </th>
                        <td>
                            {{ $opportunity->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opportunity.fields.link') }}
                        </th>
                        <td>
                            {{ $opportunity->link }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opportunity.fields.opportunity_type') }}
                        </th>
                        <td>
                            {{ App\Opportunity::OPPORTUNITY_TYPE_SELECT[$opportunity->opportunity_type] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.opportunities.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection