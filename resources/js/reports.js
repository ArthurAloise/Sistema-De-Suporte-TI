import { Chart, BarController, BarElement, LineController, LineElement, CategoryScale, LinearScale, PointElement, ArcElement, Tooltip, Legend, TimeScale } from 'chart.js';

Chart.register(BarController, BarElement, LineController, LineElement, CategoryScale, LinearScale, PointElement, ArcElement, Tooltip, Legend, TimeScale);

const qs = (sel) => document.querySelector(sel);
const qsa = (sel) => Array.from(document.querySelectorAll(sel));

function paramsFromFilters() {
  const s = new URLSearchParams();
  const v = (id) => qs(id)?.value || '';
  if (v('#f_status'))    s.set('status', v('#f_status'));
  if (v('#f_priority'))  s.set('priority', v('#f_priority'));
  if (v('#f_category'))  s.set('category_id', v('#f_category'));
  if (v('#f_type'))      s.set('type_id', v('#f_type'));
  if (v('#f_from'))      s.set('date_from', v('#f_from'));
  if (v('#f_to'))        s.set('date_to', v('#f_to'));
  // estenda se precisar (usuario_id, tecnico_id, etc.)
  return s;
}

async function jget(routeName) {
  const base = document.querySelector('base')?.href || '/';
  const url = new URL(routeName, base);
  return fetch(url).then(r => r.json());
}

async function jgetWithParams(path, params) {
  const base = window.location.origin;
  const url = new URL(path, base);
  params.forEach((v,k)=>{ if(v) url.searchParams.set(k,v) });
  const res = await fetch(url);
  return res.json();
}

function applyParamsToLink(aEl, basePath, params) {
  const url = new URL(basePath, window.location.origin);
  params.forEach((v,k)=>{ if(v) url.searchParams.set(k,v) });
  aEl.href = url.toString();
}

function toDataset(rows, labelKey='label', valueKey='total') {
  return {
    labels: rows.map(r => r[labelKey]),
    data: rows.map(r => Number(r[valueKey]) || 0),
  }
}

let charts = {};
function renderOrUpdate(id, cfg) {
  const ctx = qs(id).getContext('2d');
  if (charts[id]) { charts[id].destroy(); }
  charts[id] = new Chart(ctx, cfg);
}

async function loadAll() {
  const p = paramsFromFilters();

  // KPIs
  {
    const data = await jgetWithParams('/admin/reports/api/tickets/kpis', p);
    const map = {
      total: 'total',
      abertos: 'abertos',
      resolvidos: 'resolvidos',
      overdue: 'overdue',
      'sla': 'slaRate',
      'mttrh': 'mttrHours'
    };
    qsa('[data-kpi]').forEach(el => {
      const key = el.getAttribute('data-kpi');
      const val = data[map[key]] ?? '—';
      el.textContent = (val === null || val === undefined) ? '—' : val;
    });
  }

  // Charts Tickets
  {
    let rows = await jgetWithParams('/admin/reports/api/tickets/by-status', p);
    let {labels,data} = toDataset(rows,'status','total');
    renderOrUpdate('#ch_status', {
      type: 'bar',
      data: { labels, datasets: [{ label: 'Qtd', data }] },
      options: { responsive: true, plugins: { legend: { display: false } } }
    });

    rows = await jgetWithParams('/admin/reports/api/tickets/by-priority', p);
    ({labels,data} = toDataset(rows,'label','total'));
    renderOrUpdate('#ch_priority', { type:'bar', data:{ labels, datasets:[{ label:'Qtd', data }] }, options:{responsive:true, plugins:{legend:{display:false}}} });

    rows = await jgetWithParams('/admin/reports/api/tickets/by-category', p);
    ({labels,data} = toDataset(rows,'label','total'));
    renderOrUpdate('#ch_category', { type:'bar', data:{ labels, datasets:[{ label:'Qtd', data }] }, options:{responsive:true, plugins:{legend:{display:false}}} });

    rows = await jgetWithParams('/admin/reports/api/tickets/by-type', p);
    ({labels,data} = toDataset(rows,'label','total'));
    renderOrUpdate('#ch_type', { type:'bar', data:{ labels, datasets:[{ label:'Qtd', data }] }, options:{responsive:true, plugins:{legend:{display:false}}} });

    rows = await jgetWithParams('/admin/reports/api/tickets/created-daily', p);
    ({labels,data} = toDataset(rows,'dia','total'));
    renderOrUpdate('#ch_created', { type:'line', data:{ labels, datasets:[{ label:'Criados', data, tension: 0.2 }] }, options:{responsive:true} });

    rows = await jgetWithParams('/admin/reports/api/tickets/resolved-daily', p);
    ({labels,data} = toDataset(rows,'dia','total'));
    renderOrUpdate('#ch_resolved', { type:'line', data:{ labels, datasets:[{ label:'Resolvidos', data, tension: 0.2 }] }, options:{responsive:true} });

    rows = await jgetWithParams('/admin/reports/api/tickets/aging', p);
    ({labels,data} = toDataset(rows,'label','total'));
    renderOrUpdate('#ch_aging', { type:'bar', data:{ labels, datasets:[{ label:'Backlog', data }] }, options:{responsive:true, plugins:{legend:{display:false}}} });

    rows = await jgetWithParams('/admin/reports/api/tickets/sla-monthly', p);
    ({labels,data} = toDataset(rows,'mes','sla'));
    renderOrUpdate('#ch_sla', { type:'line', data:{ labels, datasets:[{ label:'SLA %', data, tension: 0.2 }] }, options:{responsive:true, scales:{ y:{ suggestedMin:0, suggestedMax:100 } } } });
  }

  // Charts Logs
  {
    let rows = await jgetWithParams('/admin/reports/api/logs/actions', p);
    let {labels,data} = toDataset(rows,'action','total');
    renderOrUpdate('#lg_actions', { type:'bar', data:{ labels, datasets:[{ label:'Ações', data }] }, options:{responsive:true, plugins:{legend:{display:false}}} });

    rows = await jgetWithParams('/admin/reports/api/logs/by-day', p);
    ({labels,data} = toDataset(rows,'dia','total'));
    renderOrUpdate('#lg_byday', { type:'line', data:{ labels, datasets:[{ label:'Logs/dia', data, tension:0.2 }] }, options:{responsive:true} });

    rows = await jgetWithParams('/admin/reports/api/logs/top-users', p);
    ({labels,data} = toDataset(rows,'usuario','total'));
    renderOrUpdate('#lg_users', { type:'bar', data:{ labels, datasets:[{ label:'Interações', data }] }, options:{responsive:true, plugins:{legend:{display:false}}} });

    rows = await jgetWithParams('/admin/reports/api/logs/top-routes', p);
    ({labels,data} = toDataset(rows,'route','total'));
    renderOrUpdate('#lg_routes', { type:'bar', data:{ labels, datasets:[{ label:'Hits', data }] }, options:{responsive:true, plugins:{legend:{display:false}}} });

    rows = await jgetWithParams('/admin/reports/api/logs/methods', p);
    ({labels,data} = toDataset(rows,'label','total'));
    renderOrUpdate('#lg_methods', { type:'bar', data:{ labels, datasets:[{ label:'Qtd', data }] }, options:{responsive:true, plugins:{legend:{display:false}}} });
  }

  // Atualizar links de export com os filtros correntes
  applyParamsToLink(document.getElementById('btnExportTickets'), '/admin/reports/export/tickets.csv', p);
  applyParamsToLink(document.getElementById('btnExportLogs'), '/admin/reports/export/logs.csv', p);
}

window.addEventListener('DOMContentLoaded', () => {
  qs('#btnApply')?.addEventListener('click', loadAll);
  qs('#btnClear')?.addEventListener('click', () => {
    ['#f_status','#f_priority','#f_category','#f_type','#f_from','#f_to'].forEach(id => { if (qs(id)) qs(id).value = '' });
    loadAll();
  });
  loadAll();
});
