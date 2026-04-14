import os
import jwt
from datetime import datetime, timedelta
from passlib.context import CryptContext
from fastapi import Depends, HTTPException, status
from fastapi.security import OAuth2PasswordBearer

# Configuración del Cifrado
pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto")

# JWT Secret Key
SECRET_KEY = os.getenv("SECRET_KEY", "b4s3_s3cr3t4_m4cuin_p4ssw0rd")
ALGORITHM = "HS256"
ACCESS_TOKEN_EXPIRE_HOURS = 24

# OAuth2 scheme — esto pone el candadito en Swagger
oauth2_scheme = OAuth2PasswordBearer(tokenUrl="/usuarios/login")


def verify_password(plain_password, hashed_password):
    """Verifica una contraseña contra su hash"""
    return pwd_context.verify(plain_password, hashed_password)


def get_password_hash(password):
    """Genera un hash bcrypt"""
    return pwd_context.hash(password)


def create_access_token(data: dict, expires_delta: timedelta | None = None):
    """Crea token JWT basado en un payload dict"""
    to_encode = data.copy()
    if expires_delta:
        expire = datetime.utcnow() + expires_delta
    else:
        expire = datetime.utcnow() + timedelta(hours=ACCESS_TOKEN_EXPIRE_HOURS)
    
    to_encode.update({"exp": expire})
    encoded_jwt = jwt.encode(to_encode, SECRET_KEY, algorithm=ALGORITHM)
    return encoded_jwt


def get_current_user(token: str = Depends(oauth2_scheme)):
    """Decodifica el JWT y retorna los datos del usuario. 
    Los endpoints que usen esta dependencia mostraran el candado en Swagger."""
    credentials_exception = HTTPException(
        status_code=status.HTTP_401_UNAUTHORIZED,
        detail="Token inválido o expirado",
        headers={"WWW-Authenticate": "Bearer"},
    )
    try:
        payload = jwt.decode(token, SECRET_KEY, algorithms=[ALGORITHM])
        email: str = payload.get("sub")
        if email is None:
            raise credentials_exception
        return {
            "email": email,
            "usuario_id": payload.get("usuario_id"),
            "rol": payload.get("rol")
        }
    except jwt.ExpiredSignatureError:
        raise credentials_exception
    except jwt.InvalidTokenError:
        raise credentials_exception

def get_current_admin(current_user: dict = Depends(get_current_user)):
    """Verifica explícitamente que el usuario tenga el rol 'admin'."""
    if current_user.get("rol") != "admin":
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="Operación denegada. Se requieren privilegios de Administrador."
        )
    return current_user
