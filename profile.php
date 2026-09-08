<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'db.php';

$studentTypes = [
    'Regular',
    'Irregular',
    'Transferee',
    'Returning Student'
];

$studentStatuses = [
    'Active',
    'Inactive',
    'Graduated',
    'Dropped',
    'Withdrawn'
];

$sexOptions = [
    'Male',
    'Female',
    'Prefer not to say'
];

$civilStatuses = [
    'Single',
    'Married',
    'Widowed',
    'Separated'
];

$username = $_SESSION['username'] ?? 'Registrar';

$editingNewRecord = isset($_GET['new']);
$requestedStudentId = trim($_GET['student_id'] ?? '');

$message = '';
$error = '';

function profileValue(array $profile, string $key): string
{
    return e($profile[$key] ?? '');
}

$profile = [
    'id' => null,
    'user_id' => null,
    'student_id' => '',
    'first_name' => '',
    'middle_name' => '',
    'last_name' => '',
    'suffix' => '',
    'date_of_birth' => '',
    'place_of_birth' => '',
    'sex' => '',
    'civil_status' => '',
    'nationality' => 'Filipino',
    'religion' => '',
    'student_type' => 'Regular',
    'student_status' => 'Active',
    'photo_path' => '',
    'mobile_number' => '',
    'email' => '',
    'current_address' => '',
    'permanent_address' => '',
    'city_municipality' => '',
    'province' => '',
    'academic_program' => ''
];

try {

    /*
    |--------------------------------------------------------------------------
    | LOAD STUDENT PROFILE
    |--------------------------------------------------------------------------
    */

    if ($requestedStudentId !== '') {

        $lookup = $pdo->prepare(
            'SELECT *
             FROM student_profiles
             WHERE student_id = ?
             LIMIT 1'
        );

        $lookup->execute([
            $requestedStudentId
        ]);

    } else {

        $lookup = $pdo->prepare(
            'SELECT *
             FROM student_profiles
             WHERE user_id = ?
             ORDER BY id DESC
             LIMIT 1'
        );

        $lookup->execute([
            $_SESSION['user_id']
        ]);
    }

    $savedProfile = $lookup->fetch(PDO::FETCH_ASSOC);

    if ($savedProfile && !$editingNewRecord) {
        $profile = array_merge(
            $profile,
            $savedProfile
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE PROFILE
    |--------------------------------------------------------------------------
    */

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $profileId = filter_input(
            INPUT_POST,
            'profile_id',
            FILTER_VALIDATE_INT
        );

        if (!$profileId) {
            $profileId = null;
        }

        $studentId = trim(
            $_POST['student_id'] ?? ''
        );

        $firstName = trim(
            $_POST['first_name'] ?? ''
        );

        $lastName = trim(
            $_POST['last_name'] ?? ''
        );

        /*
        |--------------------------------------------------------------------------
        | REQUIRED FIELDS
        |--------------------------------------------------------------------------
        */

        if (
            $studentId === '' ||
            $firstName === '' ||
            $lastName === ''
        ) {
            throw new RuntimeException(
                'Student ID, first name, and last name are required.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CHECK DUPLICATE STUDENT ID
        |--------------------------------------------------------------------------
        */

        if ($profileId) {

            $duplicateCheck = $pdo->prepare(
                'SELECT id
                 FROM student_profiles
                 WHERE student_id = ?
                 AND id != ?
                 LIMIT 1'
            );

            $duplicateCheck->execute([
                $studentId,
                $profileId
            ]);

        } else {

            $duplicateCheck = $pdo->prepare(
                'SELECT id
                 FROM student_profiles
                 WHERE student_id = ?
                 LIMIT 1'
            );

            $duplicateCheck->execute([
                $studentId
            ]);
        }

        if ($duplicateCheck->fetch()) {
            throw new RuntimeException(
                'The student ID already exists. Please use a different student ID.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PHOTO
        |--------------------------------------------------------------------------
        */

        $photoPath =
            $savedProfile['photo_path']
            ?? ($profile['photo_path'] ?? '');

        if (
            isset($_FILES['student_photo']) &&
            !empty($_FILES['student_photo']['tmp_name'])
        ) {

            if ($_FILES['student_photo']['error'] !== UPLOAD_ERR_OK) {
                throw new RuntimeException(
                    'The student photo could not be uploaded.'
                );
            }

            $imageInfo = @getimagesize(
                $_FILES['student_photo']['tmp_name']
            );

            if (
                !$imageInfo ||
                !in_array(
                    $imageInfo['mime'],
                    [
                        'image/jpeg',
                        'image/png',
                        'image/webp'
                    ],
                    true
                )
            ) {
                throw new RuntimeException(
                    'Please upload a JPG, PNG, or WEBP image.'
                );
            }

            if (
                $_FILES['student_photo']['size']
                > 2 * 1024 * 1024
            ) {
                throw new RuntimeException(
                    'The student photo must be 2 MB or smaller.'
                );
            }

            $uploadDirectory =
                __DIR__
                . DIRECTORY_SEPARATOR
                . 'assets'
                . DIRECTORY_SEPARATOR
                . 'uploads';

            if (
                !is_dir($uploadDirectory) &&
                !mkdir($uploadDirectory, 0755, true)
            ) {
                throw new RuntimeException(
                    'The photo upload directory could not be created.'
                );
            }

            $extension = strtolower(
                pathinfo(
                    $_FILES['student_photo']['name'],
                    PATHINFO_EXTENSION
                )
            );

            $fileName =
                bin2hex(random_bytes(12))
                . '.'
                . $extension;

            $destination =
                $uploadDirectory
                . DIRECTORY_SEPARATOR
                . $fileName;

            if (
                !move_uploaded_file(
                    $_FILES['student_photo']['tmp_name'],
                    $destination
                )
            ) {
                throw new RuntimeException(
                    'The student photo could not be saved.'
                );
            }

            $photoPath =
                'assets/uploads/'
                . $fileName;
        }

        /*
        |--------------------------------------------------------------------------
        | FORM VALUES
        |--------------------------------------------------------------------------
        */

        $dateOfBirth =
            trim($_POST['date_of_birth'] ?? '');

        if ($dateOfBirth === '') {
            $dateOfBirth = null;
        }

        $studentType =
            $_POST['student_type'] ?? 'Regular';

        if (!in_array(
            $studentType,
            $studentTypes,
            true
        )) {
            $studentType = 'Regular';
        }

        $studentStatus =
            $_POST['student_status'] ?? 'Active';

        if (!in_array(
            $studentStatus,
            $studentStatuses,
            true
        )) {
            $studentStatus = 'Active';
        }

        $sex =
            $_POST['sex'] ?? '';

        if (!in_array(
            $sex,
            $sexOptions,
            true
        )) {
            $sex = '';
        }

        $civilStatus =
            $_POST['civil_status'] ?? '';

        if (!in_array(
            $civilStatus,
            $civilStatuses,
            true
        )) {
            $civilStatus = '';
        }

        /*
        |--------------------------------------------------------------------------
        | VALUES
        |--------------------------------------------------------------------------
        */

        $values = [
            $studentId,
            $firstName,
            trim($_POST['middle_name'] ?? ''),
            $lastName,
            trim($_POST['suffix'] ?? ''),
            $dateOfBirth,
            trim($_POST['place_of_birth'] ?? ''),
            $sex,
            $civilStatus,
            trim($_POST['nationality'] ?? 'Filipino'),
            trim($_POST['religion'] ?? ''),
            $studentType,
            $studentStatus,
            $photoPath,
            trim($_POST['mobile_number'] ?? ''),
            trim($_POST['email'] ?? ''),
            trim($_POST['current_address'] ?? ''),
            trim($_POST['permanent_address'] ?? ''),
            trim($_POST['city_municipality'] ?? ''),
            trim($_POST['province'] ?? ''),
            trim($_POST['academic_program'] ?? '')
        ];

        /*
        |--------------------------------------------------------------------------
        | UPDATE EXISTING PROFILE
        |--------------------------------------------------------------------------
        */

        if ($profileId) {

            $update = $pdo->prepare(
                'UPDATE student_profiles SET
                    student_id = ?,
                    first_name = ?,
                    middle_name = ?,
                    last_name = ?,
                    suffix = ?,
                    date_of_birth = ?,
                    place_of_birth = ?,
                    sex = ?,
                    civil_status = ?,
                    nationality = ?,
                    religion = ?,
                    student_type = ?,
                    student_status = ?,
                    photo_path = ?,
                    mobile_number = ?,
                    email = ?,
                    current_address = ?,
                    permanent_address = ?,
                    city_municipality = ?,
                    province = ?,
                    academic_program = ?
                 WHERE id = ?'
            );

            $update->execute(
                array_merge(
                    $values,
                    [$profileId]
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | INSERT NEW PROFILE
        |--------------------------------------------------------------------------
        */

        else {

            $userId = $_SESSION['user_id'];

            $insert = $pdo->prepare(
                'INSERT INTO student_profiles
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
                    photo_path,
                    mobile_number,
                    email,
                    current_address,
                    permanent_address,
                    city_municipality,
                    province,
                    academic_program
                )
                VALUES
                (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )'
            );

            $insert->execute(
                array_merge(
                    [$userId],
                    $values
                )
            );

            $profileId =
                (int) $pdo->lastInsertId();
        }

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT:
        | AFTER SAVING -> ENROLLMENT REPORTS
        |--------------------------------------------------------------------------
        */

        header(
            'Location: enrollment_reports.php?saved=1&student_id='
            . urlencode($studentId)
        );

        exit;
    }

} catch (PDOException $exception) {

    if ($exception->getCode() === '42S02') {

        $error =
            'The student_profiles table is not installed yet. Import the updated database.sql file first.';

    } elseif ($exception->getCode() === '23000') {

        $error =
            'The student ID already exists or violates a database constraint.';

    } else {

        $error =
            'The record could not be loaded or saved. Please check the database connection.';
    }

} catch (RuntimeException $exception) {

    $error =
        $exception->getMessage();
}

/*
|--------------------------------------------------------------------------
| SUCCESS MESSAGE
|--------------------------------------------------------------------------
*/

if (isset($_GET['saved'])) {

    $message =
        'Student information saved successfully.';
}

/*
|--------------------------------------------------------------------------
| FULL NAME
|--------------------------------------------------------------------------
*/

$fullName = trim(
    ($profile['first_name'] ?? '')
    . ' '
    . ($profile['middle_name'] ?? '')
    . ' '
    . ($profile['last_name'] ?? '')
    . ' '
    . ($profile['suffix'] ?? '')
);

if ($fullName === '') {
    $fullName = 'New Student Record';
}

/*
|--------------------------------------------------------------------------
| INITIALS
|--------------------------------------------------------------------------
*/

$initials = '';

if (!empty($profile['first_name'])) {

    $initials .= strtoupper(
        substr(
            $profile['first_name'],
            0,
            1
        )
    );
}

if (!empty($profile['last_name'])) {

    $initials .= strtoupper(
        substr(
            $profile['last_name'],
            0,
            1
        )
    );
}

if ($initials === '') {
    $initials = 'ST';
}

?>
<!doctype html>

<html lang="en">

<head>

<meta charset="utf-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1"
>

<title>
    Personal Information Database | CRAD
</title>

<link
    rel="icon"
    type="image/png"
    href="logo.png"
>

<link
    rel="stylesheet"
    href="assets/style.css"
>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    background: #eef3f7;
    color: #172033;
    font-family:
        Arial,
        Helvetica,
        sans-serif;
}

.main {
    min-height: 100vh;
    transition: .25s ease;
}

.profile-page {
    background:
        linear-gradient(
            180deg,
            #edf3f7 0%,
            #f5f7fa 100%
        );
}

/* TOP BAR */

.topbar {
    height: 54px;
    background: #ffffff;
    border-bottom: 1px solid #dce4eb;
    display: flex;
    align-items: center;
    padding: 0 22px;
    position: sticky;
    top: 0;
    z-index: 50;
    box-shadow:
        0 1px 5px rgba(20,45,70,.04);
}

.menu-button {
    width: 34px;
    height: 34px;
    border: 0;
    background: transparent;
    color: #344054;
    font-size: 21px;
    cursor: pointer;
}

.topbar-right {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 17px;
}

.notification {
    position: relative;
    color: #344054;
    font-size: 19px;
}

.notification::after {
    content: "3";
    position: absolute;
    top: -7px;
    right: -7px;
    width: 15px;
    height: 15px;
    display: grid;
    place-items: center;
    border-radius: 50%;
    background: #c8414b;
    color: #fff;
    font-size: 8px;
    font-weight: 800;
}

.user-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: grid;
    place-items: center;
    background:
        linear-gradient(
            135deg,
            #173c68,
            #267e8a
        );
    color: #fff;
    font-size: 10px;
    font-weight: 800;
    box-shadow:
        0 2px 5px rgba(22,58,93,.18);
}

/* CONTENT */

.profile-content {
    padding: 22px 28px 40px;
    max-width: 1500px;
    margin: auto;
}

/* HEADING */

.profile-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 15px;
}

.heading-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.heading-icon {
    width: 42px;
    height: 42px;
    display: grid;
    place-items: center;
    border-radius: 9px;
    background:
        linear-gradient(
            135deg,
            #173b64,
            #237d88
        );
    color: white;
    font-size: 19px;
    box-shadow:
        0 5px 12px rgba(20,63,92,.16);
}

.profile-heading h1 {
    margin: 0;
    color: #122d60;
    font-size: 23px;
    font-weight: 800;
    letter-spacing: -.4px;
}

.profile-heading p {
    margin: 4px 0 0;
    color: #778496;
    font-size: 10px;
}

.profile-actions {
    display: flex;
    align-items: center;
    gap: 7px;
}

.profile-button {
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 14px;
    border: 0;
    border-radius: 5px;
    background:
        linear-gradient(
            135deg,
            #1665ca,
            #1f79d8
        );
    color: white;
    text-decoration: none;
    font-size: 10px;
    font-weight: 800;
    cursor: pointer;
    box-shadow:
        0 3px 7px rgba(25,100,190,.15);
}

.profile-button:hover {
    transform: translateY(-1px);
}

.profile-button.secondary {
    background: white;
    color: #52627a;
    border: 1px solid #d6dfe8;
    box-shadow: none;
}

/* ALERT */

.notice {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 13px;
    padding: 10px 13px;
    border-radius: 6px;
    background: #e8f8ef;
    color: #14794f;
    border: 1px solid #c9ecd9;
    font-size: 11px;
    font-weight: 600;
}

.notice::before {
    content: "✓";
    width: 20px;
    height: 20px;
    display: grid;
    place-items: center;
    border-radius: 50%;
    background: #c8edd8;
    font-weight: 900;
}

.notice.error {
    background: #fff1f1;
    color: #b43e3e;
    border-color: #f1d0d0;
}

.notice.error::before {
    content: "!";
    background: #f5d0d0;
}

/* SUMMARY */

.profile-summary {
    display: grid;
    grid-template-columns:
        1.35fr
        repeat(4, 1fr);
    gap: 9px;
    margin-bottom: 14px;
}

.summary-main,
.summary-stat {
    background: #fff;
    border: 1px solid #dfe6ed;
    border-radius: 8px;
    box-shadow:
        0 3px 10px rgba(28,56,99,.045);
}

.summary-main {
    min-height: 92px;
    padding: 13px 15px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.student-avatar {
    width: 62px;
    height: 62px;
    flex-shrink: 0;
    border-radius: 8px;
    overflow: hidden;
    display: grid;
    place-items: center;
    background:
        linear-gradient(
            135deg,
            #173c68,
            #348f93
        );
    color: white;
    font-size: 19px;
    font-weight: 800;
}

.student-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.summary-name {
    margin: 0;
    color: #172f5f;
    font-size: 14px;
    font-weight: 800;
}

.summary-id {
    margin-top: 4px;
    color: #718096;
    font-size: 9px;
}

.summary-program {
    margin-top: 6px;
    display: inline-flex;
    padding: 4px 7px;
    border-radius: 4px;
    background: #edf5fc;
    color: #2870b8;
    font-size: 8px;
    font-weight: 700;
}

.summary-stat {
    padding: 12px;
    position: relative;
    overflow: hidden;
}

.summary-stat::after {
    content: "";
    position: absolute;
    width: 48px;
    height: 48px;
    right: -20px;
    bottom: -21px;
    border-radius: 50%;
    background: rgba(30,107,139,.06);
}

.stat-icon {
    width: 25px;
    height: 25px;
    display: grid;
    place-items: center;
    border-radius: 6px;
    background: #edf5f9;
    color: #247188;
    font-size: 12px;
    margin-bottom: 7px;
}

.stat-label {
    color: #8a95a3;
    font-size: 7px;
    font-weight: 700;
}

.stat-value {
    margin-top: 3px;
    color: #24385b;
    font-size: 11px;
    font-weight: 800;
}

/* CARD */

.profile-card {
    background: #fff;
    border: 1px solid #dce4ec;
    box-shadow:
        0 5px 18px rgba(28,56,99,.055);
    border-radius: 9px;
    overflow: hidden;
}

.profile-card-header {
    min-height: 52px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 11px 17px;
    border-bottom: 1px solid #e7edf3;
    background:
        linear-gradient(
            90deg,
            #ffffff,
            #fbfcfd
        );
}

.profile-card-header-left {
    display: flex;
    align-items: center;
    gap: 9px;
}

.card-header-icon {
    width: 29px;
    height: 29px;
    display: grid;
    place-items: center;
    border-radius: 6px;
    background: #edf5fa;
    color: #237188;
    font-size: 12px;
}

.profile-card-header h2 {
    margin: 0;
    color: #17336a;
    font-size: 13px;
    font-weight: 800;
}

.profile-card-header span {
    color: #8792a1;
    font-size: 8px;
}

/* SECTIONS */

.profile-section {
    padding: 18px;
    border-bottom: 1px solid #edf1f5;
}

.profile-section:last-child {
    border-bottom: 0;
}

.section-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
    color: #18336b;
    font-size: 12px;
    font-weight: 800;
}

.section-description {
    margin: 4px 0 0 29px;
    color: #8994a3;
    font-size: 8px;
}

.section-number {
    width: 22px;
    height: 22px;
    display: grid;
    place-items: center;
    border-radius: 6px;
    background:
        linear-gradient(
            135deg,
            #1768d5,
            #2384d9
        );
    color: #fff;
    font-size: 9px;
    font-weight: 800;
    box-shadow:
        0 3px 7px rgba(23,104,213,.18);
}

/* GRID */

.personal-grid {
    display: grid;
    grid-template-columns:
        130px
        repeat(4, minmax(120px,1fr));
    gap: 12px;
}

.contact-grid {
    display: grid;
    grid-template-columns:
        repeat(4,minmax(130px,1fr));
    gap: 12px;
}

.additional-grid {
    display: grid;
    grid-template-columns:
        repeat(4,minmax(130px,1fr));
    gap: 12px;
}

/* PHOTO */

.photo-field {
    grid-row: span 3;
    padding: 8px;
    text-align: center;
    border: 1px dashed #cdd9e5;
    border-radius: 7px;
    background: #f8fbfd;
}

.photo-preview {
    width: 104px;
    height: 120px;
    margin: 0 auto 8px;
    border: 1px solid #d4dfeb;
    border-radius: 6px;
    background:
        linear-gradient(
            145deg,
            #edf4fa,
            #f8fafc
        );
    display: grid;
    place-items: center;
    overflow: hidden;
    color: #7d9bc0;
    font-size: 30px;
}

.photo-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-caption {
    color: #8793a2;
    font-size: 7px;
    line-height: 1.4;
    margin-bottom: 7px;
}

.photo-field label {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 9px;
    border: 1px solid #cbd9eb;
    border-radius: 4px;
    background: white;
    color: #2563b8;
    font-size: 8px;
    font-weight: 800;
    cursor: pointer;
}

.photo-field input {
    display: none;
}

/* FORM */

.field {
    min-width: 0;
}

.field label {
    display: flex;
    align-items: center;
    margin-bottom: 5px;
    color: #64748b;
    font-size: 8px;
    font-weight: 800;
}

.required {
    color: #d34848;
    margin-left: 2px;
}

.field input,
.field select,
.field textarea {
    width: 100%;
    border: 1px solid #d7e0e9;
    border-radius: 5px;
    background: #fff;
    color: #263b60;
    font-family: inherit;
    font-size: 10px;
    outline: none;
    transition:
        border-color .15s,
        box-shadow .15s,
        background .15s;
}

.field input,
.field select {
    height: 32px;
    padding: 0 9px;
}

.field textarea {
    min-height: 62px;
    padding: 8px 9px;
    resize: vertical;
    line-height: 1.4;
}

.field input:hover,
.field select:hover,
.field textarea:hover {
    border-color: #b8c8d9;
}

.field input:focus,
.field select:focus,
.field textarea:focus {
    border-color: #3d86cf;
    background: #fbfdff;
    box-shadow:
        0 0 0 3px rgba(45,124,205,.09);
}

.field.full {
    grid-column: span 2;
}

.field.wide {
    grid-column: span 2;
}

/* STATUS */

.status-preview {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 10px;
    padding: 9px 11px;
    border-radius: 5px;
    background: #f7fafc;
    border: 1px solid #e5ebf0;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #38a169;
    box-shadow:
        0 0 0 3px rgba(56,161,105,.12);
}

.status-preview-text {
    color: #627083;
    font-size: 8px;
}

.status-preview-text strong {
    color: #263b60;
}

/* INFORMATION BOXES */

.info-grid {
    display: grid;
    grid-template-columns:
        repeat(3,1fr);
    gap: 9px;
}

.info-box {
    min-height: 72px;
    padding: 11px;
    border-radius: 6px;
    border: 1px solid #e1e8ef;
    background:
        linear-gradient(
            135deg,
            #fbfcfd,
            #f5f9fc
        );
}

.info-box-title {
    color: #7d8998;
    font-size: 7px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.info-box-value {
    margin-top: 7px;
    color: #243a60;
    font-size: 10px;
    font-weight: 800;
}

.info-box-note {
    margin-top: 3px;
    color: #9099a6;
    font-size: 7px;
}

/* FOOTER */

.form-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 13px 18px;
    background: #f8fafc;
    border-top: 1px solid #e5ebf0;
}

.footer-note {
    color: #7d8997;
    font-size: 8px;
}

.footer-note strong {
    color: #52627a;
}

.footer-buttons {
    display: flex;
    gap: 7px;
}

/* SIDEBAR */

.sidebar-hidden .sidebar {
    transform: translateX(-100%);
}

.sidebar-hidden .main {
    margin-left: 0;
}

/* RESPONSIVE */

@media(max-width:1200px) {

    .profile-summary {
        grid-template-columns:
            1.5fr
            repeat(2,1fr);
    }

    .summary-main {
        grid-column: 1 / -1;
    }

    .personal-grid {
        grid-template-columns:
            130px
            repeat(3,1fr);
    }

    .contact-grid,
    .additional-grid {
        grid-template-columns:
            repeat(3,1fr);
    }
}

@media(max-width:900px) {

    .profile-content {
        padding: 18px;
    }

    .profile-summary {
        grid-template-columns:
            repeat(2,1fr);
    }

    .summary-main {
        grid-column: 1 / -1;
    }

    .personal-grid {
        grid-template-columns:
            repeat(3,1fr);
    }

    .photo-field {
        grid-row: span 2;
    }

    .contact-grid,
    .additional-grid {
        grid-template-columns:
            repeat(2,1fr);
    }

    .info-grid {
        grid-template-columns:
            repeat(2,1fr);
    }
}

@media(max-width:650px) {

    .profile-content {
        padding: 14px 12px 28px;
    }

    .profile-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .profile-actions {
        width: 100%;
    }

    .profile-actions .profile-button {
        flex: 1;
    }

    .profile-heading h1 {
        font-size: 19px;
    }

    .profile-summary {
        grid-template-columns: 1fr 1fr;
    }

    .summary-main {
        grid-column: 1 / -1;
    }

    .personal-grid,
    .contact-grid,
    .additional-grid {
        grid-template-columns: 1fr;
    }

    .photo-field {
        grid-row: auto;
    }

    .field.full,
    .field.wide {
        grid-column: auto;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .form-footer {
        align-items: stretch;
        flex-direction: column;
    }

    .footer-buttons {
        width: 100%;
    }

    .footer-buttons .profile-button {
        flex: 1;
    }
}

</style>

</head>

<body class="profile-page">

<?php include 'includes/sidebar.php'; ?>

<main class="main">

<header class="topbar">

    <button
        class="menu-button"
        type="button"
        onclick="toggleSidebar()"
        aria-label="Toggle sidebar"
    >
        ☰
    </button>

    <div class="topbar-right">

        <div class="notification">
            ♧
        </div>

        <div class="user-circle">
            <?= e(
                strtoupper(
                    substr($username, 0, 2)
                )
            ) ?>
        </div>

    </div>

</header>

<section class="profile-content">

<div class="profile-heading">

    <div class="heading-left">

        <div class="heading-icon">
            ♙
        </div>

        <div>

            <h1>
                Personal Information Database
            </h1>

            <p>
                Registrar Department
                &nbsp; • &nbsp;
                Student Records Management
            </p>

        </div>

    </div>

    <div class="profile-actions">

        <a
            class="profile-button secondary"
            href="profile.php"
        >
            × Cancel
        </a>

        <button
            class="profile-button"
            type="submit"
            form="profileForm"
        >
            ✓ Save Changes
        </button>

    </div>

</div>

<?php if ($message): ?>

<div class="notice">
    <?= e($message) ?>
</div>

<?php endif; ?>

<?php if ($error): ?>

<div class="notice error">
    <?= e($error) ?>
</div>

<?php endif; ?>

<div class="profile-summary">

    <div class="summary-main">

        <div class="student-avatar">

            <?php if (!empty($profile['photo_path'])): ?>

                <img
                    src="<?= e($profile['photo_path']) ?>"
                    alt="Student photo"
                >

            <?php else: ?>

                <?= e($initials) ?>

            <?php endif; ?>

        </div>

        <div>

            <h2 class="summary-name">
                <?= e($fullName) ?>
            </h2>

            <div class="summary-id">

                Student ID:

                <strong>
                    <?= e(
                        $profile['student_id']
                        ?: 'Not assigned'
                    ) ?>
                </strong>

            </div>

            <div class="summary-program">

                <?= e(
                    $profile['academic_program']
                    ?: 'Academic program not specified'
                ) ?>

            </div>

        </div>

    </div>

    <div class="summary-stat">

        <div class="stat-icon">
            ◉
        </div>

        <div class="stat-label">
            STUDENT STATUS
        </div>

        <div class="stat-value">
            <?= e(
                $profile['student_status']
                ?: 'Active'
            ) ?>
        </div>

    </div>

    <div class="summary-stat">

        <div class="stat-icon">
            ◆
        </div>

        <div class="stat-label">
            STUDENT TYPE
        </div>

        <div class="stat-value">
            <?= e(
                $profile['student_type']
                ?: 'Regular'
            ) ?>
        </div>

    </div>

    <div class="summary-stat">

        <div class="stat-icon">
            ◷
        </div>

        <div class="stat-label">
            DATE OF BIRTH
        </div>

        <div class="stat-value">

            <?= !empty($profile['date_of_birth'])
                ? e(
                    date(
                        'M d, Y',
                        strtotime(
                            $profile['date_of_birth']
                        )
                    )
                )
                : 'Not provided'
            ?>

        </div>

    </div>

    <div class="summary-stat">

        <div class="stat-icon">
            @
        </div>

        <div class="stat-label">
            CONTACT
        </div>

        <div class="stat-value">

            <?= e(
                $profile['mobile_number']
                ?: 'Not provided'
            ) ?>

        </div>

    </div>

</div>

<form
    id="profileForm"
    class="profile-card"
    method="post"
    enctype="multipart/form-data"
>

<input
    type="hidden"
    name="profile_id"
    value="<?= profileValue($profile, 'id') ?>"
>

<div class="profile-card-header">

    <div class="profile-card-header-left">

        <div class="card-header-icon">
            ▣
        </div>

        <div>

            <h2>
                Student Master Record
            </h2>

            <span>
                Complete and maintain accurate student information
            </span>

        </div>

    </div>

    <span>
        * Required fields
    </span>

</div>

<section class="profile-section">

    <div class="section-heading">

        <div>

            <h2 class="section-title">

                <span class="section-number">
                    1
                </span>

                Student Personal Information

            </h2>

            <p class="section-description">
                Basic identification and personal details of the student.
            </p>

        </div>

    </div>

    <div class="personal-grid">

        <div class="photo-field">

            <div
                class="photo-preview"
                id="photoPreview"
            >

                <?php if (!empty($profile['photo_path'])): ?>

                    <img
                        src="<?= e($profile['photo_path']) ?>"
                        alt="Student photo"
                    >

                <?php else: ?>

                    ◯

                <?php endif; ?>

            </div>

            <div class="photo-caption">
                JPG, PNG or WEBP<br>
                Maximum file size: 2 MB
            </div>

            <label for="studentPhoto">
                + Upload Photo
            </label>

            <input
                id="studentPhoto"
                name="student_photo"
                type="file"
                accept="image/jpeg,image/png,image/webp"
            >

        </div>

        <div class="field">

            <label for="studentId">
                Student ID / Student Number
                <span class="required">*</span>
            </label>

            <input
                id="studentId"
                name="student_id"
                required
                placeholder="e.g. 2026-00001"
                value="<?= profileValue(
                    $profile,
                    'student_id'
                ) ?>"
            >

        </div>

        <div class="field">

            <label for="firstName">
                First Name
                <span class="required">*</span>
            </label>

            <input
                id="firstName"
                name="first_name"
                required
                placeholder="Enter first name"
                value="<?= profileValue(
                    $profile,
                    'first_name'
                ) ?>"
            >

        </div>

        <div class="field">

            <label for="middleName">
                Middle Name
            </label>

            <input
                id="middleName"
                name="middle_name"
                placeholder="Enter middle name"
                value="<?= profileValue(
                    $profile,
                    'middle_name'
                ) ?>"
            >

        </div>

        <div class="field">

            <label for="lastName">
                Last Name
                <span class="required">*</span>
            </label>

            <input
                id="lastName"
                name="last_name"
                required
                placeholder="Enter last name"
                value="<?= profileValue(
                    $profile,
                    'last_name'
                ) ?>"
            >

        </div>

        <div class="field">

            <label for="suffix">
                Suffix
            </label>

            <input
                id="suffix"
                name="suffix"
                placeholder="Jr., Sr., III"
                value="<?= profileValue(
                    $profile,
                    'suffix'
                ) ?>"
            >

        </div>

        <div class="field">

            <label for="dateOfBirth">
                Date of Birth
            </label>

            <input
                id="dateOfBirth"
                name="date_of_birth"
                type="date"
                value="<?= profileValue(
                    $profile,
                    'date_of_birth'
                ) ?>"
            >

        </div>

        <div class="field">

            <label for="placeOfBirth">
                Place of Birth
            </label>

            <input
                id="placeOfBirth"
                name="place_of_birth"
                placeholder="City / Municipality"
                value="<?= profileValue(
                    $profile,
                    'place_of_birth'
                ) ?>"
            >

        </div>

        <div class="field">

            <label for="sex">
                Sex / Gender
            </label>

            <select
                id="sex"
                name="sex"
            >

                <option value="">
                    Select
                </option>

                <?php foreach ($sexOptions as $option): ?>

                    <option
                        value="<?= e($option) ?>"
                        <?= $profile['sex'] === $option
                            ? 'selected'
                            : '' ?>
                    >
                        <?= e($option) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <div class="field">

            <label for="civilStatus">
                Civil Status
            </label>

            <select
                id="civilStatus"
                name="civil_status"
            >

                <option value="">
                    Select
                </option>

                <?php foreach ($civilStatuses as $option): ?>

                    <option
                        value="<?= e($option) ?>"
                        <?= $profile['civil_status'] === $option
                            ? 'selected'
                            : '' ?>
                    >
                        <?= e($option) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <div class="field">

            <label for="nationality">
                Nationality
            </label>

            <input
                id="nationality"
                name="nationality"
                value="<?= profileValue(
                    $profile,
                    'nationality'
                ) ?>"
            >

        </div>

        <div class="field">

            <label for="religion">
                Religion
            </label>

            <input
                id="religion"
                name="religion"
                value="<?= profileValue(
                    $profile,
                    'religion'
                ) ?>"
            >

        </div>

        <div class="field">

            <label for="program">
                Academic Program
            </label>

            <input
                id="program"
                name="academic_program"
                placeholder="e.g. BS Information Technology"
                value="<?= profileValue(
                    $profile,
                    'academic_program'
                ) ?>"
            >

        </div>

        <div class="field">

            <label for="studentType">
                Student Type
            </label>

            <select
                id="studentType"
                name="student_type"
            >

                <?php foreach ($studentTypes as $option): ?>

                    <option
                        value="<?= e($option) ?>"
                        <?= $profile['student_type'] === $option
                            ? 'selected'
                            : '' ?>
                    >
                        <?= e($option) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <div class="field">

            <label for="studentStatus">
                Student Status
            </label>

            <select
                id="studentStatus"
                name="student_status"
            >

                <?php foreach ($studentStatuses as $option): ?>

                    <option
                        value="<?= e($option) ?>"
                        <?= $profile['student_status'] === $option
                            ? 'selected'
                            : '' ?>
                    >
                        <?= e($option) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>

    </div>

    <div class="status-preview">

        <span
            class="status-dot"
            id="statusDot"
        ></span>

        <div class="status-preview-text">

            Current student record status:

            <strong id="statusText">
                <?= e(
                    $profile['student_status']
                    ?: 'Active'
                ) ?>
            </strong>

        </div>

    </div>

</section>

<section class="profile-section">

    <div class="section-heading">

        <div>

            <h2 class="section-title">

                <span class="section-number">
                    2
                </span>

                Contact Information

            </h2>

            <p class="section-description">
                Current contact details and residential information.
            </p>

        </div>

    </div>

    <div class="contact-grid">

        <div class="field">

            <label for="mobile">
                Mobile Number
            </label>

            <input
                id="mobile"
                name="mobile_number"
                placeholder="+63 9XX XXX XXXX"
                value="<?= profileValue(
                    $profile,
                    'mobile_number'
                ) ?>"
            >

        </div>

        <div class="field">

            <label for="email">
                Personal Email
            </label>

            <input
                id="email"
                name="email"
                type="email"
                placeholder="student@example.com"
                value="<?= profileValue(
                    $profile,
                    'email'
                ) ?>"
            >

        </div>

        <div class="field">

            <label for="city">
                City / Municipality
            </label>

            <input
                id="city"
                name="city_municipality"
                placeholder="City or municipality"
                value="<?= profileValue(
                    $profile,
                    'city_municipality'
                ) ?>"
            >

        </div>

        <div class="field">

            <label for="province">
                Province
            </label>

            <input
                id="province"
                name="province"
                placeholder="Province"
                value="<?= profileValue(
                    $profile,
                    'province'
                ) ?>"
            >

        </div>

        <div class="field wide">

            <label for="currentAddress">
                Current Address
            </label>

            <textarea
                id="currentAddress"
                name="current_address"
                placeholder="House number, street, barangay, city / municipality, province"
            ><?= profileValue(
                $profile,
                'current_address'
            ) ?></textarea>

        </div>

        <div class="field wide">

            <label for="permanentAddress">
                Permanent Address
            </label>

            <textarea
                id="permanentAddress"
                name="permanent_address"
                placeholder="Complete permanent residential address"
            ><?= profileValue(
                $profile,
                'permanent_address'
            ) ?></textarea>

        </div>

    </div>

</section>

<section class="profile-section">

    <div class="section-heading">

        <div>

            <h2 class="section-title">

                <span class="section-number">
                    3
                </span>

                Academic & Enrollment Information

            </h2>

            <p class="section-description">
                Additional academic information used for student record management.
            </p>

        </div>

    </div>

    <div class="additional-grid">

        <div class="field">

            <label>
                Student Number
            </label>

            <input
                value="<?= profileValue(
                    $profile,
                    'student_id'
                ) ?>"
                readonly
            >

        </div>

        <div class="field">

            <label>
                Academic Program
            </label>

            <input
                value="<?= profileValue(
                    $profile,
                    'academic_program'
                ) ?>"
                readonly
            >

        </div>

        <div class="field">

            <label>
                Student Classification
            </label>

            <input
                value="<?= profileValue(
                    $profile,
                    'student_type'
                ) ?>"
                readonly
            >

        </div>

        <div class="field">

            <label>
                Enrollment Status
            </label>

            <input
                value="<?= profileValue(
                    $profile,
                    'student_status'
                ) ?>"
                readonly
            >

        </div>

    </div>

    <div style="height:10px"></div>

    <div class="info-grid">

        <div class="info-box">

            <div class="info-box-title">
                Record Classification
            </div>

            <div class="info-box-value">
                Student Master Record
            </div>

            <div class="info-box-note">
                Centralized student information
            </div>

        </div>

        <div class="info-box">

            <div class="info-box-title">
                Record Status
            </div>

            <div class="info-box-value">

                <?= e(
                    $profile['student_status']
                    ?: 'Active'
                ) ?>

            </div>

            <div class="info-box-note">
                Current enrollment classification
            </div>

        </div>

        <div class="info-box">

            <div class="info-box-title">
                Information Completeness
            </div>

            <div
                class="info-box-value"
                id="completionValue"
            >
                Calculating...
            </div>

            <div class="info-box-note">
                Based on available profile fields
            </div>

        </div>

    </div>

</section>

<div class="form-footer">

    <div class="footer-note">

        <strong>Registrar Database</strong>

        &nbsp; • &nbsp;

        Please verify all information before saving.

    </div>

    <div class="footer-buttons">

        <a
            class="profile-button secondary"
            href="profile.php"
        >
            Cancel
        </a>

        <button
            class="profile-button"
            type="submit"
        >
            ✓ Save Student Record
        </button>

    </div>

</div>

</form>

</section>

</main>

<script>

function toggleSidebar() {

    document.body.classList.toggle(
        'sidebar-hidden'
    );

}

/*
|--------------------------------------------------------------------------
| PHOTO PREVIEW
|--------------------------------------------------------------------------
*/

const photoInput =
    document.getElementById(
        'studentPhoto'
    );

const photoPreview =
    document.getElementById(
        'photoPreview'
    );

if (photoInput) {

    photoInput.addEventListener(
        'change',
        function(event) {

            const file =
                event.target.files[0];

            if (!file) {
                return;
            }

            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            if (
                !allowedTypes.includes(
                    file.type
                )
            ) {

                alert(
                    'Please select a JPG, PNG, or WEBP image.'
                );

                event.target.value = '';

                return;
            }

            if (
                file.size >
                2 * 1024 * 1024
            ) {

                alert(
                    'The student photo must be 2 MB or smaller.'
                );

                event.target.value = '';

                return;
            }

            const reader =
                new FileReader();

            reader.onload =
                function() {

                    photoPreview.innerHTML =
                        '<img src="' +
                        reader.result +
                        '" alt="Student photo preview">';

                };

            reader.readAsDataURL(file);

        }
    );
}

/*
|--------------------------------------------------------------------------
| STUDENT STATUS
|--------------------------------------------------------------------------
*/

const statusSelect =
    document.getElementById(
        'studentStatus'
    );

const statusText =
    document.getElementById(
        'statusText'
    );

const statusDot =
    document.getElementById(
        'statusDot'
    );

function updateStatus() {

    if (!statusSelect) {
        return;
    }

    const status =
        statusSelect.value;

    if (statusText) {

        statusText.textContent =
            status || 'Not specified';

    }

    const statusColors = {

        Active: '#38a169',

        Inactive: '#718096',

        Graduated: '#2877c5',

        Dropped: '#d19a37',

        Withdrawn: '#c94b54'

    };

    if (statusDot) {

        statusDot.style.background =
            statusColors[status]
            || '#718096';

    }
}

if (statusSelect) {

    statusSelect.addEventListener(
        'change',
        updateStatus
    );

    updateStatus();
}

/*
|--------------------------------------------------------------------------
| INFORMATION COMPLETENESS
|--------------------------------------------------------------------------
*/

function calculateCompleteness() {

    const fields = [

        'studentId',

        'firstName',

        'lastName',

        'dateOfBirth',

        'placeOfBirth',

        'sex',

        'civilStatus',

        'nationality',

        'program',

        'mobile',

        'email',

        'city',

        'province',

        'currentAddress',

        'permanentAddress'

    ];

    let completed = 0;

    fields.forEach(
        function(id) {

            const element =
                document.getElementById(id);

            if (
                element &&
                element.value.trim() !== ''
            ) {

                completed++;

            }

        }
    );

    const percentage =
        Math.round(
            (
                completed /
                fields.length
            ) * 100
        );

    const output =
        document.getElementById(
            'completionValue'
        );

    if (output) {

        output.textContent =
            percentage + '% Complete';

    }
}

document
    .querySelectorAll(
        '#profileForm input, #profileForm select, #profileForm textarea'
    )
    .forEach(
        function(element) {

            element.addEventListener(
                'input',
                calculateCompleteness
            );

            element.addEventListener(
                'change',
                calculateCompleteness
            );

        }
    );

calculateCompleteness();

/*
|--------------------------------------------------------------------------
| FORM VALIDATION
|--------------------------------------------------------------------------
*/

const profileForm =
    document.getElementById(
        'profileForm'
    );

if (profileForm) {

    profileForm.addEventListener(
        'submit',
        function(event) {

            const studentId =
                document.getElementById(
                    'studentId'
                ).value.trim();

            const firstName =
                document.getElementById(
                    'firstName'
                ).value.trim();

            const lastName =
                document.getElementById(
                    'lastName'
                ).value.trim();

            if (
                !studentId ||
                !firstName ||
                !lastName
            ) {

                event.preventDefault();

                alert(
                    'Please complete the required student information.'
                );

                return;
            }

        }
    );
}

</script>

</body>

</html>