<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
// admin/edit_movie.php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit;
}
include '../db_connect.php';
include 'header.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    echo "<p>Missing id</p>";
    include 'footer.php';
    exit;
}

// fetch movie
$stmt = $conn->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$movie = $res->fetch_assoc();
$stmt->close();
if (!$movie) {
    echo "<p>Movie not found.</p>";
    include 'footer.php';
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movie_name = trim($_POST['movie_name'] ?? '');
    $movie_img = $_POST['movie_img'] ?? '';
    $language = trim($_POST['language'] ?? '');
    $cert = trim($_POST['certification'] ?? '');
    $price = intval($_POST['movie_price'] ?? 0);
    $release = $_POST['release_date'] ?? null;
    $available_in = $_POST['available_in'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $type = $_POST['type'] ?? '';
    $description = $_POST['description'] ?? '';
    $cast = $_POST['cast'] ?? '';
    $cast_img = $_POST['cast_img'] ?? '';
    $trailer = $_POST['trailer'] ?? '';
    $gradient = "vasu";
    // $gradient = $_POST['gradient'] ?? '';

    // // handle new poster if uploaded
    // $new_filename = $movie['movie_img'];
    // if (!empty($_FILES['movie_img']['name'])) {
    //     $file = $_FILES['movie_img'];
    //     $allowed = ['jpg','jpeg','png','webp','avif'];
    //     $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    //     $maxSize = 2 * 1024 * 1024;
    //     if (!in_array($ext, $allowed)) $errors[] = "Poster must be jpg, png, webp or avif.";
    //     if ($file['size'] > $maxSize) $errors[] = "Poster must be <= 2MB.";
    //     if (empty($errors)) {
    //         $newName = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    //         $dest = __DIR__ . '/../uploads/movies/' . $newName;
    //         if (!move_uploaded_file($file['tmp_name'], $dest)) {
    //             $errors[] = "Failed to move uploaded poster.";
    //         } else {
    //             // delete old file if exists
    //             if (!empty($movie['movie_img']) && file_exists(__DIR__ . '/../uploads/movies/' . $movie['movie_img'])) {
    //                 @unlink(__DIR__ . '/../uploads/movies/' . $movie['movie_img']);
    //             }
    //             $new_filename = $newName;
    //         }
    //     }
    // }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE movies SET movie_name=?, movie_img=?, available_in=?, language=?, duration=?, type=?, certification=?, release_date=?, movie_price=?, description=?, cast=?, cast_img=?, trailer=?, gradient=? WHERE id=?");
        $stmt->bind_param(
    'ssssssssisssssi',
    $movie_name,
    $movie_img,     // <-- YOU MISSED THIS EARLIER
    $available_in,
    $language,
    $duration,
    $type,
    $cert,
    $release,
    $price,
    $description,
    $cast,
    $cast_img,
    $trailer,
    $gradient,
    $id
);

        if ($stmt->execute()) {
            $stmt->close();
            header('Location: ad_movies.php');
            exit;
        } else {
            $errors[] = "DB error: " . $conn->error;
            $stmt->close();
        }
    }
}
?>

<h2>Edit Movie</h2>

<?php if (!empty($errors)): ?>
  <div style="background:#fee;padding:10px;border:1px solid #f99;">
    <?php foreach($errors as $e) echo "<div>" . htmlspecialchars($e) . "</div>"; ?>
  </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" style="max-width:800px;">
  <label>Movie Name</label><br>
  <input type="text" name="movie_name" value="<?php echo htmlspecialchars($movie['movie_name']); ?>" required style="width:100%; padding:8px;"><br><br>

  <label>Current Poster</label><br>
  

  <label>Replace Poster (optional)</label><br>
  <!-- <input type="file" name="movie_img" accept=".jpg,.jpeg,.png,.webp,.avif"><br><br> -->
  <input type="Text" name="movie_img" accept=".jpg,.jpeg,.png,.webp,.avif"><br><br>


  <label>Language</label><br>
  <input type="text" name="language" value="<?php echo htmlspecialchars($movie['language']); ?>" style="padding:8px;"><br><br>

  <label>Certification</label><br>
  <input type="text" name="certification" value="<?php echo htmlspecialchars($movie['certification']); ?>" style="padding:8px;"><br><br>

  <label>Price (Rs)</label><br>
  <input type="number" name="movie_price" value="<?php echo htmlspecialchars($movie['movie_price']); ?>" min="0" style="padding:8px;"><br><br>

  <label>Release Date</label><br>
  <input type="date" name="release_date" value="<?php echo htmlspecialchars($movie['release_date']); ?>" style="padding:8px;"><br><br>

  <label>Available In</label><br>
  <input type="text" name="available_in" value="<?php echo htmlspecialchars($movie['available_in']); ?>" style="padding:8px;"><br><br>

  <label>Duration</label><br>
  <input type="text" name="duration" value="<?php echo htmlspecialchars($movie['duration']); ?>" style="padding:8px;"><br><br>

  <label>Cast</label><br>
  <input type="text" name="cast" value="<?php echo htmlspecialchars($movie['cast']); ?>" style="padding:8px;"><br><br>

  <label>Cast Img</label><br>
  <input type="text" name="cast_img" value="<?php echo htmlspecialchars($movie['cast_img']); ?>" style="padding:8px;"><br><br>

  <label>Trailer URL</label><br>
  <input type="text" name="trailer" value="<?php echo htmlspecialchars($movie['trailer']); ?>" style="padding:8px;"><br><br>

  <label>Description</label><br>
  <textarea name="description" rows="6" style="width:100%; padding:8px;"><?php echo htmlspecialchars($movie['description']); ?></textarea><br><br>

  <button class="btn" type="submit">Save Changes</button>
</form>

<?php include 'footer.php'; ?>
