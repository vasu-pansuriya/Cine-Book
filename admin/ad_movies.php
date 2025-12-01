<?php
// admin/movies.php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit;
}

include '../db_connect.php'; // make sure this sets $conn
include 'header.php'; // your admin header with topbar

// Fetch movies
$movies = [];
$res = $conn->query("SELECT id, movie_name, movie_img, language, certification, movie_price, release_date FROM movies ORDER BY id DESC");
if ($res) {
    while ($row = $res->fetch_assoc()) $movies[] = $row;
    $res->free();
}
?>

<h1>Manage Movies</h1>

<p style="margin-top:8px;">
  <a href="ad_add_movies.php" class="btn">+ Add New Movie</a>
</p>

<table style="margin-top:12px;">
  <thead>
    <tr>
      <th>#</th>
      <th>Poster</th>
      <th>Title</th>
      <th>Lang</th>
      <th>Cert</th>
      <th>Price</th>
      <th>Release</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($movies)): ?>
      <tr><td colspan="8">No movies found.</td></tr>
    <?php else: foreach($movies as $m): ?>
      <tr>
        <td><?php echo htmlspecialchars($m['id']); ?></td>
        <td>
          <?php if (!empty($m['movie_img']) && file_exists(__DIR__ . '/../uploads/movies/' . $m['movie_img'])): ?>
            <img src="<?php echo '../uploads/movies/' . rawurlencode($m['movie_img']); ?>" alt="" style="height:60px; border-radius:4px;">
          <?php else: ?>
            <div style="height:60px; width:40px; background:#eee; display:inline-block;"></div>
          <?php endif; ?>
        </td>
        <td><?php echo htmlspecialchars($m['movie_name']); ?></td>
        <td><?php echo htmlspecialchars($m['language']); ?></td>
        <td><?php echo htmlspecialchars($m['certification']); ?></td>
        <td><?php echo htmlspecialchars($m['movie_price']); ?></td>
        <td><?php echo htmlspecialchars($m['release_date']); ?></td>
        <td>
          <a href="ad_edit_movies.php?id=<?php echo $m['id']; ?>">Edit</a> |
          <a href="ad_delete_movies.php?id=<?php echo $m['id']; ?>" onclick="return confirm('Delete this movie?')">Delete</a>
        </td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>

<?php include 'footer.php'; ?>
