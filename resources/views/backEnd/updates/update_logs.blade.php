@extends('backEnd.layouts.master')

@section('title', 'System Update')

@section('content')
<table class="table">
    <thead>
        <tr>
            <th>Domain</th>
            <th>Version</th>
            <th>IP</th>
            <th>User Agent</th>
            <th>Updated At</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
            <tr>
                <td>{{ $log->domain }}</td>
                <td>{{ $log->version }}</td>
                <td>{{ $log->ip_address }}</td>
                <td>{{ Str::limit($log->user_agent, 50) }}</td>
                <td>{{ $log->updated_at_time }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection