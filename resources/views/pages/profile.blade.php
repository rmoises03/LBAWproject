<?php
$host = 'localhost';
$dbname = 'lbaw2382';
$username = 'postgres';
$password = 'postgres';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

$userID = 1; // Replace with the actual user ID

try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :userID");
    $stmt->bindParam(':userID', $userID);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
</head>
<body>
    <h1>Welcome, <?php echo $user['name']; ?></h1>
    <p>Email: <?php echo $user['email']; ?></p>
    <p>Username: <?php echo $user['username']; ?></p>
    <p>Following: <?php echo $user['following']; ?></p>
    <p>Followers: <?php echo $user['followers']; ?></p>
    <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture">
    <p>Description: <?php echo $user['description']; ?></p>
    <!-- Add more profile information here -->
</body>
</html>

<?php
// Step 4: Close the database connection
$conn = null;
?>
