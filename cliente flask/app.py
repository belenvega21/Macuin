from flask import Flask, render_template, redirect, url_for

app = Flask(__name__)

# --------------------
# AUTENTICACIÓN
# --------------------

@app.route('/')
def login():
    return render_template('login.html')

@app.route('/registro')
def registro():
    return render_template('registro.html')


# --------------------
# DASHBOARD
# --------------------

@app.route('/dashboard')
def dashboard():
    return render_template('dashboard.html')


# --------------------
# AUTOPARTES
# --------------------

@app.route('/autopartes')
def autopartes():
    return render_template('autopartes.html')

@app.route('/registrar_pieza')
def registrar_pieza():
    return render_template('registrar_pieza.html')

@app.route('/editar_pieza')
def editar_pieza():
    return render_template('editar_pieza.html')


# --------------------
# PEDIDOS
# --------------------

@app.route('/pedidos')
def pedidos():
    return render_template('pedidos.html')

@app.route('/acciones_pedido')
def acciones_pedido():
    return render_template('acciones_pedido.html')

@app.route('/reporte_pdf')
def reporte_pdf():
    return render_template('reporte_pdf.html')


# --------------------
# CLIENTES
# --------------------

@app.route('/clientes')
def clientes():
    return render_template('clientes.html')

@app.route('/historial_clientes')
def historial_clientes():
    return render_template('historial_clientes.html')


# --------------------
# REPORTES
# --------------------

@app.route('/reportes')
def reportes():
    return render_template('reportes.html')


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
    