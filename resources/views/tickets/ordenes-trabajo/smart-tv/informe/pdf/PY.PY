from reportlab.lib.pagesizes import letter
from reportlab.pdfgen import canvas
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.platypus import Paragraph
from reportlab.lib.enums import TA_JUSTIFY

def create_declaracion_jurada():
    # Datos personales
    nombre = "BRIAN ESTIT LUNA CHORRES"
    dni = "77054714"
    direccion = "Calle las orquídeas Mz T lote 13 Asociación Sol de Santa Anita, Santa anita"
    celular = "HONOR X8b 256GB COLOR Aquamarino"
    tiempo_posesion = "8 meses"
    
    # Crear el PDF
    pdf_path = "declaracion_jurada.pdf"
    c = canvas.Canvas(pdf_path, pagesize=letter)
    
    # Estilos
    styles = getSampleStyleSheet()
    style_normal = styles["Normal"]
    style_justify = ParagraphStyle(
        'Justify',
        parent=style_normal,
        alignment=TA_JUSTIFY,
        fontSize=12,
        leading=14,
    )
    style_bold = ParagraphStyle(
        'Bold',
        parent=style_normal,
        fontName='Helvetica-Bold',
        fontSize=12,
        leading=14,
    )
    style_title = ParagraphStyle(
        'Title',
        parent=style_normal,
        fontName='Helvetica-Bold',
        fontSize=14,
        leading=16,
        alignment=1,  # Centrado
        spaceAfter=20,
    )
    
    # Título
    c.setFont("Helvetica-Bold", 16)
    c.drawCentredString(300, 750, "DECLARACIÓN JURADA")
    c.line(150, 740, 450, 740)
    
    # Texto principal
    y_position = 700
    c.setFont("Helvetica", 12)
    
    # Primera parte
    text = f"Yo, <b>{nombre}</b>, identificado con DNI N° <b>{dni}</b>, con domicilio en <b>{direccion}</b>."
    p = Paragraph(text, style_justify)
    p.wrapOn(c, 400, 50)
    p.drawOn(c, 100, y_position)
    y_position -= 30
    
    # Declaración bajo juramento
    c.setFont("Helvetica-Bold", 12)
    c.drawString(100, y_position, "DECLARO BAJO JURAMENTO")
    y_position -= 30
    c.setFont("Helvetica", 12)
    
    # Cuerpo de la declaración
    text = f"""
    Que soy propietario legítimo del teléfono celular marca <b>{celular}</b>, el cual adquirí hace aproximadamente <b>{tiempo_posesion}</b>, siendo de mi exclusiva propiedad. 
    <br/><br/>
    Asimismo, declaro que debido a mi reciente mudanza de domicilio, he extraviado la boleta de compra del mencionado dispositivo.
    <br/><br/>
    En caso de resultar mentira o falsa la información que proporciono, declaro haber incurrido en el delito de Falsa Declaración en Proceso Administrativo Artículo 441 del Código Penal y delito contra la Fe Pública - Falsificación de Documentos - Falsedad Genérica - Artículo 427 y 438 del Código Penal en concordancia con el Artículo IV 1.7 del Título Preliminar de la Ley de Procedimiento Administrativo General, Ley N° 27444.
    <br/><br/>
    La presente la realizo en honor a la verdad y para los fines que sea necesario, de conformidad a lo previsto por las normas legales vigentes; con el cargo a ratificarme en caso que me sea solicitada.
    <br/><br/>
    Para mayor constancia firmo ante un Notario Público de la Ciudad de Lima a los ____ días del mes de ____________ del 2023.
    """
    
    p = Paragraph(text, style_justify)
    p.wrapOn(c, 400, 400)
    p.drawOn(c, 100, y_position - 350)
    
    # Firma
    y_position = 150
    c.setFont("Helvetica-Bold", 12)
    c.drawCentredString(300, y_position, nombre)
    c.drawCentredString(300, y_position - 20, f"DNI N° {dni}")
    
    # Guardar el PDF
    c.save()
    print(f"Se ha generado el PDF: {pdf_path}")

create_declaracion_jurada()