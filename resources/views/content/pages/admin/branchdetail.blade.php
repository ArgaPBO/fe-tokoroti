@extends('layouts.contentNavbarLayout')

@section('title', 'Branch Detail')

@section('content')

<div class="card">
  <div class="card-body">
    <div id="branchHeader" class="mb-3">
      <h4 id="branchName">Loading branch...</h4>
      <div class="small text-muted" id="branchMeta"></div>
    </div>

    <div class="row">
      <div class="col-12 mb-3">
        <div class="d-flex align-items-center">
          <input type="text" id="productSearch" class="form-control me-2" placeholder="Search product name" />
          <input type="text" id="expenseSearch" class="form-control me-2" placeholder="Search expense name" />
          <input type="date" id="startDate" class="form-control me-2" />
          <input type="date" id="endDate" class="form-control me-2" />
          <button class="btn btn-primary me-2" id="searchBothBtn">Search</button>
          <button class="btn btn-secondary" id="resetBtn">Reset</button>
        </div>
      </div>

      <div class="col-12">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Product</th>
                <th class="text-end">Price</th>
                <th class="text-end">Quantity</th>
                <th class="text-end">Total Revenue</th>
                <th class="text-end">Retail %</th>
                <th class="text-end">Pesanan %</th>
                <th class="text-end">Pagi %</th>
                <th class="text-end">Siang %</th>
              </tr>
            </thead>
            <tbody id="productsTableBody"></tbody>
          </table>
        </div>

        <nav>
          <ul class="pagination" id="productsPagination"></ul>
        </nav>
      </div>

      <div class="col-12 mt-4">
        <h5>Expenses</h5>
        <div class="table-responsive">
          <table class="table table-sm">
            <thead>
              <tr>
                <th>#</th>
                <th>Expense</th>
                <th class="text-end">Total Nominal</th>
                <th class="text-end">Pagi %</th>
                <th class="text-end">Siang %</th>
              </tr>
            </thead>
            <tbody id="expensesTableBody"></tbody>
          </table>
        </div>

        <nav>
          <ul class="pagination" id="expensesPagination"></ul>
        </nav>
      </div>
    </div>

    <div class="mt-4">
      <a id="generateLabaRugiBtn" class="btn btn-success" href="#">Generate Laba Rugi</a>
    </div>

  </div>
</div>

<script>
  const API_URL = '{{ env("API_URL") }}';

  function getCookie(name) {
    const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? decodeURIComponent(match[2]) : null;
  }

  function authHeaders() {
    const headers = { 'Content-Type': 'application/json' };
    const token = getCookie('token');
    if (token) headers['Authorization'] = `Bearer ${token}`;
    return headers;
  }

  function formatCurrency(v){
    return new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',minimumFractionDigits:0}).format(v);
  }

  function getQueryParam(name){
    return new URLSearchParams(window.location.search).get(name);
  }

  function getFirstDayOfMonth(){ const now = new Date(); return new Date(now.getFullYear(), now.getMonth(), 1); }
  function getLastDayOfMonth(){ const now = new Date(); return new Date(now.getFullYear(), now.getMonth()+1, 0); }
  function formatForInput(d){ const y=d.getFullYear(), m=String(d.getMonth()+1).padStart(2,'0'), day=String(d.getDate()).padStart(2,'0'); return `${y}-${m}-${day}`; }

  const branchId = getQueryParam('id');
  if (!branchId) {
    document.getElementById('branchName').textContent = 'Branch ID missing in URL';
  }

  // initialize shared date inputs (startDate / endDate)
  document.getElementById('startDate').value = formatForInput(getFirstDayOfMonth());
  document.getElementById('endDate').value = formatForInput(getLastDayOfMonth());

  // fetch branch info
  async function fetchBranch(){
    try{
      const res = await fetch(`${API_URL}/branches/${branchId}`, { headers: authHeaders(), credentials: 'include' });
      if(!res.ok) throw new Error('Failed to fetch branch');
      const data = await res.json();
      document.getElementById('branchName').textContent = data.name || 'Branch';
    //   document.getElementById('branchMeta').textContent = `ID: ${data.id}`;
    }catch(e){ console.error(e); document.getElementById('branchName').textContent = 'Error loading branch'; }
  }

  // products
  let productsPage = 1;
  async function fetchProducts(page=1){
    productsPage = page;
    const search = document.getElementById('productSearch').value.trim();
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;
    let url = `${API_URL}/branches/${branchId}/products?page=${page}`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (start) url += `&start_date=${encodeURIComponent(start)}`;
    if (end) url += `&end_date=${encodeURIComponent(end)}`;

    const res = await fetch(url, { headers: authHeaders(), credentials: 'include' });
    const data = await res.json();
    renderProducts(data);
  }

  function renderProducts(data){
    const rows = data.data || data;
    const tbody = document.getElementById('productsTableBody');
    tbody.innerHTML = '';
    if(!rows || rows.length===0){ tbody.innerHTML = '<tr><td colspan="5" class="text-center">No products found</td></tr>'; document.getElementById('productsTotal').textContent='-'; renderProductsPagination(data); return; }
    let total=0;
    rows.forEach((p, idx)=>{
      const revenue = Number(p.total_revenue || 0);
      total += revenue;
      const tr = document.createElement('tr');
      const retail = p.retail_percent ? parseFloat(p.retail_percent).toFixed(2) : '0.00';
      const pesanan = p.pesanan_percent ? parseFloat(p.pesanan_percent).toFixed(2) : '0.00';
      const pagi = p.pagi_percent ? parseFloat(p.pagi_percent).toFixed(2) : '0.00';
      const siang = p.siang_percent ? parseFloat(p.siang_percent).toFixed(2) : '0.00';
      tr.innerHTML = `<td>${idx+1}</td><td>${p.product?.name || '—'}</td><td class="text-end">${formatCurrency(p.branch_price || 0)}</td><td class="text-end">${p.total_quantity || 0}</td><td class="text-end">${formatCurrency(revenue)}</td><td class="text-end">${retail}%</td><td class="text-end">${pesanan}%</td><td class="text-end">${pagi}%</td><td class="text-end">${siang}%</td>`;
      tbody.appendChild(tr);
    });
    // totals are limited by pagination; omitted by default per request
    renderProductsPagination(data);
  }

  function renderProductsPagination(data){
    const list = document.getElementById('productsPagination'); list.innerHTML='';
    if(!data || !data.last_page) return;
    const prev = document.createElement('li'); prev.className = `page-item ${data.current_page===1? 'disabled':''}`; prev.innerHTML = `<a class="page-link" href="#" onclick="fetchProducts(${data.current_page-1})">Prev</a>`; list.appendChild(prev);
    for(let i=1;i<=data.last_page;i++){ const li=document.createElement('li'); li.className=`page-item ${i===data.current_page?'active':''}`; li.innerHTML=`<a class="page-link" href="#" onclick="fetchProducts(${i})">${i}</a>`; list.appendChild(li); }
    const nxt = document.createElement('li'); nxt.className = `page-item ${data.current_page===data.last_page ? 'disabled':''}`; nxt.innerHTML = `<a class="page-link" href="#" onclick="fetchProducts(${data.current_page+1})">Next</a>`; list.appendChild(nxt);
  }

  // expenses
  let expensesPage = 1;
  async function fetchExpenses(page=1){
    expensesPage = page;
    const search = document.getElementById('expenseSearch').value.trim();
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;
    let url = `${API_URL}/branches/${branchId}/expenses?page=${page}`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (start) url += `&start_date=${encodeURIComponent(start)}`;
    if (end) url += `&end_date=${encodeURIComponent(end)}`;

    const res = await fetch(url, { headers: authHeaders(), credentials: 'include' });
    const data = await res.json();
    renderExpenses(data);
  }

  function renderExpenses(data){
    const rows = data.data || data;
    const tbody = document.getElementById('expensesTableBody');
    tbody.innerHTML = '';
    if(!rows || rows.length===0){ tbody.innerHTML = '<tr><td colspan="3" class="text-center">No expenses found</td></tr>'; document.getElementById('expensesTotal').textContent='-'; renderExpensesPagination(data); return; }
    let total=0;
    rows.forEach((r, idx)=>{
      const nominal = Number(r.total_nominal || 0);
      total += nominal;
      const tr = document.createElement('tr');
      const name = r.expense?.name || (r.name || '—');
      const pagi = r.pagi_percent ? parseFloat(r.pagi_percent).toFixed(2) : '0.00';
      const siang = r.siang_percent ? parseFloat(r.siang_percent).toFixed(2) : '0.00';
      tr.innerHTML = `<td>${idx+1}</td><td>${name}</td><td class="text-end">${formatCurrency(nominal)}</td><td class="text-end">${pagi}%</td><td class="text-end">${siang}%</td>`;
      tbody.appendChild(tr);
    });
    // totals are limited by pagination; omitted by default per request
    renderExpensesPagination(data);
  }

  function renderExpensesPagination(data){
    const list = document.getElementById('expensesPagination'); list.innerHTML='';
    if(!data || !data.last_page) return;
    const prev = document.createElement('li'); prev.className = `page-item ${data.current_page===1? 'disabled':''}`; prev.innerHTML = `<a class="page-link" href="#" onclick="fetchExpenses(${data.current_page-1})">Prev</a>`; list.appendChild(prev);
    for(let i=1;i<=data.last_page;i++){ const li=document.createElement('li'); li.className=`page-item ${i===data.current_page?'active':''}`; li.innerHTML=`<a class="page-link" href="#" onclick="fetchExpenses(${i})">${i}</a>`; list.appendChild(li); }
    const nxt = document.createElement('li'); nxt.className = `page-item ${data.current_page===data.last_page ? 'disabled':''}`; nxt.innerHTML = `<a class="page-link" href="#" onclick="fetchExpenses(${data.current_page+1})">Next</a>`; list.appendChild(nxt);
  }

  // wire buttons: combined search uses both values
  document.getElementById('searchBothBtn').addEventListener('click', ()=>{ fetchProducts(1); fetchExpenses(1); });
  document.getElementById('resetBtn').addEventListener('click', ()=>{
    document.getElementById('productSearch').value = '';
    document.getElementById('expenseSearch').value = '';
    document.getElementById('startDate').value = formatForInput(getFirstDayOfMonth());
    document.getElementById('endDate').value = formatForInput(getLastDayOfMonth());
    fetchProducts(1);
    fetchExpenses(1);
  });

  document.getElementById('generateLabaRugiBtn').addEventListener('click', (e)=>{
    e.preventDefault();
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;
    const url = `/admin/export/labarugi?start_date=${encodeURIComponent(start)}&end_date=${encodeURIComponent(end)}&branch_id=${encodeURIComponent(branchId)}`;
    window.location.href = url;
  });

  document.addEventListener('DOMContentLoaded', ()=>{
    if(branchId) fetchBranch();
    if(branchId) fetchProducts(1);
    if(branchId) fetchExpenses(1);
  });

</script>

@endsection
