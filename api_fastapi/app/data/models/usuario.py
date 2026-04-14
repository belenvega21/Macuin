from sqlalchemy import Column, Integer, String
from app.data.database import Base

class Usuario(Base):
    __tablename__ = "usuarios"

    id = Column(Integer, primary_key=True, index=True)
    nombre = Column(String, nullable=False)
    email = Column(String, unique=True, index=True)
    telefono = Column(String)
    password = Column(String)
    rol = Column(String, default="cliente")
    imagen_perfil = Column(String, default="https://via.placeholder.com/150?text=Perfil")