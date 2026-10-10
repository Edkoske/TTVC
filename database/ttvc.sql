CREATE TABLE users (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 full_name VARCHAR(120) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 role ENUM('student') NOT NULL DEFAULT 'student',
 status ENUM('active','disabled') NOT NULL DEFAULT 'active',
 force_password_change TINYINT(1) NOT NULL DEFAULT 0,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE students (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id BIGINT UNSIGNED NOT NULL UNIQUE,
 phone VARCHAR(30) NULL,
 county VARCHAR(100) NULL,
 student_number VARCHAR(40) NULL UNIQUE,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_students_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE admins (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 full_name VARCHAR(120) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 role ENUM('Super Admin','Admissions Officer','Content Manager') NOT NULL DEFAULT 'Content Manager',
 status ENUM('active','disabled') NOT NULL DEFAULT 'active',
 force_password_change TINYINT(1) NOT NULL DEFAULT 1,
 last_login DATETIME NULL,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE admin_password_resets (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 admin_id BIGINT UNSIGNED NOT NULL UNIQUE,
 token_hash CHAR(64) NOT NULL,
 expires_at DATETIME NOT NULL,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_admin_password_resets_admin FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE departments (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(150) NOT NULL UNIQUE,
 description TEXT NULL,
 status ENUM('active','inactive') NOT NULL DEFAULT 'active',
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE courses (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 department_id BIGINT UNSIGNED NOT NULL,
 name VARCHAR(180) NOT NULL,
 level ENUM('Artisan','Certificate','Craft','Diploma','Other') NOT NULL,
 duration VARCHAR(80) NOT NULL,
 entry_requirements TEXT NOT NULL,
 description TEXT NOT NULL,
 modules TEXT NULL,
 careers TEXT NULL,
 fees VARCHAR(160) NULL,
 funding TEXT NULL,
 image VARCHAR(255) NULL,
 status ENUM('draft','published') NOT NULL DEFAULT 'draft',
 featured TINYINT(1) NOT NULL DEFAULT 0,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 INDEX idx_courses_search (name, level, status), INDEX idx_courses_department (department_id, status),
 CONSTRAINT fk_courses_department FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE RESTRICT
) ENGINE=InnoDB;
CREATE TABLE applications (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 application_number VARCHAR(32) NOT NULL UNIQUE,
 user_id BIGINT UNSIGNED NULL,
 full_name VARCHAR(150) NOT NULL,
 gender VARCHAR(40) NOT NULL,
 date_of_birth DATE NOT NULL,
 id_number VARCHAR(60) NOT NULL,
 phone VARCHAR(35) NOT NULL,
 email VARCHAR(190) NOT NULL,
 county VARCHAR(100) NOT NULL,
 sub_county VARCHAR(100) NULL,
 postal_address VARCHAR(255) NULL,
 kcse_index VARCHAR(50) NULL,
 kcse_year SMALLINT UNSIGNED NOT NULL,
 grade VARCHAR(10) NOT NULL,
 previous_institution VARCHAR(180) NULL,
 highest_qualification VARCHAR(180) NULL,
 course_id BIGINT UNSIGNED NOT NULL,
 intake VARCHAR(60) NOT NULL,
 status ENUM('Submitted','Under Review','Shortlisted','Accepted','Rejected','Completed') NOT NULL DEFAULT 'Submitted',
 admin_notes TEXT NULL,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 INDEX idx_applications_status (status, created_at), INDEX idx_applications_email (email),
 CONSTRAINT fk_applications_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
 CONSTRAINT fk_applications_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE RESTRICT
) ENGINE=InnoDB;
CREATE TABLE application_documents (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 application_id BIGINT UNSIGNED NOT NULL,
 document_type VARCHAR(40) NOT NULL,
 file_path VARCHAR(255) NOT NULL,
 uploaded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_documents_application FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE news (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(220) NOT NULL,
 slug VARCHAR(240) NOT NULL UNIQUE,
 excerpt VARCHAR(500) NULL,
 image VARCHAR(255) NULL,
 content MEDIUMTEXT NOT NULL,
 author VARCHAR(120) NULL,
 category VARCHAR(100) NOT NULL DEFAULT 'College news',
 status ENUM('draft','published') NOT NULL DEFAULT 'draft',
 published_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_news_status_date (status, published_at)
) ENGINE=InnoDB;
CREATE TABLE events (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(200) NOT NULL,
 event_date DATE NOT NULL,
 event_time TIME NULL,
 location VARCHAR(180) NULL,
 description TEXT NOT NULL,
 image VARCHAR(255) NULL,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_events_date (event_date)
) ENGINE=InnoDB;
CREATE TABLE gallery (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(180) NOT NULL,
 category VARCHAR(100) NOT NULL,
 image_path VARCHAR(255) NOT NULL,
 is_published TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE facilities (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(180) NOT NULL,
 description TEXT NOT NULL,
 image VARCHAR(255) NULL,
 sort_order SMALLINT NOT NULL DEFAULT 0,
 is_published TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;
CREATE TABLE downloads (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(180) NOT NULL,
 description VARCHAR(500) NULL,
 category ENUM('Admission','Fees','Brochures','Forms','Policies','Student documents','Other') NOT NULL DEFAULT 'Other',
 file_path VARCHAR(255) NOT NULL,
 is_public TINYINT(1) NOT NULL DEFAULT 1,
 download_count BIGINT UNSIGNED NOT NULL DEFAULT 0,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE staff (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(150) NOT NULL,
 position VARCHAR(150) NOT NULL,
 department_id BIGINT UNSIGNED NULL,
 biography TEXT NULL,
 photo VARCHAR(255) NULL,
 email VARCHAR(190) NULL,
 status ENUM('active','inactive') NOT NULL DEFAULT 'active',
 CONSTRAINT fk_staff_department FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
) ENGINE=InnoDB;
CREATE TABLE enquiries (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(120) NOT NULL,
 email VARCHAR(190) NOT NULL,
 phone VARCHAR(35) NULL,
 subject VARCHAR(180) NOT NULL,
 message TEXT NOT NULL,
 status ENUM('new','read','resolved') NOT NULL DEFAULT 'new',
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_enquiries_status (status, created_at)
) ENGINE=InnoDB;
CREATE TABLE faqs (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 question VARCHAR(240) NOT NULL,
 answer TEXT NOT NULL,
 sort_order SMALLINT NOT NULL DEFAULT 0,
 is_published TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;
CREATE TABLE vacancies (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(180) NOT NULL,
 department VARCHAR(150) NOT NULL,
 description TEXT NOT NULL,
 requirements TEXT NOT NULL,
 deadline DATE NOT NULL,
 file_path VARCHAR(255) NULL,
 instructions TEXT NOT NULL,
 status ENUM('draft','published','closed') NOT NULL DEFAULT 'draft'
) ENGINE=InnoDB;
CREATE TABLE notifications (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id BIGINT UNSIGNED NULL,
 application_id BIGINT UNSIGNED NULL,
 title VARCHAR(180) NOT NULL,
 message TEXT NOT NULL,
 is_read TINYINT(1) NOT NULL DEFAULT 0,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_notifications_user (user_id, is_read, created_at),
 CONSTRAINT fk_notifications_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
 CONSTRAINT fk_notifications_application FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE settings (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 setting_key VARCHAR(100) NOT NULL UNIQUE,
 setting_value TEXT NULL,
 updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE activity_logs (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 admin_id BIGINT UNSIGNED NULL,
 action VARCHAR(120) NOT NULL,
 entity VARCHAR(100) NULL,
 entity_id BIGINT UNSIGNED NULL,
 ip_address VARCHAR(45) NULL,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_activity_created (created_at),
 CONSTRAINT fk_activity_admin FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO admins (full_name,email,password_hash,role,force_password_change) VALUES
('TTVC Demo Administrator','admin@ttvc.ac.ke','$2y$10$JoZn0niffovZV2EtXNNOOeLBkD38wrwOIR7eWXRlpzCecZr79JBYO','Super Admin',1);
INSERT INTO departments (name,description) VALUES
('Computing and Informatics','Digital skills, software, networks and information systems.'),
('Electrical and Electronics Engineering','Electrical installation, electronics and renewable energy.'),
('Building and Civil Engineering','Construction, drafting, surveying and civil works.'),
('Business Studies','Business operations, accounting, procurement and enterprise.'),
('Agriculture','Crop production, agribusiness and sustainable agriculture.'),
('Hospitality','Food production, accommodation and service management.'),
('Fashion Design','Garment construction, fashion illustration and textiles.'),
('Automotive and Mechanical Engineering','Vehicle systems, fabrication and mechanical maintenance.');
INSERT INTO courses (department_id,name,level,duration,entry_requirements,description,modules,careers,fees,funding,status,featured) VALUES
(1,'Diploma in Information Communication Technology','Diploma','3 years','KCSE mean grade C- or equivalent, subject to current placement requirements.','Build practical capability in computer systems, software, networking and user support.','Computer systems; programming; networking; databases; project practice.','ICT support technician; junior developer; network assistant.','Contact admissions for current fee structure.','Check current government and HELB funding eligibility.','published',1),
(1,'Certificate in Information Communication Technology','Certificate','2 years','KCSE D plain or equivalent.','Develop digital literacy and foundational IT support skills.','Computer applications; operating systems; basic networks; customer support.','IT support assistant; office technology assistant.','Contact admissions for current fee structure.','Funding guidance available from admissions.','published',1),
(1,'Artisan Certificate in Computer Applications','Artisan','1 year','Open entry; placement guidance applies.','A hands-on introduction to essential computer applications and digital work practices.','Word processing; spreadsheets; presentations; internet safety.','Digital office assistant; data entry clerk.','Contact admissions.','Ask admissions about available options.','published',0),
(1,'Certificate in ICT Technician','Certificate','2 years','KCSE D plain or equivalent.','Prepare for entry-level systems and hardware support roles.','Hardware; operating systems; help desk; network fundamentals.','Computer technician; help desk assistant.','Contact admissions.','Ask admissions about available options.','published',0),
(2,'Diploma in Electrical and Electronic Engineering','Diploma','3 years','KCSE C- or equivalent, with mathematics and science recommended.','Study electrical principles with workshop-based installation and maintenance practice.','Electrical machines; installation; electronics; safety; project work.','Electrical technician; installation supervisor trainee.','Contact admissions.','Check current government and HELB funding eligibility.','published',1),
(2,'Craft Certificate in Electrical Installation','Craft','2 years','KCSE D plain or equivalent.','Gain supervised practical skills in domestic and commercial electrical installation.','Wiring systems; circuit protection; testing; safety regulations.','Electrical installer assistant; maintenance assistant.','Contact admissions.','Funding guidance available from admissions.','published',1),
(2,'Artisan Certificate in Solar PV Systems','Artisan','1 year','Basic literacy and numeracy; placement guidance applies.','Learn safe installation and maintenance fundamentals for small solar PV systems.','PV basics; wiring; batteries; installation; safety.','Solar installation assistant.','Contact admissions.','Ask admissions about available options.','published',0),
(3,'Diploma in Building Technology','Diploma','3 years','KCSE C- or equivalent.','Develop technical skills for building construction, materials and site practice.','Construction technology; materials; estimating; site management.','Building technician; site assistant.','Contact admissions.','Check current government and HELB funding eligibility.','published',1),
(3,'Craft Certificate in Building Technology','Craft','2 years','KCSE D plain or equivalent.','A practical foundation in construction methods and building trades.','Masonry; setting out; materials; occupational safety.','Masonry technician; construction assistant.','Contact admissions.','Funding guidance available from admissions.','published',0),
(3,'Certificate in Plumbing','Certificate','1 year','KCSE D plain or equivalent.','Train in water supply, drainage and safe plumbing installation.','Pipework; fittings; drainage; maintenance; safety.','Plumbing assistant; maintenance technician.','Contact admissions.','Ask admissions about available options.','published',0),
(4,'Diploma in Business Management','Diploma','3 years','KCSE C- or equivalent.','Build broad business skills in management, communication and enterprise.','Business communication; management; marketing; entrepreneurship.','Business administrator; enterprise assistant.','Contact admissions.','Check current funding opportunities.','published',1),
(4,'Certificate in Accounting and Finance','Certificate','2 years','KCSE D plain or equivalent.','Develop practical bookkeeping and finance administration skills.','Bookkeeping; accounting software; taxation basics; budgeting.','Accounts assistant; cashier.','Contact admissions.','Funding guidance available from admissions.','published',0),
(4,'Craft Certificate in Supply Chain Management','Craft','2 years','KCSE D plain or equivalent.','Study purchasing, inventory and logistics fundamentals.','Procurement; stores; inventory; logistics.','Stores assistant; procurement clerk.','Contact admissions.','Ask admissions about available options.','published',0),
(4,'Artisan Certificate in Entrepreneurship','Artisan','1 year','Open entry; placement guidance applies.','Turn a business idea into a practical plan with financial and customer basics.','Business planning; costing; customer care; record keeping.','Self-employment; micro-enterprise.','Contact admissions.','Ask admissions about available options.','published',0),
(5,'Diploma in Agriculture','Diploma','3 years','KCSE C- or equivalent.','Combine crop and livestock knowledge with sustainable farm enterprise practice.','Crop production; animal science; soil care; agribusiness.','Agricultural extension assistant; farm supervisor.','Contact admissions.','Check current government and HELB funding eligibility.','published',1),
(5,'Certificate in General Agriculture','Certificate','2 years','KCSE D plain or equivalent.','Practical training in farm production and agricultural value chains.','Crop husbandry; livestock; farm tools; agribusiness basics.','Farm assistant; production trainee.','Contact admissions.','Funding guidance available from admissions.','published',0),
(6,'Diploma in Hospitality Management','Diploma','3 years','KCSE C- or equivalent.','Prepare for guest services and hospitality operations through practical learning.','Food production; front office; housekeeping; service management.','Hospitality supervisor trainee; front office assistant.','Contact admissions.','Check current funding opportunities.','published',1),
(6,'Certificate in Food and Beverage Production','Certificate','2 years','KCSE D plain or equivalent.','Build kitchen and food service competencies in supervised practical settings.','Food preparation; hygiene; nutrition; service practice.','Commis cook; food service assistant.','Contact admissions.','Funding guidance available from admissions.','published',0),
(7,'Certificate in Fashion Design and Garment Making','Certificate','2 years','KCSE D plain or equivalent.','Develop creative and technical skills in clothing design and construction.','Pattern drafting; garment construction; textiles; portfolio practice.','Tailoring professional; garment production assistant.','Contact admissions.','Ask admissions about available options.','published',0),
(8,'Diploma in Automotive Engineering','Diploma','3 years','KCSE C- or equivalent.','Study vehicle systems, diagnostics and maintenance through workshop practice.','Engine systems; electrical systems; diagnostics; workshop safety.','Automotive technician; service advisor trainee.','Contact admissions.','Check current government and HELB funding eligibility.','published',1),
(8,'Craft Certificate in Mechanical Engineering','Craft','2 years','KCSE D plain or equivalent.','Build foundational mechanical, fabrication and workshop maintenance skills.','Workshop processes; fitting; fabrication; maintenance.','Mechanical technician assistant; fabricator.','Contact admissions.','Funding guidance available from admissions.','published',0);
INSERT INTO news (title,slug,excerpt,image,content,author,category,status,published_at) VALUES
('TTVC opens applications for the new academic intake','applications-new-intake','Prospective learners can now apply online to join a practical programme at TTVC.','https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=900&q=80','Applications are open for the coming intake. Explore current programmes and submit your details through the online application form. Keep your application number to follow progress.','Admissions Office','Admissions','published',NOW()),
('Engineering learners put workshop skills into practice','engineering-workshop-practice','Students turn classroom concepts into practical workshop experience.','https://images.unsplash.com/photo-1581092334651-ddf26d9a09d0?auto=format&fit=crop&w=900&q=80','Practical sessions help learners build confidence with tools, teamwork and the problem-solving habits required in the workplace.','TTVC Communications','Campus life','published',DATE_SUB(NOW(),INTERVAL 4 DAY)),
('College and local employers discuss skills needs','industry-skills-dialogue','A new dialogue focuses on stronger training and employment connections.','https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=900&q=80','Representatives from local employers met with trainers to discuss the competencies that help graduates contribute from day one.','TTVC Communications','Partnerships','published',DATE_SUB(NOW(),INTERVAL 8 DAY)),
('Agriculture students explore sustainable production','sustainable-agriculture-learning','Learners connect soil care and crop planning with resilient farm practice.','https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=900&q=80','Students explored practical approaches to soil health, crop planning and responsible use of resources as part of applied training.','Agriculture Department','Learning','published',DATE_SUB(NOW(),INTERVAL 13 DAY)),
('Hospitality class hosts a student service showcase','hospitality-service-showcase','A student showcase celebrates teamwork, creativity and professional service.','https://images.unsplash.com/photo-1551218808-94e220e084d2?auto=format&fit=crop&w=900&q=80','Hospitality learners demonstrated food preparation and guest-service skills in a practical showcase for the campus community.','Hospitality Department','Campus life','published',DATE_SUB(NOW(),INTERVAL 18 DAY)),
('Digital skills clinic supports the wider community','digital-skills-clinic','A campus clinic shares essential digital skills and safe online practices.','https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80','The clinic brought learners and community members together for practical sessions on everyday productivity tools and online safety.','Computing Department','Community','published',DATE_SUB(NOW(),INTERVAL 25 DAY));
INSERT INTO events (title,event_date,event_time,location,description,image) VALUES
('New student orientation',DATE_ADD(CURDATE(),INTERVAL 8 DAY),'09:00:00','Main assembly hall','Meet your department, trainers and fellow students.','https://images.unsplash.com/photo-1523580494863-6f3031224c94?auto=format&fit=crop&w=900&q=80'),
('Open workshop day',DATE_ADD(CURDATE(),INTERVAL 17 DAY),'10:00:00','Engineering workshops','Explore training spaces and see practical demonstrations.','https://images.unsplash.com/photo-1581092334651-ddf26d9a09d0?auto=format&fit=crop&w=900&q=80'),
('Careers and enterprise clinic',DATE_ADD(CURDATE(),INTERVAL 24 DAY),'11:00:00','Library resource centre','Get guidance on CV writing, interviews and starting a small enterprise.','https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=900&q=80'),
('Agriculture field learning day',DATE_ADD(CURDATE(),INTERVAL 34 DAY),'08:30:00','College demonstration farm','Join practical sessions on crop management and soil health.','https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=900&q=80'),
('Student innovation showcase',DATE_ADD(CURDATE(),INTERVAL 43 DAY),'10:00:00','Main campus','Learners present projects from across academic departments.','https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80'),
('Sports and wellness afternoon',DATE_ADD(CURDATE(),INTERVAL 52 DAY),'14:00:00','College sports grounds','A friendly afternoon of sport, movement and campus community.','https://images.unsplash.com/photo-1526676037777-05a232554f77?auto=format&fit=crop&w=900&q=80');
INSERT INTO faqs (question,answer,sort_order) VALUES
('How do I apply?','Choose a course and use the online application form. Keep your application number to check status. You may also contact the admissions office for help.',1),
('What courses are available?','Visit the Courses page for current programmes, levels, durations and department filters.',2),
('What are the entry requirements?','Requirements vary by programme and level. Review the relevant course details or ask admissions for guidance.',3),
('How long do courses take?','Course durations vary from approximately one year for some artisan programmes to three years for diploma programmes.',4),
('How do I pay fees?','Current fee guidance is provided by the admissions office. Confirm payment instructions directly with the college before making a payment.',5),
('Is funding available?','Funding eligibility and availability can change. Contact admissions for current information on applicable support.',6),
('Where is TTVC located?','The college is in Tindiret, Nandi County, Kenya. Contact the college for directions.',7),
('Can I apply before receiving all my documents?','You can submit your application and follow up with admissions about outstanding documents and deadlines.',8),
('How do I check my application status?','Use the Application Status page and enter the application number shown on your acknowledgement.',9),
('Can I apply for more than one course?','For guidance about multiple programme choices, contact admissions before submitting separate applications.',10);
INSERT INTO gallery (title,category,image_path) VALUES
('Practical learning in the workshop','Workshops','https://images.unsplash.com/photo-1581092334651-ddf26d9a09d0?auto=format&fit=crop&w=900&q=80'),
('Campus learning community','Students','https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=900&q=80'),
('Hands-on engineering practice','Laboratories','https://images.unsplash.com/photo-1581092580497-e0d23cbdf1dc?auto=format&fit=crop&w=900&q=80'),
('Learning together','Campus','https://images.unsplash.com/photo-1523580494863-6f3031224c94?auto=format&fit=crop&w=900&q=80'),
('Food production training','Students','https://images.unsplash.com/photo-1551218808-94e220e084d2?auto=format&fit=crop&w=900&q=80'),
('Agriculture in practice','Campus','https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=900&q=80'),
('Digital learning skills','Laboratories','https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80'),
('Collaborative project work','Students','https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=900&q=80'),
('A day to celebrate achievement','Graduation','https://images.unsplash.com/photo-1523580494863-6f3031224c94?auto=format&fit=crop&w=900&q=80'),
('Campus sports and wellness','Sports','https://images.unsplash.com/photo-1526676037777-05a232554f77?auto=format&fit=crop&w=900&q=80');
INSERT INTO facilities (name,description,image,sort_order) VALUES
('ICT laboratories','Computer laboratories support hands-on practice in applications, programming and network fundamentals.','https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80',1),
('Engineering workshops','Dedicated workshop learning supports technical practice, fabrication and safe tool use.','https://images.unsplash.com/photo-1581092334651-ddf26d9a09d0?auto=format&fit=crop&w=900&q=80',2),
('Library and resource centre','A quiet study environment with print and digital learning resources.','https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=900&q=80',3),
('Learning classrooms','Flexible teaching spaces for discussion, demonstrations and group learning.','https://images.unsplash.com/photo-1580582932707-520aed937b7b?auto=format&fit=crop&w=900&q=80',4),
('Hospitality training space','Practical spaces help hospitality learners develop food preparation and service skills.','https://images.unsplash.com/photo-1551218808-94e220e084d2?auto=format&fit=crop&w=900&q=80',5),
('Agriculture demonstration area','Outdoor learning connects agricultural theory with production and environmental practice.','https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=900&q=80',6);
INSERT INTO downloads (title,description,category,file_path) VALUES
('Admissions information guide','Overview of applying and preparing your documents.','Admission','assets/uploads/resources/ttvc-admission-guide.pdf'),
('Course directory','Summary of programme levels, departments and study pathways.','Brochures','assets/uploads/resources/ttvc-course-directory.pdf'),
('Student application checklist','Checklist for preparing your documents and online application form.','Forms','assets/uploads/resources/ttvc-application-checklist.pdf'),
('Fees and levies guide','Current fee guidance and important student financial information.','Fees','assets/uploads/resources/ttvc-fees-and-levies.pdf'),
('Student handbook','A practical guide to student life, support services and expectations.','Student documents','assets/uploads/resources/ttvc-student-handbook.pdf'),
('Student services guide','Guidance on student support, wellbeing and campus services.','Student documents','assets/uploads/resources/ttvc-student-services-guide.pdf'),
('College information sheet','A short introduction to TTVC programmes and services.','Other','assets/uploads/resources/ttvc-information.txt');
INSERT INTO settings (setting_key,setting_value) VALUES
('phone','+254 700 000 000'),('email','info@ttvc.ac.ke'),('address','Tindiret, Nandi County, Kenya'),('stat_courses','20+'),('stat_departments','8'),('elearning_url','https://moodle.org');
