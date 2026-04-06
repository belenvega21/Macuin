from sqlalchemy import Column, Integer, ForeignKey
from app.data.database import SessionLocal
from app.data.database import Base

class Pedido(Base):
    __tablename__ = "pedidos"

    id = Column(Integer, primary_key=True, index=True)
    usuario_id = Column(Integer)