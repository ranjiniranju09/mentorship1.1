@extends('layouts.new_mentee')

@section('content')

<style>
    .container {
        margin-top: 30px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        font-size: 1rem;
        font-weight: bold;
        color: #343a40;
    }
    .form-control {
        border-radius: 5px;
        border: 1px solid #ced4da;
        padding: 10px;
        font-size: 1rem;
        color: #495057;
    }
    .btn-primary {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        font-size: 16px;
        font-weight: bold;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
</style>
{{--
<div class="container">
    <h2>Create New Ticket</h2>
    <form action="{{ route('mentee.tickets.store') }}" method="POST">
    <form action="#" method="POST">
        @csrf

        
        <div class="form-group">
            <label for="status">Ticket Category</label>
            <select id="status" name="status" class="form-control" required>
                <option value="open">Open</option>
                <option value="pending">Pending</option>
                <option value="closed">Closed</option>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="5" required></textarea>
        </div>

        

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
--}}
<div class="container">
    <h2>Raise New Ticket</h2>
    <form action="{{ route('mentee.tickets.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
        
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label class="required" for="ticket_category_id">{{ trans('cruds.ticketDescription.fields.ticket_category') }}</label>
                </div>
                <div class="form-group">
                    <select class="form-control select2 {{ $errors->has('ticket_category') ? 'is-invalid' : '' }}" name="ticket_category_id" id="ticket_category_id" required>
                        <option value="#">Please Select</option>
                        @foreach($ticket_categories as $id => $entry)
                            <option value="{{ $id }}" {{ old('ticket_category_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="ticket_description">{{ trans('cruds.ticketDescription.fields.ticket_description') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('ticket_description') ? 'is-invalid' : '' }}" name="ticket_description" id="ticket_description">{!! old('ticket_description') !!}</textarea>
                    @if($errors->has('ticket_description'))
                        <div class="invalid-feedback">
                            {{ $errors->first('ticket_description') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.ticketDescription.fields.ticket_description_helper') }}</span>
                </div>

                {{-- ✅ File Upload Section --}}
                <div class="form-group">
                    <label for="attachment_url">Upload File (Optional)</label>
                    <input type="file" class="form-control-file" name="attachment_url" id="attachment_url">
                    @if($errors->has('attachment_url'))
                        <div class="invalid-feedback">
                            {{ $errors->first('attachment_url') }}
                        </div>
                    @endif
                </div>


                <div class="form-group">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
    // Optional JavaScript for additional functionality
</script>
@endsection
