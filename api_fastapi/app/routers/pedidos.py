from fastapi import APIRouter, Depends, HTTPException
from sqlalchemy.orm import Session
from app.data.database import SessionLocal
from app.models.pedido import Pedido
from app.models.detalle_pedido import DetallePedido
from app.schemas.pedido import PedidoBase

router = APIRouter(prefix="/pedidos", tags=["Pedidos"])

def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()

# 🛒 CREAR PEDIDO
@router.post("/")
def crear_pedido(data: PedidoBase, db: Session = Depends(get_db)):
    
    if not data.productos:
        raise HTTPException(status_code=400, detail="El pedido debe tener al menos un producto")

    nuevo_pedido = Pedido(usuario_id=data.usuario_id)
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
            "productos": detalles_creados
        }
    }


# 📦 CONSULTAR PEDIDOS (MEJORADO)
@router.get("/")
def obtener_pedidos(db: Session = Depends(get_db)):
    pedidos = db.query(Pedido).all()

    resultado = []

    for pedido in pedidos:
        detalles = db.query(DetallePedido).filter(DetallePedido.pedido_id == pedido.id).all()

        productos = []
        for d in detalles:
            productos.append({
                "autoparte_id": d.autoparte_id,
                "cantidad": d.cantidad
            })

        resultado.append({
            "id": pedido.id,
            "usuario_id": pedido.usuario_id,
            "productos": productos
        })

    return resultado