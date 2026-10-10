from pathlib import Path

root = Path(r'C:\xampp\htdocs\TTVC\assets\uploads\resources')
root.mkdir(parents=True, exist_ok=True)

resources = {
    'ttvc-admission-guide.pdf': [
        'TINDIRET TECHNICAL AND VOCATIONAL COLLEGE',
        'Admissions Information Guide',
        '',
        'This guide explains how to apply to TTVC and what to prepare.',
        '1. Choose your preferred course and programme level.',
        '2. Review entry requirements and academic qualifications.',
        '3. Complete the online application form at ttvc.ac.ke/apply.php.',
        '4. Upload copies of your identification and academic certificates.',
        '5. Keep your application number to monitor progress.',
        '6. Contact admissions for guidance on deadlines and funding.',
        '',
        'Contact: info@ttvc.ac.ke | +254 700 000 000',
        'Location: Tindiret, Nandi County, Kenya'
    ],
    'ttvc-course-directory.pdf': [
        'TTVC Course Directory',
        '',
        'Departments include Computing, Electrical, Building, Business,',
        'Agriculture, Hospitality, Fashion Design and Automotive.',
        'Programmes run from artisan and certificate to diploma levels.',
        'Each course is designed to develop practical workplace skills.',
        'Applicants should check the exact course requirements before applying.',
        '',
        'Examples: ICT, Electrical Installation, Building Technology,',
        'Business Management, Agriculture, Hospitality, Fashion Design,',
        'Automotive Engineering.'
    ],
    'ttvc-fees-and-levies.pdf': [
        'TTVC Fees and Levies Guide',
        '',
        'Fees are reviewed annually and depend on the programme selected.',
        'Students are advised to contact the admissions office for current rates.',
        'The College may also share information on funding and sponsorship.',
        '',
        'Key points:',
        '- Confirm the exact charges for your chosen course.',
        '- Ask about tuition, training materials and other levies.',
        '- Keep payment records for all student transactions.',
        '',
        'For current information, contact the admissions office.'
    ],
    'ttvc-application-checklist.pdf': [
        'TTVC Application Checklist',
        '',
        'Before submitting an application, prepare the following:',
        '- National ID or passport copy',
        '- KCSE results or equivalent certificate',
        '- Birth certificate or valid identification',
        '- Passport-size photograph',
        '- Course selection details',
        '- Contact information and next of kin details',
        '',
        'Finish all required fields and upload documents in the right format.',
        'Do not forget to save your application number.'
    ],
    'ttvc-student-handbook.pdf': [
        'TTVC Student Handbook',
        '',
        'Welcome to TTVC.',
        'Students are expected to participate in learning, maintain discipline',
        'and respect the values of practical excellence, integrity and teamwork.',
        '',
        'Student support services include academic guidance, health awareness,',
        'career information and college administration support.',
        'The college encourages respectful conduct and continuous improvement.',
        '',
        'For questions, contact the student services office or admissions.'
    ],
    'ttvc-student-services-guide.pdf': [
        'TTVC Student Services Guide',
        '',
        'Student services covers admissions, registration, learning support,',
        'career guidance and general campus information.',
        '',
        'Support is available to help students settle in, learn well,',
        'and make the most of opportunities available at the college.'
    ]
}

# Simple PDF writer for valid downloadable documents.
def pdf_escape(text):
    return text.replace('\\', '\\\\').replace('(', '\\(').replace(')', '\\)')


def make_pdf(path: Path, lines):
    content_lines = ['BT /F1 12 Tf 50 790 Td']
    y = 760
    for line in lines:
        content_lines.append(f'50 {y} Td ({pdf_escape(line)}) Tj')
        y -= 20
    content_lines.append('ET')
    content = '\n'.join(content_lines)

    objects = [
        '<< /Type /Catalog /Pages 2 0 R >>',
        '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
        '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>',
        '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
        f'<< /Length {len(content.encode("latin-1"))} >>\nstream\n{content}\nendstream'
    ]

    out = bytearray(b'%PDF-1.4\n')
    offsets = [0]
    for idx, obj in enumerate(objects, start=1):
        offsets.append(len(out))
        out.extend(f'{idx} 0 obj\n'.encode('latin-1'))
        out.extend(obj.encode('latin-1'))
        out.extend(b'\nendobj\n')

    xref_start = len(out)
    out.extend(f'xref\n0 {len(objects)+1}\n'.encode('latin-1'))
    out.extend(b'0000000000 65535 f \n')
    for offset in offsets[1:]:
        out.extend(f'{offset:010d} 00000 n \n'.encode('latin-1'))
    out.extend(f'trailer\n<< /Size {len(objects)+1} /Root 1 0 R >>\nstartxref\n{xref_start}\n%%EOF\n'.encode('latin-1'))
    path.write_bytes(out)

for name, lines in resources.items():
    make_pdf(root / name, lines)

print(f'Created {len(resources)} resource documents in {root}')
