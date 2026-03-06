from flask import Flask, render_template, request, redirect, url_for

app = Flask(__name__)


#RUTA LOGIN

@app.route('/')
def home():
    return render_template("login.html")


@app.route('/login', methods=['POST'])
def login():

    email = request.form['email']
    password = request.form['password']

    print(email, password)

    return redirect(url_for('inicio'))


#RUTA REGISTRO

@app.route('/registro')
def registro():
    return render_template("registro.html")


@app.route('/registro', methods=['POST'])
def guardar_usuario():

    nombre = request.form['nombre']
    email = request.form['email']
    telefono = request.form['telefono']
    password = request.form['password']

    print(nombre, email, telefono, password)

    return redirect('/')


#RUTA INICIO

@app.route('/inicio')
def inicio():
    return render_template("inicio.html")


#RUTA CATALOGO

@app.route('/catalogo')
def catalogo():
    return render_template("catalogo.html")


#RUTA CARRITO

@app.route('/carrito')
def carrito():
    return render_template("carrito.html")


#RUTA PEDIDOS

@app.route('/pedidos')
def pedidos():
    return render_template("pedidos.html")


#RUTA RECIBO / DETALLE PEDIDO

@app.route('/recibo')
def recibo():
    return render_template("recibo.html")


#EJECUTAR SERVIDOR

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
    
    
    
    #docker compose down
    #UN SOLO COMANDO docker compose up --build