-- ============================================================
-- STUDENT MANAGEMENT SYSTEM
-- COMPLETE DATABASE
-- ============================================================

CREATE DATABASE IF NOT EXISTS student_management
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE student_management;

SET FOREIGN_KEY_CHECKS = 0;


-- ============================================================
-- USERS
-- ============================================================

DROP TABLE IF EXISTS attendance;
DROP TABLE IF EXISTS grades;
DROP TABLE IF EXISTS subject_enrollments;
DROP TABLE IF EXISTS enrollment_subjects;
DROP TABLE IF EXISTS enrollments;
DROP TABLE IF EXISTS class_schedules;
DROP TABLE IF EXISTS subjects;
DROP TABLE IF EXISTS scholarships;
DROP TABLE IF EXISTS account_statements;
DROP TABLE IF EXISTS announcements;
DROP TABLE IF EXISTS advisers;
DROP TABLE IF EXISTS student_profiles;
DROP TABLE IF EXISTS users;


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,

    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,

    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150),

    course VARCHAR(150),
    admission_type VARCHAR(100),
    student_id VARCHAR(50),
    year_level VARCHAR(100),

    avatar_initials VARCHAR(10),

    role VARCHAR(50) DEFAULT 'Student',

    status VARCHAR(30) DEFAULT 'Active',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP

) ENGINE=InnoDB;


-- ============================================================
-- STUDENT PROFILES
-- ============================================================

CREATE TABLE student_profiles (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NULL,

    student_id VARCHAR(50) NOT NULL UNIQUE,

    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    last_name VARCHAR(100) NOT NULL,
    suffix VARCHAR(20),

    date_of_birth DATE,
    place_of_birth VARCHAR(150),

    sex VARCHAR(30),
    civil_status VARCHAR(30),

    nationality VARCHAR(80) DEFAULT 'Filipino',
    religion VARCHAR(80),

    student_type VARCHAR(40) DEFAULT 'Regular',
    student_status VARCHAR(40) DEFAULT 'Active',

    photo_path VARCHAR(255),

    mobile_number VARCHAR(40),
    email VARCHAR(150),

    current_address TEXT,
    permanent_address TEXT,

    city_municipality VARCHAR(100),
    province VARCHAR(100),

    academic_program VARCHAR(150),

    year_level VARCHAR(50),

    section_name VARCHAR(100),

    emergency_contact_name VARCHAR(150),
    emergency_contact_number VARCHAR(50),
    emergency_contact_relationship VARCHAR(80),

    guardian_name VARCHAR(150),
    guardian_contact VARCHAR(50),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_student_profile_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE

) ENGINE=InnoDB;


-- ============================================================
-- ADVISERS
-- ============================================================

CREATE TABLE advisers (

    id INT AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(150) NOT NULL,

    email VARCHAR(150),

    contact_number VARCHAR(50),

    department VARCHAR(150),

    section_name VARCHAR(100),

    academic_program VARCHAR(150),

    status VARCHAR(30) DEFAULT 'Active',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB;


-- ============================================================
-- ACADEMIC YEARS / SEMESTERS
-- ============================================================

CREATE TABLE academic_terms (

    id INT AUTO_INCREMENT PRIMARY KEY,

    academic_year VARCHAR(30) NOT NULL,

    semester ENUM(
        'First Semester',
        'Second Semester',
        'Summer'
    ) NOT NULL,

    start_date DATE,

    end_date DATE,

    status ENUM(
        'Upcoming',
        'Active',
        'Completed'
    ) DEFAULT 'Upcoming',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY unique_term (
        academic_year,
        semester
    )

) ENGINE=InnoDB;


-- ============================================================
-- SUBJECTS
-- ============================================================

CREATE TABLE subjects (

    id INT AUTO_INCREMENT PRIMARY KEY,

    code VARCHAR(30) NOT NULL UNIQUE,

    name VARCHAR(200) NOT NULL,

    description TEXT,

    units DECIMAL(3,1) DEFAULT 3.0,

    lecture_hours DECIMAL(5,2),

    laboratory_hours DECIMAL(5,2),

    instructor VARCHAR(150),

    department VARCHAR(150),

    year_level VARCHAR(50),

    semester VARCHAR(50),

    prerequisite VARCHAR(200),

    status VARCHAR(30) DEFAULT 'Active',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB;


-- ============================================================
-- CLASS SCHEDULES
-- ============================================================

CREATE TABLE class_schedules (

    id INT AUTO_INCREMENT PRIMARY KEY,

    subject_id INT NOT NULL,

    academic_term_id INT NOT NULL,

    instructor VARCHAR(150),

    room VARCHAR(100),

    day VARCHAR(30),

    start_time TIME,

    end_time TIME,

    section_name VARCHAR(100),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_schedule_subject
        FOREIGN KEY (subject_id)
        REFERENCES subjects(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_schedule_term
        FOREIGN KEY (academic_term_id)
        REFERENCES academic_terms(id)
        ON DELETE CASCADE

) ENGINE=InnoDB;


-- ============================================================
-- ENROLLMENTS
-- ============================================================

CREATE TABLE enrollments (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    academic_term_id INT NULL,

    academic_year VARCHAR(30),

    semester VARCHAR(50),

    year_level VARCHAR(50),

    section_name VARCHAR(100),

    status ENUM(
        'Pending',
        'Enrolled',
        'Approved',
        'Dropped',
        'Completed',
        'Cancelled'
    ) DEFAULT 'Pending',

    enrolled_at DATE,

    approved_at DATE,

    remarks TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_enrollment_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_enrollment_term
        FOREIGN KEY (academic_term_id)
        REFERENCES academic_terms(id)
        ON DELETE SET NULL

) ENGINE=InnoDB;


-- ============================================================
-- ENROLLMENT SUBJECTS
-- ============================================================

CREATE TABLE enrollment_subjects (

    id INT AUTO_INCREMENT PRIMARY KEY,

    enrollment_id INT NOT NULL,

    subject_id INT NOT NULL,

    schedule_id INT NULL,

    status VARCHAR(30) DEFAULT 'Enrolled',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_es_enrollment
        FOREIGN KEY (enrollment_id)
        REFERENCES enrollments(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_es_subject
        FOREIGN KEY (subject_id)
        REFERENCES subjects(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_es_schedule
        FOREIGN KEY (schedule_id)
        REFERENCES class_schedules(id)
        ON DELETE SET NULL,

    UNIQUE KEY unique_enrollment_subject (
        enrollment_id,
        subject_id
    )

) ENGINE=InnoDB;


-- ============================================================
-- SUBJECT ENROLLMENTS
-- ============================================================

CREATE TABLE subject_enrollments (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    subject_id INT NOT NULL,

    academic_year VARCHAR(30),

    semester VARCHAR(50),

    section_name VARCHAR(100),

    status VARCHAR(30) DEFAULT 'Enrolled',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_se_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_se_subject
        FOREIGN KEY (subject_id)
        REFERENCES subjects(id)
        ON DELETE CASCADE

) ENGINE=InnoDB;


-- ============================================================
-- GRADES
-- ============================================================

CREATE TABLE grades (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    subject_id INT NOT NULL,

    academic_term_id INT NULL,

    academic_year VARCHAR(30),

    semester VARCHAR(50),

    prelim DECIMAL(5,2),

    midterm DECIMAL(5,2),

    prefinal DECIMAL(5,2),

    final DECIMAL(5,2),

    final_grade DECIMAL(5,2),

    equivalent VARCHAR(20),

    remarks VARCHAR(100),

    instructor VARCHAR(150),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_grade_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_grade_subject
        FOREIGN KEY (subject_id)
        REFERENCES subjects(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_grade_term
        FOREIGN KEY (academic_term_id)
        REFERENCES academic_terms(id)
        ON DELETE SET NULL

) ENGINE=InnoDB;


-- ============================================================
-- ATTENDANCE
-- ============================================================

CREATE TABLE attendance (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    subject_id INT NOT NULL,

    attendance_date DATE NOT NULL,

    status ENUM(
        'Present',
        'Absent',
        'Late',
        'Excused'
    ) NOT NULL DEFAULT 'Present',

    remarks VARCHAR(255),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_attendance_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_attendance_subject
        FOREIGN KEY (subject_id)
        REFERENCES subjects(id)
        ON DELETE CASCADE

) ENGINE=InnoDB;


-- ============================================================
-- ACCOUNT STATEMENTS
-- ============================================================

CREATE TABLE account_statements (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    description VARCHAR(200),

    transaction_type ENUM(
        'Charge',
        'Payment',
        'Refund',
        'Adjustment',
        'Scholarship'
    ) DEFAULT 'Charge',

    amount DECIMAL(12,2) DEFAULT 0.00,

    transaction_date DATE,

    reference_number VARCHAR(100),

    status VARCHAR(30) DEFAULT 'Posted',

    remarks TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_account_statement_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE

) ENGINE=InnoDB;


-- ============================================================
-- SCHOLARSHIPS
-- ============================================================

CREATE TABLE scholarships (

    id INT AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(150) NOT NULL,

    description TEXT,

    provider VARCHAR(150),

    amount DECIMAL(12,2),

    deadline DATE,

    requirements TEXT,

    status ENUM(
        'Open',
        'Closed',
        'Upcoming'
    ) DEFAULT 'Open',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB;


-- ============================================================
-- STUDENT SCHOLARSHIPS
-- ============================================================

CREATE TABLE student_scholarships (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    scholarship_id INT NOT NULL,

    application_date DATE,

    status ENUM(
        'Pending',
        'Approved',
        'Rejected',
        'Active',
        'Completed'
    ) DEFAULT 'Pending',

    amount_awarded DECIMAL(12,2),

    remarks TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_student_scholarship_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_student_scholarship
        FOREIGN KEY (scholarship_id)
        REFERENCES scholarships(id)
        ON DELETE CASCADE

) ENGINE=InnoDB;


-- ============================================================
-- ANNOUNCEMENTS
-- ============================================================

CREATE TABLE announcements (

    id INT AUTO_INCREMENT PRIMARY KEY,

    title VARCHAR(200) NOT NULL,

    body TEXT NOT NULL,

    category VARCHAR(100) DEFAULT 'General',

    priority ENUM(
        'Normal',
        'Important',
        'Urgent'
    ) DEFAULT 'Normal',

    published_by VARCHAR(150),

    publish_date DATE,

    expiry_date DATE,

    status ENUM(
        'Draft',
        'Published',
        'Archived'
    ) DEFAULT 'Published',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP

) ENGINE=InnoDB;


-- ============================================================
-- NOTIFICATIONS
-- ============================================================

CREATE TABLE notifications (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    title VARCHAR(200) NOT NULL,

    message TEXT NOT NULL,

    notification_type VARCHAR(50) DEFAULT 'General',

    is_read TINYINT(1) DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_notification_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE

) ENGINE=InnoDB;


-- ============================================================
-- DOCUMENTS
-- ============================================================

CREATE TABLE student_documents (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    document_type VARCHAR(100) NOT NULL,

    document_name VARCHAR(200),

    file_path VARCHAR(255),

    status ENUM(
        'Pending',
        'Submitted',
        'Verified',
        'Rejected'
    ) DEFAULT 'Pending',

    remarks TEXT,

    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    verified_at TIMESTAMP NULL,

    CONSTRAINT fk_document_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE

) ENGINE=InnoDB;


-- ============================================================
-- SAMPLE USERS
-- ============================================================

INSERT INTO users
(
    username,
    password,
    full_name,
    email,
    course,
    admission_type,
    student_id,
    year_level,
    avatar_initials,
    role,
    status
)
VALUES
(
    'student',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC2XbG1y0Qm3wQj7W',
    'Juan Dela Cruz',
    'student@example.com',
    'Bachelor of Science in Information Technology',
    'Regular',
    '2024-00001',
    '4th Year',
    'JD',
    'Student',
    'Active'
);


-- ============================================================
-- SAMPLE STUDENT PROFILE
-- ============================================================

INSERT INTO student_profiles
(
    user_id,
    student_id,
    first_name,
    middle_name,
    last_name,
    suffix,
    date_of_birth,
    place_of_birth,
    sex,
    civil_status,
    nationality,
    religion,
    student_type,
    student_status,
    mobile_number,
    email,
    current_address,
    permanent_address,
    city_municipality,
    province,
    academic_program,
    year_level,
    section_name
)
SELECT
    id,
    '2024-00001',
    'Juan',
    'Santos',
    'Dela Cruz',
    '',
    '2003-05-15',
    'Manila',
    'Male',
    'Single',
    'Filipino',
    '',
    'Regular',
    'Active',
    '09171234567',
    'student@example.com',
    'Manila, Philippines',
    'Manila, Philippines',
    'Manila',
    'Metro Manila',
    'Bachelor of Science in Information Technology',
    '4th Year',
    'BSIT-4A'
FROM users
WHERE username = 'student'
LIMIT 1;


-- ============================================================
-- ADVISER
-- ============================================================

INSERT INTO advisers
(
    name,
    email,
    contact_number,
    department,
    section_name,
    academic_program,
    status
)
VALUES
(
    'Faculty Adviser',
    'adviser@example.com',
    '09170000000',
    'Information Technology',
    'BSIT-4A',
    'Bachelor of Science in Information Technology',
    'Active'
);


-- ============================================================
-- ACADEMIC TERMS
-- ============================================================

INSERT INTO academic_terms
(
    academic_year,
    semester,
    start_date,
    end_date,
    status
)
VALUES
(
    '2025-2026',
    'First Semester',
    '2025-08-01',
    '2025-12-20',
    'Completed'
),
(
    '2025-2026',
    'Second Semester',
    '2026-01-05',
    '2026-05-30',
    'Completed'
),
(
    '2026-2027',
    'First Semester',
    '2026-08-01',
    '2026-12-20',
    'Active'
),
(
    '2026-2027',
    'Second Semester',
    '2027-01-05',
    '2027-05-30',
    'Upcoming'
);


-- ============================================================
-- SUBJECTS
-- ============================================================

INSERT INTO subjects
(
    code,
    name,
    description,
    units,
    lecture_hours,
    laboratory_hours,
    instructor,
    department,
    year_level,
    semester,
    status
)
VALUES
(
    'IT401',
    'Web Security',
    'Fundamentals of web application security and secure development.',
    3,
    2,
    2,
    'Faculty Instructor',
    'Information Technology',
    '4th Year',
    'First Semester',
    'Active'
),
(
    'IT402',
    'System Administration and Maintenance',
    'System administration, maintenance, monitoring, and troubleshooting.',
    3,
    2,
    2,
    'Faculty Instructor',
    'Information Technology',
    '4th Year',
    'First Semester',
    'Active'
),
(
    'IAS402',
    'Information Assurance and Security 2',
    'Advanced concepts in information assurance and security.',
    3,
    3,
    0,
    'Faculty Instructor',
    'Information Technology',
    '4th Year',
    'First Semester',
    'Active'
),
(
    'BPM401',
    'Business Process Management in IT',
    'Business process analysis and management using information technology.',
    3,
    3,
    0,
    'Faculty Instructor',
    'Information Technology',
    '4th Year',
    'First Semester',
    'Active'
),
(
    'TE401',
    'Technopreneurship',
    'Technology entrepreneurship and business development.',
    3,
    3,
    0,
    'Faculty Instructor',
    'Information Technology',
    '4th Year',
    'First Semester',
    'Active'
),
(
    'SPI401',
    'Social and Professional Issues',
    'Social, ethical, legal, and professional issues in computing.',
    3,
    3,
    0,
    'Faculty Instructor',
    'Information Technology',
    '4th Year',
    'First Semester',
    'Active'
);


-- ============================================================
-- ANNOUNCEMENTS
-- ============================================================

INSERT INTO announcements
(
    title,
    body,
    category,
    priority,
    published_by,
    publish_date,
    status
)
VALUES
(
    'Welcome to the Student Management System',
    'Welcome to your student portal. Check this page regularly for important announcements.',
    'General',
    'Normal',
    'Registrar',
    CURRENT_DATE,
    'Published'
),
(
    'First Semester Enrollment',
    'Students are reminded to complete their enrollment requirements for the First Semester.',
    'Enrollment',
    'Important',
    'Registrar',
    CURRENT_DATE,
    'Published'
),
(
    'Academic Advising',
    'Students may coordinate with their assigned adviser regarding subjects and enrollment concerns.',
    'Academic',
    'Normal',
    'Registrar',
    CURRENT_DATE,
    'Published'
);


-- ============================================================
-- SCHOLARSHIPS
-- ============================================================

INSERT INTO scholarships
(
    name,
    description,
    provider,
    amount,
    deadline,
    requirements,
    status
)
VALUES
(
    'Academic Excellence Scholarship',
    'Scholarship for qualified students with excellent academic performance.',
    'CRAD Scholarship Office',
    15000.00,
    '2026-10-30',
    'Must maintain the required academic standing.',
    'Open'
),
(
    'Student Assistance Scholarship',
    'Financial assistance program for qualified students.',
    'Student Affairs Office',
    10000.00,
    '2026-11-15',
    'Submit the required supporting documents.',
    'Open'
);


-- ============================================================
-- SAMPLE ENROLLMENT
-- ============================================================

INSERT INTO enrollments
(
    user_id,
    academic_term_id,
    academic_year,
    semester,
    year_level,
    section_name,
    status,
    enrolled_at
)
SELECT
    u.id,
    t.id,
    t.academic_year,
    t.semester,
    '4th Year',
    'BSIT-4A',
    'Enrolled',
    CURRENT_DATE
FROM users u
JOIN academic_terms t
    ON t.academic_year = '2026-2027'
    AND t.semester = 'First Semester'
WHERE u.username = 'student'
LIMIT 1;


-- ============================================================
-- SAMPLE ACCOUNT STATEMENTS
-- ============================================================

INSERT INTO account_statements
(
    user_id,
    description,
    transaction_type,
    amount,
    transaction_date,
    reference_number,
    status
)
SELECT
    id,
    'Tuition and Miscellaneous Fees',
    'Charge',
    25000.00,
    CURRENT_DATE,
    'CHG-2026-001',
    'Posted'
FROM users
WHERE username = 'student'
LIMIT 1;


INSERT INTO account_statements
(
    user_id,
    description,
    transaction_type,
    amount,
    transaction_date,
    reference_number,
    status
)
SELECT
    id,
    'Enrollment Payment',
    'Payment',
    10000.00,
    CURRENT_DATE,
    'PAY-2026-001',
    'Posted'
FROM users
WHERE username = 'student'
LIMIT 1;


-- ============================================================
-- RESTORE FOREIGN KEY CHECKING
-- ============================================================

SET FOREIGN_KEY_CHECKS = 1;


-- ============================================================
-- VERIFY DATABASE
-- ============================================================

SELECT 'DATABASE INSTALLED SUCCESSFULLY' AS message;

SHOW TABLES;