from fastapi import APIRouter, Depends
from sqlalchemy.orm import Session
from app.data.database import SessionLocal
from app.data.models.autoparte import Autoparte
from app.data.models.pedido import Pedido
from app.data.models.detalle_pedido import DetallePedido
from app.data.models.usuario import Usuario
import pandas as pd
from fastapi.responses import FileResponse
from docx import Document 
from reportlab.platypus import SimpleDocTemplate, Paragraph, Table, TableStyle, Spacer
from reportlab.lib.styles import getSampleStyleSheet
from reportlab.lib import colors

router = APIRouter(prefix="/reportes", tags=["Reportes"])


def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()


# inventario json
@router.get("/inventario")
def reporte_inventario(db: Session = Depends(get_db)):
    return db.query(Autoparte).all()


# total de productos
@router.get("/total-productos")
def total_productos(db: Session = Depends(get_db)):
    total = db.query(Autoparte).count()
    return {"total_productos": total}


# pedidos
@router.get("/pedidos")
def total_pedidos(db: Session = Depends(get_db)):
    total = db.query(Pedido).count()
    return {"total_pedidos": total}


# mas vendidos
@router.get("/mas-vendidos")
def productos_mas_vendidos(db: Session = Depends(get_db)):
    detalles = db.query(DetallePedido).all()

    conteo = {}
    for d in detalles:
        conteo[d.autoparte_id] = conteo.get(d.autoparte_id, 0) + d.cantidad

    return conteo


# clientes json
@router.get("/clientes")
def reporte_clientes(db: Session = Depends(get_db)):
    usuarios = db.query(Usuario).all()
    return [
        {
            "id": u.id,
            "nombre": u.nombre,
            "email": u.email,
            "telefono": u.telefono,
            "rol": u.rol
        }
        for u in usuarios
    ]


# inventario excel
@router.get("/inventario/excel")
def exportar_inventario_excel(db: Session = Depends(get_db)):
    from openpyxl.styles import Font, PatternFill, Alignment, Border, Side
    productos = db.query(Autoparte).all()

    data = [
        {"SKU": p.sku or "N/A", "Nombre": p.nombre, "Marca": p.marca, "Categoría": p.categoria, "Precio": p.precio, "Stock": p.stock}
        for p in productos
    ]

    df = pd.DataFrame(data)
    file_path = "/tmp/reporte_inventario.xlsx"
    df.to_excel(file_path, index=False, startrow=2)

    from openpyxl import load_workbook
    wb = load_workbook(file_path)
    ws = wb.active
    ws.title = "Inventario"

    header_font = Font(name="Calibri", bold=True, size=14, color="333333")
    sub_font = Font(name="Calibri", size=10, color="666666")
    col_header_font = Font(name="Calibri", bold=True, size=10, color="FFFFFF")
    col_header_fill = PatternFill(start_color="333333", end_color="333333", fill_type="solid")
    even_fill = PatternFill(start_color="F5F5F5", end_color="F5F5F5", fill_type="solid")
    border = Border(bottom=Side(style="thin", color="E0E0E0"))
    currency_fmt = '"$"#,##0.00'

    ws.merge_cells("A1:F1")
    ws["A1"] = "MACUIN AUTOPARTES — Reporte de Inventario"
    ws["A1"].font = header_font
    ws["A2"] = f"Total de referencias: {len(productos)} piezas únicas"
    ws["A2"].font = sub_font

    for col_idx in range(1, 7):
        cell = ws.cell(row=3, column=col_idx)
        cell.font = col_header_font
        cell.fill = col_header_fill
        cell.alignment = Alignment(horizontal="center")

    for row_idx in range(4, ws.max_row + 1):
        if row_idx % 2 == 0:
            for col_idx in range(1, 7):
                ws.cell(row=row_idx, column=col_idx).fill = even_fill
        for col_idx in range(1, 7):
            ws.cell(row=row_idx, column=col_idx).border = border
        ws.cell(row=row_idx, column=5).number_format = currency_fmt

    ws.column_dimensions["A"].width = 16
    ws.column_dimensions["B"].width = 30
    ws.column_dimensions["C"].width = 16
    ws.column_dimensions["D"].width = 16
    ws.column_dimensions["E"].width = 14
    ws.column_dimensions["F"].width = 10

    wb.save(file_path)
    return FileResponse(file_path, filename="reporte_inventario.xlsx")


# inventario word
@router.get("/inventario/word")
def exportar_inventario_word(db: Session = Depends(get_db)):
    from docx.shared import Inches, Pt, RGBColor
    from docx.enum.table import WD_TABLE_ALIGNMENT
    productos = db.query(Autoparte).all()

    doc = Document()
    doc.add_heading("MACUIN AUTOPARTES", 0)
    doc.add_heading("Reporte de Inventario", level=2)
    doc.add_paragraph(f"Total de referencias: {len(productos)} piezas únicas")
    doc.add_paragraph("")

    table = doc.add_table(rows=1, cols=6)
    table.style = "Table Grid"
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    headers = ["SKU", "Nombre", "Marca", "Categoría", "Precio", "Stock"]
    hdr = table.rows[0]
    for i, h in enumerate(headers):
        cell = hdr.cells[i]
        cell.text = h
        for p in cell.paragraphs:
            for run in p.runs:
                run.bold = True
                run.font.size = Pt(9)
                run.font.color.rgb = RGBColor(255, 255, 255)
        from docx.oxml.ns import qn
        shading = cell._element.get_or_add_tcPr()
        shading_elm = shading.makeelement(qn('w:shd'), {qn('w:fill'): '333333', qn('w:val'): 'clear'})
        shading.append(shading_elm)

    for prod in productos:
        row = table.add_row().cells
        row[0].text = prod.sku or "N/A"
        row[1].text = prod.nombre
        row[2].text = prod.marca or ""
        row[3].text = prod.categoria or ""
        row[4].text = f"${prod.precio:,.2f}"
        row[5].text = f"{prod.stock} pz"

    file_path = "/tmp/reporte_inventario.docx"
    doc.save(file_path)
    return FileResponse(file_path, filename="reporte_inventario.docx")


# inventario pdf
@router.get("/inventario/pdf")
def exportar_inventario_pdf(db: Session = Depends(get_db)):
    productos = db.query(Autoparte).all()

    file_path = "/tmp/reporte_inventario.pdf"
    doc = SimpleDocTemplate(file_path, rightMargin=40, leftMargin=40, topMargin=40, bottomMargin=40, title="Reporte de Inventario", author="MACUIN Autopartes")
    styles = getSampleStyleSheet()

    elementos = []
    
    # Encabezado
    header_data = [
        [Paragraph("<b>MACUIN AUTOPARTES</b>", styles["Heading1"]), Paragraph("<font size=12><b>Reporte de Inventario</b></font>", styles["Normal"])]
    ]
    header_table = Table(header_data, colWidths=[300, 200])
    header_table.setStyle(TableStyle([
        ('ALIGN', (1, 0), (1, 0), 'RIGHT'),
        ('BACKGROUND', (1, 0), (1, 0), colors.HexColor("#f0f0f0")),
        ('PADDING', (1, 0), (1, 0), 10),
    ]))
    elementos.append(header_table)
    elementos.append(Spacer(1, 20))

    # Info General
    elementos.append(Paragraph(f"<b>Total de referencias:</b> {len(productos)} piezas únicas", styles["Normal"]))
    elementos.append(Spacer(1, 10))

    # Tabla Inventario
    tabla_data = [["ID", "Nombre", "Marca", "Stock", "Precio Público"]]
    for p in productos:
        tabla_data.append([
            f"#{p.id}",
            p.nombre,
            p.marca,
            f"{p.stock} pz",
            f"${p.precio:,.2f}"
        ])

    t = Table(tabla_data, colWidths=[50, 200, 100, 70, 80])
    t.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), colors.HexColor("#333333")),
        ('TEXTCOLOR', (0,0), (-1,0), colors.white),
        ('FONTNAME', (0,0), (-1,0), 'Helvetica-Bold'),
        ('ALIGN', (3,1), (-1,-1), 'RIGHT'),
        ('LINEBELOW', (0,0), (-1,-1), 0.5, colors.HexColor("#e0e0e0")),
        ('PADDING', (0,0), (-1,-1), 8),
    ]))
    elementos.append(t)

    doc.build(elementos)

    return FileResponse(file_path, media_type="application/pdf", headers={"Content-Disposition": "inline; filename=\"reporte_inventario.pdf\""})


# clientes excel
@router.get("/clientes/excel")
def exportar_clientes_excel(db: Session = Depends(get_db)):
    from openpyxl.styles import Font, PatternFill, Alignment, Border, Side
    usuarios = db.query(Usuario).all()
    data = [{"ID": u.id, "Nombre": u.nombre, "Email": u.email, "Teléfono": u.telefono or "N/A", "Rol": u.rol} for u in usuarios]
    df = pd.DataFrame(data)
    file_path = "/tmp/reporte_clientes.xlsx"
    df.to_excel(file_path, index=False, startrow=2)
    from openpyxl import load_workbook
    wb = load_workbook(file_path)
    ws = wb.active
    ws.title = "Clientes"
    ws.merge_cells("A1:E1")
    ws["A1"] = "MACUIN AUTOPARTES — Directorio de Clientes"
    ws["A1"].font = Font(name="Calibri", bold=True, size=14, color="333333")
    ws["A2"] = f"N° de socios: {len(usuarios)} registrados"
    ws["A2"].font = Font(name="Calibri", size=10, color="666666")
    hdr_font = Font(name="Calibri", bold=True, size=10, color="FFFFFF")
    hdr_fill = PatternFill(start_color="333333", end_color="333333", fill_type="solid")
    even_fill = PatternFill(start_color="F5F5F5", end_color="F5F5F5", fill_type="solid")
    border = Border(bottom=Side(style="thin", color="E0E0E0"))
    for c in range(1, 6):
        cell = ws.cell(row=3, column=c)
        cell.font = hdr_font
        cell.fill = hdr_fill
        cell.alignment = Alignment(horizontal="center")
    for r in range(4, ws.max_row + 1):
        if r % 2 == 0:
            for c in range(1, 6): ws.cell(row=r, column=c).fill = even_fill
        for c in range(1, 6): ws.cell(row=r, column=c).border = border
    ws.column_dimensions["A"].width = 8
    ws.column_dimensions["B"].width = 25
    ws.column_dimensions["C"].width = 30
    ws.column_dimensions["D"].width = 16
    ws.column_dimensions["E"].width = 12
    wb.save(file_path)
    return FileResponse(file_path, filename="reporte_clientes.xlsx")


# clientes word
@router.get("/clientes/word")
def exportar_clientes_word(db: Session = Depends(get_db)):
    from docx.shared import Pt, RGBColor
    from docx.enum.table import WD_TABLE_ALIGNMENT
    usuarios = db.query(Usuario).all()
    doc = Document()
    doc.add_heading("MACUIN AUTOPARTES", 0)
    doc.add_heading("Directorio de Clientes", level=2)
    doc.add_paragraph(f"N° de socios: {len(usuarios)} registrados")
    doc.add_paragraph("")
    table = doc.add_table(rows=1, cols=5)
    table.style = "Table Grid"
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    headers = ["ID", "Nombre / Razón Social", "Contacto", "Teléfono", "Rol"]
    hdr = table.rows[0]
    for i, h in enumerate(headers):
        cell = hdr.cells[i]
        cell.text = h
        for p in cell.paragraphs:
            for run in p.runs:
                run.bold = True
                run.font.size = Pt(9)
                run.font.color.rgb = RGBColor(255, 255, 255)
        from docx.oxml.ns import qn
        shading = cell._element.get_or_add_tcPr()
        shading_elm = shading.makeelement(qn('w:shd'), {qn('w:fill'): '333333', qn('w:val'): 'clear'})
        shading.append(shading_elm)
    for u in usuarios:
        row = table.add_row().cells
        row[0].text = f"#{u.id}"
        row[1].text = u.nombre
        row[2].text = u.email
        row[3].text = u.telefono or "N/A"
        row[4].text = u.rol
    file_path = "/tmp/reporte_clientes.docx"
    doc.save(file_path)
    return FileResponse(file_path, filename="reporte_clientes.docx")


# clientes pdf
@router.get("/clientes/pdf")
def exportar_clientes_pdf(db: Session = Depends(get_db)):
    usuarios = db.query(Usuario).all()

    file_path = "/tmp/reporte_clientes.pdf"
    doc = SimpleDocTemplate(file_path, rightMargin=40, leftMargin=40, topMargin=40, bottomMargin=40, title="Reporte de Clientes", author="MACUIN Autopartes")
    styles = getSampleStyleSheet()

    elementos = []
    
    header_data = [
        [Paragraph("<b>MACUIN AUTOPARTES</b>", styles["Heading1"]), Paragraph("<font size=12><b>Directorio de Clientes</b></font>", styles["Normal"])]
    ]
    header_table = Table(header_data, colWidths=[300, 200])
    header_table.setStyle(TableStyle([
        ('ALIGN', (1, 0), (1, 0), 'RIGHT'),
        ('BACKGROUND', (1, 0), (1, 0), colors.HexColor("#f0f0f0")),
        ('PADDING', (1, 0), (1, 0), 10),
    ]))
    elementos.append(header_table)
    elementos.append(Spacer(1, 20))

    elementos.append(Paragraph(f"<b>N° de socios:</b> {len(usuarios)} registrados", styles["Normal"]))
    elementos.append(Spacer(1, 10))

    tabla_data = [["ID", "Nombre / Razón Social", "Contacto", "Teléfono", "Rol"]]
    for u in usuarios:
        tabla_data.append([
            f"#{u.id}",
            u.nombre,
            u.email,
            u.telefono or "N/A",
            u.rol
        ])

    t = Table(tabla_data, colWidths=[40, 160, 160, 80, 60])
    t.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), colors.HexColor("#333333")),
        ('TEXTCOLOR', (0,0), (-1,0), colors.white),
        ('FONTNAME', (0,0), (-1,0), 'Helvetica-Bold'),
        ('LINEBELOW', (0,0), (-1,-1), 0.5, colors.HexColor("#e0e0e0")),
        ('PADDING', (0,0), (-1,-1), 8),
    ]))
    elementos.append(t)

    doc.build(elementos)

    return FileResponse(file_path, media_type="application/pdf", headers={"Content-Disposition": "inline; filename=\"reporte_clientes.pdf\""})


# pedidos excel
@router.get("/pedidos/excel")
def exportar_pedidos_excel(db: Session = Depends(get_db)):
    from openpyxl.styles import Font, PatternFill, Alignment, Border, Side
    pedidos_db = db.query(Pedido).all()
    data = []
    for p in pedidos_db:
        detalles = db.query(DetallePedido).filter(DetallePedido.pedido_id == p.id).all()
        data.append({"Pedido": f"ORD-{str(p.id).zfill(4)}", "Usuario ID": p.usuario_id, "Estado": p.estado, "Artículos": len(detalles)})
    df = pd.DataFrame(data)
    file_path = "/tmp/reporte_pedidos.xlsx"
    df.to_excel(file_path, index=False, startrow=2)
    from openpyxl import load_workbook
    wb = load_workbook(file_path)
    ws = wb.active
    ws.title = "Pedidos"
    ws.merge_cells("A1:D1")
    ws["A1"] = "MACUIN AUTOPARTES — Reporte Histórico de Pedidos"
    ws["A1"].font = Font(name="Calibri", bold=True, size=14, color="333333")
    ws["A2"] = f"Total: {len(pedidos_db)} pedidos procesados"
    ws["A2"].font = Font(name="Calibri", size=10, color="666666")
    hdr_font = Font(name="Calibri", bold=True, size=10, color="FFFFFF")
    hdr_fill = PatternFill(start_color="333333", end_color="333333", fill_type="solid")
    even_fill = PatternFill(start_color="F5F5F5", end_color="F5F5F5", fill_type="solid")
    border = Border(bottom=Side(style="thin", color="E0E0E0"))
    for c in range(1, 5):
        cell = ws.cell(row=3, column=c)
        cell.font = hdr_font
        cell.fill = hdr_fill
        cell.alignment = Alignment(horizontal="center")
    for r in range(4, ws.max_row + 1):
        if r % 2 == 0:
            for c in range(1, 5): ws.cell(row=r, column=c).fill = even_fill
        for c in range(1, 5): ws.cell(row=r, column=c).border = border
    ws.column_dimensions["A"].width = 16
    ws.column_dimensions["B"].width = 14
    ws.column_dimensions["C"].width = 20
    ws.column_dimensions["D"].width = 14
    wb.save(file_path)
    return FileResponse(file_path, filename="reporte_pedidos.xlsx")


# pedidos word
@router.get("/pedidos/word")
def exportar_pedidos_word(db: Session = Depends(get_db)):
    from docx.shared import Pt, RGBColor
    from docx.enum.table import WD_TABLE_ALIGNMENT
    pedidos_db = db.query(Pedido).all()
    doc = Document()
    doc.add_heading("MACUIN AUTOPARTES", 0)
    doc.add_heading("Reporte Histórico de Pedidos", level=2)
    doc.add_paragraph(f"Total: {len(pedidos_db)} pedidos procesados")
    doc.add_paragraph("")
    table = doc.add_table(rows=1, cols=4)
    table.style = "Table Grid"
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    headers = ["Pedido", "Usuario ID", "Estado", "Artículos"]
    hdr = table.rows[0]
    for i, h in enumerate(headers):
        cell = hdr.cells[i]
        cell.text = h
        for p in cell.paragraphs:
            for run in p.runs:
                run.bold = True
                run.font.size = Pt(9)
                run.font.color.rgb = RGBColor(255, 255, 255)
        from docx.oxml.ns import qn
        shading = cell._element.get_or_add_tcPr()
        shading_elm = shading.makeelement(qn('w:shd'), {qn('w:fill'): '333333', qn('w:val'): 'clear'})
        shading.append(shading_elm)
    for p in pedidos_db:
        detalles = db.query(DetallePedido).filter(DetallePedido.pedido_id == p.id).all()
        row = table.add_row().cells
        row[0].text = f"ORD-{str(p.id).zfill(4)}"
        row[1].text = f"ID: {p.usuario_id}"
        row[2].text = p.estado
        row[3].text = f"{len(detalles)} items"
    file_path = "/tmp/reporte_pedidos.docx"
    doc.save(file_path)
    return FileResponse(file_path, filename="reporte_pedidos.docx")


# pedidos pdf
@router.get("/pedidos/pdf")
def exportar_pedidos_pdf(db: Session = Depends(get_db)):
    pedidos = db.query(Pedido).all()

    file_path = "/tmp/reporte_pedidos.pdf"
    doc = SimpleDocTemplate(file_path, rightMargin=40, leftMargin=40, topMargin=40, bottomMargin=40, title="Reporte de Pedidos", author="MACUIN Autopartes")
    styles = getSampleStyleSheet()

    elementos = []
    
    header_data = [
        [Paragraph("<b>MACUIN AUTOPARTES</b>", styles["Heading1"]), Paragraph("<font size=12><b>Reporte Histórico de Pedidos</b></font>", styles["Normal"])]
    ]
    header_table = Table(header_data, colWidths=[200, 300])
    header_table.setStyle(TableStyle([
        ('ALIGN', (1, 0), (1, 0), 'RIGHT'),
        ('BACKGROUND', (1, 0), (1, 0), colors.HexColor("#f0f0f0")),
        ('PADDING', (1, 0), (1, 0), 10),
    ]))
    elementos.append(header_table)
    elementos.append(Spacer(1, 20))

    elementos.append(Paragraph(f"<b>Total:</b> {len(pedidos)} pedidos procesados", styles["Normal"]))
    elementos.append(Spacer(1, 10))

    tabla_data = [["Pedido", "Usuario ID", "Estado", "Artículos"]]
    for p in pedidos:
        tabla_data.append([
            f"ORD-{str(p.id).zfill(4)}",
            f"ID: {p.usuario_id}",
            p.estado,
            f"{len(p.productos)} items"
        ])

    t = Table(tabla_data, colWidths=[80, 100, 160, 100])
    t.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), colors.HexColor("#333333")),
        ('TEXTCOLOR', (0,0), (-1,0), colors.white),
        ('FONTNAME', (0,0), (-1,0), 'Helvetica-Bold'),
        ('LINEBELOW', (0,0), (-1,-1), 0.5, colors.HexColor("#e0e0e0")),
        ('PADDING', (0,0), (-1,-1), 8),
        ('ALIGN', (3,1), (3,-1), 'CENTER'),
    ]))
    elementos.append(t)

    doc.build(elementos)

    return FileResponse(file_path, media_type="application/pdf", headers={"Content-Disposition": "inline; filename=\"reporte_pedidos.pdf\""})


# ventas excel
@router.get("/ventas/excel")
def exportar_ventas_excel(db: Session = Depends(get_db)):
    from openpyxl.styles import Font, PatternFill, Alignment, Border, Side
    detalles = db.query(DetallePedido).all()
    autopartes = {a.id: a for a in db.query(Autoparte).all()}
    data = []
    total_global = 0
    for d in detalles:
        ap = autopartes.get(d.autoparte_id)
        if ap:
            total = ap.precio * d.cantidad
            total_global += total
            data.append({"Autoparte": ap.nombre, "Precio Unit.": ap.precio, "Cant.": d.cantidad, "Subtotal": total})
    df = pd.DataFrame(data)
    file_path = "/tmp/reporte_ventas.xlsx"
    df.to_excel(file_path, index=False, startrow=2)
    from openpyxl import load_workbook
    wb = load_workbook(file_path)
    ws = wb.active
    ws.title = "Ventas"
    ws.merge_cells("A1:D1")
    ws["A1"] = "MACUIN AUTOPARTES — Reporte Analítico de Ventas"
    ws["A1"].font = Font(name="Calibri", bold=True, size=14, color="333333")
    ws["A2"] = f"Operaciones totales registradas: {len(detalles)} movimientos"
    ws["A2"].font = Font(name="Calibri", size=10, color="666666")
    hdr_font = Font(name="Calibri", bold=True, size=10, color="FFFFFF")
    hdr_fill = PatternFill(start_color="333333", end_color="333333", fill_type="solid")
    even_fill = PatternFill(start_color="F5F5F5", end_color="F5F5F5", fill_type="solid")
    border = Border(bottom=Side(style="thin", color="E0E0E0"))
    currency_fmt = '"$"#,##0.00'
    for c in range(1, 5):
        cell = ws.cell(row=3, column=c)
        cell.font = hdr_font
        cell.fill = hdr_fill
        cell.alignment = Alignment(horizontal="center")
    for r in range(4, ws.max_row + 1):
        if r % 2 == 0:
            for c in range(1, 5): ws.cell(row=r, column=c).fill = even_fill
        for c in range(1, 5): ws.cell(row=r, column=c).border = border
        ws.cell(row=r, column=2).number_format = currency_fmt
        ws.cell(row=r, column=4).number_format = currency_fmt
    total_row = ws.max_row + 2
    ws.cell(row=total_row, column=3).value = "TOTAL:"
    ws.cell(row=total_row, column=3).font = Font(name="Calibri", bold=True, size=12)
    ws.cell(row=total_row, column=4).value = total_global
    ws.cell(row=total_row, column=4).number_format = currency_fmt
    ws.cell(row=total_row, column=4).font = Font(name="Calibri", bold=True, size=12, color="333333")
    ws.column_dimensions["A"].width = 30
    ws.column_dimensions["B"].width = 16
    ws.column_dimensions["C"].width = 10
    ws.column_dimensions["D"].width = 20
    wb.save(file_path)
    return FileResponse(file_path, filename="reporte_ventas.xlsx")


# ventas word
@router.get("/ventas/word")
def exportar_ventas_word(db: Session = Depends(get_db)):
    from docx.shared import Pt, RGBColor
    from docx.enum.table import WD_TABLE_ALIGNMENT
    detalles = db.query(DetallePedido).all()
    autopartes = {a.id: a for a in db.query(Autoparte).all()}
    doc = Document()
    doc.add_heading("MACUIN AUTOPARTES", 0)
    doc.add_heading("Reporte Analítico: Artículos Vendidos", level=2)
    doc.add_paragraph(f"Operaciones totales registradas: {len(detalles)} movimientos")
    doc.add_paragraph("")
    table = doc.add_table(rows=1, cols=4)
    table.style = "Table Grid"
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    headers = ["Autoparte", "Precio Unit.", "Cant.", "Subtotal"]
    hdr = table.rows[0]
    for i, h in enumerate(headers):
        cell = hdr.cells[i]
        cell.text = h
        for p in cell.paragraphs:
            for run in p.runs:
                run.bold = True
                run.font.size = Pt(9)
                run.font.color.rgb = RGBColor(255, 255, 255)
        from docx.oxml.ns import qn
        shading = cell._element.get_or_add_tcPr()
        shading_elm = shading.makeelement(qn('w:shd'), {qn('w:fill'): '333333', qn('w:val'): 'clear'})
        shading.append(shading_elm)
    total_global = 0
    for d in detalles:
        ap = autopartes.get(d.autoparte_id)
        if ap:
            total = ap.precio * d.cantidad
            total_global += total
            row = table.add_row().cells
            row[0].text = ap.nombre
            row[1].text = f"${ap.precio:,.2f}"
            row[2].text = f"x{d.cantidad}"
            row[3].text = f"${total:,.2f}"
    doc.add_paragraph("")
    doc.add_heading(f"Ingresos Totales Globales: ${total_global:,.2f}", level=2)
    file_path = "/tmp/reporte_ventas.docx"
    doc.save(file_path)
    return FileResponse(file_path, filename="reporte_ventas.docx")


# ventas pdf
@router.get("/ventas/pdf")
def exportar_ventas_pdf(db: Session = Depends(get_db)):
    detalles = db.query(DetallePedido).all()
    autopartes = {a.id: a for a in db.query(Autoparte).all()}

    file_path = "/tmp/reporte_ventas.pdf"
    doc = SimpleDocTemplate(file_path, rightMargin=40, leftMargin=40, topMargin=40, bottomMargin=40, title="Reporte de Mas Vendidos", author="MACUIN Autopartes")
    styles = getSampleStyleSheet()

    elementos = []
    
    header_data = [
        [Paragraph("<b>MACUIN AUTOPARTES</b>", styles["Heading1"]), Paragraph("<font size=12><b>Reporte Analítico: Artículos Vendidos</b></font>", styles["Normal"])]
    ]
    header_table = Table(header_data, colWidths=[200, 300])
    header_table.setStyle(TableStyle([
        ('ALIGN', (1, 0), (1, 0), 'RIGHT'),
        ('BACKGROUND', (1, 0), (1, 0), colors.HexColor("#f0f0f0")),
        ('PADDING', (1, 0), (1, 0), 10),
    ]))
    elementos.append(header_table)
    elementos.append(Spacer(1, 20))

    elementos.append(Paragraph(f"<b>Operaciones totales registradas:</b> {len(detalles)} movimientos", styles["Normal"]))
    elementos.append(Spacer(1, 10))

    tabla_data = [["Autoparte", "Precio Unit.", "Cant.", "Subtotal por Producto"]]
    total_global = 0
    for d in detalles:
        ap = autopartes.get(d.autoparte_id)
        if ap:
            total = ap.precio * d.cantidad
            total_global += total
            tabla_data.append([
                ap.nombre,
                f"${ap.precio:,.2f}",
                f"x{d.cantidad}",
                f"${total:,.2f}"
            ])

    t = Table(tabla_data, colWidths=[180, 100, 60, 140])
    t.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), colors.HexColor("#333333")),
        ('TEXTCOLOR', (0,0), (-1,0), colors.white),
        ('FONTNAME', (0,0), (-1,0), 'Helvetica-Bold'),
        ('LINEBELOW', (0,0), (-1,-1), 0.5, colors.HexColor("#e0e0e0")),
        ('PADDING', (0,0), (-1,-1), 8),
        ('ALIGN', (1,1), (-1,-1), 'RIGHT'),
    ]))
    elementos.append(t)
    elementos.append(Spacer(1, 20))
    
    elementos.append(Paragraph(f"Ingresos Totales Globales: <b>${total_global:,.2f}</b>", styles["Heading2"]))

    doc.build(elementos)

    return FileResponse(file_path, media_type="application/pdf", headers={"Content-Disposition": "inline; filename=\"reporte_ventas.pdf\""})