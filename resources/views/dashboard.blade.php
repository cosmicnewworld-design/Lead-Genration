<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <!-- Main Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                        <!-- KPI: Total Leads -->
                        <div class="bg-blue-500 text-white rounded-lg shadow-lg p-6">
                            <h3 class="text-lg font-semibold mb-2">Total Leads</h3>
                            <p class="text-4xl font-bold">{{ $totalLeads }}</p>
                        </div>

                        <!-- KPI: Hot Leads -->
                        <div class="bg-red-500 text-white rounded-lg shadow-lg p-6">
                            <h3 class="text-lg font-semibold mb-2">Hot Leads</h3>
                            <p class="text-4xl font-bold">{{ $hotLeadsCount }}</p>
                        </div>
                        
                        <!-- Chart: Lead Acquisition Trend (Full Width on Mobile) -->
                        <div class="md:col-span-2 lg:col-span-3 bg-gray-50 rounded-lg shadow p-4">
                            <h3 class="text-lg font-semibold mb-4 text-center">Lead Acquisition Trend (Last 30 Days)</h3>
                            <canvas id="leadTrendChart"></canvas>
                        </div>

                        <!-- Chart: Lead Status Distribution -->
                        <div class="bg-gray-50 rounded-lg shadow p-4">
                            <h3 class="text-lg font-semibold mb-4 text-center">Lead Status Distribution</h3>
                            <canvas id="statusDistributionChart"></canvas>
                        </div>

                        <!-- Chart: Top Lead Sources -->
                        <div class="md:col-span-2 bg-gray-50 rounded-lg shadow p-4">
                             <h3 class="text-lg font-semibold mb-4 text-center">Top 5 Lead Sources</h3>
                            <canvas id="topSourcesChart"></canvas>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Lead Trend Chart (Line)
            const trendCtx = document.getElementById('leadTrendChart').getContext('2d');
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: Object.keys(@json($trendData)),
                    datasets: [{
                        label: 'New Leads',
                        data: Object.values(@json($trendData)),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        tension: 0.4
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // 2. Status Distribution Chart (Doughnut)
            const statusCtx = document.getElementById('statusDistributionChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(@json($statusDistribution)),
                    datasets: [{
                        data: Object.values(@json($statusDistribution)),
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)', 
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)', 
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // 3. Top Sources Chart (Bar)
            const sourcesCtx = document.getElementById('topSourcesChart').getContext('2d');
            new Chart(sourcesCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(@json($topSources)),
                    datasets: [{
                        label: 'Lead Count',
                        data: Object.values(@json($topSources)),
                        backgroundColor: 'rgba(75, 192, 192, 0.7)'
                    }]
                },
                options: {
                     indexAxis: 'y', // Horizontal Bar Chart
                     scales: {
                        x: {
                            beginAtZero: true
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
