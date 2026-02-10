@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Campaigns</h1>
        @can('create', App\Models\Campaign::class)
            <a href="{{ route('campaigns.create') }}" class="btn btn-primary">Create Campaign</a>
        @endcan
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($campaigns as $campaign)
                        <tr>
                            <td>{{ $campaign->name }}</td>
                            <td>{{ Str::limit($campaign->description, 70) }}</td>
                            <td>
                                @can('update', $campaign)
                                    <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-sm btn-warning">Edit</a>
                                @endcan
                                @can('delete', $campaign)
                                    <form action="{{ route('campaigns.destroy', $campaign) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this campaign?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No campaigns found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $campaigns->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
