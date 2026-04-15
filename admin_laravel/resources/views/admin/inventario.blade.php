@extends('admin.dashboard')

@section('titulo', 'Gestión de Inventario')

@section('contenido')
<div class="flex items-center gap-4 mb-8">
    <div class="w-16 h-16 bg-yellow-400/10 rounded-[1.5rem] flex items-center justify-center text-yellow-400 shadow-[0_0_20px_rgba(255,193,7,0.15)]">
        <span class="material-symbols-rounded text-[32px]">inventory_2</span>
    </div>
    <div>
        <h2 class="text-4xl font-black tracking-tight mb-1">Inventario</h2>
        <p class="text-gray-400 font-medium">Gestión corporativa del catálogo de autopartes.</p>
    </div>
</div>

<div class="card p-8 min-h-[600px] flex flex-col">

    <!-- Header Tools -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
        <div class="relative w-full md:w-96 group">
            <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-yellow-400 transition">search</span>
            <input type="text" id="buscador" placeholder="Buscar SKU, Nombre, Marca..." class="w-full px-4 py-3 pl-12 rounded-full bg-white/5 border border-white/5 text-white focus:outline-none focus:border-yellow-400 focus:bg-white/10 transition font-medium">
        </div>
        <div class="flex gap-4 w-full md:w-auto">
            <select id="filtro-categoria" class="bg-white/5 border border-white/5 text-gray-300 text-sm rounded-full focus:ring-yellow-400 focus:border-yellow-400 block px-6 py-3 font-bold appearance-none w-full md:w-auto">
                <option value="">Todas las Categorías</option>
                <option value="motor">Motor</option>
                <option value="frenos">Frenos</option>
                <option value="suspension">Suspensión</option>
                <option value="electrico">Eléctrico</option>
            </select>
            <button onclick="abrirModalCrear()" class="w-full md:w-auto bg-yellow-400 font-black text-black px-6 py-3 rounded-full hover:bg-yellow-500 transition shadow-[0_0_15px_rgba(255,193,7,0.3)] flex items-center justify-center gap-2">
                <span class="material-symbols-rounded">add</span> Nueva Pieza
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="flex-1 overflow-x-auto rounded-[1.5rem] bg-white/[0.02] border border-white/5">
        <table class="w-full text-left whitespace-nowrap">
            <thead>
                <tr class="text-xs text-gray-400 uppercase tracking-widest border-b border-gray-800 bg-black/20">
                    <th class="px-6 py-5 font-bold">Identificador</th>
                    <th class="px-6 py-5 font-bold">Autoparte</th>
                    <th class="px-6 py-5 font-bold text-center">Existencias</th>
                    <th class="px-6 py-5 font-bold text-right">Precio P.</th>
                    <th class="px-6 py-5 font-bold text-right">Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-contenido" class="divide-y divide-gray-800/60 text-[15px] font-medium">
                <tr><td colspan="5" class="py-10 text-center"><span class="material-symbols-rounded animate-spin text-[30px] text-gray-500">autorenew</span></td></tr>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const API_URL = API + '/autopartes';
    let inventarioCache = [];

    const Toast = Swal.mixin({
        toast: true, position: 'bottom-end', showConfirmButton: false, timer: 3000,
        background: '#111', color: '#fff', customClass: { popup: 'rounded-2xl border border-gray-800' }
    });

    async function cargarInventario() {
        try {
            const resp = await fetch(API_URL);
            inventarioCache = await resp.json();
            renderTabla(inventarioCache);
        } catch (error) {
            document.getElementById('tabla-contenido').innerHTML = '<tr><td colspan="5" class="text-center py-10 text-red-400 font-bold">Error de conexión con FastAPI</td></tr>';
        }
    }

    function renderTabla(datos) {
        const tbody = document.getElementById('tabla-contenido');
        if(datos.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center py-10 text-gray-500 font-bold">Sin resultados</td></tr>';
            return;
        }
        tbody.innerHTML = datos.map(item => {
            const lowStock = item.stock < 5;
            const stockColor = lowStock ? 'text-red-400 bg-red-400/10 border border-red-400/20' : 'text-emerald-400 bg-emerald-400/10 border border-emerald-400/20';
            return `
            <tr class="hover:bg-white/[0.02] transition">
                <td class="px-6 py-5">
                    <div class="px-3 py-1 bg-white/5 border border-white/5 rounded-md inline-block font-mono text-gray-400 text-xs shadow-inner">SKU-${item.id}</div>
                </td>
                <td class="px-6 py-5">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-black/50 rounded-[1rem] border border-white/5 flex items-center justify-center p-1 relative overflow-hidden flex-shrink-0 group">
                            <span class="material-symbols-rounded absolute text-white/5 text-[30px]">settings</span>
                            ${item.imagen ? `<img src="${API}${item.imagen}" class="max-h-full max-w-full relative z-10 object-contain group-hover:scale-110 transition">` : ''}
                        </div>
                        <div>
                            <p class="font-bold text-white text-[15px] mb-0.5">${item.nombre}</p>
                            <p class="text-[11px] text-gray-500 font-extrabold uppercase tracking-widest">${item.marca}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-5 text-center">
                    <span class="px-3 py-1.5 rounded-full text-xs font-bold leading-none ${stockColor} shadow-sm backdrop-blur-md inline-flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full ${lowStock ? 'bg-red-400 animate-pulse' : 'bg-emerald-400'}"></span>
                        ${item.stock} pz
                    </span>
                </td>
                <td class="px-6 py-5 text-right font-black font-mono text-[1.1rem]">
                    $${parseFloat(item.precio).toLocaleString('en-US', {minimumFractionDigits:2})}
                </td>
                <td class="px-6 py-5 text-right">
                    <div class="flex justify-end gap-2">
                        <button onclick='abrirModalEditar(${JSON.stringify(item).replace(/'/g, "&#39;")})' class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-yellow-400/20 hover:text-yellow-400 border border-transparent hover:border-yellow-400/30 transition text-gray-400" title="Editar">
                            <span class="material-symbols-rounded text-[20px]">edit</span>
                        </button>
                        <button onclick="eliminarProducto(${item.id})" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-red-500/20 hover:text-red-500 border border-transparent hover:border-red-500/30 transition text-gray-400" title="Eliminar">
                            <span class="material-symbols-rounded text-[20px]">delete_sweep</span>
                        </button>
                    </div>
                </td>
            </tr>`;
        }).join('');
    }

    // CREAR
    function abrirModalCrear() {
        Swal.fire({
            title: 'Nueva Autoparte',
            background: '#111', color: '#fff', width: 520,
            customClass: { popup: 'rounded-[2rem] border border-white/10' },
            html: generarFormHTML(),
            showCancelButton: true,
            confirmButtonColor: '#facc15',
            cancelButtonColor: '#374151',
            confirmButtonText: '<span class="text-black font-bold">Guardar</span>',
            cancelButtonText: 'Cancelar',
            preConfirm: () => validarForm()
        }).then(result => {
            if (result.isConfirmed) {
                const uploadAndSave = async (formData) => {
                    try {
                        const fileInput = document.getElementById('swal-file');
                        if (fileInput.files.length > 0) {
                            const fileData = new FormData();
                            fileData.append('file', fileInput.files[0]);
                            const upResp = await fetch(API_URL + '/upload', { 
                                method: 'POST', 
                                headers: { 'Authorization': 'Bearer ' + JWT_TOKEN },
                                body: fileData 
                            });
                            if(!upResp.ok) throw new Error('Error al subir imagen');
                            const upJson = await upResp.json();
                            formData.imagen = upJson.url.replace(API, '');
                        }
                        
                        // Clean API URL for the POST call (some environments prefer no trailing slash or specific ones)
                        const postRes = await fetch(API_URL + '/', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': 'Bearer ' + JWT_TOKEN
                            },
                            body: JSON.stringify(formData)
                        });

                        if(postRes.ok) {
                            Toast.fire({icon:'success',title:'Pieza creada Maestro'});
                            cargarInventario();
                        } else {
                            const errorData = await postRes.json();
                            console.error('API Error:', errorData);
                            Toast.fire({icon:'error',title:'Error: ' + (errorData.detail || 'No se pudo crear')});
                        }
                    } catch (err) {
                        console.error(err);
                        Toast.fire({icon:'error',title: err.message || 'Error de conexión'});
                    }
                };
                uploadAndSave(result.value);
            }
        });
    }

    // EDITAR
    function abrirModalEditar(item) {
        Swal.fire({
            title: 'Editar Autoparte',
            background: '#111', color: '#fff', width: 520,
            customClass: { popup: 'rounded-[2rem] border border-white/10' },
            html: generarFormHTML(item),
            showCancelButton: true,
            confirmButtonColor: '#facc15',
            cancelButtonColor: '#374151',
            confirmButtonText: '<span class="text-black font-bold">Actualizar</span>',
            cancelButtonText: 'Cancelar',
            preConfirm: () => validarForm()
        }).then(result => {
            if (result.isConfirmed) {
                const uploadAndSave = async (formData) => {
                    try {
                        const fileInput = document.getElementById('swal-file');
                        if (fileInput.files.length > 0) {
                            const fileData = new FormData();
                            fileData.append('file', fileInput.files[0]);
                            const upResp = await fetch(API_URL + '/upload', { 
                                method: 'POST', 
                                headers: { 'Authorization': 'Bearer ' + JWT_TOKEN },
                                body: fileData 
                            });
                            if(!upResp.ok) throw new Error('Error al subir imagen');
                            const upJson = await upResp.json();
                            formData.imagen = upJson.url.replace(API, '');
                        }
                        const putRes = await fetch(API_URL + '/' + item.id, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': 'Bearer ' + JWT_TOKEN
                            },
                            body: JSON.stringify(formData)
                        });

                        if(putRes.ok) {
                            Toast.fire({icon:'success',title:'Pieza actualizada'});
                            cargarInventario();
                        } else {
                            Toast.fire({icon:'error',title:'Error al actualizar'});
                        }
                    } catch (err) {
                        console.error(err);
                        Toast.fire({icon:'error',title:'Error técnico al guardar'});
                    }
                };
                uploadAndSave(result.value);
            }
        });
    }

    // ELIMINAR — Modal Inmersivo Premium
    function eliminarProducto(id) {
        const item = inventarioCache.find(i => i.id === id);
        const itemName = item ? item.nombre : 'Pieza #' + id;

        Swal.fire({
            html: `
                <div class="text-center">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-red-500/20 to-red-900/10 text-red-500 flex items-center justify-center mx-auto mb-6 border-2 border-red-500/30 shadow-[0_0_30px_rgba(239,68,68,0.3)] relative">
                        <span class="material-symbols-rounded text-5xl">delete_forever</span>
                        <div class="absolute inset-0 rounded-full border border-red-500/20 animate-ping"></div>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-3">Eliminar del Catálogo</h3>
                    <p class="text-gray-400 text-sm leading-relaxed mb-2">Estás a punto de remover permanentemente:</p>
                    <div class="bg-black/40 rounded-xl border border-white/5 px-4 py-3 mb-4 inline-block">
                        <span class="font-bold text-white text-lg">${itemName}</span>
                    </div>
                    <p class="text-gray-500 text-xs leading-relaxed">Los registros históricos de pedidos que incluyan esta pieza permanecerán intactos en la base de datos.</p>
                </div>
            `,
            background: '#111', color: '#fff', width: 450,
            customClass: { popup: 'rounded-[2rem] border border-red-500/20 p-4 shadow-[0_20px_50px_rgba(239,68,68,0.15)]' },
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#374151',
            confirmButtonText: '<span class="font-bold text-white flex items-center gap-1"><span class="material-symbols-rounded text-[18px]">delete_forever</span> Sí, eliminar pieza</span>',
            cancelButtonText: '<span class="font-bold">Conservar</span>',
            focusCancel: true,
            reverseButtons: true
        }).then(result => {
            if (result.isConfirmed) {
                fetch(API_URL + '/' + id, { 
                    method: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + JWT_TOKEN }
                }).then(r => {
                    if(r.ok) {
                        Swal.fire({
                            html: `
                                <div class="text-center">
                                    <div class="w-20 h-20 rounded-full bg-emerald-500/10 text-emerald-400 flex items-center justify-center mx-auto mb-6 border border-emerald-500/20 shadow-[0_0_20px_rgba(16,185,129,0.2)]">
                                        <span class="material-symbols-rounded text-4xl">check_circle</span>
                                    </div>
                                    <h3 class="text-xl font-black text-white mb-2">Pieza Removida</h3>
                                    <p class="text-gray-400 text-sm">El artículo fue eliminado exitosamente del inventario.</p>
                                </div>
                            `,
                            background: '#111', color: '#fff', width: 380,
                            customClass: { popup: 'rounded-[2rem] border border-white/10' },
                            showConfirmButton: false, timer: 2000, timerProgressBar: true
                        });
                        cargarInventario();
                    }
                    else Toast.fire({icon:'error',title:'Bloqueo de seguridad al eliminar'});
                });
            }
        });
    }

    function generarFormHTML(item = {}) {
        return `
        <div class="text-left space-y-4 mt-2">
            <div><label class="text-xs text-gray-400 font-bold mb-1 block">Nombre *</label>
            <input id="swal-nombre" class="swal2-input !w-full !m-0 !bg-[#1a1a1a] !border-white/10 !text-white !rounded-xl" value="${item.nombre||''}" placeholder="Ej: Batería LTH"></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="text-xs text-gray-400 font-bold mb-1 block">Precio *</label>
                <input id="swal-precio" type="number" step="0.01" class="swal2-input !w-full !m-0 !bg-[#1a1a1a] !border-white/10 !text-white !rounded-xl" value="${item.precio||''}" placeholder="0.00"></div>
                <div><label class="text-xs text-gray-400 font-bold mb-1 block">Stock *</label>
                <input id="swal-stock" type="number" class="swal2-input !w-full !m-0 !bg-[#1a1a1a] !border-white/10 !text-white !rounded-xl" value="${item.stock||0}" placeholder="0"></div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="text-xs text-gray-400 font-bold mb-1 block">Marca *</label>
                <input id="swal-marca" class="swal2-input !w-full !m-0 !bg-[#1a1a1a] !border-white/10 !text-white !rounded-xl" value="${item.marca||''}" placeholder="Ej: Bosch"></div>
                <div><label class="text-xs text-gray-400 font-bold mb-1 block">Categoría</label>
                <select id="swal-categoria" class="swal2-input !w-full !m-0 !bg-[#1a1a1a] !border-white/10 !text-white !rounded-xl">
                    <option value="motor" ${(item.categoria||'')==='motor'?'selected':''}>Motor</option>
                    <option value="frenos" ${(item.categoria||'')==='frenos'?'selected':''}>Frenos</option>
                    <option value="suspension" ${(item.categoria||'')==='suspension'?'selected':''}>Suspensión</option>
                    <option value="electrico" ${(item.categoria||'')==='electrico'?'selected':''}>Eléctrico</option>
                </select></div>
            </div>
            <div><label class="text-xs text-gray-400 font-bold mb-1 block">Descripción *</label>
            <textarea id="swal-desc" class="swal2-textarea !w-full !m-0 !bg-[#1a1a1a] !border-white/10 !text-white !rounded-xl !h-20" placeholder="Descripción del producto">${item.descripcion||''}</textarea></div>
            <div class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="text-xs text-gray-400 font-bold mb-1 block">Imagen del Producto</label>
                    <div class="relative group h-[120px] bg-black/40 border-2 border-dashed border-white/10 rounded-2xl flex flex-col items-center justify-center overflow-hidden hover:border-yellow-400/50 transition cursor-pointer" onclick="document.getElementById('swal-file').click()">
                        <img id="swal-img-preview" src="${item.imagen ? API+item.imagen : 'https://placehold.co/400x300/111/333?text=Click+subir'}" class="absolute inset-0 w-full h-full object-contain ${item.imagen ? '' : 'opacity-20'}">
                        <div class="relative z-10 flex flex-col items-center gap-1 group-hover:scale-110 transition">
                            <span class="material-symbols-rounded text-2xl text-yellow-400">add_a_photo</span>
                            <span class="text-[10px] text-gray-400 font-black uppercase tracking-tighter">Seleccionar Archivo</span>
                        </div>
                        <input type="file" id="swal-file" class="hidden" accept="image/*" onchange="const fr=new FileReader(); fr.onload=e=>{document.getElementById('swal-img-preview').src=e.target.result; document.getElementById('swal-img-preview').classList.remove('opacity-20')}; fr.readAsDataURL(this.files[0])">
                    </div>
                </div>
                <div class="w-1/3">
                    <label class="text-xs text-gray-400 font-bold mb-1 block">O URL</label>
                    <input id="swal-imagen" class="swal2-input !w-full !m-0 !bg-[#1a1a1a] !border-white/10 !text-white !rounded-xl text-[10px]" value="${item.imagen||''}" placeholder="https://...">
                </div>
            </div>
        </div>`;
    }

    function validarForm() {
        const nombre = document.getElementById('swal-nombre').value.trim();
        const precio = document.getElementById('swal-precio').value;
        const stock = document.getElementById('swal-stock').value;
        const marca = document.getElementById('swal-marca').value.trim();
        const desc = document.getElementById('swal-desc').value.trim();

        if (!nombre || nombre.length < 3) { Swal.showValidationMessage('El nombre debe tener al menos 3 caracteres'); return false; }
        if (!precio || parseFloat(precio) <= 0) { Swal.showValidationMessage('El precio debe ser mayor a 0'); return false; }
        if (stock === '' || parseInt(stock) < 0) { Swal.showValidationMessage('El stock no puede ser negativo'); return false; }
        if (!marca || marca.length < 2) { Swal.showValidationMessage('La marca debe tener al menos 2 caracteres'); return false; }
        if (!desc || desc.length < 5) { Swal.showValidationMessage('La descripción debe tener al menos 5 caracteres'); return false; }

        return {
            nombre, precio: parseFloat(precio), stock: parseInt(stock), marca, descripcion: desc,
            categoria: document.getElementById('swal-categoria').value,
            imagen: document.getElementById('swal-imagen').value || 'https://via.placeholder.com/300x200?text=Sin+Imagen'
        };
    }

    // Filtros
    document.getElementById('buscador').addEventListener('input', filtrarData);
    document.getElementById('filtro-categoria').addEventListener('change', filtrarData);

    function filtrarData() {
        const req = document.getElementById('buscador').value.toLowerCase();
        const resCat = document.getElementById('filtro-categoria').value.toLowerCase();
        let fd = inventarioCache;
        if(req) fd = fd.filter(i => i.nombre.toLowerCase().includes(req) || i.marca.toLowerCase().includes(req) || i.id.toString()===req);
        if(resCat) fd = fd.filter(i => (i.categoria||'').toLowerCase() === resCat);
        renderTabla(fd);
    }

    cargarInventario();
</script>
@endsection