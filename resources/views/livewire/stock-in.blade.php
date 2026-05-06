{{-- ============================================================
     Stockify — Stock In  |  BoxHero-style  |  FULLY WORKING
     ============================================================ --}}


<div data-si>

<style>
/* ── Root variables ── */
[data-si] {
  --si-bg:       #F4F5F8;
  --si-surface:  #FFFFFF;
  --si-s2:       #F9FAFB;
  --si-border:   #E8EAF0;
  --si-border2:  #D1D5E0;
  --si-focus:    #4361EE;
  --si-t1:       #0F1117;
  --si-t2:       #4B5168;
  --si-t3:       #9CA3B8;
  --si-blue:     #4361EE;
  --si-blue-s:   #EEF1FD;
  --si-blue-m:   #C7D0FA;
  --si-green:    #12B76A;
  --si-green-s:  #ECFDF5;
  --si-green-m:  #A7F3D0;
  --si-red:      #F04438;
  --si-red-s:    #FEF3F2;
  --si-amber:    #F79009;
  --si-amber-s:  #FFFAEB;
  --si-r-sm:     6px;
  --si-r:        10px;
  --si-r-lg:     14px;
  --si-r-xl:     18px;
  --si-xs:       0 1px 2px rgba(15,17,23,.04);
  --si-sm:       0 1px 3px rgba(15,17,23,.08),0 1px 2px rgba(15,17,23,.04);
  --si-md:       0 4px 12px rgba(15,17,23,.10),0 2px 4px rgba(15,17,23,.04);
  --si-lg:       0 16px 40px rgba(15,17,23,.16),0 4px 8px rgba(15,17,23,.06);
  --si-font:     'Plus Jakarta Sans', sans-serif;
  --si-mono:     'JetBrains Mono', monospace;
  --si-ease:     all .15s ease;

  font-family: var(--si-font);
  background:   var(--si-bg);
  min-height:   100vh;
  color:        var(--si-t1);
}

[data-si] *, [data-si] *::before, [data-si] *::after {
  box-sizing: border-box;
}

/* ── Topbar ── */
[data-si] .si-bar {
  background: var(--si-surface);
  border-bottom: 1px solid var(--si-border);
  padding: 0 18px;
  height: 56px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: sticky;
  top: 0;
  z-index: 30;
  box-shadow: var(--si-xs);
}
[data-si] .si-bar-title {
  font-size: 15px;
  font-weight: 700;
  color: var(--si-t1);
  display: flex;
  align-items: center;
  gap: 7px;
}
[data-si] .si-bar-title i { color: var(--si-blue); font-size: 20px; }
[data-si] .si-bar-right { display: flex; gap: 8px; }

/* ── Buttons ── */
[data-si] .si-btn {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 8px 14px;
  border-radius: var(--si-r-sm);
  font-size: 13px;
  font-weight: 600;
  font-family: var(--si-font);
  border: none;
  cursor: pointer;
  transition: var(--si-ease);
  text-decoration: none;
  line-height: 1;
  white-space: nowrap;
}
[data-si] .si-btn:hover  { transform: translateY(-1px); box-shadow: var(--si-md); }
[data-si] .si-btn:active { transform: translateY(0); box-shadow: none; }
[data-si] .si-btn:disabled { opacity: .55; cursor: not-allowed; transform: none; box-shadow: none; }
[data-si] .si-btn-ghost { background: var(--si-s2); color: var(--si-t2); border: 1px solid var(--si-border); }
[data-si] .si-btn-ghost:hover { background: var(--si-border); box-shadow: none; }
[data-si] .si-btn-blue  { background: var(--si-blue);  color: #fff; }
[data-si] .si-btn-green { background: var(--si-green); color: #fff; }
[data-si] .si-btn-sm    { padding: 6px 11px; font-size: 12px; }

/* ── Page body ── */
[data-si] .si-body {
  padding: 14px 16px;
  max-width: 1300px;
  margin: 0 auto;
}
@media (min-width: 768px) {
  [data-si] .si-body { padding: 20px 28px; }
}

/* ── Flash alerts ── */
[data-si] .si-alerts { display: flex; flex-direction: column; gap: 7px; margin-bottom: 14px; }
[data-si] .si-alert {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 13px;
  border-radius: var(--si-r);
  font-size: 13px;
  font-weight: 500;
  animation: si-in .2s ease;
}
@keyframes si-in { from { opacity:0; transform:translateY(-4px); } to { opacity:1; transform:none; } }
[data-si] .si-alert-ok  { background: var(--si-green-s); color: #065F46; border: 1px solid var(--si-green-m); }
[data-si] .si-alert-inf { background: var(--si-blue-s);  color: #1E3A8A; border: 1px solid var(--si-blue-m); }
[data-si] .si-alert-err { background: var(--si-red-s);   color: #991B1B; border: 1px solid #FECACA; }

/* ── Scanner card ── */
[data-si] .si-scan-card {
  background: var(--si-surface);
  border: 1px solid var(--si-border);
  border-radius: var(--si-r-lg);
  padding: 13px 15px;
  margin-bottom: 13px;
}
[data-si] .si-scan-label {
  font-size: 11px;
  font-weight: 700;
  color: var(--si-t3);
  text-transform: uppercase;
  letter-spacing: .07em;
  margin-bottom: 9px;
  display: flex;
  align-items: center;
  gap: 5px;
}
[data-si] .si-notice {
  display: flex;
  align-items: flex-start;
  gap: 9px;
  background: var(--si-amber-s);
  border: 1px solid #FDE68A;
  border-radius: var(--si-r);
  padding: 10px 13px;
  font-size: 13px;
  color: #92400E;
  margin-bottom: 13px;
}
[data-si] .si-notice i { font-size: 17px; flex-shrink: 0; margin-top: 1px; }

/* ── Filters ── */
[data-si] .si-filters {
  display: flex;
  gap: 7px;
  margin-bottom: 13px;
  flex-wrap: wrap;
  align-items: stretch;
}
[data-si] .si-search-wrap {
  flex: 1;
  min-width: 170px;
  position: relative;
}
[data-si] .si-search-wrap > i {
  position: absolute;
  left: 10px; top: 50%;
  transform: translateY(-50%);
  color: var(--si-t3);
  font-size: 16px;
  pointer-events: none;
}
[data-si] .si-input {
  display: block;
  width: 100%;
  padding: 9px 11px 9px 33px;
  border: 1.5px solid var(--si-border);
  border-radius: var(--si-r-sm);
  font-size: 13.5px;
  font-family: var(--si-font);
  color: var(--si-t1);
  background: var(--si-surface);
  outline: none;
  transition: var(--si-ease);
  height: 40px;
}
[data-si] .si-input:focus {
  border-color: var(--si-focus);
  box-shadow: 0 0 0 3px rgba(67,97,238,.1);
}
[data-si] .si-input::placeholder { color: var(--si-t3); }

[data-si] .si-daterange {
  display: flex;
  align-items: center;
  gap: 5px;
  background: var(--si-surface);
  border: 1.5px solid var(--si-border);
  border-radius: var(--si-r-sm);
  padding: 0 10px;
  height: 40px;
}
[data-si] .si-daterange > i   { color: var(--si-t3); font-size: 15px; }
[data-si] .si-daterange > span{ font-size: 11px; color: var(--si-t3); }
[data-si] .si-daterange input  {
  border: none; outline: none;
  background: transparent;
  font-size: 12px;
  font-family: var(--si-mono);
  color: var(--si-t2);
  width: 106px;
  padding: 0;
}

/* ── Two-column grid ── */
[data-si] .si-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 13px;
}
@media (min-width: 1024px) {
  [data-si] .si-grid { grid-template-columns: 1fr 1fr; }
}

/* ── Card ── */
[data-si] .si-card {
  background: var(--si-surface);
  border: 1px solid var(--si-border);
  border-radius: var(--si-r-lg);
  box-shadow: var(--si-xs);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}
[data-si] .si-card-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 15px;
  border-bottom: 1px solid var(--si-border);
  background: var(--si-s2);
  flex-shrink: 0;
}
[data-si] .si-card-title { font-size: 13.5px; font-weight: 700; color: var(--si-t1); }
[data-si] .si-card-body  { padding: 12px 14px; flex: 1; }
[data-si] .si-card-body.np { padding: 0; }

/* ── Item list ── */
[data-si] .si-item-list {
  display: flex;
  flex-direction: column;
  gap: 5px;
  max-height: 360px;
  overflow-y: auto;
}
[data-si] .si-item-list::-webkit-scrollbar { width: 3px; }
[data-si] .si-item-list::-webkit-scrollbar-thumb { background: var(--si-border2); border-radius: 2px; }

[data-si] .si-item {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 9px 10px;
  border: 1.5px solid var(--si-border);
  border-radius: var(--si-r);
  cursor: pointer;
  transition: var(--si-ease);
  background: var(--si-surface);
}
[data-si] .si-item:hover  { border-color: var(--si-blue-m); background: var(--si-blue-s); }
[data-si] .si-item.on     { border-color: var(--si-blue);   background: var(--si-blue-s); }

[data-si] .si-chk {
  width: 18px; height: 18px;
  border-radius: 50%;
  border: 2px solid var(--si-border2);
  background: var(--si-surface);
  flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  transition: var(--si-ease);
}
[data-si] .si-item.on .si-chk { background: var(--si-blue); border-color: var(--si-blue); }
[data-si] .si-item.on .si-chk::after {
  content: '';
  display: block;
  width: 5px; height: 9px;
  border: 2px solid #fff;
  border-top: none; border-left: none;
  transform: rotate(45deg) translateY(-1px);
}

[data-si] .si-thumb {
  width: 34px; height: 34px;
  border-radius: var(--si-r-sm);
  object-fit: cover;
  border: 1px solid var(--si-border);
  flex-shrink: 0;
}
[data-si] .si-thumb-ph {
  width: 34px; height: 34px;
  border-radius: var(--si-r-sm);
  background: var(--si-s2);
  border: 1px solid var(--si-border);
  display: flex; align-items: center; justify-content: center;
  color: var(--si-t3);
  font-size: 17px;
  flex-shrink: 0;
}
[data-si] .si-item-info { flex: 1; min-width: 0; }
[data-si] .si-item-name { font-size: 13px; font-weight: 600; color: var(--si-t1); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
[data-si] .si-item-sku  { font-size: 11px; color: var(--si-t3); font-family: var(--si-mono); margin-top: 1px; }

[data-si] .si-pill {
  font-size: 11.5px; font-weight: 700;
  font-family: var(--si-mono);
  padding: 3px 8px;
  border-radius: 20px;
  flex-shrink: 0;
}
[data-si] .si-pill.ok  { background: var(--si-green-s); color: var(--si-green); }
[data-si] .si-pill.low { background: var(--si-red-s);   color: var(--si-red);   }

/* ── Selected section ── */
[data-si] .si-sel-section { border-top: 1px solid var(--si-border); padding: 11px 14px 0; }
[data-si] .si-sublabel {
  font-size: 11px; font-weight: 700;
  color: var(--si-t3);
  text-transform: uppercase;
  letter-spacing: .07em;
  margin-bottom: 7px;
  display: flex; align-items: center; gap: 5px;
}
[data-si] .si-sublabel i { color: var(--si-blue); font-size: 13px; }
[data-si] .si-sel-list { display: flex; flex-direction: column; gap: 5px; }
[data-si] .si-sel-row {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 9px 10px;
  border: 1px solid var(--si-border);
  border-radius: var(--si-r);
  background: var(--si-s2);
}
[data-si] .si-qty-box {
  width: 66px;
  padding: 6px 7px;
  border: 1.5px solid var(--si-border);
  border-radius: var(--si-r-sm);
  font-size: 14px; font-weight: 600;
  font-family: var(--si-mono);
  text-align: center;
  background: var(--si-surface);
  color: var(--si-t1);
  outline: none;
  transition: var(--si-ease);
  flex-shrink: 0;
}
[data-si] .si-qty-box:focus {
  border-color: var(--si-focus);
  box-shadow: 0 0 0 3px rgba(67,97,238,.1);
}

/* ── Cart footer ── */
[data-si] .si-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  padding: 11px 14px;
  border-top: 1px solid var(--si-border);
  background: var(--si-surface);
  flex-shrink: 0;
}
[data-si] .si-footer-meta { font-size: 12px; color: var(--si-t3); }
[data-si] .si-footer-meta b { color: var(--si-t1); font-weight: 700; }

/* ── Table ── */
[data-si] .si-tbl-wrap { overflow-x: auto; }
[data-si] .si-tbl { width: 100%; border-collapse: collapse; min-width: 360px; }
[data-si] .si-tbl th {
  padding: 9px 13px;
  text-align: left;
  font-size: 10.5px; font-weight: 700;
  color: var(--si-t3);
  text-transform: uppercase;
  letter-spacing: .07em;
  background: var(--si-s2);
  border-bottom: 1px solid var(--si-border);
  white-space: nowrap;
}
[data-si] .si-tbl td {
  padding: 10px 13px;
  border-bottom: 1px solid var(--si-border);
  font-size: 13px;
  vertical-align: middle;
}
[data-si] .si-tbl tr:last-child td { border-bottom: none; }
[data-si] .si-tbl tbody tr:hover td { background: var(--si-s2); }
[data-si] .td-date  { font-family: var(--si-mono); font-size: 11.5px; color: var(--si-t3); white-space: nowrap; }
[data-si] .td-name  { font-weight: 600; color: var(--si-t1); }
[data-si] .td-qty   { font-weight: 700; color: var(--si-green); font-family: var(--si-mono); }
[data-si] .td-price { font-family: var(--si-mono); font-size: 12.5px; color: var(--si-t2); }
[data-si] .si-tbl-empty { text-align: center; padding: 36px 20px; color: var(--si-t3); font-size: 13px; }
[data-si] .si-tbl-empty i { font-size: 28px; display: block; margin-bottom: 7px; color: var(--si-border2); }

/* ── Empty ── */
[data-si] .si-empty { text-align: center; padding: 28px; color: var(--si-t3); font-size: 13px; }
[data-si] .si-empty i { font-size: 30px; display: block; margin-bottom: 7px; color: var(--si-border2); }

/* ══════════════════
   MODAL - WORKING FIX
══════════════════ */
[data-si] .si-overlay {
  position: fixed;
  inset: 0;
  background: rgba(10,12,20,.54);
  backdrop-filter: blur(3px);
  display: flex;
  align-items: flex-end;
  justify-content: center;
  z-index: 9999;
  padding: 0;
  animation: si-fade .18s ease;
}
@keyframes si-fade { from { opacity:0; } to { opacity:1; } }
@media (min-width: 640px) {
  [data-si] .si-overlay { align-items: center; padding: 20px; }
}

[data-si] .si-modal {
  background: var(--si-surface);
  width: 100%;
  max-width: 500px;
  max-height: 94vh;
  border-radius: var(--si-r-xl) var(--si-r-xl) 0 0;
  display: flex;
  flex-direction: column;
  box-shadow: var(--si-lg);
  animation: si-up .22s ease;
  overflow: hidden;
}
@keyframes si-up {
  from { transform: translateY(16px); opacity: 0; }
  to   { transform: translateY(0);    opacity: 1; }
}
@media (min-width: 640px) {
  [data-si] .si-modal { border-radius: var(--si-r-xl); }
}

[data-si] .si-modal-handle {
  width: 32px; height: 4px;
  background: var(--si-border2);
  border-radius: 2px;
  margin: 10px auto 2px;
  display: block;
  flex-shrink: 0;
}
@media (min-width: 640px) {
  [data-si] .si-modal-handle { display: none; }
}

[data-si] .si-modal-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 13px 18px;
  border-bottom: 1px solid var(--si-border);
  flex-shrink: 0;
}
[data-si] .si-modal-title {
  font-size: 15px;
  font-weight: 700;
  display: flex; align-items: center; gap: 7px;
}
[data-si] .si-modal-title i { color: var(--si-blue); font-size: 18px; }
[data-si] .si-modal-x {
  width: 30px; height: 30px;
  border-radius: var(--si-r-sm);
  border: 1px solid var(--si-border);
  background: var(--si-s2);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  color: var(--si-t2);
  font-size: 18px;
  transition: var(--si-ease);
  flex-shrink: 0;
}
[data-si] .si-modal-x:hover { background: var(--si-red-s); color: var(--si-red); border-color: #FECACA; }

[data-si] .si-modal-body {
  flex: 1;
  overflow-y: auto;
  padding: 15px 18px;
}

[data-si] .si-modal-foot {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 8px;
  padding: 11px 18px;
  border-top: 1px solid var(--si-border);
  background: var(--si-s2);
  flex-shrink: 0;
}

/* ── Form fields ── */
[data-si] .si-field { margin-bottom: 12px; }
[data-si] .si-field:last-child { margin-bottom: 0; }
[data-si] .si-label {
  display: block;
  font-size: 12px;
  font-weight: 600;
  color: var(--si-t2);
  margin-bottom: 5px;
}
[data-si] .si-label .opt { font-weight: 400; color: var(--si-t3); }
[data-si] .si-finput {
  display: block;
  width: 100%;
  padding: 9px 12px;
  border: 1.5px solid var(--si-border);
  border-radius: var(--si-r-sm);
  font-size: 13.5px;
  font-family: var(--si-font);
  color: var(--si-t1);
  background: var(--si-surface);
  outline: none;
  transition: var(--si-ease);
  margin: 0;
}
[data-si] .si-finput:focus {
  border-color: var(--si-focus);
  box-shadow: 0 0 0 3px rgba(67,97,238,.1);
}
[data-si] .si-finput::placeholder { color: var(--si-t3); }
[data-si] .si-finput.mono { font-family: var(--si-mono); }

[data-si] .si-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
[data-si] .si-hint { font-size: 11px; color: var(--si-t3); margin-top: 4px; line-height: 1.5; }
[data-si] .si-ferr { font-size: 11px; color: var(--si-red); margin-top: 3px; display: flex; align-items: center; gap: 3px; }

[data-si] .si-fgroup {
  border: 1.5px solid var(--si-border);
  border-radius: var(--si-r);
  padding: 12px;
  background: var(--si-s2);
  margin-bottom: 12px;
}
[data-si] .si-fgroup-lbl {
  font-size: 12px; font-weight: 700;
  color: var(--si-t2);
  margin-bottom: 9px;
  display: flex; align-items: center; gap: 5px;
}
[data-si] .si-fgroup-lbl i { font-size: 15px; }

[data-si] .si-btn-row { display: flex; gap: 6px; align-items: center; }
[data-si] .si-btn-row .si-finput { flex: 1; }

[data-si] .si-scanner-inner {
  margin-top: 10px;
  padding-top: 10px;
  border-top: 1px solid var(--si-border);
}

/* ── Serial tags ── */
[data-si] .si-serial-box {
  background: var(--si-blue-s);
  border: 1px solid var(--si-blue-m);
  border-radius: var(--si-r);
  padding: 9px;
  margin-top: 8px;
}
[data-si] .si-serial-count {
  font-size: 11px; font-weight: 700;
  color: var(--si-blue);
  margin-bottom: 7px;
  display: flex; align-items: center; gap: 4px;
}
[data-si] .si-serial-tags { display: flex; flex-wrap: wrap; gap: 5px; }
[data-si] .si-serial-tag {
  display: inline-flex; align-items: center; gap: 4px;
  background: var(--si-surface);
  border: 1px solid var(--si-blue-m);
  border-radius: 4px;
  padding: 3px 8px;
  font-size: 11.5px;
  font-family: var(--si-mono);
  color: var(--si-t1);
}
[data-si] .si-serial-tag button {
  background: none; border: none;
  cursor: pointer;
  color: var(--si-t3); font-size: 15px; line-height: 1;
  padding: 0;
}
[data-si] .si-serial-tag button:hover { color: var(--si-red); }

/* ── Image upload ── */
[data-si] .si-img-prev {
  width: 54px; height: 54px;
  object-fit: cover;
  border-radius: var(--si-r-sm);
  border: 1px solid var(--si-border);
  margin-bottom: 7px;
  display: block;
}
[data-si] .si-file {
  display: block;
  width: 100%;
  padding: 8px 10px;
  border: 1.5px dashed var(--si-border2);
  border-radius: var(--si-r-sm);
  font-size: 12.5px;
  font-family: var(--si-font);
  color: var(--si-t3);
  cursor: pointer;
  background: var(--si-s2);
}
[data-si] .si-file::file-selector-button {
  background: var(--si-blue-s);
  color: var(--si-blue);
  border: none;
  padding: 4px 10px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  margin-right: 8px;
  font-family: var(--si-font);
}
</style>

  {{-- ── Topbar ── --}}
  <div class="si-bar">
    <div class="si-bar-title">
      <i class='bx bx-archive-in'></i> Stock In
    </div>
    <div class="si-bar-right">
      <button wire:click="loadItems" class="si-btn si-btn-ghost si-btn-sm">
        <i class='bx bx-refresh'></i>
        <span>Refresh</span>
      </button>
    </div>
  </div>

  <div class="si-body">

    {{-- ── Flash alerts ── --}}
    <div class="si-alerts">
      @if(session()->has('message'))
        <div class="si-alert si-alert-ok"><i class='bx bx-check-circle'></i> {{ session('message') }}</div>
      @endif
      @if(session()->has('success'))
        <div class="si-alert si-alert-inf"><i class='bx bx-info-circle'></i> {{ session('success') }}</div>
      @endif
      @if(session()->has('error'))
        <div class="si-alert si-alert-err"><i class='bx bx-error-circle'></i> {{ session('error') }}</div>
      @endif
    </div>

    {{-- ── Barcode scanner (selection mode) ── --}}
    @feature('barcode-scanning')
      <div class="si-scan-card">
        <div class="si-scan-label"><i class='bx bx-scan'></i> Scan to auto-select item</div>
        <livewire:qr-scanner :scannerId="'selection-scanner'" />
      </div>
    @else
      <div class="si-notice">
        <i class='bx bx-camera-off'></i>
        <span>Camera scanning is not available on your plan. Search and select products manually below.</span>
      </div>
    @endfeature

    {{-- ── Filters ── --}}
    <div class="si-filters">
      <div class="si-search-wrap">
        <i class='bx bx-search'></i>
        <input
          type="text"
          wire:model.live.debounce.300ms="search"
          placeholder="Search name or SKU…"
          class="si-input"
          autocomplete="off">
      </div>
      <div class="si-daterange">
        <i class='bx bx-calendar'></i>
        <input type="date" wire:model.live="dateRange.start" title="From date">
        <span>—</span>
        <input type="date" wire:model.live="dateRange.end" title="To date">
      </div>
    </div>

    {{-- ── Main grid ── --}}
    <div class="si-grid">

      {{-- LEFT: operations --}}
      <div class="si-card">
        <div class="si-card-head">
          <span class="si-card-title">Select Items</span>
          @can('create items')
            <button
              wire:click="openModal"
              type="button"
              class="si-btn si-btn-green si-btn-sm">
              <i class='bx bx-plus'></i> New Item
            </button>
          @endcan
        </div>

        <div class="si-card-body">
          <div class="si-item-list">
            @forelse($items as $item)
              <div
                wire:key="item-{{ $item->id }}"
                wire:click="toggleItemSelection({{ $item->id }})"
                class="si-item {{ in_array($item->id, array_column($selectedItems, 'id')) ? 'on' : '' }}">
                <div class="si-chk"></div>
                @if($item->image)
                  <img src="{{ Storage::url($item->image) }}" class="si-thumb" alt="">
                @else
                  <div class="si-thumb-ph"><i class='bx bx-package'></i></div>
                @endif
                <div class="si-item-info">
                  <div class="si-item-name">{{ $item->name }}</div>
                  <div class="si-item-sku">{{ $item->sku }}</div>
                </div>
                <span class="si-pill {{ $item->quantity > 0 ? 'ok' : 'low' }}">
                  {{ $item->quantity }}
                </span>
              </div>
            @empty
              <div class="si-empty">
                <i class='bx bx-package'></i>
                No items found.
              </div>
            @endforelse
          </div>
        </div>

        {{-- Selected items + confirm button --}}
        @if(count($selectedItems) > 0)
          <div class="si-sel-section">
            <div class="si-sublabel">
              <i class='bx bx-check-double'></i> Selected for Stock In
            </div>
            <div class="si-sel-list">
              @foreach($selectedItems as $index => $item)
                <div wire:key="sel-{{ $item['id'] }}" class="si-sel-row">
                  @if($item['image'])
                    <img src="{{ Storage::url($item['image']) }}" class="si-thumb" alt="">
                  @else
                    <div class="si-thumb-ph"><i class='bx bx-package'></i></div>
                  @endif
                  <div class="si-item-info">
                    <div class="si-item-name">{{ $item['name'] }}</div>
                  </div>
                  <input
                    type="number"
                    min="1"
                    wire:model.live.debounce.300ms="selectedItems.{{ $index }}.quantity"
                    class="si-qty-box">
                </div>
              @endforeach
            </div>
          </div>

          <div class="si-footer">
            <div class="si-footer-meta">
              <b>{{ count($selectedItems) }}</b> items ·
              <b>{{ array_sum(array_column($selectedItems, 'quantity')) }}</b> units
            </div>
            @can('manage stock')
              <button
                wire:click="handleStockIn"
                wire:loading.attr="disabled"
                wire:target="handleStockIn"
                type="button"
                class="si-btn si-btn-green">
                <i class='bx bx-down-arrow-circle'></i>
                <span wire:loading.remove wire:target="handleStockIn">Confirm Stock In</span>
                <span wire:loading       wire:target="handleStockIn">Processing…</span>
              </button>
            @endcan
          </div>
        @endif
      </div>

      {{-- RIGHT: history --}}
      <div class="si-card">
        <div class="si-card-head">
          <span class="si-card-title">Stock In History</span>
          <span style="font-size:11px;color:var(--si-t3);font-family:var(--si-mono)">Recent</span>
        </div>
        <div class="si-card-body np">
          <div class="si-tbl-wrap">
            <table class="si-tbl">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Item</th>
                  <th>Qty</th>
                  @can('view financial metrics')
                    <th>Unit</th>
                    <th>Total</th>
                  @endcan
                </tr>
              </thead>
              <tbody>
                @forelse($transactions as $transaction)
                  <tr wire:key="tx-{{ $transaction->id }}">
                    <td class="td-date">{{ \Carbon\Carbon::parse($transaction->date)->format('M d, Y') }}</td>
                    <td class="td-name">{{ $transaction->item_name }}</td>
                    <td class="td-qty">+{{ $transaction->quantity }}</td>
                    @can('view financial metrics')
                      <td class="td-price">{{ config('app.currency_symbol') }}{{ number_format($transaction->unit_price, 2) }}</td>
                      <td class="td-price" style="font-weight:700;color:var(--si-t1)">
                        {{ config('app.currency_symbol') }}{{ number_format($transaction->total_price, 2) }}
                      </td>
                    @endcan
                  </tr>
                @empty
                  <tr>
                    <td colspan="5">
                      <div class="si-tbl-empty">
                        <i class='bx bx-receipt'></i>
                        No transactions yet.
                      </div>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>{{-- /si-grid --}}
  </div>{{-- /si-body --}}

  {{-- ═══════════════════════════════════════════════
       MODAL — using openModal/closeModal methods
  ═══════════════════════════════════════════════ --}}
  @if($isModalOpen)
  <div class="si-overlay" wire:click.self="closeModal">
    <div class="si-modal" role="dialog" aria-modal="true">
      <span class="si-modal-handle"></span>

      <div class="si-modal-head">
        <span class="si-modal-title">
          <i class='bx bx-plus-circle'></i> Add New Item
        </span>
        <button type="button" wire:click="closeModal" class="si-modal-x">
          <i class='bx bx-x'></i>
        </button>
      </div>

      <div class="si-modal-body">
        {{-- SKU --}}
        <div class="si-fgroup">
          <div class="si-fgroup-lbl">
            <i class='bx bx-barcode' style="color:var(--si-blue)"></i>
            SKU / Barcode
          </div>
          <div class="si-btn-row">
            <input
              type="text"
              wire:model.live="newItem.sku"
              placeholder="Enter or scan SKU…"
              class="si-finput"
              autocomplete="off">
            @feature('barcode-scanning')
              <button
                type="button"
                wire:click="$toggle('isScanningForSku')"
                class="si-btn si-btn-ghost si-btn-sm"
                style="flex-shrink:0">
                <i class='bx {{ $isScanningForSku ? "bx-x" : "bx-scan" }}'></i>
                {{ $isScanningForSku ? 'Hide' : 'Scan' }}
              </button>
            @endfeature
          </div>
          @error('newItem.sku')
            <div class="si-ferr"><i class='bx bx-error-circle'></i> {{ $message }}</div>
          @enderror

          @feature('barcode-scanning')
            @if($isScanningForSku)
              <div class="si-scanner-inner">
                <livewire:qr-scanner :scannerId="'modal-scanner'" />
              </div>
            @endif
          @endfeature
        </div>

        {{-- Tracking type --}}
        <div class="si-field">
          <label class="si-label">Tracking Type</label>
          <select wire:model.live="newItem.tracking_type" class="si-finput">
            <option value="standard">Standard (Bulk quantity)</option>
            <option value="serialized">Serialized (Unique barcodes per unit)</option>
          </select>
        </div>

        {{-- Name --}}
        <div class="si-field">
          <label class="si-label">Product Name</label>
          <input
            type="text"
            wire:model.live="newItem.name"
            placeholder="e.g. Samsung 65W Charger"
            class="si-finput">
          @error('newItem.name') <div class="si-ferr">{{ $message }}</div> @enderror
        </div>

        {{-- Additional barcodes --}}
        <div class="si-field">
          <label class="si-label">
            Additional Barcodes <span class="opt">(optional)</span>
          </label>
          <input
            type="text"
            wire:model.live="additionalCodes"
            placeholder="890123, 890124, …"
            class="si-finput">
          <div class="si-hint">Comma-separated. Each code maps to this same product.</div>
          @error('additionalCodes') <div class="si-ferr">{{ $message }}</div> @enderror
        </div>

        {{-- Cost / Price --}}
        <div class="si-row2">
          <div class="si-field">
            <label class="si-label">Cost Price</label>
            <input type="number" step="0.01" wire:model.live="newItem.cost" placeholder="0.00" class="si-finput mono">
            @error('newItem.cost') <div class="si-ferr">{{ $message }}</div> @enderror
          </div>
          <div class="si-field">
            <label class="si-label">Selling Price</label>
            <input type="number" step="0.01" wire:model.live="newItem.price" placeholder="0.00" class="si-finput mono">
            @error('newItem.price') <div class="si-ferr">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- Type / Brand --}}
        <div class="si-row2">
          <div class="si-field">
            <label class="si-label">Type</label>
            <input type="text" wire:model.live="newItem.type" placeholder="e.g. Electronics" class="si-finput">
            @error('newItem.type') <div class="si-ferr">{{ $message }}</div> @enderror
          </div>
          <div class="si-field">
            <label class="si-label">Brand</label>
            <input type="text" wire:model.live="newItem.brand" placeholder="e.g. Samsung" class="si-finput">
            @error('newItem.brand') <div class="si-ferr">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- Quantity or serials --}}
        @if($newItem['tracking_type'] === 'standard')
          <div class="si-field">
            <label class="si-label">Initial Quantity</label>
            <input type="number" min="0" wire:model.live="newItem.quantity" placeholder="0" class="si-finput mono">
            @error('newItem.quantity') <div class="si-ferr">{{ $message }}</div> @enderror
          </div>
        @else
          <div class="si-fgroup" style="background:var(--si-blue-s);border-color:var(--si-blue-m)">
            <div class="si-fgroup-lbl" style="color:var(--si-blue)">
              <i class='bx bx-qr-scan'></i> Rapid Serial Scan
            </div>
            <div class="si-btn-row">
              <input
                type="text"
                wire:model.live="currentSerial"
                wire:keydown.enter.prevent="addSerial"
                placeholder="Scan or type serial, press Enter…"
                class="si-finput"
                autocomplete="off">
              <button type="button" wire:click="addSerial" class="si-btn si-btn-blue si-btn-sm" style="flex-shrink:0">
                <i class='bx bx-plus'></i> Add
              </button>
            </div>
            @error('currentSerial')  <div class="si-ferr">{{ $message }}</div> @enderror
            @error('scannedSerials') <div class="si-ferr">{{ $message }}</div> @enderror

            @if(count($scannedSerials) > 0)
              <div class="si-serial-box">
                <div class="si-serial-count">
                  <i class='bx bx-check-circle'></i>
                  {{ count($scannedSerials) }} serial{{ count($scannedSerials) !== 1 ? 's' : '' }} captured
                </div>
                <div class="si-serial-tags">
                  @foreach($scannedSerials as $idx => $serial)
                    <div class="si-serial-tag">
                      {{ $serial }}
                      <button type="button" wire:click="removeSerial({{ $idx }})">×</button>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif
          </div>
        @endif

        {{-- Image --}}
        <div class="si-field">
          <label class="si-label">Product Image <span class="opt">(optional)</span></label>
          @if($newItem['image'])
            <img src="{{ $newItem['image']->temporaryUrl() }}" class="si-img-prev" alt="Preview">
          @endif
          <input type="file" wire:model="newItem.image" accept="image/*" class="si-file">
          @error('newItem.image') <div class="si-ferr">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="si-modal-foot">
        <button type="button" wire:click="closeModal" class="si-btn si-btn-ghost">
          Cancel
        </button>
        <button
          type="button"
          wire:click="addItem"
          wire:loading.attr="disabled"
          wire:target="addItem"
          class="si-btn si-btn-blue">
          <i class='bx bx-save'></i>
          <span wire:loading.remove wire:target="addItem">Save Item</span>
          <span wire:loading       wire:target="addItem">Saving…</span>
        </button>
      </div>
    </div>
  </div>
  @endif

</div>