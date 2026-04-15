from flask import Flask, render_template, request, redirect, url_for, send_file, session, flash, jsonify
from reportlab.pdfgen import canvas
from reportlab.platypus import SimpleDocTemplate, Table, TableStyle, Paragraph, Spacer
from reportlab.lib.styles import getSampleStyleSheet
from reportlab.lib import colors
import os
import requests

app = Flask(__name__)
app.secret_key = "super_secret_flask_macuin"

API_URL = "http://api:8000"

# 🧠 SESIÓN LOCAL / CARRITO
# El carrito sigue en memoria, pero el estado del usuario ya migró a sesión JWT
carritos = {}

# 🔐 LOGIN PAGE
@app.route("/")
def index():
    return redirect("/inicio")

@app.route("/login")
def login():
    return render_template("login.html")

# 🔐 LOGIN
@app.route("/login", methods=["POST"])
def do_login():
    email = request.form["email"]
    password = request.form["password"]

    try:
        response = requests.post(f"{API_URL}/usuarios/login", data={"email": email, "password": password})
        if response.status_code == 200:
            data = response.json()
            if "error" in data:
                return redirect("/login?error=1")
            
            # Autenticar estado con JWT en sesión nativa
            session["jwt_token"] = data.get("access_token")
            session["user"] = {
                "id": data.get("usuario_id"),
                "email": data.get("email"),
                "nombre": data.get("nombre")
            }
            return redirect("/inicio")
    except Exception as e:
        print("Error connecting to API:", e)
        return redirect("/login?error=1")

    return redirect("/login")

# 📝 REGISTER PAGE
@app.route("/register")
def register():
    return render_template("register.html")

# 📝 REGISTER
@app.route("/auth/registro", methods=["POST"])
def do_register():
    nombre = request.form["nombre"]
    email = request.form["email"]
    telefono = request.form["telefono"]
    password = request.form["password"]
    confirmar = request.form["confirmar"]

    try:
        response = requests.post(f"{API_URL}/usuarios/registro", data={
            "nombre": nombre,
            "email": email,
            "telefono": telefono,
            "password": password,
            "confirmar": confirmar
        })
        if response.status_code == 200:
            data = response.json()
            if "error" in data:
                return redirect("/register?error=1")
            return redirect("/login")
    except Exception as e:
        print("Error connecting to API:", e)
        return redirect("/register?error=1")

    return redirect("/login")

marcas_global = [
    {"nombre": "ford", "imagen": "/static/img/ford.png"},
    {"nombre": "bmw", "imagen": "/static/img/bmw.png"},
    {"nombre": "toyota", "imagen": "/static/img/toyota.png"},
    {"nombre": "chevrolet", "imagen": "/static/img/che.png"}
]

def obtener_productos_api():
    headers = {}
    if "jwt_token" in session:
        headers["Authorization"] = f"Bearer {session['jwt_token']}"
        
    try:
        res = requests.get(f"{API_URL}/autopartes/", headers=headers)
        if res.status_code == 200:
            return res.json()
    except Exception as e:
        print("Error fetching products:", e)
    return []

# 🏠 HOME
@app.route("/inicio")
def inicio():
    if "user" not in session:
        return redirect("/login")

    productos = obtener_productos_api()
    return render_template("inicio.html", productos=productos, marcas=marcas_global)

# 📦 CATÁLOGO
import unicodedata
def strip_accents(s):
    return ''.join(c for c in unicodedata.normalize('NFD', s) if unicodedata.category(c) != 'Mn')

@app.route("/catalogo")
def catalogo():
    busqueda = strip_accents(request.args.get("q", "").lower())
    categoria = strip_accents(request.args.get("categoria", "").lower())
    marca = strip_accents(request.args.get("marca", "").lower())

    productos = obtener_productos_api()
    filtrados = productos

    if busqueda:
        filtrados = [p for p in filtrados if busqueda in strip_accents(p["nombre"].lower())]
    if categoria:
        filtrados = [p for p in filtrados if strip_accents(p.get("categoria", "").lower()) == categoria]
    if marca:
        filtrados = [p for p in filtrados if strip_accents(p.get("marca", "").lower()) == marca]

    # Extraer marcas únicas de los productos reales
    marcas_reales = []
    seen = set()
    for p in productos:
        m = p.get("marca", "")
        if m and m.lower() not in seen:
            seen.add(m.lower())
            marcas_reales.append({"nombre": m})

    return render_template("catalogo.html", productos=filtrados, marcas=marcas_reales)

# ➕ AGREGAR AL CARRITO (CORRECTO)
@app.route("/agregar/<int:producto_id>")
def agregar_carrito(producto_id):
    is_ajax = request.headers.get("X-Requested-With") == "XMLHttpRequest"
    if "user" not in session:
        if is_ajax:
            return jsonify({"error": "No autenticado"}), 401
        return redirect("/login")
        
    user_id = session["user"]["id"]

    if user_id not in carritos:
        carritos[user_id] = []

    productos = obtener_productos_api()
    producto = next((p for p in productos if p["id"] == producto_id), None)

    if producto:
        carritos[user_id].append(producto)

    if is_ajax:
        return jsonify({"success": True})
    return redirect("/carrito")

# VER CARRITO
@app.route("/carrito")
def ver_carrito():
    if "user" not in session:
        return redirect("/login")
        
    user_id = session["user"]["id"]

    items = carritos.get(user_id, [])
    total = sum(p["precio"] for p in items)

    return render_template("carrito.html", carrito=items, total=total)

# 🗑 VACIAR Y ELIMINAR DEL CARRITO
@app.route("/carrito/vaciar")
def vaciar_carrito():
    if "user" not in session: return redirect("/login")
    user_id = session["user"]["id"]
    carritos[user_id] = []
    return redirect("/carrito")

@app.route("/carrito/eliminar/<int:index>")
def eliminar_item_carrito(index):
    if "user" not in session: return redirect("/login")
    user_id = session["user"]["id"]
    if user_id in carritos and index < len(carritos[user_id]):
        carritos[user_id].pop(index)
    return redirect("/carrito")
# 💳 COMPRAR
@app.route("/comprar")
def comprar():
    if "user" not in session:
        return redirect("/login")

    user_id = session["user"]["id"]
    carrito_items = carritos.get(user_id, [])

    if len(carrito_items) == 0:
        flash("Error: Tu carrito está vacío. Agrega productos al carrito primero.", "error")
        return redirect("/catalogo")

    # Adaptar para el formato API (espera list of productos {id, cantidad})
    productos_api = []
    # Conteo simple de cantidad por id
    contador_cantidades = {}
    for item in carrito_items:
        contador_cantidades[item["id"]] = contador_cantidades.get(item["id"], 0) + 1
        
    for p_id, count in contador_cantidades.items():
        productos_api.append({"id": p_id, "cantidad": count})

    try:
        headers = {"Authorization": f"Bearer {session.get('jwt_token')}"}
        res = requests.post(f"{API_URL}/pedidos/", json={
            "usuario_id": user_id,
            "productos": productos_api
        }, headers=headers)
        if res.status_code == 200:
            carritos[user_id] = []
            flash("¡Compra realizada con éxito! Ve a tu perfil para revisar el seguimiento de tu pedido.", "success")
        else:
            flash("Error al procesar la compra en el servidor.", "error")
    except Exception as e:
        print("Error creating order:", e)
        flash("Hubo un problema procesando tu compra.", "error")

    return redirect("/perfil")

# 👤 PERFIL
@app.route("/perfil", methods=["GET", "POST"])
def perfil():
    if "user" not in session:
        return redirect("/login")

    user_id = session["user"]["id"]
    token = session.get('jwt_token')
    
    if request.method == "POST":
        # Manejar archivo si existe
        file = request.files.get("imagen_perfil")
        headers = {"Authorization": f"Bearer {token}"}
        
        if file and file.filename != '':
            try:
                files = {'file': (file.filename, file.stream, file.mimetype)}
                requests.post(f"{API_URL}/usuarios/{user_id}/upload_perfil", headers=headers, files=files)
            except Exception as e:
                print("Error uploading profile picture:", e)

        # Manejar datos de texto
        nombre = request.form.get("nombre")
        telefono = request.form.get("telefono")
        
        try:
            # PUT normal
            requests.put(f"{API_URL}/usuarios/{user_id}", headers=headers, data={
                "nombre": nombre, "email": session["user"]["email"], "telefono": telefono, "rol": session["user"].get("rol", "user")
            })
        except: pass

        # Manejar Password
        actual_pass = request.form.get("actual_password")
        new_pass = request.form.get("password")
        
        if actual_pass and new_pass:
            try:
                res_pass = requests.patch(f"{API_URL}/usuarios/{user_id}/password", headers=headers, data={
                    "password_actual": actual_pass, "nueva_password": new_pass
                })
                if res_pass.status_code == 200:
                    flash("Contraseña actualizada exitosamente", "success")
                else:
                    flash(res_pass.json().get("detail", "Error al actualizar la contraseña"), "error")
            except Exception as e:
                flash("Error de conexión al cambiar contraseña", "error")
        else:
            flash("Perfil guardado", "success")

        # Refresh user info
        try:
            user_res = requests.get(f"{API_URL}/usuarios/me", headers=headers)
            if user_res.status_code == 200:
                session["user"] = user_res.json()
                session.modified = True
        except: pass

        return redirect("/perfil")

    user_pedidos = []
    
    try:
        headers = {"Authorization": f"Bearer {session.get('jwt_token')}"}
        res = requests.get(f"{API_URL}/pedidos/", headers=headers)
        if res.status_code == 200:
            all_pedidos = res.json()
            user_pedidos = [p for p in all_pedidos if p["usuario_id"] == user_id]
    except Exception as e:
        print("Error fetching orders:", e)

    total_gastado = 0
    productos = obtener_productos_api()
    producto_map = {p["id"]: p for p in productos}
    
    # Mapear datos completos para la plantilla
    pedidos_view = []
    for pedido in user_pedidos:
        pedido_view = {
            "id": pedido["id"], 
            "estado": pedido.get("estado", "recibido"), 
            "paqueteria": pedido.get("paqueteria"),
            "num_seguimiento": pedido.get("num_seguimiento"),
            "productos": [], 
            "total": 0
        }
        for det in pedido["productos"]:
            p_data = producto_map.get(det["autoparte_id"] if "autoparte_id" in det else det["id"])
            if p_data:
                # Flask template iteraba sin cantidades, simulamos los copias
                for _ in range(det["cantidad"]):
                    pedido_view["productos"].append(p_data)
                    pedido_view["total"] += p_data["precio"]
                    total_gastado += p_data["precio"]
        pedidos_view.append(pedido_view)

    usuario_info = {
        "nombre": session["user"].get("nombre", ""),
        "email": session["user"].get("email", ""),
        "telefono": session["user"].get("telefono", ""),
        "rol": session["user"].get("rol", "usuario")
    }

    return render_template(
        "perfil.html",
        pedidos=pedidos_view,
        total_gastado=total_gastado,
        user=usuario_info
    )

# 📋 MIS PEDIDOS (vista HTML)
@app.route("/pedidos")
def mis_pedidos():
    if "user" not in session:
        return redirect("/login")

    user_id = session["user"]["id"]
    pedidos_lista = []

    try:
        headers = {"Authorization": f"Bearer {session.get('jwt_token')}"}
        res = requests.get(f"{API_URL}/pedidos/", headers=headers)
        if res.status_code == 200:
            all_pedidos = res.json()
            pedidos_lista = [p for p in all_pedidos if p["usuario_id"] == user_id]
    except Exception as e:
        print("Error fetching orders:", e)

    # Calcular total por pedido
    productos = obtener_productos_api()
    producto_map = {p["id"]: p for p in productos}

    pedidos_view = []
    for pedido in pedidos_lista:
        total = 0
        for det in pedido.get("productos", []):
            p_data = producto_map.get(det.get("autoparte_id", det.get("id")))
            if p_data:
                total += p_data["precio"] * det["cantidad"]
        pedidos_view.append({
            "id": pedido["id"],
            "estado": pedido.get("estado", "en_proceso"),
            "fecha_pedido": pedido.get("fecha", "N/A"),
            "total": total
        })

    return render_template("pedidos.html", pedidos=pedidos_view)

# 🧾 VER RECIBO HTML
@app.route("/recibo/<int:pedido_id>")
def generar_recibo(pedido_id):
    if "user" not in session:
        return redirect("/login")

    pedido_data = None
    try:
        headers = {"Authorization": f"Bearer {session.get('jwt_token')}"}
        res = requests.get(f"{API_URL}/pedidos/", headers=headers)
        if res.status_code == 200:
            all_pedidos = res.json()
            user_pedidos = [p for p in all_pedidos if p["usuario_id"] == session["user"]["id"]]
            pedido_data = next((p for p in user_pedidos if p["id"] == pedido_id), None)
            
            if not pedido_data:
                return "Acceso denegado o pedido inexistente."
    except:
        return "Error de validación de seguridad."

    # Obtener productos para relacionarlos
    productos = obtener_productos_api()
    producto_map = {p["id"]: p for p in productos}
    
    total = 0
    detalles = []
    
    for item in pedido_data.get("productos", []):
        ap_id = item.get("autoparte_id", item.get("id"))
        p_data = producto_map.get(ap_id)
        if p_data:
            qty = item["cantidad"]
            precio = p_data["precio"]
            sub = precio * qty
            total += sub
            detalles.append({
                "cantidad": qty,
                "precio_unitario": precio,
                "subtotal": sub,
                "autoparte": {"nombre": p_data["nombre"]}
            })

    fecha_texto = pedido_data.get('fecha', 'N/A')
    
    pedido_obj = {
        "id": pedido_data["id"],
        "usuario": {
            "nombre": session["user"]["nombre"],
            "email": session["user"]["email"]
        },
        "fecha_pedido": fecha_texto,
        "total": total
    }

    return render_template("recibo.html", pedido=pedido_obj, detalles=detalles)

# ❌ CANCELAR PEDIDO
@app.route("/cancelar/<int:pedido_id>")
def cancelar_pedido(pedido_id):
    if "user" not in session:
        return redirect("/login")

    try:
        headers = {"Authorization": f"Bearer {session.get('jwt_token')}"}
        requests.delete(f"{API_URL}/pedidos/{pedido_id}", headers=headers)
    except Exception as e:
        print("Error cancelling order:", e)

    return redirect("/perfil")

# 🚪 LOGOUT
@app.route("/logout")
def logout():
    session.clear()
    return redirect("/login")

# 🚀 RUN SERVER
if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)