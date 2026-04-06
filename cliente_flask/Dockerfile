FROM python:3.11

WORKDIR /app

# Copia todo el proyecto
COPY . .

# Instala dependencias
RUN pip install --no-cache-dir -r requirements.txt

# (opcional pero ok)
COPY ./static /app/static
COPY ./templates /app/templates

# 👇 CORREGIDO
CMD ["python", "app.py"]


