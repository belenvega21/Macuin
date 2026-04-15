from fastapi import APIRouter, Depends, HTTPException
from sqlalchemy.orm import Session
from app.data.database import SessionLocal
from app.data.models.detalle_pedido import DetallePedido
from app.data.models.pedido import Pedido
from app.data.models.autoparte import Autoparte
from app.data.schemas.pedido import PedidoBase, PedidoEstado
from app.core.security import get_current_user, get_current_admin

router = APIRouter(prefix="/pedidos", tags=["Pedidos"])

def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()

# CREAR PEDIDO
@router.post("/", summary="Crear pedido")
def crear_pedido(data: PedidoBase, current_user: dict = Depends(get_current_user), db: Session = Depends(get_db)):
    
    if not data.productos:
        raise HTTPException(status_code=400, detail="El pedido debe tener al menos un producto")

    nuevo_pedido = Pedido(usuario_id=data.usuario_id, estado="recibido")
    db.add(nuevo_pedido)
    db.commit()
    db.refresh(nuevo_pedido)

    detalles_creados = []

    for producto in data.productos:
        detalle = DetallePedido(
            pedido_id=nuevo_pedido.id,
            autoparte_id=producto.id,
            cantidad=producto.cantidad
        )
        db.add(detalle)

        detalles_creados.append({
            "autoparte_id": producto.id,
            "cantidad": producto.cantidad
        })

    db.commit()

    return {
        "msg": "Pedido creado correctamente",
        "pedido": {
            "id": nuevo_pedido.id,
            "usuario_id": nuevo_pedido.usuario_id,
            "estado": nuevo_pedido.estado,
            "productos": detalles_creados
        }
    }


# CONSULTAR TODOS LOS PEDIDOS (público para dashboard)
@router.get("/", summary="Consultar todos los pedidos")
def obtener_pedidos(db: Session = Depends(get_db)):
    pedidos = db.query(Pedido).all()
    autopartes_map = {a.id: a for a in db.query(Autoparte).all()}

    resultado = []

    for pedido in pedidos:
        detalles = db.query(DetallePedido).filter(DetallePedido.pedido_id == pedido.id).all()

        productos = []
        total = 0
        for d in detalles:
            ap = autopartes_map.get(d.autoparte_id)
            precio_unitario = ap.precio if ap else 0
            subtotal = precio_unitario * d.cantidad
            total += subtotal
            productos.append({
                "autoparte_id": d.autoparte_id,
                "cantidad": d.cantidad,
                "precio_unitario": float(precio_unitario),
                "subtotal": float(subtotal)
            })

        resultado.append({
            "id": pedido.id,
            "usuario_id": pedido.usuario_id,
            "estado": pedido.estado,
            "fecha": pedido.fecha.isoformat() if pedido.fecha else None,
            "paqueteria": pedido.paqueteria,
            "num_seguimiento": pedido.num_seguimiento,
            "total": float(total),
            "productos": productos,
            "detalles": productos
        })

    return resultado


# CAMBIAR ESTADO DE PEDIDO
@router.put("/{pedido_id}/estado", summary="Cambiar estado de pedido")
def cambiar_estado(pedido_id: int, data: PedidoEstado, current_user: dict = Depends(get_current_admin), db: Session = Depends(get_db)):
    pedido = db.query(Pedido).filter(Pedido.id == pedido_id).first()
    if not pedido:
        raise HTTPException(status_code=404, detail="Pedido no encontrado")

    estados_validos = ["recibido", "en_proceso", "enviado", "en_ruta", "entregado", "cancelado"]
    if data.estado not in estados_validos:
        raise HTTPException(status_code=400, detail=f"Estado inválido. Opciones: {estados_validos}")

    pedido.estado = data.estado
    if data.paqueteria:
        pedido.paqueteria = data.paqueteria
    if data.num_seguimiento:
        pedido.num_seguimiento = data.num_seguimiento
        
    db.commit()
    return {"msg": f"Estado del pedido {pedido_id} actualizado a '{data.estado}'"}


# CANCELAR PEDIDO
@router.delete("/{pedido_id}", summary="Cancelar pedido")
def cancelar_pedido(pedido_id: int, current_user: dict = Depends(get_current_user), db: Session = Depends(get_db)):
    pedido = db.query(Pedido).filter(Pedido.id == pedido_id).first()
    if not pedido:
        raise HTTPException(status_code=404, detail="Pedido no encontrado")

    pedido.estado = "cancelado"
    db.commit()
    return {"msg": f"Pedido {pedido_id} cancelado exitosamente"}