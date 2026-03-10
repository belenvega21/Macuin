from flask import Flask, render_template, request, redirect, url_for

app = Flask(__name__)

@app.route('/')
def home():
    return render_template("login.html")

@app.route('/login', methods=['POST'])
def login():

    email = request.form.get('email')
    password = request.form.get('password')

    print(email, password)

    return redirect(url_for('inicio'))

@app.route('/registro')
def registro():
    return render_template("registro.html")

@app.route('/registro', methods=['POST'])
def guardar_usuario():
    nombre = request.form.get('nombre')
    email = request.form.get('email')
    telefono = request.form.get('telefono')
    password = request.form.get('password')

    print(nombre, email, telefono, password)

    return redirect(url_for('home'))

@app.route('/inicio')
def inicio():
    return render_template("inicio.html")

@app.route('/catalogo')
def catalogo():
    return render_template("catalogo.html")

@app.route('/carrito')
def carrito():
    return render_template("carrito.html")

@app.route('/pedidos')
def pedidos():
    return render_template("pedidos.html")

@app.route('/recibo')
def recibo():
    return render_template("recibo.html")

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)