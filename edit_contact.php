<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "T2311E_LastAssignment";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT ID, Name, PhoneNumber FROM PhoneBook WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $contact = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $phoneNumber = $_POST["phoneNumber"];

    $stmt = $conn->prepare("UPDATE PhoneBook SET Name = ?, PhoneNumber = ? WHERE ID = ?");
    $stmt->bind_param("ssi", $name, $phoneNumber, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Contact updated successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Contact</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Edit Contact</h1>
        <?php if (isset($contact)) { ?>
        <form method="POST" action="edit_contact.php">
            <input type="hidden" name="id" value="<?php echo $contact['ID']; ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $contact['Name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="phoneNumber">Phone Number</label>
                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" value="<?php echo $contact['PhoneNumber']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
        <?php } else { ?>
        <div class="alert alert-danger" role="alert">
            Contact not found.
        </div>
        <?php } ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
