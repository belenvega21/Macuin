from sqlalchemy import Column, Integer, String, Float
from app.data.database import SessionLocal
from app.data.database import Base

class Autoparte(Base):
    __tablename__ = "autopartes"

    id = Column(Integer, primary_key=True, index=True)
    sku = Column(String(50), nullable=True, unique=True)
    nombre = Column(String(100))
    descripcion = Column(String(200))
    precio = Column(Float)
    stock = Column(Integer)
    imagen = Column(String(200), default="/static/img/car.webp")
    categoria = Column(String(50), default="motor")
    marca = Column(String(50), default="desconocida")
    modelo = Column(String(50), default="general")
