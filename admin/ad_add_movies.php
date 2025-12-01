

<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

// admin/add_movie.php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit;
}
include '../db_connect.php';
include 'header.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simple inputs
    // $movie_name = trim($_POST['movie_name'] ?? '');
    // $language = trim($_POST['language'] ?? '');
    // $cert = trim($_POST['certification'] ?? '');
    // $price = intval($_POST['movie_price'] ?? 0);
    // $release = $_POST['release_date'] ?? null;
    // $available_in = $_POST['available_in'] ?? '';
    // $duration = $_POST['duration'] ?? '';
    // $type = $_POST['type'] ?? '';
    // $description = $_POST['description'] ?? '';
    // $cast = $_POST['cast'] ?? '';
    // $trailer = $_POST['trailer'] ?? '';
    // $gradient = $_POST['gradient'] ?? '';   

    $movie_name = trim($_POST['movie_name']);
    $movie_img = $_POST['movie_img'];
    $available_in = $_POST['available_in'];
    $language = trim($_POST['language']);
    $duration = $_POST['duration'];
    $type = $_POST['type'];
    $cert = trim($_POST['certification']);
    $release = $_POST['release_date'];
    $price = intval($_POST['movie_price']);
    $description = $_POST['description'];
    $cast = $_POST['cast'];
    $cast_img = $_POST['cast_img'];
    $trailer = $_POST['trailer'];
    // $gradient = $_POST['gradient'];
    $gradient = "vasu";

    // $movie_img = "leo.avif";

    // validate
    if ($movie_name === '') $errors[] = "Movie name is required.";

    // Insert data into database
    $sql = "INSERT INTO movies (movie_name, movie_img, available_in, language, duration, type, certification, release_date, movie_price, description, `cast`, cast_img, trailer, gradient) 
            VALUES ('$movie_name', '$movie_img', '$available_in', '$language', '$duration', '$type', '$cert', '$release', '$price', '$description', '$cast', '$cast_img', '$trailer', '$gradient')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Movie Added Successful!'); window.location.href='ad_add_movies.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();

    // handle image upload
    // $uploaded_filename = '';
    // if (!empty($_FILES['movie_img']['name'])) {
    //     $file = $_FILES['movie_img'];
    //     $allowed = ['jpg','jpeg','png','webp','avif'];
    //     $maxSize = 2 * 1024 * 1024; // 2MB

    //     $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    //     if (!in_array($ext, $allowed)) $errors[] = "Poster must be jpg, png, webp or avif.";
    //     if ($file['size'] > $maxSize) $errors[] = "Poster must be <= 2MB.";

    //     if (empty($errors)) {
    //         $newName = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    //         $dest = __DIR__ . '/../uploads/movies/' . $newName;
    //         if (!move_uploaded_file($file['tmp_name'], $dest)) {
    //             $errors[] = "Failed to move uploaded poster.";
    //         } else {
    //             $uploaded_filename = $newName;
    //         }
    //     }
    // }

    // if (empty($errors)) {
    //     $stmt = $conn->prepare("INSERT INTO movies (movie_name, movie_img, available_in, language, duration, type, certification, release_date, movie_price, description, cast, trailer, gradient) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    //     $stmt->bind_param('ssssssssdisss',
    //         $movie_name,
    //         $uploaded_filename,
    //         $available_in,
    //         $language,
    //         $duration,
    //         $type,
    //         $cert,
    //         $release,
    //         $price,
    //         $description,
    //         $cast,
    //         $trailer,
    //         $gradient
    //     );
        // Note: using 'd' for release? We bind release as string into 's' in a moment — to keep it simple, change types:
        // We'll redo bind to all strings and ints to avoid mismatch
        // $stmt->close();

        // simpler bind: all strings except price
        // $stmt = $conn->prepare("INSERT INTO movies (movie_name, movie_img, available_in, language, duration, type, certification, release_date, movie_price, description, cast, trailer, gradient) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        // $stmt->bind_param('ssssssssisss',
        //     $movie_name,
        //     $uploaded_filename,
        //     $available_in,
        //     $language,
        //     $duration,
        //     $type,
        //     $cert,
        //     $release,
        //     $price,
        //     $description,
        //     $cast,
        //     $trailer,
        //     $gradient
        // );
        // if ($stmt->execute()) {
        //     $stmt->close();
        //     header('Location: ad_movies.php');
        //     exit;
        // } else {
        //     $errors[] = "DB error: " . $conn->error;
        //     $stmt->close();
        // }
    // }
}
?>

<h2>Add Movie</h2>

<?php if (!empty($errors)): ?>
  <div style="background:#fee;padding:10px;border:1px solid #f99;">
    <?php foreach($errors as $e) echo "<div>" . htmlspecialchars($e) . "</div>"; ?>
  </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" style="margin-top:12px; max-width:800px;">
  <label>Movie Name</label><br>
  <input type="text" name="movie_name" required style="width:100%; padding:8px;"><br><br>

  <label>Poster (jpg/png/webp/avif, max 2MB)</label><br>
  <input type="text" name="movie_img"><br><br>
  <!-- <input type="text" name="movie_img" accept=".jpg,.jpeg,.png,.webp,.avif"><br><br> -->

  <label>Language</label><br>
  <input type="text" name="language" style="padding:8px;"><br><br>

  <label>Certification</label><br>
  <input type="text" name="certification" style="padding:8px;"><br><br>

  <label>Price (Rs)</label><br>
  <input type="number" name="movie_price" min="0" style="padding:8px;"><br><br>

  <label>Release Date</label><br>
  <input type="date" name="release_date" style="padding:8px;"><br><br>

  <label>Available In (e.g., 2D)</label><br>
  <input type="text" name="available_in" style="padding:8px;"><br><br>

  <label>Duration (e.g., 2h 10m)</label><br>
  <input type="text" name="duration" style="padding:8px;"><br><br>

  <label>Type</label><br>
  <input type="text" name="type" style="padding:8px;"><br><br>

  <label>Cast (comma separated)</label><br>
  <input type="text" name="cast" style="padding:8px;"><br><br>

  <label>Cast Img(comma separated)</label><br>
  <input type="text" name="cast_img" style="padding:8px;"><br><br>

  <label>Trailer URL</label><br>
  <input type="text" name="trailer" style="padding:8px;"><br><br>

  <label>Description</label><br>
  <textarea name="description" rows="6" style="width:100%; padding:8px;"></textarea><br><br>

  <button class="btn" type="submit">Add Movie</button>
</form>

<?php include 'footer.php'; ?>
