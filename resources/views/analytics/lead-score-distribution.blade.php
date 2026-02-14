@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Lead Score Distribution</h1>
        <canvas id="leadScoreChart"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        fetch('/api/analytics/lead-score-distribution')
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('leadScoreChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: Object.keys(data),
                        datasets: [{
                            label: 'Number of Leads',
                            data: Object.values(data),
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
    </script>
@endsection
