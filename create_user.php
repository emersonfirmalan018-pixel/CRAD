<?php

require_once 'db.php';

$password = password_hash(
    'password123',
    PASSWORD_DEFAULT
);

$stmt = $pdo->prepare(
    "INSERT INTO users
    (
        username,
        password,
        full_name,
        email,
        course,
        admission_type,
        student_id,
        year_level,
        avatar_initials
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

$stmt->execute([

    's22122364',

    $password,

    'Emerson Barrientos Firmalan',

    '22122364@bcp.edu.ph',

    'Bachelor of Science in Information Technology',

    'Regular',

    '22122364',

    '4th Year College',

    'EF'

]);

echo "Student account created.";

?>