<?php

require_once "../configs/Dbconn.php"; 
if (isset($_GET['author_id'])) {
    $authorId = $_GET['author_id'];

   
    $stmt = $DBconn->prepare("SELECT * FROM authortb WHERE author_id = :authorId");
    $stmt->bindParam(':authorId', $authorId);
    $stmt->execute();
    $author = $stmt->fetch(PDO::FETCH_ASSOC);

   
    if (!$author) {
        echo "Author not found.";
        exit();
    }
} else {
    echo "Author ID not provided.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $email = $_POST['email'];
    $address = $_POST['address'];
    $fullNames = $_POST['full_names'];
    $biography = $_POST['biography'];
    $dateOfBirth = $_POST['date_of_birth'];
    $suspension = isset($_POST['suspension']) ? 1 : 0;


    $stmt = $DBconn->prepare("UPDATE author_table SET email = :email, address = :address, 
                             full_names = :fullNames, biography = :biography, 
                             date_of_birth = :dateOfBirth, suspension = :suspension 
                             WHERE author_id = :authorId");

    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':fullNames', $fullNames);
    $stmt->bindParam(':biography', $biography);
    $stmt->bindParam(':dateOfBirth', $dateOfBirth);
    $stmt->bindParam(':suspension', $suspension);
    $stmt->bindParam(':authorId', $authorId);

    try {
        $stmt->execute();
        echo "Author details successfully updated.";
    } catch (PDOException $e) {
        echo "Error updating author details: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Author</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <h2>Edit Author</h2>
    <form method="post" action="">
        <label>Email:</label>
        <input type="email" name="email" value="<?= $author['email']; ?>" required><br>

        <label>Address:</label>
        <input type="text" name="address" value="<?= $author['address']; ?>" required><br>

        <label>Full Names:</label>
        <input type="text" name="full_names" value="<?= $author['full_names']; ?>" required><br>

        <label>Biography:</label>
        <textarea name="biography" rows="4" required><?= $author['biography']; ?></textarea><br>

        <label>Date of Birth:</label>
        <input type="date" name="date_of_birth" value="<?= $author['date_of_birth']; ?>" required><br>

        <label>Suspend Account:</label>
        <input type="checkbox" name="suspension" <?= $author['suspension'] ? 'checked' : ''; ?>><br>

        <input type="submit" value="Update">
    </form>
</body>
</html>