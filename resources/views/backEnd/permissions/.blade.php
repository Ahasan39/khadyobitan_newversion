@extends('backEnd.layouts.master')

@section('content')
<div class="container-fluid">
    <h2>Update Permissions</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('backEnd.permissions.update') }}" method="POST">
        @csrf

        <div class="form-group">
            @foreach($permissions as $permission)
                <div class="form-check">
                    <input class="form-check-input"
                           type="checkbox"
                           name="permissions[]"
                           value="{{ $permission->id }}"
                           {{ $permission->status ? 'checked' : '' }}>
                    <label class="form-check-label">
                        {{ $permission->name }}
                    </label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary mt-3">Save</button>
    </form>
</div>
@endsection