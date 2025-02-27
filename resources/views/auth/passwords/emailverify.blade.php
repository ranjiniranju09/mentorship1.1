@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h4>Reset Password</h4>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('email.send') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="email">Enter your registered email to verify:</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Send Verification Email</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
