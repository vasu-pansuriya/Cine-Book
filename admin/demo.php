<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ad_login.php');
    exit;
}

include '../db_connect.php';
include 'header.php'; // header with sidebar and topbar

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    echo "<p>Missing ID</p>";
    include 'footer.php';
    exit;
}

// fetch cinema
$stmt = $conn->prepare("SELECT * FROM cinemas WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$cinema = $res->fetch_assoc();
$stmt->close();

if (!$cinema) {
    echo "<p>Cinema not found.</p>";
    include 'footer.php';
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cinema_name = trim($_POST['name'] ?? '');
    $features = trim($_POST['features'] ?? '');
    $show_times = trim($_POST['show_times'] ?? '');
    $cancelation = trim($_POST['cancelation'] ?? '');

    if ($cinema_name === '') $errors[] = "Cinema name is required.";

    $stmt = $conn->prepare("UPDATE cinemas SET name=?, features=?, show_times=?, cancelation=? WHERE id=?");
    $stmt->bind_param('ssssi', $cinema_name, $features, $show_times, $cancelation, $id);

    if ($stmt->execute()) {
        $stmt->close();
        header('Location: ad_cinemas.php');
        exit;
    } else {
        $errors[] = "DB error: " . $conn->error;
        $stmt->close();
    }
}
?>

<div class="content">
    <h2 style="margin-bottom:20px; color:#333;">Edit Cinema</h2>

    <?php if (!empty($errors)): ?>
        <div style="background:#fee;padding:10px;border:1px solid #f99;margin-bottom:15px;border-radius:6px;">
            <?php foreach ($errors as $e) echo "<div>" . htmlspecialchars($e) . "</div>"; ?>
        </div>
    <?php endif; ?>

    <div class="card" style="max-width:800px; padding:25px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.15);">
        <form method="post" enctype="multipart/form-data">
            <label>Cinema Name</label><br>
            <input type="text" name="name" value="<?php echo htmlspecialchars($cinema['name']); ?>" required style="width:100%; padding:10px; margin-bottom:15px;"><br>

            <label>Features</label><br>
            <textarea name="features" rows="4" style="width:100%; padding:10px; margin-bottom:15px;"><?php echo htmlspecialchars($cinema['features']); ?></textarea><br>

            <label>Show Times</label><br>
            <input type="text" name="show_times" value="<?php echo htmlspecialchars($cinema['show_times']); ?>" style="width:100%; padding:10px; margin-bottom:15px;"><br>

            <label>Cancellation Policy</label><br>
            <input type="text" name="cancelation" value="<?php echo htmlspecialchars($cinema['cancelation']); ?>" style="width:100%; padding:10px; margin-bottom:15px;"><br>

            <button type="submit" style="background:#6a11cb; color:white; padding:10px 20px; border:none; border-radius:6px; font-size:16px; cursor:pointer;">Update Cinema</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>