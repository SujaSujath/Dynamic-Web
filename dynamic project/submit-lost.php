<?php
// DB connection settings
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "lostfound";

// Connect to database
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get form data
$item_name  = $_POST['item_name'];
$description = $_POST['description'];
$location   = $_POST['location'];
$contact    = $_POST['contact'];

// Handle image upload
$target_dir = "uploads/";
$image_name = basename($_FILES["item_image"]["name"]);
$target_file = $target_dir . time() . "_" . $image_name;
$image_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check file type (optional but safer)
$allowed_types = ["jpg", "jpeg", "png", "gif"];
if (!in_array($image_type, $allowed_types)) {
  die("Only JPG, JPEG, PNG & GIF files are allowed.");
}

if (move_uploaded_file($_FILES["item_image"]["tmp_name"], $target_file)) {
  // Insert into database
  $sql = "INSERT INTO lost_items (item_name, description, location, contact, image_path)
          VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssss", $item_name, $description, $location, $contact, $target_file);

  if ($stmt->execute()) {
    echo "<h2>✅ Lost item reported successfully!</h2>";
    echo "<a href='index.html'>Go Back Home</a>";
  } else {
    echo "❌ Error: " . $stmt->error;
  }

  $stmt->close();
} else {
  echo "❌ Failed to upload image.";
}

$conn->close();
?>
