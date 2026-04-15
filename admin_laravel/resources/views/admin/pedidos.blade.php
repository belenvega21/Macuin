@extends('admin.dashboard')

@section('titulo', 'Gestión de Órdenes')

@section('contenido')
<style>
    .date-input-wrap {
        position: relative; display: flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,0.03); border: 1.5px solid rgba(255,255,255,0.08);
        border-radius: 14px; padding: 0 16px; height: 46px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer;
    }
    .date-input-wrap:hover { border-color: rgba(255,255,255,0.15); }
    .date-input-wrap:focus-within, .date-input-wrap.active {
        border-color: rgba(250, 204, 21, 0.6);
        box-shadow: 0 0 0 3px rgba(250, 204, 21, 0.08), 0 0 20px rgba(250, 204, 21, 0.05);
        background: rgba(250, 204, 21, 0.03);
    }
    .date-input-wrap input {
        background: transparent; border: none; outline: none; color: #fff;
        font-size: 13px; font-weight: 700; width: 110px; font-family: 'Inter', sans-serif;
    }
    .date-input-wrap input::placeholder { color: #555; font-weight: 500; }
    .date-separator { color: #444; font-size: 11px; font-weight: 800; letter-spacing: 0.1em; padding: 0 4px; }
    .filter-btn {
        display: flex; align-items: center; gap: 10px;
        background: rgba(255,255,255,0.03); border: 1.5px solid rgba(255,255,255,0.08);
        border-radius: 14px; padding: 0 18px; height: 46px;
        font-size: 13px; font-weight: 700; color: #fff; cursor: pointer;
        transition: all 0.2s; user-select: none;
    }
    .filter-btn:hover { border-color: rgba(255,255,255,0.15); background: rgba(255,255,255,0.05); }
    .filter-dropdown {
        position: absolute; top: calc(100% + 8px); left: 0; min-width: 200px;
        background: #141414; border: 1px solid rgba(255,255,255,0.08);
        border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.6);
        z-index: 100; overflow: hidden; animation: dropIn 0.15s ease-out;
    }
    .filter-dropdown button {
        width: 100%; padding: 12px 18px; text-align: left; font-size: 13px; font-weight: 600;
        color: #aaa; display: flex; align-items: center; gap: 10px;
        transition: all 0.15s; border: none; background: transparent; cursor: pointer;
    }
    .filter-dropdown button:hover { background: rgba(255,255,255,0.04); color: #fff; }
    .filter-dropdown button.active-item { color: #c084fc; background: rgba(192,132,252,0.06); }
    .filter-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(147, 51, 234, 0.1); border: 1.5px solid rgba(147, 51, 234, 0.25);
        border-radius: 14px; padding: 0 16px; height: 46px;
        color: #c084fc; font-size: 13px; font-weight: 700;
    }
    .filter-badge .dot { width: 6px; height: 6px; background: #c084fc; border-radius: 50%; animation: ppulse 2s infinite; }
    @keyframes ppulse { 0%,100%{opacity:1;box-shadow:0 0 0 0 rgba(192,132,252,0.5)} 50%{opacity:.8;box-shadow:0 0 0 4px rgba(192,132,252,0)} }
    @keyframes dropIn { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }
    .flatpickr-day.selected,.flatpickr-day.startRange,.flatpickr-day.endRange { background:#9333ea!important; border-color:#9333ea!important; color:#fff!important; font-weight:800!important; }
    .flatpickr-day.inRange { background:rgba(147,51,234,0.15)!important; border-color:transparent!important; box-shadow:none!important; }
    .flatpickr-day:hover { background:rgba(147,51,234,0.2)!important; color:#c084fc!important; }
</style>

<!-- Header -->
<div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 mb-6">
    <div class="flex items-center gap-4">
        <div class="w-14 h-14 bg-emerald-500/10 rounded-[1.2rem] flex items-center justify-center text-emerald-400 shadow-[0_0_20px_rgba(16,185,129,0.12)]">
            <span class="material-symbols-rounded text-[28px]">local_mall</span>
        </div>
        <div>
            <h2 class="text-3xl font-black tracking-tight leading-none">Centro de Órdenes</h2>
            <p class="text-gray-500 text-sm font-medium mt-1">Procesamiento y seguimiento de ventas corporativas.</p>
        </div>
    </div>
</div>

<!-- ========== PROFESSIONAL FILTER BAR ========== -->
<div class="flex flex-wrap items-center gap-3 mb-6">
    <!-- Granularity -->
    <div class="relative" id="granularity-wrap">
        <button onclick="toggleDD('granularity-dd')" class="filter-btn">
            <span class="material-symbols-rounded text-gray-400 text-[20px]">bar_chart</span>
            <span id="granularity-label">Por Día</span>
            <span class="material-symbols-rounded text-gray-500 text-[16px]">expand_more</span>
        </button>
        <div id="granularity-dd" class="filter-dropdown hidden">
            <button onclick="setGranularity('dia','Por Día')" class="active-item"><span class="material-symbols-rounded text-[18px] text-purple-400">today</span> Por Día</button>
            <button onclick="setGranularity('semana','Por Semana')"><span class="material-symbols-rounded text-[18px] text-blue-400">date_range</span> Por Semana</button>
            <button onclick="setGranularity('mes','Por Mes')"><span class="material-symbols-rounded text-[18px] text-emerald-400">calendar_month</span> Por Mes</button>
        </div>
    </div>

    <!-- Period -->
    <div class="relative" id="period-wrap">
        <button onclick="toggleDD('period-dd')" class="filter-btn">
            <span class="material-symbols-rounded text-gray-400 text-[20px]">calendar_month</span>
            <span id="period-label">Todos</span>
            <span class="material-symbols-rounded text-gray-500 text-[16px]">expand_more</span>
        </button>
        <div id="period-dd" class="filter-dropdown hidden">
            <button onclick="setPeriodPreset('hoy','Hoy')"><span class="material-symbols-rounded text-[18px] text-yellow-400">light_mode</span> Hoy</button>
            <button onclick="setPeriodPreset('7d','Últimos 7 días')"><span class="material-symbols-rounded text-[18px] text-blue-400">date_range</span> Últimos 7 días</button>
            <button onclick="setPeriodPreset('30d','Últimos 30 días')"><span class="material-symbols-rounded text-[18px] text-emerald-400">calendar_month</span> Últimos 30 días</button>
            <button onclick="setPeriodPreset('90d','Últimos 90 días')"><span class="material-symbols-rounded text-[18px] text-purple-400">event_note</span> Últimos 90 días</button>
            <div style="border-top:1px solid rgba(255,255,255,0.05);margin:2px 0"></div>
            <button onclick="setPeriodPreset('todos','Todos')" class="active-item"><span class="material-symbols-rounded text-[18px] text-gray-500">all_inclusive</span> Todos</button>
        </div>
    </div>

    <!-- Date inputs -->
    <div class="date-input-wrap" id="wrap-start">
        <span class="material-symbols-rounded text-gray-500 text-[20px]">calendar_today</span>
        <input type="text" id="date-start" placeholder="dd/mm/aaaa" readonly>
        <span class="material-symbols-rounded text-gray-600 text-[18px]">event</span>
    </div>
    <span class="date-separator">A</span>
    <div class="date-input-wrap" id="wrap-end">
        <span class="material-symbols-rounded text-gray-500 text-[20px]">calendar_today</span>
        <input type="text" id="date-end" placeholder="dd/mm/aaaa" readonly>
        <span class="material-symbols-rounded text-gray-600 text-[18px]">event</span>
    </div>

    <!-- Clear -->
    <button onclick="limpiarFiltro()" class="filter-btn" style="padding:0 14px" title="Limpiar Filtros">
        <span class="material-symbols-rounded text-gray-500 text-[20px]">filter_alt_off</span>
    </button>

    <!-- Badge -->
    <div id="filter-badge" class="filter-badge hidden">
        <span class="dot"></span>
        <span id="filter-badge-text"></span>
    </div>

    <!-- Count -->
    <div class="ml-auto flex items-center gap-2 bg-white/[0.03] border border-white/5 rounded-xl px-4 py-2 text-gray-400 text-xs font-bold">
        <span class="material-symbols-rounded text-[16px]">receipt_long</span>
        <span id="order-count">0</span> órdenes
    </div>
</div>

<!-- Status Tabs -->
<div class="card p-8 min-h-[500px]">
    <div class="flex justify-between items-center mb-8">
        <div class="flex gap-3 flex-wrap">
            <button onclick="filtrarEstado(''); activateTab(this)" class="tab-btn px-6 py-2.5 rounded-full text-sm font-bold bg-white/10 text-white hover:bg-white/20 transition border border-white/5">Todos</button>
            <button onclick="filtrarEstado('recibido'); activateTab(this)" class="tab-btn px-6 py-2.5 rounded-full text-sm font-bold text-gray-500 hover:bg-white/20 hover:text-white transition border border-transparent"><span class="w-2 h-2 rounded-full bg-sky-400 inline-block mr-2"></span>Recibidos</button>
            <button onclick="filtrarEstado('en_proceso'); activateTab(this)" class="tab-btn px-6 py-2.5 rounded-full text-sm font-bold text-gray-500 hover:bg-white/20 hover:text-white transition border border-transparent"><span class="w-2 h-2 rounded-full bg-yellow-400 inline-block mr-2"></span>En Proceso</button>
            <button onclick="filtrarEstado('entregado'); activateTab(this)" class="tab-btn px-6 py-2.5 rounded-full text-sm font-bold text-gray-500 hover:bg-white/20 hover:text-white transition border border-transparent"><span class="w-2 h-2 rounded-full bg-emerald-400 inline-block mr-2"></span>Entregados</button>
            <button onclick="filtrarEstado('cancelado'); activateTab(this)" class="tab-btn px-6 py-2.5 rounded-full text-sm font-bold text-gray-500 hover:bg-white/20 hover:text-white transition border border-transparent"><span class="w-2 h-2 rounded-full bg-red-400 inline-block mr-2"></span>Cancelados</button>
        </div>
    </div>

    <div class="overflow-x-auto rounded-[1.5rem] border border-white/5 bg-white/[0.01]">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-[11px] text-gray-500 uppercase tracking-[0.2em] font-black border-b border-white/5 bg-black/20">
                    <th class="px-8 py-6">ID / Fase</th>
                    <th class="px-8 py-6">Cliente</th>
                    <th class="px-8 py-6">Inversión</th>
                    <th class="px-8 py-6">Fecha</th>
                    <th class="px-8 py-6 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-pedidos" class="divide-y divide-white/[0.03]">
                <tr><td colspan="5" class="py-32 text-center">
                    <span class="material-symbols-rounded animate-spin text-[48px] text-gray-700">sync</span>
                    <p class="text-gray-500 mt-4 font-bold tracking-widest text-xs uppercase">Sincronizando...</p>
                </td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    let pedidosCache = @json($pedidos) || [];
    let usuariosCache = {};
    let productosCache = {};
    let filtroEstadoActual = '';
    let dateStart = null, dateEnd = null;
    let pickerStart, pickerEnd;

    try {
        (@json($usuarios) || []).forEach(u => { if(u?.id) usuariosCache[u.id] = u; });
        (@json($productos) || []).forEach(p => { if(p?.id) productosCache[p.id] = p; });
    } catch(e) {}

    function activateTab(el) {
        document.querySelectorAll('.tab-btn').forEach(b => { b.classList.remove('bg-white/10','text-white'); b.classList.add('text-gray-500'); });
        el.classList.add('bg-white/10','text-white'); el.classList.remove('text-gray-500');
    }

    // ========== PICKERS ==========
    function initPickers() {
        const cfg = { dateFormat: "d/m/Y", locale: "es", disableMobile: true };
        pickerStart = flatpickr("#date-start", { ...cfg, onChange: ([d]) => { if(d){ dateStart=d; document.getElementById('wrap-start').classList.add('active'); if(pickerEnd)pickerEnd.set('minDate',d); syncBadge(); aplicarFiltrosGlobales(); } } });
        pickerEnd = flatpickr("#date-end", { ...cfg, onChange: ([d]) => { if(d){ dateEnd=new Date(d); dateEnd.setHours(23,59,59,999); document.getElementById('wrap-end').classList.add('active'); syncBadge(); aplicarFiltrosGlobales(); } } });
    }

    // ========== DROPDOWNS ==========
    function toggleDD(id) {
        ['granularity-dd','period-dd'].forEach(dd => { if(dd!==id) document.getElementById(dd)?.classList.add('hidden'); });
        document.getElementById(id)?.classList.toggle('hidden');
    }
    document.addEventListener('click', e => {
        ['granularity-wrap','period-wrap'].forEach(w => { const el=document.getElementById(w); if(el&&!el.contains(e.target)) el.querySelector('.filter-dropdown')?.classList.add('hidden'); });
    });

    function setGranularity(val, label) {
        document.getElementById('granularity-label').textContent = label;
        document.getElementById('granularity-dd').classList.add('hidden');
        document.querySelectorAll('#granularity-dd button').forEach(b => b.classList.remove('active-item'));
        event.target.closest('button').classList.add('active-item');
    }

    function setPeriodPreset(val, label) {
        document.getElementById('period-label').textContent = label;
        document.getElementById('period-dd').classList.add('hidden');
        document.querySelectorAll('#period-dd button').forEach(b => b.classList.remove('active-item'));
        event.target.closest('button')?.classList.add('active-item');

        const now = new Date(), today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        if (val==='hoy') { dateStart=new Date(today); dateEnd=new Date(today); dateEnd.setHours(23,59,59,999); }
        else if (val==='7d') { dateStart=new Date(today); dateStart.setDate(dateStart.getDate()-6); dateEnd=new Date(today); dateEnd.setHours(23,59,59,999); }
        else if (val==='30d') { dateStart=new Date(today); dateStart.setDate(dateStart.getDate()-29); dateEnd=new Date(today); dateEnd.setHours(23,59,59,999); }
        else if (val==='90d') { dateStart=new Date(today); dateStart.setDate(dateStart.getDate()-89); dateEnd=new Date(today); dateEnd.setHours(23,59,59,999); }
        else { dateStart=null; dateEnd=null; }

        if(dateStart&&pickerStart){pickerStart.setDate(dateStart,false);document.getElementById('wrap-start').classList.add('active');}else if(pickerStart){pickerStart.clear();document.getElementById('wrap-start').classList.remove('active');}
        if(dateEnd&&pickerEnd){pickerEnd.setDate(dateEnd,false);document.getElementById('wrap-end').classList.add('active');}else if(pickerEnd){pickerEnd.clear();document.getElementById('wrap-end').classList.remove('active');}
        syncBadge(); aplicarFiltrosGlobales();
    }

    function syncBadge() {
        const b=document.getElementById('filter-badge'), t=document.getElementById('filter-badge-text');
        if(dateStart&&dateEnd){ b.classList.remove('hidden'); const f=d=>d.toLocaleDateString('es-MX',{day:'2-digit',month:'short'}); t.textContent=`${f(dateStart)} → ${f(dateEnd)}`; }
        else { b.classList.add('hidden'); }
    }

    function limpiarFiltro() {
        dateStart=null; dateEnd=null;
        if(pickerStart){pickerStart.clear();pickerEnd.set('minDate',null);}
        if(pickerEnd)pickerEnd.clear();
        document.getElementById('wrap-start').classList.remove('active');
        document.getElementById('wrap-end').classList.remove('active');
        document.getElementById('period-label').textContent='Todos';
        syncBadge(); aplicarFiltrosGlobales();
        Toast.fire({icon:'success',title:'Filtros restablecidos'});
    }

    // ========== DATA ==========
    if(Array.isArray(pedidosCache)&&pedidosCache.length>0) try{renderTablaPedidos(pedidosCache);}catch(e){}
    cargarPedidos();
    if(!window.pedidosSyncInterval) window.pedidosSyncInterval=setInterval(cargarPedidos,5000);

    async function cargarPedidos() {
        try {
            const h=JWT_TOKEN?{'Authorization':'Bearer '+JWT_TOKEN}:{};
            const [pR,uR,aR]=await Promise.all([fetch(API+'/pedidos/',{headers:h}),fetch(API+'/usuarios/',{headers:h}),fetch(API+'/autopartes/',{headers:h})]);
            if(!pR.ok||!uR.ok||!aR.ok)throw new Error('Server error');
            pedidosCache=await pR.json();
            (await uR.json()).forEach(u=>usuariosCache[u.id]=u);
            (await aR.json()).forEach(p=>productosCache[p.id]=p);
            aplicarFiltrosGlobales();
        } catch(e) {
            document.getElementById('tabla-pedidos').innerHTML=`<tr><td colspan="5" class="py-20 text-center text-red-500 font-bold text-sm">Error: ${e.message}</td></tr>`;
        }
    }

    function filtrarEstado(e) { filtroEstadoActual=e; aplicarFiltrosGlobales(); }

    function aplicarFiltrosGlobales() {
        let f=pedidosCache;
        if(filtroEstadoActual) f=f.filter(p=>p.estado===filtroEstadoActual);
        if(dateStart&&dateEnd) {
            f=f.filter(p=>{ if(!p.fecha)return false; const s=p.fecha.toString(); const d=new Date(s.includes('T')?s:s.replace(' ','T')); return d>=dateStart&&d<=dateEnd; });
        }
        renderTablaPedidos(f);
    }

    function renderTablaPedidos(data) {
        const c=document.getElementById('tabla-pedidos');
        document.getElementById('order-count').textContent=data.length;
        if(!data.length){c.innerHTML=`<tr><td colspan="5" class="py-32 text-center"><span class="material-symbols-rounded text-[64px] text-gray-700">inventory_2</span><p class="text-gray-500 font-bold text-lg mt-2">Sin registros</p></td></tr>`;return;}
        c.innerHTML=data.map(p=>{
            const us=usuariosCache[p.usuario_id]||{nombre:'Desconocido',email:'N/A'};
            const st=(p.estado||'recibido').toLowerCase();
            const isDone=st==='entregado', isCanc=st==='cancelado';
            let badge='bg-yellow-400/5 text-yellow-500 border-yellow-400/20', dot='bg-yellow-400';
            if(isDone){badge='bg-emerald-500/5 text-emerald-400 border-emerald-500/20';dot='bg-emerald-400';}
            else if(isCanc){badge='bg-red-500/5 text-red-500 border-red-500/20';dot='bg-red-500';}
            else if(st==='en_ruta'||st==='enviado'){badge='bg-blue-500/5 text-blue-400 border-blue-500/20';dot='bg-blue-400';}
            else if(st==='recibido'){badge='bg-sky-500/5 text-sky-400 border-sky-500/20';dot='bg-sky-400';}
            const total='$'+(parseFloat(p.total)||0).toLocaleString('en-US',{minimumFractionDigits:2});
            const date=p.fecha?String(p.fecha).replace('T',' ').split('.')[0]:'N/A';
            return `<tr class="group hover:bg-white/[0.02] transition-colors">
                <td class="px-8 py-6"><div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-white/5 rounded-xl flex items-center justify-center font-mono text-[10px] font-black text-gray-500 border border-white/5 group-hover:border-yellow-400/30 group-hover:text-yellow-400 transition shadow-inner">#${p.id}</div>
                    <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-[0.1em] border ${badge} flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full ${dot} ${!isDone&&!isCanc?'animate-pulse':''}"></span>${st.replace('_',' ')}</span>
                </div></td>
                <td class="px-8 py-6"><div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-gray-800 to-gray-700 flex items-center justify-center text-[11px] font-black text-white border border-white/10 shadow-lg uppercase">${us.nombre.charAt(0)}</div>
                    <div><p class="font-bold text-sm text-white leading-tight group-hover:text-yellow-400 transition">${us.nombre}</p><p class="text-[11px] text-gray-500 mt-0.5">${us.email}</p></div>
                </div></td>
                <td class="px-8 py-6 text-sm font-black text-white tracking-tight">${total}</td>
                <td class="px-8 py-6"><p class="text-xs text-gray-400 font-bold">${date}</p><p class="text-[10px] text-gray-600 font-black uppercase tracking-[0.15em]">Web Checkout</p></td>
                <td class="px-8 py-6 text-right"><div class="flex justify-end gap-2">
                    <button onclick='verDetallePedido(${p.id})' class="w-10 h-10 bg-white/5 rounded-xl flex items-center justify-center text-gray-500 hover:bg-white/10 hover:text-white transition border border-transparent hover:border-white/10"><span class="material-symbols-rounded text-[20px]">visibility</span></button>
                    <button onclick='actualizarPedidoModal(${p.id})' class="w-10 h-10 bg-white/5 rounded-xl flex items-center justify-center text-yellow-500/70 hover:bg-yellow-400 hover:text-black transition border border-transparent hover:border-yellow-400/50"><span class="material-symbols-rounded text-[20px]">settings</span></button>
                </div></td>
            </tr>`;
        }).join('');
    }

    function verDetallePedido(id) {
        const p=pedidosCache.find(x=>x.id===id); if(!p)return;
        const us=usuariosCache[p.usuario_id]||{nombre:'Desconocido',email:'N/A'};
        const det=(p.productos||[]).map(d=>{const pr=productosCache[d.autoparte_id]||{nombre:'#'+d.autoparte_id,precio:0};return`<tr class="border-b border-white/5"><td class="py-3 text-sm text-white font-bold">${pr.nombre}</td><td class="py-3 text-sm text-center text-gray-500 font-mono">x${d.cantidad}</td><td class="py-3 text-sm text-right font-black text-white">$${(pr.precio*d.cantidad).toLocaleString()}</td></tr>`;}).join('');
        Swal.fire({html:`<div class="text-left"><div class="flex items-center gap-4 mb-8"><div class="w-16 h-16 bg-yellow-400/10 rounded-2xl flex items-center justify-center font-black text-yellow-400 font-mono text-xl border border-yellow-400/20 shadow-inner">#${p.id}</div><div><h3 class="text-2xl font-black text-white">${us.nombre}</h3><p class="text-gray-500 text-sm">${us.email}</p></div></div><div class="bg-black/40 rounded-3xl border border-white/5 p-6 shadow-inner mb-6"><table class="w-full"><thead><tr class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-black border-b border-white/5"><th class="pb-3 text-left">Concepto</th><th class="pb-3 text-center">Cant.</th><th class="pb-3 text-right">Subtotal</th></tr></thead><tbody>${det}</tbody></table></div><div class="flex justify-between items-center px-4"><span class="text-xs text-gray-500 uppercase font-black tracking-[0.15em]">Total</span><span class="text-3xl font-black text-white">$${parseFloat(p.total).toLocaleString()}</span></div><div class="mt-6"><a href="${API}/reportes/pedidos/${p.id}/recibo/pdf" target="_blank" class="w-full bg-white/5 hover:bg-white/10 text-white font-bold py-4 rounded-2xl transition border border-white/10 flex items-center justify-center gap-2"><span class="material-symbols-rounded">picture_as_pdf</span> Ticket PDF</a></div></div>`,background:'#111',color:'#fff',width:500,showConfirmButton:false,showCloseButton:true,customClass:{popup:'rounded-[2.5rem] border border-white/10 shadow-2xl'}});
    }

    function actualizarPedidoModal(id) {
        const p=pedidosCache.find(x=>x.id===id); if(!p)return;
        Swal.fire({
            html:`<div class="text-left"><div class="flex items-center gap-4 mb-8"><div class="w-16 h-16 bg-gradient-to-br from-yellow-400/20 to-yellow-400/5 text-yellow-500 flex items-center justify-center rounded-2xl border border-yellow-400/20 shadow-inner"><span class="material-symbols-rounded text-3xl">local_shipping</span></div><div><h3 class="text-2xl font-black text-white">Estatus Logístico</h3><p class="text-xs text-gray-500 uppercase tracking-widest font-black">Expediente #${p.id}</p></div></div><div class="space-y-6"><div class="grid grid-cols-2 gap-3">${['recibido','en_proceso','en_ruta','entregado','cancelado'].map(st=>`<button type="button" data-val="${st}" class="estado-opt px-4 py-4 rounded-2xl border text-xs font-black uppercase tracking-widest flex items-center gap-3 transition ${p.estado===st?'bg-yellow-400/10 border-yellow-400/30 text-yellow-400 shadow-inner':'bg-white/5 border-white/5 text-gray-500 hover:border-white/20'}"><span class="w-2 h-2 rounded-full ${p.estado===st?'bg-yellow-400 shadow-[0_0_10px_rgba(250,204,21,0.5)]':'bg-gray-700'}"></span>${st.replace('_',' ')}</button>`).join('')}</div><input type="hidden" id="swal-estado-val" value="${p.estado}"><div class="space-y-3"><label class="text-[10px] text-gray-500 font-black uppercase tracking-widest ml-4">Paquetería</label><select id="swal-paqueteria" class="w-full bg-black/40 border border-white/5 text-white rounded-2xl px-6 py-4 text-sm focus:ring-1 focus:ring-yellow-400 font-bold appearance-none cursor-pointer"><option value="" ${!p.paqueteria?'selected':''}>Seleccionar...</option><option value="DHL" ${p.paqueteria==='DHL'?'selected':''}>DHL</option><option value="FedEx" ${p.paqueteria==='FedEx'?'selected':''}>FedEx</option><option value="Estafeta" ${p.paqueteria==='Estafeta'?'selected':''}>Estafeta</option><option value="UPS" ${p.paqueteria==='UPS'?'selected':''}>UPS</option><option value="Paquetexpress" ${p.paqueteria==='Paquetexpress'?'selected':''}>Paquetexpress</option></select></div><div class="space-y-3"><label class="text-[10px] text-gray-500 font-black uppercase tracking-widest ml-4">Nº de Seguimiento</label><div class="relative"><input id="swal-seguimiento" class="w-full bg-black/40 border border-white/5 text-white rounded-2xl px-6 py-4 text-sm focus:ring-1 focus:ring-yellow-400 font-mono font-bold placeholder-gray-700 pr-24" value="${p.num_seguimiento||''}" placeholder="TRKXXXXXXX"><button type="button" onclick="document.getElementById('swal-seguimiento').value='TRK-'+Math.random().toString(36).substring(2,6).toUpperCase()+'-'+Math.random().toString(36).substring(2,6).toUpperCase()" class="absolute right-2 top-2 bottom-2 px-4 rounded-xl bg-yellow-400 text-black text-[10px] font-black uppercase hover:bg-yellow-500 transition shadow-lg">Generar</button></div></div></div></div>`,
            background:'#111',color:'#fff',width:480,showCancelButton:true,confirmButtonColor:'#facc15',cancelButtonColor:'#222',
            confirmButtonText:'<span class="text-black font-black uppercase tracking-widest text-xs">Guardar</span>',
            cancelButtonText:'<span class="text-gray-500 font-black uppercase tracking-widest text-xs">Cerrar</span>',
            customClass:{popup:'rounded-[3rem] border border-white/10 p-4',confirmButton:'rounded-2xl py-4 shadow-xl',cancelButton:'rounded-2xl py-4'},
            didOpen:()=>{document.querySelectorAll('.estado-opt').forEach(btn=>btn.addEventListener('click',function(){document.querySelectorAll('.estado-opt').forEach(b=>b.className='estado-opt px-4 py-4 rounded-2xl border text-xs font-black uppercase tracking-widest flex items-center gap-3 transition bg-white/5 border-white/5 text-gray-500');this.className='estado-opt px-4 py-4 rounded-2xl border text-xs font-black uppercase tracking-widest flex items-center gap-3 transition bg-yellow-400/10 border-yellow-400/30 text-yellow-400 shadow-inner';document.getElementById('swal-estado-val').value=this.dataset.val;}));},
            preConfirm:()=>({estado:document.getElementById('swal-estado-val').value,paqueteria:document.getElementById('swal-paqueteria').value.trim(),num_seguimiento:document.getElementById('swal-seguimiento').value.trim()})
        }).then(r=>{
            if(r.isConfirmed){fetch(`${API}/pedidos/${p.id}/estado`,{method:'PUT',headers:{'Authorization':'Bearer '+JWT_TOKEN,'Content-Type':'application/json'},body:JSON.stringify(r.value)}).then(x=>{if(x.ok){Toast.fire({icon:'success',title:'Orden actualizada'});cargarPedidos();}else Toast.fire({icon:'error',title:'Error'});});}
        });
    }

    // ========== BOOT ==========
    initPickers();
</script>
@endsection