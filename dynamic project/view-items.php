<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "lostfound";

// DB connection
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to show items
function displayItems($result, $type) {
  if ($result->num_rows > 0) {
    echo "<h2 style='color:" . ($type === "Lost" ? "#d9534f" : "#5cb85c") . "'>$type Items</h2>";
    while ($row = $result->fetch_assoc()) {
      echo "<div class='item-card'>";
      echo "<img src='" . $row["image_path"] . "' alt='Item Image'>";
      echo "<div class='item-info'>";
      echo "<h3>" . htmlspecialchars($row["item_name"]) . "</h3>";
      echo "<p><strong>Description:</strong> " . htmlspecialchars($row["description"]) . "</p>";
      echo "<p><strong>Location:</strong> " . htmlspecialchars($row["location"]) . "</p>";
      echo "<p><strong>Contact:</strong> " . htmlspecialchars($row["contact"]) . "</p>";
      echo "<p><em>Reported at: " . $row["reported_at"] . "</em></p>";
      echo "</div></div>";
    }
  } else {
    echo "<p>No $type items found.</p>";
  }
}

// Get lost items
$sql_lost = "SELECT * FROM lost_items ORDER BY reported_at DESC";
$result_lost = $conn->query($sql_lost);
displayItems($result_lost, "Lost");

// Get found items
$sql_found = "SELECT * FROM found_items ORDER BY reported_at DESC";
$result_found = $conn->query($sql_found);
displayItems($result_found, "Found");

$conn->close();
?>
