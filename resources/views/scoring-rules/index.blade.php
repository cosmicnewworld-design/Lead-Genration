@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Scoring Rules</h1>
        <a href="{{ route('scoring-rules.create') }}" class="btn btn-primary">Add Rule</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Value</th>
                    <th>Points</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rules as $rule)
                    <tr>
                        <td>{{ $rule->field }}</td>
                        <td>{{ $rule->value }}</td>
                        <td>{{ $rule->points }}</td>
                        <td>
                            <a href="{{ route('scoring-rules.edit', $rule) }}" class="btn btn-secondary">Edit</a>
                            <form action="{{ route('scoring-rules.destroy', $rule) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
