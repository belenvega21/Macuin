from sqlalchemy import Column, Integer, ForeignKey
from app.data.database import SessionLocal
from app.data.database import Base

class DetallePedido(Base):
    __tablename__ = "detalle_pedidos"

    id = Column(Integer, primary_key=True, index=True)
    pedido_id = Column(Integer, ForeignKey("pedidos.id"))
    autoparte_id = Column(Integer)
    cantidad = Column(Integer)