<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    return view('welcome');
})->name('inicio');

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:4'
    ], [
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'Este correo no tiene un formato válido.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener más de 4 caracteres.'
    ]);

    $response = Http::asForm()->post('http://api:8000/usuarios/login', [
        'email' => $request->input('email'),
        'password' => $request->input('password')
    ]);

    if ($response->successful()) {
        $data = $response->json();
        if (!isset($data['error'])) {
            if (in_array($data['rol'] ?? '', ['admin', 'ventas', 'almacen'])) {
                session([
                    'admin_logged' => true,
                    'user_name' => $data['nombre'],
                    'user_id' => $data['usuario_id'],
                    'rol' => $data['rol'],
                    'jwt_token' => $data['access_token'],
                    'imagen_perfil' => $data['imagen_perfil'] ?? null
                ]);
                return redirect('/admin');
            } else {
                return back()->withErrors(['email' => 'Acceso denegado: No tienes permisos de administrador.']);
            }
        }
    }
    
    return back()->withErrors(['email' => 'Credenciales inválidas.']);
})->name('login');

Route::get('/login', function () {
    return redirect('/');
});

// ========== UPLOAD PERFIL ADMIN ==========
Route::post('/admin/upload-perfil', function (Request $request) {
    if (!session('admin_logged')) return response()->json(['error' => 'No autorizado'], 401);

    $token = session('jwt_token');
    $userId = session('user_id');

    if (!$request->hasFile('file')) {
        return response()->json(['error' => 'No se envió archivo'], 400);
    }

    $file = $request->file('file');
    $response = Http::withToken($token)
        ->attach('file', file_get_contents($file->path()), $file->getClientOriginalName())
        ->post("http://api:8000/usuarios/{$userId}/upload_perfil");

    if ($response->successful()) {
        $data = $response->json();
        session(['imagen_perfil' => $data['url'] ?? null]);
        return response()->json($data);
    }

    return response()->json(['error' => 'Error al subir imagen'], $response->status());
});


Route::get('/admin', function () {
    if (!session('admin_logged')) return redirect('/');
    return view('admin.dashboard');
})->name('dashboard'); 

// ========== INVENTARIO ==========
Route::get('/inventario', function () {
    $token = session('jwt_token');
    $response = Http::withToken($token)->get('http://api:8000/autopartes/');
    $productos = $response->successful() ? $response->json() : [];
    return view('admin.inventario', compact('productos'));
});

Route::get('/inventario/crear', function () {
    return view('admin.crear_pieza');
});

Route::post('/inventario', function (Request $request) {
    $request->validate([
        'nombre' => 'required|min:3',
        'precio' => 'required|numeric|min:0.01',
        'stock' => 'required|integer|min:0',
        'marca' => 'required|min:2',
        'descripcion' => 'required|min:5',
        'imagen' => 'nullable|url',
        'imagen_file' => 'nullable|image|max:5120'
    ], [
        'nombre.required' => 'El nombre de la pieza es obligatorio.',
        'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
        'precio.required' => 'El precio es obligatorio.',
        'precio.numeric' => 'El precio debe ser un número.',
        'precio.min' => 'El precio debe ser mayor a 0.',
        'stock.required' => 'El stock es obligatorio.',
        'stock.integer' => 'El stock debe ser un número entero.',
        'stock.min' => 'El stock no puede ser negativo.',
        'marca.required' => 'La marca es obligatoria.',
        'marca.min' => 'La marca debe tener al menos 2 caracteres.',
        'descripcion.required' => 'La descripción es obligatoria.',
        'descripcion.min' => 'La descripción debe tener al menos 5 caracteres.',
        'imagen.url' => 'La URL de la imagen no es válida.',
        'imagen_file.image' => 'El archivo debe ser una imagen válida.',
        'imagen_file.max' => 'La imagen no puede pesar más de 5MB.'
    ]);

    $token = session('jwt_token');
    $imagen_url = $request->input('imagen');

    if ($request->hasFile('imagen_file')) {
        $file = $request->file('imagen_file');
        $uploadResponse = Http::withToken($token)
            ->attach(
                'file', file_get_contents($file->path()), $file->getClientOriginalName()
            )
            ->post('http://api:8000/autopartes/upload');
            
        if ($uploadResponse->successful()) {
            $imagen_url = $uploadResponse->json()['url'];
        }
    }

    Http::withToken($token)->post('http://api:8000/autopartes/', [
        'nombre' => $request->input('nombre'),
        'descripcion' => $request->input('descripcion'),
        'precio' => (float)$request->input('precio'),
        'stock' => (int)$request->input('stock'),
        'marca' => $request->input('marca'),
        'categoria' => $request->input('categoria'),
        'imagen' => $imagen_url ?: 'https://via.placeholder.com/300x200?text=Sin+Imagen',
    ]);
    return redirect('/inventario')->with('success', 'Pieza creada correctamente.');
});

Route::delete('/inventario/eliminar/{id}', function ($id) {
    $token = session('jwt_token');
    Http::withToken($token)->delete("http://api:8000/autopartes/{$id}");
    return redirect('/inventario')->with('success', 'Pieza eliminada.');
});

Route::get('/inventario/editar/{id}', function ($id) {
    $token = session('jwt_token');
    $response = Http::withToken($token)->get("http://api:8000/autopartes/{$id}");
    if (!$response->successful()) return redirect('/inventario');
    $producto = $response->json();
    return view('admin.editar_pieza', compact('producto'));
});

Route::put('/inventario/editar/{id}', function (Request $request, $id) {
    $request->validate([
        'nombre' => 'required|min:3',
        'precio' => 'required|numeric|min:0.01',
        'stock' => 'required|integer|min:0',
        'marca' => 'required|min:2',
        'descripcion' => 'required|min:5',
        'imagen' => 'nullable|url',
        'imagen_file' => 'nullable|image|max:5120'
    ], [
        'nombre.required' => 'El nombre es obligatorio.',
        'precio.required' => 'El precio es obligatorio.',
        'stock.required' => 'El stock es obligatorio.',
        'marca.required' => 'La marca es obligatoria.',
        'descripcion.required' => 'La descripción es obligatoria.',
        'imagen.url' => 'La URL de la imagen no es válida.',
        'imagen_file.image' => 'El archivo debe ser una imagen válida.',
        'imagen_file.max' => 'La imagen no puede pesar más de 5MB.'
    ]);

    $token = session('jwt_token');
    $imagen_url = $request->input('imagen');

    if ($request->hasFile('imagen_file')) {
        $file = $request->file('imagen_file');
        $uploadResponse = Http::withToken($token)
            ->attach(
                'file', file_get_contents($file->path()), $file->getClientOriginalName()
            )
            ->post('http://api:8000/autopartes/upload');
            
        if ($uploadResponse->successful()) {
            $imagen_url = $uploadResponse->json()['url'];
        }
    }

    Http::withToken($token)->put("http://api:8000/autopartes/{$id}", [
        'nombre' => $request->input('nombre'),
        'descripcion' => $request->input('descripcion'),
        'precio' => (float)$request->input('precio'),
        'stock' => (int)$request->input('stock'),
        'marca' => $request->input('marca'),
        'categoria' => $request->input('categoria'),
        'imagen' => $imagen_url ?: 'https://via.placeholder.com/300x200?text=Sin+Imagen',
    ]);
    return redirect('/inventario')->with('success', 'Pieza actualizada.');
});

// ========== PEDIDOS ==========
Route::get('/pedidos', function () {
    $token = session('jwt_token');
    $response = Http::withToken($token)->get('http://api:8000/pedidos/');
    \Illuminate\Support\Facades\Log::info("API Pedidos Response: " . $response->status() . " - Count: " . (is_array($response->json()) ? count($response->json()) : 'N/A'));
    $pedidos = $response->successful() ? $response->json() : [];

    $resUsuarios = Http::withToken($token)->get('http://api:8000/usuarios/');
    $usuarios = $resUsuarios->successful() ? $resUsuarios->json() : [];

    $resProductos = Http::withToken($token)->get('http://api:8000/autopartes/');
    $productos = $resProductos->successful() ? $resProductos->json() : [];

    return view('admin.pedidos', compact('pedidos', 'usuarios', 'productos'));
});

Route::post('/pedidos/estado/{id}', function (Request $request, $id) {
    $token = session('jwt_token');
    $estado = $request->input('estado');
    
    $payload = ['estado' => $estado];
    if ($request->has('paqueteria')) $payload['paqueteria'] = $request->input('paqueteria');
    if ($request->has('num_seguimiento')) $payload['num_seguimiento'] = $request->input('num_seguimiento');

    Http::withToken($token)->put("http://api:8000/pedidos/{$id}/estado", $payload);
    return redirect('/pedidos')->with('success', 'Estado actualizado.');
});

// ========== CLIENTES ==========
Route::get('/clientes', function () {
    $token = session('jwt_token');
    $response = Http::withToken($token)->get('http://api:8000/usuarios/');
    $clientes = $response->successful() ? $response->json() : [];

    $resPedidos = Http::withToken($token)->get('http://api:8000/pedidos/');
    $pedidos = $resPedidos->successful() ? $resPedidos->json() : [];

    $resProductos = Http::withToken($token)->get('http://api:8000/autopartes/');
    $productos = $resProductos->successful() ? $resProductos->json() : [];

    return view('admin.clientes', compact('clientes', 'pedidos', 'productos'));
});

// ========== REPORTES ==========
Route::get('/reportes', function () {
    return view('admin.reportes');
});

Route::get('/admin/cuentas', function () {
    if (!session('admin_logged') || !in_array(session('rol'), ['admin', 'ventas', 'almacen'])) {
        return redirect('/')->withErrors(['email' => 'Acceso denegado.']);
    }

    $response = Http::withToken(session('jwt_token'))->get('http://api:8000/usuarios/');
    $usuarios = $response->successful() ? $response->json() : [];

    return view('admin.cuentas', ['usuarios' => $usuarios]);
});

// ========== LOGOUT ==========
Route::get('/logout', function () {
    session()->flush();
    return redirect('/');
});