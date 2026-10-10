from docx import Document
from docx.shared import Inches


def add_heading(doc, text, level=1):
    doc.add_heading(text, level=level)


def add_paragraph(doc, text):
    if text:
        doc.add_paragraph(text)


doc = Document()
doc.styles['Normal'].font.name = 'Calibri'
doc.styles['Normal'].font.size = 11

# Title
p = doc.add_paragraph()
p.add_run('TTVC Website Documentation').bold = True
p.runs[0].font.size = 22

add_paragraph(doc, 'This document explains the TTVC project step by step, using the actual code structure and workflow implemented in this website.')

add_heading(doc, '1. Project overview', 1)
add_paragraph(doc, 'Tindiret Technical and Vocational College is a custom PHP and MySQL website built as a monolithic application. It has public pages for admissions, courses, news, downloads, and contacts; a student portal; and an admin panel for managing content and applications.')
add_paragraph(doc, 'The site does not use a framework. Instead, it relies on standard PHP files, reusable includes, and a MySQL database to manage content, forms, and records.')

add_heading(doc, '2. Main folders and purpose', 1)
sections = [
    ('config/', 'Stores database and application configuration files. Includes the database connection and global settings.'),
    ('includes/', 'Holds shared files such as the page header, footer, navbar, flash messages, and common functions.'),
    ('admin/', 'Contains administrative pages for dashboards, applications, courses, facilities, downloads, and settings.'),
    ('student/', 'Contains protected student pages such as dashboard, profile, document uploads, and application status.'),
    ('assets/', 'Stores CSS, JS, images, and uploaded files such as PDFs and documents.'),
    ('database/', 'Contains the SQL schema and seed data used to create the system tables and demo records.'),
    ('index.php', 'Public homepage and main landing page for the college.'),
    ('apply.php', 'Online application form where applicants submit personal details and supporting documents.'),
    ('download.php', 'Secure download endpoint for serving uploaded files.'),
    ('downloads.php', 'Public list of downloadable resources.'),
]
for name, explain in sections:
    add_paragraph(doc, f'- {name}: {explain}')

add_heading(doc, '3. Configuration and startup flow', 1)
add_paragraph(doc, 'The app starts by loading config/config.php. This file sets the base URL, application environment, upload limits, timezone, and session security settings.')
add_paragraph(doc, 'The most important values are APP_ENV, APP_BASE_URL, UPLOAD_DIR, and MAX_UPLOAD_BYTES. These values control how the app behaves in local development and in production.')
add_paragraph(doc, 'The session is initialized here with secure settings such as HttpOnly, SameSite, and HTTPS-aware cookies.')

add_heading(doc, '4. Database connection', 1)
add_paragraph(doc, 'The database connection is created in config/database.php using PDO. This file reads environment variables such as TTVC_DB_HOST, TTVC_DB_NAME, TTVC_DB_USER, TTVC_DB_PASS, and TTVC_DB_PORT, then builds a MySQL DSN.')
add_paragraph(doc, 'The db() function caches one PDO instance so the application reuses the same connection across many requests. This improves performance and keeps the database logic centralised.')
add_paragraph(doc, 'Each database access is managed using prepared statements, which helps prevent SQL injection and keeps data access consistent across the whole project.')

add_heading(doc, '5. Shared helper functions', 1)
add_paragraph(doc, 'The includes/functions.php file is the backbone of the site. It contains helper functions used by almost every page.')
add_paragraph(doc, 'Functions include: e() to escape HTML values, url() to build URLs, redirect() to send users to another page, csrf_token() and verify_csrf() to protect forms, setting() to read site settings, flash() to show notification messages, and upload_file() to validate and store uploaded files.')
add_paragraph(doc, 'This file makes the code easier to maintain because the logic is not repeated in each page; instead, all modules use the same standard behaviour.')

add_heading(doc, '6. Public homepage flow', 1)
add_paragraph(doc, 'The homepage is created in index.php. It begins by loading the shared functions and then reads a few records from the database to display featured courses and recent news.')
add_paragraph(doc, 'The page uses a hero slider with several background images, a statistics band, course cards, department cards, campaign panels, and a resource centre section. This makes the site feel like a real college landing page.')
add_paragraph(doc, 'It also links to key pages such as Apply Now, Courses, Departments, Download Centre, Contact, and About. These links help the public navigate the site and discover the offerings of the college.')

add_heading(doc, '7. The application process', 1)
add_paragraph(doc, 'The application workflow is handled in apply.php. It loads available courses from the database and validates form submission data when a user submits the form.')
add_paragraph(doc, 'The first step validates required fields such as full name, gender, date of birth, phone, email, county, KCSE year, and selected course. The script also checks if the email is well formatted and if the chosen course is actually available.')
add_paragraph(doc, 'If the form is valid, the code loops through uploaded files and stores them in the assets/uploads/applications folder using upload_file(). It enforces allowed MIME types and a maximum size of 5 MB.')
add_paragraph(doc, 'After saving the files, the application record is inserted into the applications table and a generated application number is assigned. The document references are then saved in the application_documents table so they remain traceable.')
add_paragraph(doc, 'If any error occurs, the page shows a user-friendly message and does not submit incomplete data.')

add_heading(doc, '8. File upload and security details', 1)
add_paragraph(doc, 'The upload_file() function checks for no-file errors, upload errors, file size limits, MIME type, and destination directory. It creates the folder if needed and saves the file using a random name.')
add_paragraph(doc, 'Only safe media types are accepted. For passport photos, JPEG, PNG, and WEBP are allowed. For academic or support documents, PDF, JPG, or PNG is allowed.')
add_paragraph(doc, 'The project also protects uploaded application files by keeping them under controlled server access and through .htaccess-related restrictions. This reduces the chance of direct public access to private student records.')

add_heading(doc, '9. Download logic', 1)
add_paragraph(doc, 'The download flow is built around download.php and the downloads table in the database. A public page lists resources in downloads.php and links them to the file-serving endpoint.')
add_paragraph(doc, 'When a user clicks a download, the site reads the requested file record and checks whether the file exists in the allowed uploads root. It then sends the proper headers and content to the browser.')
add_paragraph(doc, 'This design prevents arbitrary file traversal. The code compares the resolved real path against the allowed upload directory before sending anything to the client.')
add_paragraph(doc, 'The download script also increments the download count, helping the college track which resources are being accessed most often.')

add_heading(doc, '10. Student portal', 1)
add_paragraph(doc, 'The student folder contains a protected area for logged-in learners. This includes dashboard.php, profile.php, document.php, documents.php, application.php, and application-status.php.')
add_paragraph(doc, 'This area is designed for student-specific tasks such as viewing a profile, seeing application status, tracking submitted documents, and downloading relevant academic files. These pages rely on login checks and session validation to ensure access is limited to the correct learners.')

add_heading(doc, '11. Admin panel', 1)
add_paragraph(doc, 'The admin folder holds the management functions for the college. It includes pages for dashboard, applications, courses, departments, events, gallery, downloads, FAQs, notifications, staff, students, users, and settings.')
add_paragraph(doc, 'These pages let the administrator manage admissions, student records, course content, public resources, and general settings. This is the control centre for the entire website.')
add_paragraph(doc, 'The admin flow is role-aware, and the code uses role checks and session validation to keep non-admin users away from restricted pages.')

add_heading(doc, '12. Database structure and content model', 1)
add_paragraph(doc, 'The SQL file in database/ttvc.sql creates the main tables used by the app. These normally include users, roles, settings, courses, departments, applications, documents, downloads, news, events, gallery items, FAQs, facilities, and vacancies.')
add_paragraph(doc, 'The database provides the content for the site rather than hardcoded text only. This makes the site easier to update without changing PHP code for every new course or downloadable item.')

add_heading(doc, '13. Why the site structure works well', 1)
add_paragraph(doc, 'This project is reliable because it keeps code in a few clear layers: configuration, shared functions, public pages, student pages, admin pages, and data storage.')
add_paragraph(doc, 'The code is simple to follow for a PHP developer because each page loads common functions, handles logic, and then renders HTML. There is no framework to obscure the process, but the application still uses good practices such as prepared statements, session security, and file validation.')

add_heading(doc, '14. Deployment and maintenance notes', 1)
add_paragraph(doc, 'To run the site locally, place it inside the XAMPP htdocs folder, start Apache and MySQL, and import the SQL schema into a ttvc database. Then open the site in a browser to verify the homepage and application flow.')
add_paragraph(doc, 'For production, the app should use HTTPS, a secure database user, strong admin passwords, protected upload folders, and correct environment settings. The demo credentials and placeholder site details should be replaced before public launch.')
add_paragraph(doc, 'Regular backups are strongly recommended for both the database and the uploaded files, especially because the application handles student records and application documents.')

add_heading(doc, 'Conclusion', 1)
add_paragraph(doc, 'The TTVC project is a complete, data-driven college management and public website built in native PHP. It combines public content, student account features, application submission, download management, and admin control into one organised system. The project is practical, maintainable, and suitable for a real education institution website.')

# Save
output_path = 'TTVC_Project_Documentation.docx'
doc.save(output_path)
print(f'Created: {output_path}')
