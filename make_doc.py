from zipfile import ZipFile, ZIP_DEFLATED
from xml.sax.saxutils import escape
from pathlib import Path

out = Path('TTVC_Project_Documentation.docx')

sections = [
    ("TTVC Website Documentation", [
        "This document explains the TTVC college website step by step. It describes the project structure, how each file works, how data flows through the app, and how the site keeps public users, students, and admins separated safely.",
        "The project is a PHP 8 and MySQL college website built without a framework. It uses plain PHP files, shared include files, and database-driven content for courses, news, applications, and admin management.",
        "The codebase is structured for a small academic institution that needs a public website, a student portal, and an administration dashboard in one app."
    ]),
    ("1. Project Overview", [
        "TTVC stands for Tindiret Technical and Vocational College. The website is used to display college information, publish courses, allow online applications, support student login, and manage content from the admin area.",
        "The main technologies are PHP, MySQL, HTML, CSS, and JavaScript. The app uses PDO for secure SQL access, sessions for login state, and CSRF protection for form submissions.",
        "The site runs in a standard XAMPP or Apache environment with PHP and MySQL enabled."
    ]),
    ("2. Folder Structure", [
        "The root folder holds public pages such as index.php, about.php, courses.php, apply.php, contact.php, login.php, register.php, and search.php.",
        "The admin/ directory contains restricted pages for admissions, content management, settings, users, notifications, and dashboard functions.",
        "The student/ directory contains pages for student dashboard, application status, profile, and private documents.",
        "The config/ folder handles application configuration, database connection, and auth helpers.",
        "The includes/ folder contains shared layout files such as header.php, navbar.php, footer.php, alerts.php, and functions.php.",
        "The assets/ folder contains CSS, JavaScript, images, and uploaded files.",
        "The database/ folder contains the SQL schema and demo data used to seed the site.",
        "The uploads directory stores application documents and public resources securely."
    ]),
    ("3. Configuration Files", [
        "The file config/config.php defines application-wide constants and settings. It sets APP_NAME, APP_ENV, APP_BASE_URL, UPLOAD_DIR, and MAX_UPLOAD_BYTES.",
        "This file also sets the timezone to Africa/Nairobi and configures session security using cookie flags. This improves safety and consistency.",
        "The app reads environment variables such as TTVC_ENV, TTVC_BASE_URL, TTVC_DB_HOST, TTVC_DB_NAME, TTVC_DB_USER, TTVC_DB_PASS, and TTVC_DB_PORT. This makes the app portable across development and deployment environments."
    ]),
    ("4. Database Connection", [
        "The database logic is in config/database.php.",
        "It contains the db() function, which returns a single PDO instance for the whole app. This pattern avoids opening many database connections in every page.",
        "The function sets PDO to throw exceptions on error, uses an associative fetch mode, and disables emulated prepares to improve SQL safety.",
        "The DSN is built using host, port, database name, and charset. This is the main connection between the PHP app and MySQL.",
        "If environment variables are not set, the app falls back to the local XAMPP defaults: localhost, ttvc database, user root, and default password settings."
    ]),
    ("5. Shared Helper Functions", [
        "The file includes/functions.php is one of the most important common files in the project.",
        "The e() function escapes HTML output to prevent cross-site scripting. Every value printed into HTML should go through this helper when appropriate.",
        "The url() helper builds paths using the detected app base URL, which is important if the project is installed in a subfolder like /TTVC.",
        "The redirect() function sends a Location header and exits, which is commonly used after a successful form submission or login.",
        "The csrf_token() and verify_csrf() functions protect forms from CSRF attacks. Every form submission checks the token before processing.",
        "The setting() function reads values from the settings table, which is used to display contact information and site statistics.",
        "The flash() helper stores a success or error message in the session for display on the next page load.",
        "The upload_file() helper validates upload size, MIME type, and storage path, then renames the file and saves it safely."
    ]),
    ("6. Shared Layout Files", [
        "The includes/header.php file creates the common HTML head section. It includes metadata, title tags, CSS links, fonts, and JSON-LD schema markup for SEO.",
        "The includes/navbar.php file builds the top navigation and the main menu. It contains links to public pages and the application button.",
        "The includes/footer.php file builds the common footer with address, quick links, and contact details.",
        "The includes/alerts.php file displays flash messages like errors or success notifications after a redirect."
    ]),
    ("7. Public Home Page", [
        "The home page is index.php.",
        "It loads the shared header and footer and fetches a few featured courses and recent news items from the database.",
        "The page displays the hero slider, stats panel, course cards, value section, and campus-life section.",
        "The slider uses JavaScript to rotate through uploaded campus images and update the dot indicators.",
        "This page is meant to serve as the main landing page for visitors, applicants, and students."
    ]),
    ("8. Courses and Department Information", [
        "The public courses listing is implemented in courses.php. It loads courses, groups them by level and department, and displays searchable listing cards.",
        "The file course-details.php loads a single course by id and shows detailed information including entry requirements, modules, careers, fees, and funding guidance.",
        "The departments.php page lists the academic departments, and admissions.php provides guidance about application steps and requirements.",
        "This part of the site is essential to student recruitment and course discovery."
    ]),
    ("9. News, Events, and Gallery", [
        "The public content pages are news.php, news-details.php, events.php, gallery.php, facilities.php, and faq.php.",
        "News items are stored in the news table and displayed with titles, excerpts, images, and dates.",
        "Event pages display campus activities and upcoming dates.",
        "The gallery page loads images from the gallery table and uses a lightbox effect so users can view photos more clearly.",
        "The facilities page shows campus infrastructure and spaces, while the FAQ page answers common questions."
    ]),
    ("10. Contact, Search, and Downloads", [
        "The contact.php page collects enquiries from visitors and stores them in the enquiries table.",
        "The search.php page scans the site content and helps users find relevant information.",
        "The downloads.php page displays downloadable files for applicants and students, while download.php serves the file securely and increments a download count.",
        "This pattern keeps files private and prevents users from browsing a raw upload directory."
    ]),
    ("11. Student Registration and Login", [
        "The register.php page creates a new student account. It stores full name, email, password hash, role, and status.",
        "Passwords are stored using password_hash() and validated with password_verify(). This is the standard secure approach for PHP applications.",
        "The login.php page checks the submitted email and password against the users table and, if valid, creates a session and redirects the user to the student dashboard.",
        "The config/auth.php file defines require_student() and require_admin() to protect routes and enforce user access rules."
    ]),
    ("12. Student Portal", [
        "The student/ folder contains the authenticated area for students.",
        "The dashboard page shows the student’s application overview, notifications, and summary content.",
        "The student/application.php page allows a logged-in student to submit an application, while student/application-status.php lets them check the status of past applications.",
        "The student/profile.php page lets the student update their details, and student/documents.php lists private documents linked to their application.",
        "The document download endpoint is protected so a user can only access files tied to their own application."
    ]),
    ("13. Application Submission Flow", [
        "The public apply.php page is the center of the applicant journey.",
        "It loads courses and asks applicants to fill a multi-step form. The form collects personal data, academic information, and supporting documents.",
        "When the form is submitted, the server starts a PDO transaction to ensure the application is saved consistently. It inserts into the applications table and related application_documents table.",
        "A unique application number is created and stored for later lookup.",
        "Uploaded files are saved to the assets/uploads directory and tracked by application_id. This supports the academic and identity document workflow."
    ]),
    ("14. Database Structure", [
        "The database/sql file database/ttvc.sql creates the schema and seeds data for the app.",
        "The main tables include users, students, admins, departments, courses, applications, application_documents, news, events, gallery, facilities, downloads, staff, enquiries, faqs, vacancies, notifications, settings, and activity_logs.",
        "This design allows the site to manage academic records, user access, public content, and application data in one database.",
        "Default demo data is inserted so the site often works immediately after import."
    ]),
    ("15. Admin Panel", [
        "The admin area is under the admin/ directory and is protected by admin login.",
        "The admin login page checks email and password against the admins table and stores user session information.",
        "The admin dashboard shows counts and summaries for applications, content, students, and enquiries.",
        "Other admin pages manage application review, course editing, news, facility management, downloads, notifications, settings, and user access.",
        "Roles include Super Admin, Admissions Officer, and Content Manager. Access is checked using require_admin() and role restrictions."
    ]),
    ("16. Security and Upload Safety", [
        "The site protects against common web application risks.",
        "Prepared statements are used for database queries. This helps defend against SQL injection.",
        "HTML output is escaped, and CSRF tokens are checked on forms. This helps defend against XSS and form tampering.",
        "Uploaded files are validated by size, MIME type, and safe folder location. This prevents dangerous uploads and traversal issues.",
        "The .htaccess file prevents direct browsing of sensitive files and directories and blocks execution of script files in public folders."
    ]),
    ("17. How the App Runs End-to-End", [
        "A visitor loads index.php or another public page. The page includes the header, loads site config, and queries the database when needed.",
        "If the visitor views a course, they click through to a details page. If they apply, they submit data to apply.php and receive an application number.",
        "If the visitor logs in as a student, the session is created and the student sees the student portal. From there, they can view documents and application progress.",
        "If the visitor logs in as staff, they are redirected to the admin area where they can review applications and update content.",
        "This architecture is simple, clear, and suitable for a college website that needs a public presence and internal operations in one system."
    ]),
    ("18. Deployment Notes", [
        "The project is intended to run in XAMPP or a PHP/MySQL hosting environment.",
        "Before deployment, the database should be imported from database/ttvc.sql, the uploads folder should be writable, and environment variables should be configured appropriately.",
        "The app should run with HTTPS in production, and sensitive files should remain protected from direct public access.",
        "Default credentials and demo content should be replaced before launch to avoid exposing a test account to the public."
    ]),
    ("19. Summary", [
        "This codebase is a simple but functional PHP college website. It combines public information, student services, application processing, and admin-only management in one project.",
        "Its design is easy to follow because each page is a separate PHP file, shared logic is centralized, and the database model sits at the center of the app.",
        "Understanding the app requires following the flow from config and database setup, to page logic, to database queries, and then to session-based access rules."
    ])
]

paragraphs = []
for heading, lines in sections:
    paragraphs.append((heading, 'H1'))
    for line in lines:
        paragraphs.append((line, 'P'))

content_xml = []
for text, kind in paragraphs:
    if kind == 'H1':
        content_xml.append(f'<w:p><w:pPr><w:pStyle w:val="Heading1"/></w:pPr><w:r><w:t xml:space="preserve">{escape(text)}</w:t></w:r></w:p>')
    else:
        content_xml.append(f'<w:p><w:r><w:t xml:space="preserve">{escape(text)}</w:t></w:r></w:p>')

document_xml = '''<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:body>
    <w:p><w:pPr><w:pStyle w:val="Title"/></w:pPr><w:r><w:t>TTVC Website Code Documentation</w:t></w:r></w:p>
    {content}
    <w:sectPr>
      <w:pgSz w:w="12240" w:h="15840"/>
      <w:pgMar w:top="1440" w:right="1440" w:bottom="1440" w:left="1440" w:header="720" w:footer="720" w:gutter="0"/>
    </w:sectPr>
  </w:body>
</w:document>
'''.format(content='\n'.join(content_xml))

styles_xml = '''<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:style w:type="paragraph" w:default="1" w:styleId="Normal"><w:name w:val="Normal"/></w:style>
  <w:style w:type="paragraph" w:styleId="Title"><w:name w:val="Title"/><w:basedOn w:val="Normal"/><w:qFormat/><w:rPr><w:b/><w:sz w:val="30"/></w:rPr></w:style>
  <w:style w:type="paragraph" w:styleId="Heading1"><w:name w:val="heading 1"/><w:basedOn w:val="Normal"/><w:next w:val="Normal"/><w:qFormat/><w:rPr><w:b/><w:sz w:val="24"/></w:rPr></w:style>
</w:styles>
'''

content_types = '''<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
  <Override PartName="/word/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>
  <Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>
  <Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>
</Types>
'''

rels = '''<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>
  <Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>
</Relationships>
'''

core = '''<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:dcmitype="http://purl.org/dc/dcmitype/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <dc:title>TTVC Website Code Documentation</dc:title>
  <dc:creator>GitHub Copilot</dc:creator>
  <cp:lastModifiedBy>GitHub Copilot</cp:lastModifiedBy>
  <dcterms:created xsi:type="dcterms:W3CDTF">2026-10-08T00:00:00Z</dcterms:created>
  <dcterms:modified xsi:type="dcterms:W3CDTF">2026-10-08T00:00:00Z</dcterms:modified>
</cp:coreProperties>
'''

app = '''<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties" xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">
  <Application>Microsoft Office Word</Application>
  <DocSecurity>0</DocSecurity>
  <ScaleCrop>false</ScaleCrop>
  <HeadingPairs>
    <vt:vector size="2" baseType="variant">
      <vt:variant><vt:lpstr>Title</vt:lpstr></vt:variant>
      <vt:variant><vt:i4>1</vt:i4></vt:variant>
    </vt:vector>
  </HeadingPairs>
  <TitlesOfParts>
    <vt:vector size="1" baseType="lpstr">
      <vt:lpstr>TTVC Website Code Documentation</vt:lpstr>
    </vt:vector>
  </TitlesOfParts>
  <Company>TTVC</Company>
  <LinksUpToDate>false</LinksUpToDate>
  <SharedDoc>false</SharedDoc>
  <HyperlinksChanged>false</HyperlinksChanged>
  <AppVersion>16.0000</AppVersion>
</Properties>
'''

with ZipFile(out, 'w', compression=ZIP_DEFLATED) as z:
    z.writestr('[Content_Types].xml', content_types)
    z.writestr('_rels/.rels', rels)
    z.writestr('docProps/core.xml', core)
    z.writestr('docProps/app.xml', app)
    z.writestr('word/document.xml', document_xml)
    z.writestr('word/styles.xml', styles_xml)

print(f'Created: {out.resolve()}')
