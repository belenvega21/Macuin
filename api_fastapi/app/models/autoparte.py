from sqlalchemy import Column, Integer, String, Float
from app.data.database import SessionLocal
from app.data.database import Base

class Autoparte(Base):
    __tablename__ = "autopartes"

    id = Column(Integer, primary_key=True, index=True)
    nombre = Column(String(100))
    descripcion = Column(String(200))
    precio = Column(Float)
    stock = Column(Integer)
    
    
    
