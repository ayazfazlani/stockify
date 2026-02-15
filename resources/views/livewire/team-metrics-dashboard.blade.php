<div>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <!-- Metrics Controls -->
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
        <div class="p-6">
          <div class="flex justify-between items-center">
            <div class="flex space-x-4">
              <select wire:model="selectedMetric" class="rounded-md border-gray-300 shadow-sm">
                <option value="transactions">Transactions</option>
                <option value="items">Items</option>
                <option value="storage">Storage Usage</option>
                <option value="api_requests">API Requests</option>
              </select>

              <select wire:model="timeframe" class="rounded-md border-gray-300 shadow-sm">
                <option value="7">Last 7 days</option>
                <option value="30">Last 30 days</option>
                <option value="90">Last 90 days</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Metrics Chart -->
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
        <div class="p-6">
          <h3 class="text-lg font-semibold mb-4">{{ ucfirst($selectedMetric) }} Over Time</h3>
          <div class="h-64">
            <!-- Chart container -->
            <div id="metrics-chart" class="w-full h-full"></div>
          </div>
        </div>
      </div>

      <!-- Quotas -->
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6">
          <h3 class="text-lg font-semibold mb-4">Usage Quotas</h3>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($quotas as $quota)
            <div class="bg-gray-50 rounded-lg p-4">
              <div class="flex justify-between items-center mb-2">
                <h4 class="font-medium">{{ ucfirst(str_replace('_', ' ', $quota['name'])) }}</h4>
                <span class="text-sm text-gray-500">
                  {{ number_format($quota['used']) }} / {{ number_format($quota['limit']) }}
                </span>
              </div>

              <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div
                  class="h-2.5 rounded-full {{ $quota['percentage'] > 90 ? 'bg-red-500' : ($quota['percentage'] > 75 ? 'bg-yellow-500' : 'bg-blue-500') }}"
                  style="width: {{ min($quota['percentage'], 100) }}%"></div>
              </div>

              @if($quota['reset_at'])
              <p class="text-sm text-gray-500 mt-2">
                Resets on {{ \Carbon\Carbon::parse($quota['reset_at'])->format('M j, Y') }}
              </p>
              @endif
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('livewire:load', function() {
            let chart = null;

            function initChart(data) {
                const ctx = document.getElementById('metrics-chart').getContext('2d');
                
                if (chart) {
                    chart.destroy();
                }

                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: Object.keys(data),
                        datasets: [{
                            label: '{{ ucfirst($selectedMetric) }}',
                            data: Object.values(data),
                            fill: false,
                            borderColor: 'rgb(59, 130, 246)',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Initialize chart with current data
            initChart(@json($metrics));

            // Update chart when data changes
            Livewire.on('metricsUpdated', data => {
                initChart(data);
            });
        });
  </script>
  @endpush
</div>