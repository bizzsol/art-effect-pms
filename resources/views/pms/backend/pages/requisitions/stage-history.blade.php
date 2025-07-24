@extends('pms.backend.layouts.master-layout')
@section('main-content')
    <div class="container">
        <h3 class="mb-4">Requisition Stage History</h3>

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Stage</th>
                <th>Note</th>
                <th>Updated At</th>
                <th>Updated By</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($histories as $index => $history)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $history->stage }}</td>
                    <td>{{ $history->note }}</td>
                    <td>{{ \Carbon\Carbon::parse($history->stage_updated_at)->format('Y-m-d H:i') }}</td>
                    <td>{{ $history->createdBy->name ?? 'System' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No history found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
