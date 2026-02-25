@extends('backEnd.layouts.master')
@section('title', 'Offer List')

@section('content')
<div class="container mt-4">

    <h3>All Offers</h3>
    <a href="{{ route('offers.create') }}" class="btn btn-primary mb-3">Create New Offer</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Products</th>
                <th width="120">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($offers as $offer)
            <tr>
                <td>{{ $offer->title }}</td>
                <td>{{ $offer->description }}</td>
                <td>
                    @foreach($offer->products as $p)
                        <span class="badge bg-info">{{ $p->name }}</span>
                    @endforeach
                </td>
                <td>
                    {{-- Edit --}}
                    <a href="{{ route('offers.edit', $offer->id) }}" 
                       class="btn btn-sm btn-warning">
                        ✏️
                    </a>

                    {{-- Delete --}}
                    <form action="{{ route('offers.destroy', $offer->id) }}" 
                          method="POST" 
                          style="display:inline-block;"
                          onsubmit="return confirm('Are you sure you want to delete this offer?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">🗑️</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
