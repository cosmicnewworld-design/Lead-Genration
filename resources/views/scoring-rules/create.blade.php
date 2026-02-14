@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Add Scoring Rule</h1>
        <form action="{{ route('scoring-rules.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="field">Field</label>
                <input type="text" name="field" id="field" class="form-control">
            </div>
            <div class="form-group">
                <label for="value">Value</label>
                <input type="text" name="value" id="value" class="form-control">
            </div>
            <div class="form-group">
                <label for="points">Points</label>
                <input type="number" name="points" id="points" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Add Rule</button>
        </form>
    </div>
@endsection
