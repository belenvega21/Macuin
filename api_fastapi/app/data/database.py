from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker, declarative_base

# conexión a postgres (Docker)
DATABASE_URL = "postgresql://postgres:password@db:5432/macuin"

engine = create_engine(DATABASE_URL)

SessionLocal = sessionmaker(bind=engine)

Base = declarative_base()