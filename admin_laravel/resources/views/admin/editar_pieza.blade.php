<!DOCTYPE html>
<html class="dark" lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Pieza | MACUIN</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
darkMode:"class",
theme:{extend:{
colors:{primary:"#BE0000",dashboardbg:"#1A1A1A"},
fontFamily:{display:["Space Grotesk","sans-serif"],body:["Noto Sans","sans-serif"]}
}}
}
</script>
<style>
.glass-panel{background:rgba(30,30,30,0.6);backdrop-filter:blur(12px);border:1px solid rgba(190,0,0,0.2);}
.glass-panel-heavy{background:rgba(20,20,20,0.85);backdrop-filter:blur(20px);border:1px solid rgba(190,0,0,0.3);}
</style>
</head>

<body class="bg-dashboardbg text-white font-body min-h-screen overflow-hidden">
<div class="flex h-screen">

<aside class="w-20 lg:w-64 glass-panel-heavy border-r border-white/10 flex flex-col justify-between">
<div>
<div class="h-20 flex items-center justify-center lg:px-6 border-b border-white/10">
<div class="hidden lg:block">
<h1 class="font-display font-bold text-lg">MACUIN</h1>
<p class="text-xs text-gray-400 uppercase">Autopartes</p>
</div>
</div>
<nav class="mt-8 px-2 lg:px-4 space-y-2">
<a href="/admin" class="flex items-center p-3 rounded-lg text-gray-400 hover:bg-white/5">
<span class="material-symbols-outlined">dashboard</span>
<span class="hidden lg:block ml-3 text-sm">Dashboard</span>
</a>
<a href="/inventario" class="flex items-center p-3 rounded-lg bg-primary/10 border border-primary/30">
<span class="material-symbols-outlined">inventory_2</span>
<span class="hidden lg:block ml-3 text-sm">Inventario</span>
</a>
</nav>
</div>
</aside>

<main class="flex-1 flex flex-col overflow-hidden">
<header class="h-20 glass-panel border-b border-white/10 flex items-center px-8">
<h2 class="text-xl font-display font-bold uppercase">
Inventario <span class="text-primary mx-2">/</span>
<span class="text-gray-400 text-base normal-case">Editar Pieza #{{ $producto['id'] }}</span>
</h2>
</header>

<div class="flex-1 overflow-y-auto p-10 flex justify-center">
<div class="w-full max-w-4xl glass-panel rounded-2xl p-8">

<h3 class="text-2xl font-display font-bold mb-8">Editar Autoparte</h3>

@if($errors->any())
<div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4 mb-6">
<p class="text-red-400 text-sm font-bold mb-2">⚠️ Corrige los errores:</p>
<ul class="list-disc list-inside text-red-400 text-sm space-y-1">
@foreach($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

<form action="/inventario/editar/{{ $producto['id'] }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
@csrf
@method('PUT')

<div>
<label class="text-sm text-gray-400">SKU (Código único)</label>
<input type="text" name="sku" value="{{ old('sku', $producto['sku'] ?? '') }}" placeholder="Ej. MAC-001"
class="w-full bg-black/40 border rounded-lg p-3 mt-1 border-gray-700">
</div>

<div>
<label class="text-sm text-gray-400">Nombre <span class="text-red-500">*</span></label>
<input type="text" name="nombre" value="{{ old('nombre', $producto['nombre']) }}"
class="w-full bg-black/40 border rounded-lg p-3 mt-1 @error('nombre') border-red-500 @else border-gray-700 @enderror">
@error('nombre')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>

<div>
<label class="text-sm text-gray-400">Marca <span class="text-red-500">*</span></label>
<input type="text" name="marca" value="{{ old('marca', $producto['marca']) }}"
class="w-full bg-black/40 border rounded-lg p-3 mt-1 @error('marca') border-red-500 @else border-gray-700 @enderror">
@error('marca')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>

<div>
<label class="text-sm text-gray-400">Stock <span class="text-red-500">*</span></label>
<input type="number" name="stock" value="{{ old('stock', $producto['stock']) }}" min="0"
class="w-full bg-black/40 border rounded-lg p-3 mt-1 @error('stock') border-red-500 @else border-gray-700 @enderror">
@error('stock')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>

<div>
<label class="text-sm text-gray-400">Precio <span class="text-red-500">*</span></label>
<input type="number" step="0.01" name="precio" value="{{ old('precio', $producto['precio']) }}"
class="w-full bg-black/40 border rounded-lg p-3 mt-1 @error('precio') border-red-500 @else border-gray-700 @enderror">
@error('precio')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>

<div>
<label class="text-sm text-gray-400">Categoría</label>
<select name="categoria" class="w-full bg-black/40 border border-gray-700 rounded-lg p-3 mt-1">
@foreach(['Motor','Frenos','Suspensión','Eléctrico'] as $cat)
<option value="{{ $cat }}" {{ old('categoria', $producto['categoria']) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
@endforeach
</select>
</div>

<!-- IMAGEN ARCHIVO O URL -->
<div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="text-sm text-gray-400">Subir Archivo de Imagen</label>
        <div class="mt-1 flex items-center bg-black/40 border border-gray-700 rounded-lg p-2 @error('imagen_file') border-red-500 @enderror">
            <span class="material-symbols-outlined text-gray-400 mr-2">upload_file</span>
            <input type="file" name="imagen_file" id="imagenFile" accept="image/*" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-yellow-400 file:text-black file:font-semibold hover:file:bg-yellow-300" onchange="previewImagenLocal(event)">
        </div>
        @error('imagen_file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm text-gray-400">O Cambiar URL de Imagen</label>
        <input type="url" name="imagen" id="imagenUrl" value="{{ old('imagen', $producto['imagen']) }}"
        class="w-full bg-black/40 border rounded-lg p-3 mt-1 @error('imagen') border-red-500 @else border-gray-700 @enderror"
        oninput="previewImagen()">
        @error('imagen')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
</div>

<div class="md:col-span-2">
<label class="text-sm text-gray-400 mb-2 block">Vista previa</label>
<div class="w-32 h-32 bg-black/40 border border-gray-700 rounded-lg overflow-hidden">
<img id="previewImg" src="{{ $producto['imagen'] }}" class="w-full h-full object-contain p-2"
onerror="this.src='https://via.placeholder.com/80x80?text=N/A'">
</div>
</div>

<div class="md:col-span-2">
<label class="text-sm text-gray-400">Descripción <span class="text-red-500">*</span></label>
<textarea name="descripcion" rows="3"
class="w-full bg-black/40 border rounded-lg p-3 mt-1 @error('descripcion') border-red-500 @else border-gray-700 @enderror">{{ old('descripcion', $producto['descripcion']) }}</textarea>
@error('descripcion')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>

<div class="md:col-span-2 flex justify-end gap-4 mt-4">
<a href="/inventario" class="px-6 py-3 border border-gray-600 rounded-lg text-gray-400">Cancelar</a>
<button type="submit" class="px-8 py-3 bg-yellow-400 text-black font-bold rounded-lg hover:bg-yellow-300 transition">
Actualizar Pieza
</button>
</div>
</form>
</div>
</div>
</main>
</div>

<script>
function previewImagen() {
    const url = document.getElementById('imagenUrl').value;
    const fileInput = document.getElementById('imagenFile');
    if (fileInput && fileInput.files && fileInput.files[0]) return; // Si hay archivo, ignorar URL

    document.getElementById('previewImg').src = url || 'https://via.placeholder.com/80x80?text=N/A';
}

function previewImagenLocal(event) {
    const file = event.target.files[0];
    const img = document.getElementById('previewImg');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
        }
        reader.readAsDataURL(file);
    } else {
        previewImagen(); // Fallback to URL if file is removed
    }
}
</script>
</body>
</html>