{{-- Stockify Dashboard — BoxHero-inspired UI --}}
{{-- Drop-in replacement for your existing dashboard blade --}}

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<style>
  :root {
    --bg:          #F4F5F8;
    --surface:     #FFFFFF;
    --surface-2:   #F9FAFB;
    --border:      #E8EAF0;
    --border-2:    #D1D5E0;
    --text-1:      #0F1117;
    --text-2:      #4B5168;
    --text-3:      #9CA3B8;
    --blue:        #4361EE;
    --blue-soft:   #EEF1FD;
    --blue-mid:    #C7D0FA;
    --green:       #12B76A;
    --green-soft:  #ECFDF5;
    --red:         #F04438;
    --red-soft:    #FEF3F2;
    --amber:       #F79009;
    --amber-soft:  #FFFAEB;
    --purple:      #7C3AED;
    --purple-soft: #F5F3FF;
    --radius-sm:   6px;
    --radius:      10px;
    --radius-lg:   14px;
    --shadow-xs:   0 1px 2px rgba(15,17,23,.04);
    --shadow-sm:   0 1px 3px rgba(15,17,23,.08), 0 1px 2px rgba(15,17,23,.04);
    --shadow-md:   0 4px 8px rgba(15,17,23,.07), 0 2px 4px rgba(15,17,23,.04);
    --font:        'Plus Jakarta Sans', sans-serif;
    --mono:        'JetBrains Mono', monospace;
  }

  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  .db-wrap {
    font-family: var(--font);
    background: var(--bg);
    min-height: 100vh;
    color: var(--text-1);
    padding: 0;
  }

  /* ── Top bar ── */
  .db-topbar {
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    padding: 0 28px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 50;
    box-shadow: var(--shadow-xs);
  }
  .db-topbar-left { display: flex; align-items: center; gap: 10px; }
  .db-logo {
    font-size: 17px;
    font-weight: 800;
    color: var(--blue);
    letter-spacing: -0.4px;
  }
  .db-logo span { color: var(--text-1); }
  .db-divider { width: 1px; height: 20px; background: var(--border); }
  .db-page-title { font-size: 14px; font-weight: 600; color: var(--text-2); }
  .db-topbar-right { display: flex; align-items: center; gap: 12px; }
  .db-date {
    font-size: 12.5px;
    color: var(--text-3);
    font-family: var(--mono);
  }
  .db-live-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    background: var(--green-soft);
    color: var(--green);
    font-size: 11.5px;
    font-weight: 600;
  }
  .db-live-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: var(--green);
    animation: pulse 2s infinite;
  }
  @keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: .4; }
  }

  /* ── Main layout ── */
  .db-main { padding: 24px 28px; max-width: 1320px; margin: 0 auto; }

  /* ── Action bar ── */
  .db-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 24px;
  }
  .db-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 16px;
    border-radius: var(--radius-sm);
    font-size: 13px;
    font-weight: 600;
    font-family: var(--font);
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all .15s ease;
    white-space: nowrap;
    letter-spacing: -0.1px;
  }
  .db-btn:hover { transform: translateY(-1px); box-shadow: var(--shadow-md); }
  .db-btn:active { transform: translateY(0); }
  .db-btn-blue   { background: var(--blue); color: #fff; }
  .db-btn-red    { background: var(--red); color: #fff; }
  .db-btn-amber  { background: var(--amber); color: #fff; }
  .db-btn-indigo { background: #4F46E5; color: #fff; }
  .db-btn-green  { background: var(--green); color: #fff; }

  /* ── Section label ── */
  .db-section-label {
    font-size: 11px;
    font-weight: 700;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-bottom: 12px;
    margin-top: 28px;
  }

  /* ── Stat cards ── */
  .db-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
    gap: 12px;
    margin-bottom: 0;
  }
  .db-stat-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 18px 20px;
    box-shadow: var(--shadow-xs);
    position: relative;
    overflow: hidden;
    transition: box-shadow .15s;
  }
  .db-stat-card:hover { box-shadow: var(--shadow-md); }
  .db-stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: var(--radius-lg) var(--radius-lg) 0 0;
  }
  .db-stat-card.blue::before  { background: var(--blue); }
  .db-stat-card.green::before { background: var(--green); }
  .db-stat-card.amber::before { background: var(--amber); }
  .db-stat-card.purple::before { background: var(--purple); }

  .db-stat-icon {
    width: 36px; height: 36px;
    border-radius: var(--radius-sm);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px;
    margin-bottom: 14px;
  }
  .db-stat-icon.blue   { background: var(--blue-soft);   color: var(--blue); }
  .db-stat-icon.green  { background: var(--green-soft);  color: var(--green); }
  .db-stat-icon.amber  { background: var(--amber-soft);  color: var(--amber); }
  .db-stat-icon.purple { background: var(--purple-soft); color: var(--purple); }

  .db-stat-label { font-size: 12px; font-weight: 500; color: var(--text-3); margin-bottom: 4px; }
  .db-stat-value {
    font-size: 26px;
    font-weight: 800;
    color: var(--text-1);
    letter-spacing: -1px;
    line-height: 1;
    font-family: var(--mono);
  }
  .db-stat-value.green { color: var(--green); }

  /* ── Charts row ── */
  .db-charts {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
  }
  @media (max-width: 768px) { .db-charts { grid-template-columns: 1fr; } }

  .db-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-xs);
    overflow: hidden;
    transition: box-shadow .15s;
  }
  .db-card:hover { box-shadow: var(--shadow-md); }
  .db-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
  }
  .db-card-title { font-size: 14px; font-weight: 700; color: var(--text-1); }
  .db-card-tag {
    font-size: 11px;
    font-weight: 600;
    color: var(--text-3);
    background: var(--surface-2);
    border: 1px solid var(--border);
    padding: 3px 8px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: .04em;
  }
  .db-card-body { padding: 16px 20px; }
  .db-chart-wrap { height: 220px; position: relative; }

  /* ── Two-col row ── */
  .db-two-col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
  }
  @media (max-width: 900px) { .db-two-col { grid-template-columns: 1fr; } }

  /* ── Table ── */
  .db-table { width: 100%; border-collapse: collapse; font-size: 13px; }
  .db-table th {
    text-align: left;
    padding: 8px 12px;
    font-size: 11px;
    font-weight: 700;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .06em;
    border-bottom: 1px solid var(--border);
    background: var(--surface-2);
  }
  .db-table td {
    padding: 11px 12px;
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
  }
  .db-table tr:last-child td { border-bottom: none; }
  .db-table tr:hover td { background: var(--surface-2); }
  .db-item-name { font-weight: 600; color: var(--text-1); font-size: 13px; }
  .db-item-meta { font-size: 11px; color: var(--text-3); margin-top: 1px; font-family: var(--mono); }
  .db-margin-pct { font-weight: 700; color: var(--text-1); }
  .db-profit { font-weight: 700; color: var(--green); font-family: var(--mono); }

  /* ── Audit trail ── */
  .db-audit-list { display: flex; flex-direction: column; gap: 6px; max-height: 280px; overflow-y: auto; }
  .db-audit-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 12px;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    background: var(--surface-2);
    transition: background .1s;
  }
  .db-audit-item:hover { background: var(--blue-soft); border-color: var(--blue-mid); }
  .db-audit-action {
    display: inline-block;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: .04em;
    margin-left: 6px;
  }
  .db-audit-action.in  { background: var(--green-soft); color: var(--green); }
  .db-audit-action.out { background: var(--red-soft); color: var(--red); }
  .db-audit-action.adj { background: var(--amber-soft); color: var(--amber); }
  .db-audit-qty {
    font-family: var(--mono);
    font-size: 12px;
    font-weight: 600;
    color: var(--text-2);
    text-align: right;
  }
  .db-audit-who { font-size: 11px; color: var(--text-3); }

  /* ── Low stock ── */
  .db-low-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 10px;
  }
  .db-low-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 14px;
    border: 1px solid #FECDC9;
    border-radius: var(--radius);
    background: var(--red-soft);
    transition: all .15s;
  }
  .db-low-card:hover { border-color: var(--red); box-shadow: var(--shadow-sm); }
  .db-low-card-left {}
  .db-low-name { font-size: 13px; font-weight: 700; color: var(--text-1); }
  .db-low-sku  { font-size: 11px; color: var(--text-3); font-family: var(--mono); margin-top: 1px; }
  .db-low-reorder { font-size: 11px; color: var(--text-3); margin-top: 3px; }
  .db-low-qty {
    font-size: 22px;
    font-weight: 800;
    color: var(--red);
    font-family: var(--mono);
    letter-spacing: -1px;
  }
  .db-low-qty-label { font-size: 10px; color: var(--red); font-weight: 600; text-align: right; }

  .db-empty {
    text-align: center;
    padding: 40px 20px;
    color: var(--text-3);
    font-size: 13px;
  }
  .db-empty i { font-size: 32px; display: block; margin-bottom: 8px; color: var(--border-2); }

  /* scrollbar */
  .db-audit-list::-webkit-scrollbar { width: 4px; }
  .db-audit-list::-webkit-scrollbar-track { background: transparent; }
  .db-audit-list::-webkit-scrollbar-thumb { background: var(--border-2); border-radius: 2px; }
</style>

<div class="db-wrap">

  {{-- Top Bar --}}
  <div class="db-topbar">
    <div class="db-topbar-left">
      <div class="db-logo">Stock<span>ify</span></div>
      <div class="db-divider"></div>
      <div class="db-page-title">Operational Dashboard</div>
    </div>
    <div class="db-topbar-right">
      <span class="db-date">{{ \Carbon\Carbon::now()->format('D, M j Y') }}</span>
      <span class="db-live-badge">
        <span class="db-live-dot"></span>
        Live
      </span>
    </div>
  </div>

  <div class="db-main">

    {{-- Action Buttons --}}
    <div class="db-actions">
      <a href="{{ route('tenant.stock-in', ['tenant' => tenant('slug')]) }}" class="db-btn db-btn-blue">
        <i class='bx bx-plus-circle'></i> Stock In
      </a>
      <a href="{{ route('tenant.stock-out', ['tenant' => tenant('slug')]) }}" class="db-btn db-btn-red">
        <i class='bx bx-minus-circle'></i> Stock Out
      </a>
      <a href="{{ route('tenant.adjust', ['tenant' => tenant('slug')]) }}" class="db-btn db-btn-amber">
        <i class='bx bx-slider'></i> Adjust
      </a>
      <a href="{{ route('tenant.purchase-orders', ['tenant' => tenant('slug')]) }}" class="db-btn db-btn-indigo">
        <i class='bx bx-file'></i> Purchase Order
      </a>
      <button wire:click="sendLowStockAlerts" class="db-btn db-btn-green">
        <i class='bx bx-bell'></i> Send Alerts
      </button>
    </div>

    {{-- KPI Cards --}}
    <div class="db-section-label">Overview</div>
    <div class="db-stats">

      <div class="db-stat-card blue">
        <div class="db-stat-icon blue"><i class='bx bx-box'></i></div>
        <div class="db-stat-label">Total Units</div>
        <div class="db-stat-value">{{ number_format($summary['totalInventory']) }}</div>
      </div>

      @feature('analytics')
      <div class="db-stat-card green">
        <div class="db-stat-icon green"><i class='bx bx-coin-stack'></i></div>
        <div class="db-stat-label">Inventory Equity</div>
        <div class="db-stat-value">{{ config('app.currency_symbol') }}{{ number_format($summary['inventoryEquity'], 0) }}</div>
      </div>

      <div class="db-stat-card amber">
        <div class="db-stat-icon amber"><i class='bx bx-trending-up'></i></div>
        <div class="db-stat-label">Potential Revenue</div>
        <div class="db-stat-value">{{ config('app.currency_symbol') }}{{ number_format($summary['potentialRevenue'], 0) }}</div>
      </div>

      <div class="db-stat-card purple">
        <div class="db-stat-icon purple"><i class='bx bx-badge-check'></i></div>
        <div class="db-stat-label">Expected Profit</div>
        <div class="db-stat-value green">{{ config('app.currency_symbol') }}{{ number_format($summary['potentialProfit'], 0) }}</div>
      </div>
      @endfeature

    </div>

    {{-- Charts --}}
    @feature('analytics')
    <div class="db-section-label">Analytics</div>
    <div class="db-charts">

      <div class="db-card">
        <div class="db-card-head">
          <span class="db-card-title">Stock Flow</span>
          <span class="db-card-tag">Inventory · In · Out</span>
        </div>
        <div class="db-card-body">
          <div class="db-chart-wrap">
            <canvas id="stockFlowChart"></canvas>
          </div>
        </div>
      </div>

      <div class="db-card">
        <div class="db-card-head">
          <span class="db-card-title">Top Brands</span>
          <span class="db-card-tag">Distribution</span>
        </div>
        <div class="db-card-body">
          <div class="db-chart-wrap">
            <canvas id="topBrandsChart"></canvas>
          </div>
        </div>
      </div>

    </div>
    @endfeature

    {{-- Profit Leaders + Audit Trail --}}
    <div class="db-section-label">Details</div>
    <div class="db-two-col">

      @feature('analytics')
      <div class="db-card">
        <div class="db-card-head">
          <span class="db-card-title">Profit & Margin Leaders</span>
          <span class="db-card-tag">Top items</span>
        </div>
        <div class="db-card-body" style="padding:0">
          <table class="db-table">
            <thead>
              <tr>
                <th>Item</th>
                <th>Margin</th>
                <th>Profit Pool</th>
              </tr>
            </thead>
            <tbody>
              @forelse($marginLeaders as $leader)
              <tr>
                <td>
                  <div class="db-item-name">{{ $leader['name'] }}</div>
                  <div class="db-item-meta">{{ $leader['sku'] }} · Qty {{ $leader['qty'] }}</div>
                </td>
                <td>
                  <span class="db-margin-pct">{{ number_format($leader['margin_pct'], 1) }}%</span>
                </td>
                <td>
                  <span class="db-profit">{{ config('app.currency_symbol') }}{{ number_format($leader['profit_pool'], 0) }}</span>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="3">
                  <div class="db-empty">
                    <i class='bx bx-bar-chart-alt-2'></i>
                    No margin data yet.
                  </div>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      @endfeature

      @feature('audit-log')
      <div class="db-card">
        <div class="db-card-head">
          <span class="db-card-title">Audit Trail</span>
          <span class="db-card-tag">Recent activity</span>
        </div>
        <div class="db-card-body">
          <div class="db-audit-list">
            @forelse($recentAudits as $audit)
            <div class="db-audit-item">
              <div>
                <div style="display:flex;align-items:center;gap:0">
                  <span style="font-size:13px;font-weight:600;color:var(--text-1)">{{ $audit->item?->name ?? 'Item' }}</span>
                  <span class="db-audit-action {{ strtolower($audit->action) === 'in' ? 'in' : (strtolower($audit->action) === 'out' ? 'out' : 'adj') }}">
                    {{ strtoupper($audit->action) }}
                  </span>
                </div>
                <div class="db-audit-who">{{ $audit->user?->name ?? 'System' }} · {{ $audit->created_at?->diffForHumans() }}</div>
              </div>
              <div class="db-audit-qty">
                {{ $audit->before_qty }} → {{ $audit->after_qty }}
                <div class="db-audit-who" style="text-align:right">{{ $audit->reason ?: '—' }}</div>
              </div>
            </div>
            @empty
            <div class="db-empty">
              <i class='bx bx-history'></i>
              No audit events yet.
            </div>
            @endforelse
          </div>
        </div>
      </div>
      @endfeature

    </div>

    {{-- Low Stock --}}
    <div class="db-section-label" style="margin-top:28px">
      <i class='bx bx-error-circle' style="color:var(--red);vertical-align:middle;margin-right:4px"></i>
      Low Stock Alerts
    </div>
    <div class="db-card" style="margin-bottom:32px">
      <div class="db-card-body">
        <div class="db-low-grid">
          @forelse($lowStockItems as $itemStat)
          <div class="db-low-card">
            <div class="db-low-card-left">
              <div class="db-low-name">{{ $itemStat->name ?? 'Unknown' }}</div>
              <div class="db-low-sku">{{ $itemStat->sku }}</div>
              <div class="db-low-reorder">Reorder: {{ $itemStat->reorder_quantity }}</div>
            </div>
            <div style="text-align:right">
              <div class="db-low-qty">{{ $itemStat->quantity }}</div>
              <div class="db-low-qty-label">left</div>
            </div>
          </div>
          @empty
          <div class="db-empty" style="grid-column:1/-1">
            <i class='bx bx-check-shield' style="color:var(--green)"></i>
            All stock levels are healthy.
          </div>
          @endforelse
        </div>
      </div>
    </div>

  </div>{{-- /db-main --}}
</div>{{-- /db-wrap --}}

@script
<script>
(() => {
    const chartState = window.stockifyDashboardCharts || { stockFlow: null, topBrands: null };
    window.stockifyDashboardCharts = chartState;

    const summary   = JSON.parse(@js($summaryJson)   || '{}');
    const stockFlow = JSON.parse(@js($stockFlowJson) || '{}');
    const topBrands = JSON.parse(@js($topBrandsJson) || '[]');

    const palette = ['#4361EE','#12B76A','#7C3AED','#F79009','#F04438','#06B6D4','#EC4899','#84CC16'];

    const renderCharts = () => {
        if (typeof Chart === 'undefined') return;

        const sfCanvas = document.getElementById('stockFlowChart');
        const tbCanvas = document.getElementById('topBrandsChart');
        if (!sfCanvas || !tbCanvas) return;

        if (chartState.stockFlow)  chartState.stockFlow.destroy();
        if (chartState.topBrands) chartState.topBrands.destroy();

        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";

        chartState.stockFlow = new Chart(sfCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: stockFlow.labels || ['Total Inventory', 'Stock In', 'Stock Out'],
                datasets: [{
                    data: stockFlow.values || [
                        Number(summary.totalInventory || 0),
                        Number(summary.stockIn || 0),
                        Number(summary.stockOut || 0),
                    ],
                    backgroundColor: ['rgba(67,97,238,.12)', 'rgba(18,183,106,.12)', 'rgba(240,68,56,.12)'],
                    borderColor:     ['#4361EE', '#12B76A', '#F04438'],
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#9CA3B8', font: { size: 11 } },
                        grid: { color: 'rgba(209,213,224,.4)' },
                        border: { display: false }
                    },
                    x: {
                        ticks: { color: '#4B5168', font: { size: 12, weight: '600' } },
                        grid: { display: false },
                        border: { display: false }
                    }
                }
            }
        });

        const hasBrands = Array.isArray(topBrands) && topBrands.length > 0;
        chartState.topBrands = new Chart(tbCanvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels:   hasBrands ? topBrands.map(b => b.brand)           : ['No Data'],
                datasets: [{ 
                    data:            hasBrands ? topBrands.map(b => Number(b.count || 0)) : [1],
                    backgroundColor: hasBrands ? topBrands.map((_,i) => palette[i % palette.length]) : ['#E8EAF0'],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#4B5168', boxWidth: 10, padding: 14, font: { size: 12 } }
                    }
                },
                cutout: '65%'
            }
        });
    };

    const load = () => {
        if (typeof Chart !== 'undefined') { renderCharts(); return; }
        const s = document.createElement('script');
        s.src = 'https://cdn.jsdelivr.net/npm/chart.js';
        s.onload = renderCharts;
        document.head.appendChild(s);
    };

    load();
    document.addEventListener('livewire:navigated', load);
    if (window.Livewire?.hook) {
        window.Livewire.hook('message.processed', () => load());
    }
})();
</script>
@endscript