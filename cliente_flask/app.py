from flask import Flask, render_template, request, redirect, url_for, send_file, session
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

    return render_template("catalogo.html", productos=filtrados, marcas=marcas_global)

# ➕ AGREGAR AL CARRITO (CORRECTO)
@app.route("/agregar/<int:producto_id>")
def agregar_carrito(producto_id):
    if "user" not in session:
        return redirect("/login")
        
    user_id = session["user"]["id"]

    if user_id not in carritos:
        carritos[user_id] = []

    productos = obtener_productos_api()
    producto = next((p for p in productos if p["id"] == producto_id), None)

    if producto:
        carritos[user_id].append(producto)

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
    except Exception as e:
        print("Error creating order:", e)

    return redirect("/perfil")

# 👤 PERFIL
@app.route("/perfil", methods=["GET", "POST"])
def perfil():
    if "user" not in session:
        return redirect("/login")

    user_id = session["user"]["id"]
    token = session.get('jwt_token')
    
    if request.method == "POST":
        file = request.files.get("imagen_perfil")
        if file and file.filename != '':
            try:
                headers = {"Authorization": f"Bearer {token}"}
                files = {'file': (file.filename, file.stream, file.mimetype)}
                res = requests.post(f"{API_URL}/usuarios/{user_id}/upload_perfil", headers=headers, files=files)
                if res.status_code == 200:
                    # Refresh user info
                    user_res = requests.get(f"{API_URL}/usuarios/me", headers=headers)
                    if user_res.status_code == 200:
                        session["user"] = user_res.json()
                        session.modified = True
            except Exception as e:
                print("Error uploading profile picture:", e)
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

    return render_template(
        "perfil.html",
        pedidos=pedidos_view,
        total_gastado=total_gastado,
        user=session["user"]
    )

# 🧾 GENERAR PDF
@app.route("/recibo/<int:pedido_id>")
def generar_recibo(pedido_id):
    if "user" not in session:
        return redirect("/login")

    try:
        headers = {"Authorization": f"Bearer {session.get('jwt_token')}"}
        res = requests.get(f"{API_URL}/pedidos/", headers=headers)
        if res.status_code == 200:
            all_pedidos = res.json()
            user_pedidos = [p for p in all_pedidos if p["usuario_id"] == session["user"]["id"]]
    except:
        user_pedidos = []

    pedido = next((p for p in user_pedidos if p["id"] == pedido_id), None)
    if not pedido:
        return "Pedido no encontrado"

    file_path = f"recibo_{session['user']['id']}_{pedido_id}.pdf"

    # Preparar el documento Platypus
    doc = SimpleDocTemplate(
        file_path, rightMargin=40, leftMargin=40, topMargin=40, bottomMargin=40,
        title=f"Recibo Macuin - Pedido #{pedido_id}",
        author="MACUIN Autopartes"
    )
    styles = getSampleStyleSheet()
    elementos = []

    # Encabezado (Logo simulado o Nombre de empresa + "Nota de Venta")
    title_table_data = [
        [Paragraph("<b>MACUIN AUTOPARTES</b>", styles["Heading1"]), Paragraph("<font size=12><b>Nota de venta</b></font>", styles["Normal"])]
    ]
    title_table = Table(title_table_data, colWidths=[300, 200])
    title_table.setStyle(TableStyle([
        ('ALIGN', (1, 0), (1, 0), 'RIGHT'),
        ('BACKGROUND', (1, 0), (1, 0), colors.HexColor("#f0f0f0")),
        ('PADDING', (1, 0), (1, 0), 10),
    ]))
    elementos.append(title_table)
    elementos.append(Spacer(1, 20))

    # Información general (Cliente, Ticket y Fecha)
    fecha_texto = pedido.get('fecha', 'N/A')
    if 'T' in fecha_texto:
        fecha_texto = fecha_texto.replace('T', ' ')[:16]

    info_data = [
        [Paragraph("<b>MACUIN</b><br/>Av. Paseo de la República<br/>Querétaro, México<br/>ventas@macuin.com.mx<br/>+524422216728", styles["Normal"]), 
         Paragraph(f"<b>Ticket:</b> VENTA-{pedido_id}<br/><b>Fecha:</b> {fecha_texto}<br/><b>Cliente:</b> {session['user']['nombre']}<br/><b>Status:</b> {pedido.get('estado', 'recibido')}", styles["Normal"])]
    ]
    info_table = Table(info_data, colWidths=[250, 250])
    info_table.setStyle(TableStyle([
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
        ('LINEAFTER', (0,0), (0,0), 1, colors.HexColor("#e0e0e0")),
        ('PADDING', (0,0), (-1,-1), 10),
    ]))
    elementos.append(info_table)
    elementos.append(Spacer(1, 20))

    # Tabla de productos
    tabla_productos_data = [["Producto", "Cantidad", "Precio Unitario", "Subtotal"]]
    total = 0
    
    productos = obtener_productos_api()
    producto_map = {p["id"]: p for p in productos}

    for det in pedido["productos"]:
        p_data = producto_map.get(det["autoparte_id"] if "autoparte_id" in det else det["id"])
        if p_data:
            qty = det["cantidad"]
            precio = p_data["precio"]
            subtotal = precio * qty
            tabla_productos_data.append([
                Paragraph(f"<font size=10>{p_data['nombre']}</font><br/><font size=8 color=gray>{p_data['marca']} | ID: {p_data['id']}</font>", styles["Normal"]),
                f"{qty} piezas",
                f"${precio:,.2f}",
                f"${subtotal:,.2f}"
            ])
            total += subtotal

    tabla_prod = Table(tabla_productos_data, colWidths=[250, 70, 90, 90])
    tabla_prod.setStyle(TableStyle([
        ('FONTNAME', (0,0), (-1,0), 'Helvetica-Bold'),
        ('LINEBELOW', (0,0), (-1,0), 1, colors.black),
        ('ALIGN', (1,0), (-1,-1), 'CENTER'),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('BOTTOMPADDING', (0,0), (-1,-1), 10),
        ('TOPPADDING', (0,0), (-1,-1), 10),
        ('LINEBELOW', (0,1), (-1,-2), 0.5, colors.HexColor("#e0e0e0")),
    ]))
    elementos.append(tabla_prod)
    elementos.append(Spacer(1, 20))

    # Cuadro de totales
    iva = total * 0.16
    total_con_iva = total + iva
    totales_data = [
        ["Subtotal", f"${total:,.2f}"],
        ["IVA 16%", f"${iva:,.2f}"],
        [Paragraph("<b>Total a Pagar</b>", styles["Normal"]), Paragraph(f"<b>${total_con_iva:,.2f}</b>", styles["Normal"])]
    ]
    totales_table = Table(totales_data, colWidths=[100, 100])
    totales_table.setStyle(TableStyle([
        ('ALIGN', (0,0), (-1,-1), 'RIGHT'),
        ('LINEABOVE', (0,2), (1,2), 1, colors.black),
        ('PADDING', (0,0), (-1,-1), 5),
    ]))
    
    # Grid de Totales a la derecha
    layout_totales = Table([["", totales_table]], colWidths=[300, 200])
    elementos.append(layout_totales)
    
    elementos.append(Spacer(1, 30))
    elementos.append(Paragraph("<font size=10 color=gray>Gracias por tu compra — MACUIN Autopartes.</font>", styles["Normal"]))

    doc.build(elementos)

    # Evitar cache de navegador modificando el nombre de descarga
    import time
    timestamp = int(time.time())
    nombre_descarga = f"Recibo_Macuin_Pedido_{pedido_id}_{timestamp}.pdf"

    return send_file(file_path, as_attachment=False, download_name=nombre_descarga)

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