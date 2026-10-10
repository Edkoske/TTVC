from pathlib import Path
from reportlab.lib.pagesizes import A4
from reportlab.lib import colors
from reportlab.lib.colors import HexColor
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib.units import mm
from reportlab.platypus import PageBreak, Paragraph, Spacer, Table, TableStyle, SimpleDocTemplate

BASE = Path('assets/uploads/resources')
BASE.mkdir(parents=True, exist_ok=True)

NAVY = HexColor('#122d32')
GREEN = HexColor('#1f6b5a')
GOLD = HexColor('#d8a836')
LIGHT = HexColor('#f4f7f2')
TEXT = HexColor('#1d2a2d')
SLATE = HexColor('#2d4650')
CREAM = HexColor('#f9f5ee')

THEMES = {
    'admissions': {'primary': NAVY, 'secondary': GOLD, 'panel': HexColor('#edf3f6'), 'seal': HexColor('#e6d4a9')},
    'courses': {'primary': GREEN, 'secondary': GOLD, 'panel': HexColor('#eef5f1'), 'seal': HexColor('#dbeadf')},
    'checklist': {'primary': HexColor('#1f4e7a'), 'secondary': HexColor('#7aa7c4'), 'panel': HexColor('#edf4fa'), 'seal': HexColor('#dfeaf6')},
    'fees': {'primary': HexColor('#7b4d17'), 'secondary': GOLD, 'panel': HexColor('#f9f0e7'), 'seal': HexColor('#f0d8a7')},
    'handbook': {'primary': HexColor('#1a5d52'), 'secondary': HexColor('#9ac7b7'), 'panel': HexColor('#edf7f3'), 'seal': HexColor('#d4ebe3')},
    'services': {'primary': SLATE, 'secondary': HexColor('#c7a64c'), 'panel': HexColor('#f2f5f6'), 'seal': HexColor('#dfe5e9')},
}


def make_styles():
    styles = getSampleStyleSheet()
    styles.add(ParagraphStyle(
        name='TTVCBrand',
        fontName='Helvetica-Bold',
        fontSize=18,
        textColor=colors.white,
        leading=18,
        alignment=1,
        spaceAfter=0,
    ))
    styles.add(ParagraphStyle(
        name='TTVCHeader',
        fontName='Helvetica-Bold',
        fontSize=14,
        textColor=NAVY,
        leading=18,
        spaceAfter=2,
    ))
    styles.add(ParagraphStyle(
        name='TTVCSubhead',
        fontName='Helvetica-Bold',
        fontSize=8,
        textColor=GREEN,
        letterSpace=1.8,
        leading=10,
        spaceAfter=6,
        alignment=1,
    ))
    styles.add(ParagraphStyle(
        name='TTVCSection',
        fontName='Helvetica-Bold',
        fontSize=12,
        textColor=NAVY,
        borderLeftWidth=3,
        borderLeftColor=NAVY,
        leftIndent=8,
        spaceBefore=14,
        spaceAfter=8,
        leading=15,
    ))
    styles.add(ParagraphStyle(
        name='TTVCBody',
        fontName='Helvetica',
        fontSize=10,
        textColor=TEXT,
        leading=14,
        spaceAfter=6,
    ))
    styles.add(ParagraphStyle(
        name='TTVCBullet',
        fontName='Helvetica',
        fontSize=10,
        textColor=TEXT,
        leftIndent=18,
        bulletIndent=10,
        bulletFontName='Helvetica-Bold',
        bulletColor=GREEN,
        leading=14,
        spaceAfter=5,
    ))
    styles.add(ParagraphStyle(
        name='TTVCMetaLabel',
        fontName='Helvetica-Bold',
        fontSize=8,
        textColor=colors.grey,
        leading=10,
        spaceAfter=2,
    ))
    styles.add(ParagraphStyle(
        name='TTVCMetaValue',
        fontName='Helvetica',
        fontSize=8,
        textColor=NAVY,
        leading=10,
        spaceAfter=4,
    ))
    styles.add(ParagraphStyle(
        name='TTVCFooter',
        fontName='Helvetica',
        fontSize=8,
        textColor=colors.grey,
        alignment=1,
        leading=10,
    ))
    return styles


def build_letterhead(accent):
    styles = make_styles()
    line = Table([
        [Paragraph('OFFICIAL TTVC DOCUMENT', styles['TTVCSubhead'])]
    ], colWidths=[150 * mm])
    line.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (-1, -1), accent['panel']),
        ('GRID', (0, 0), (-1, -1), 0.5, accent['primary']),
        ('VALIGN', (0, 0), (-1, -1), 'MIDDLE'),
        ('LEFTPADDING', (0, 0), (-1, -1), 8),
        ('RIGHTPADDING', (0, 0), (-1, -1), 8),
        ('TOPPADDING', (0, 0), (-1, -1), 4),
        ('BOTTOMPADDING', (0, 0), (-1, -1), 4),
    ]))

    badge = Table([
        [Paragraph('TTVC', styles['TTVCBrand']), Paragraph('TINDIRET TECHNICAL & VOCATIONAL COLLEGE', styles['TTVCHeader'])]
    ], colWidths=[28 * mm, 122 * mm])
    badge.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (0, 0), accent['primary']),
        ('BACKGROUND', (1, 0), (1, 0), accent['panel']),
        ('VALIGN', (0, 0), (-1, -1), 'MIDDLE'),
        ('ALIGN', (0, 0), (0, 0), 'CENTER'),
        ('GRID', (0, 0), (-1, -1), 0.7, accent['primary']),
        ('LEFTPADDING', (0, 0), (-1, -1), 8),
        ('RIGHTPADDING', (0, 0), (-1, -1), 8),
        ('TOPPADDING', (0, 0), (-1, -1), 8),
        ('BOTTOMPADDING', (0, 0), (-1, -1), 8),
    ]))

    contact = Table([
        [Paragraph('Principal / Admissions Office', styles['TTVCMetaLabel']), Paragraph('+254 700 000 000', styles['TTVCMetaValue'])],
        [Paragraph('Email', styles['TTVCMetaLabel']), Paragraph('info@ttvc.ac.ke', styles['TTVCMetaValue'])],
        [Paragraph('Website', styles['TTVCMetaLabel']), Paragraph('www.ttvc.ac.ke', styles['TTVCMetaValue'])],
    ], colWidths=[62 * mm, 88 * mm])
    contact.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (-1, -1), accent['panel']),
        ('GRID', (0, 0), (-1, -1), 0.5, accent['primary']),
        ('LEFTPADDING', (0, 0), (-1, -1), 7),
        ('RIGHTPADDING', (0, 0), (-1, -1), 7),
        ('TOPPADDING', (0, 0), (-1, -1), 5),
        ('BOTTOMPADDING', (0, 0), (-1, -1), 5),
    ]))
    return [Spacer(1, 4), line, Spacer(1, 8), badge, Spacer(1, 8), contact]


def build_cover(title, subtitle, accent):
    styles = make_styles()
    content = build_letterhead(accent)
    content.extend([
        Spacer(1, 8),
        Paragraph(title, styles['Title']),
        Spacer(1, 6),
        Paragraph(subtitle, styles['TTVCBody']),
        Spacer(1, 12),
        Table([
            ['Official document', 'Prepared for student use'],
            ['Issued by', 'TTVC Admissions & Student Services'],
            ['Campus', 'Tindiret, Elgeyo-Marakwet County'],
        ], colWidths=[55 * mm, 95 * mm]),
    ])
    table = content[-1]
    table.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (-1, -1), accent['panel']),
        ('GRID', (0, 0), (-1, -1), 0.7, accent['primary']),
        ('FONTNAME', (0, 0), (-1, -1), 'Helvetica-Bold'),
        ('FONTSIZE', (0, 0), (-1, -1), 8),
        ('TEXTCOLOR', (0, 0), (-1, -1), accent['primary']),
        ('VALIGN', (0, 0), (-1, -1), 'MIDDLE'),
    ]))
    return content


def add_section(title, paragraphs, accent, styles):
    story = []
    section_style = ParagraphStyle(
        name='SectionAccent',
        parent=styles['TTVCSection'],
        textColor=accent['primary'],
        borderLeftColor=accent['secondary'],
        leftIndent=8,
    )
    story.append(Paragraph(title, section_style))
    for p in paragraphs:
        if isinstance(p, list):
            bullet_style = ParagraphStyle(
                name='BulletAccent',
                parent=styles['TTVCBullet'],
                bulletColor=accent['secondary'],
                leftIndent=18,
            )
            for item in p:
                story.append(Paragraph(f'• {item}', bullet_style))
        else:
            story.append(Paragraph(p, styles['TTVCBody']))
    return story


def build_document(filename, title, subtitle, sections, accent_name):
    path = BASE / filename
    accent = THEMES[accent_name]
    styles = make_styles()
    story = build_cover(title, subtitle, accent)
    story.append(PageBreak())
    for section in sections:
        story.extend(add_section(section['heading'], section['body'], accent, styles))
    story.append(Spacer(1, 20))
    story.append(Paragraph('Prepared by Tindiret Technical and Vocational College • Official institutional record', styles['TTVCFooter']))

    doc = SimpleDocTemplate(str(path), pagesize=A4, leftMargin=36, rightMargin=36, topMargin=30, bottomMargin=30)
    doc.build(story)
    print(f'Created: {path}')


files = {
    'ttvc-admission-guide.pdf': {
        'accent': 'admissions',
        'title': 'TTVC Admissions Information Guide',
        'subtitle': 'Everything you need to know before applying to Tindiret Technical and Vocational College.',
        'sections': [
            {'heading': 'About TTVC Admissions', 'body': [
                'TTVC welcomes learners who want practical, work-oriented, and career-focused education in technical and vocational disciplines.',
                'The admissions process is designed to guide prospective students from application to registration and first-day orientation.',
            ]},
            {'heading': 'Application Process', 'body': [
                'Select a programme that matches your interests and academic goals.',
                'Complete the online application form accurately and attach all required supporting documents.',
                'Submit the application for review by the admissions office and monitor updates through the application status portal.',
            ]},
            {'heading': 'Documents Required', 'body': [
                ['KCSE result slip or certificate', 'Birth certificate or national ID', 'Passport-size photograph', 'Any programme-specific statement or additional evidence requested by the college']
            ]},
            {'heading': 'How to Reach Us', 'body': [
                'Call +254 700 000 000 or email info@ttvc.ac.ke for guidance on admissions requirements, courses, and student support.',
                'The admissions office is available to help with programme choice, documentation, and the next steps after application.',
            ]}
        ]
    },
    'ttvc-course-directory.pdf': {
        'accent': 'courses',
        'title': 'TTVC Course Directory',
        'subtitle': 'A guide to the programmes offered at TTVC and how they relate to career pathways.',
        'sections': [
            {'heading': 'Academic Programmes', 'body': [
                'TTVC offers a wide range of technical and vocational education programmes in key skills areas including Computing, Engineering, Business, Agriculture, Hospitality, Fashion Design, and Automotive studies.',
                'Each programme is built around practical learning, enterprise readiness, and job market relevance.',
            ]},
            {'heading': 'Programme Levels', 'body': [
                'Certificate programmes provide foundation skills and early employment readiness.',
                'Craft programmes focus on specialised technical skills and supervised workplace practice.',
                'Diploma programmes provide deeper academic and practical knowledge for advanced technical roles and enterprise development.',
            ]},
            {'heading': 'Departments and Focus Areas', 'body': [
                'Computing and Informatics covers digital literacy, ICT support, and software applications.',
                'Electrical and Electronics supports installation, maintenance, and renewable energy systems.',
                'Business Studies supports entrepreneurship, accounting, procurement, and management skills.',
                'Agriculture, Hospitality, Fashion Design, and Automotive Engineering prepare learners for practical, skilled roles in local and national industries.',
            ]},
            {'heading': 'Apply with Confidence', 'body': [
                'Review the detailed course information before applying and contact the college for current intake and fee guidance.',
                'TTVC programmes are designed to help students build practical capability, confidence, and career readiness.',
            ]}
        ]
    },
    'ttvc-application-checklist.pdf': {
        'accent': 'checklist',
        'title': 'TTVC Student Application Checklist',
        'subtitle': 'A practical checklist to help applicants complete and submit a strong application.',
        'sections': [
            {'heading': 'Before Completing the Form', 'body': [
                'Use this checklist to make sure your application is complete before submission.',
                'Prepare all personal, academic, and contact information carefully so the admissions office can review your file without delay.',
            ]},
            {'heading': 'Required Items', 'body': [
                ['KCSE result slip or certificate', 'Birth certificate or national ID', 'Passport-size photograph', 'Current contact details', 'Next of kin or emergency contact details', 'Any additional supporting information requested for your chosen programme']
            ]},
            {'heading': 'Final Submission Check', 'body': [
                'Confirm that every required field is accurately filled in and that supporting files are uploaded correctly.',
                'Keep your application number safe so you can check your status and follow up with the admissions office if needed.',
            ]}
        ]
    },
    'ttvc-fees-and-levies.pdf': {
        'accent': 'fees',
        'title': 'TTVC Fees and Levies Guide',
        'subtitle': 'A clear guide to the financial information students need before registering and studying.',
        'sections': [
            {'heading': 'Fee Information', 'body': [
                'Fees and levies are reviewed regularly and may change according to institutional costs and programme requirements.',
                'It is important that students confirm the current fee structure with the admissions or finance office before payment.',
            ]},
            {'heading': 'Payment and Planning', 'body': [
                'Students should keep all fee documents, payment slips, and admission acknowledgement records safely for reference.',
                'Planning early helps learners and guardians understand the cost of study and prepare appropriately.',
            ]},
            {'heading': 'Funding and Support', 'body': [
                'Government funding, bursaries, and related support options may be available depending on eligibility and current policy.',
                'Students are encouraged to ask admissions about available support channels before registration.',
            ]}
        ]
    },
    'ttvc-student-handbook.pdf': {
        'accent': 'handbook',
        'title': 'TTVC Student Handbook',
        'subtitle': 'A guide to student life, learning expectations, conduct, and support at TTVC.',
        'sections': [
            {'heading': 'Learning and Academic Expectations', 'body': [
                'Students are expected to attend classes regularly, complete assignments, and take part in practical learning activities seriously.',
                'Academic performance, professionalism, and cooperation are important parts of the TTVC learning experience.',
            ]},
            {'heading': 'Campus Conduct', 'body': [
                'Show respect for staff, fellow students, and training facilities.',
                'Follow all safety rules, workshop instructions, and institutional policies while in class, labs, or practical areas.',
            ]},
            {'heading': 'Support at TTVC', 'body': [
                'Students can access guidance, library support, and institutional communication through the college channels and student portal.',
                'A productive student experience depends on regular attendance, good communication, and timely follow-up on notices and opportunities.',
            ]}
        ]
    },
    'ttvc-student-services-guide.pdf': {
        'accent': 'services',
        'title': 'TTVC Student Services Guide',
        'subtitle': 'Support services designed to help students succeed throughout their learning journey.',
        'sections': [
            {'heading': 'Student Support Services', 'body': [
                'TTVC provides guidance and support to help learners manage their academic progress, documentation, and overall student experience.',
                'Support services may cover application follow-up, student records, orientation, and general communication with the college.',
            ]},
            {'heading': 'Academic and Administrative Help', 'body': [
                'Students are encouraged to keep their personal information up to date and follow institutional notices through the website and student portal.',
                'Regular communication with the college ensures that learners receive timely updates on fees, attendance, results, and campus events.',
            ]},
            {'heading': 'How to Stay Connected', 'body': [
                'Use the student portal and official college contacts to ask questions, access updates, and remain informed about important academic and administrative matters.',
                'TTVC is committed to supporting students through practical learning, guidance, and a positive college experience.',
            ]}
        ]
    },
}

for filename, data in files.items():
    build_document(filename, data['title'], data['subtitle'], data['sections'], data['accent'])
