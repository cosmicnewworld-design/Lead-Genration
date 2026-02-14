@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Scoring Rule</h1>
        <form action="{{ route('scoring-rules.update', $scoringRule) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="field">Field</label>
                <input type="text" name="field" id="field" class="form-control" value="{{ $scoringRule->field }}">
            </div>
            <div class="form-group">
                <label for="value">Value</label>
                <input type="text" name="value" id="value" class="form-control" value="{{ $scoringRule->value }}">
            </div>
            <div class="form-group">
                <label for="points">Points</label>
                <input type="number" name="points" id="points" class="form-control" value="{{ $scoringRule->points }}">
            </div>
            <button type="submit" class="btn btn-primary">Update Rule</button>
        </form>
    </div>
@endsection
