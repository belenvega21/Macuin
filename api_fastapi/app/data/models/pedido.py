from sqlalchemy import Column, Integer, String, ForeignKey, DateTime
from app.data.database import Base
from datetime import datetime

class Pedido(Base):
    __tablename__ = "pedidos"

    id = Column(Integer, primary_key=True, index=True)
    usuario_id = Column(Integer)
    estado = Column(String(30), default="recibido")
    fecha = Column(DateTime, default=datetime.utcnow)
    paqueteria = Column(String(100), nullable=True)
    num_seguimiento = Column(String(100), nullable=True)