<?php

include_once("utils.php");


// Leggere dal database tutti i record presenti nella tabella posts utilizzando PDO
// Fare un ciclo che sia in grado di stampare all'interno di una card di bootstrap tutti i post e le loro info
// Predisporre un bottone modifica e cancella per ogni post per permettere le azioni di Update e Delete

session_start();

$page = "list"; // Identificatore univoco della pagina 
$html_title = "Lista dei Post"; // Titolo della pagina

include_once("header.php");
$searchbar = "";

$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false
];

$pdo = new PDO("mysql:host=localhost;dbname=posts-likes;charset=utf8mb4;", "root", "", $options);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if (isset($_POST["searchbar"])) {
    $searchbar = htmlspecialchars(trim($_POST["searchbar"]));
    $query = $pdo->prepare("SELECT posts.id AS post_id, posts.title AS title, posts.description AS description, profile_image AS image, posts.user_id AS user_id_post, posts.created_at AS created_at, users.id AS user_id, users.name AS name FROM posts INNER JOIN users ON posts.user_id = users.id WHERE title LIKE '%$searchbar%' ORDER BY posts.created_at DESC;");
    $query->execute();
  }
}
if (!isset($query)) $query = $pdo->query("SELECT posts.id AS post_id, posts.title AS title, posts.description AS description, profile_image AS image, posts.user_id AS user_id_post, posts.created_at AS created_at, users.id AS user_id, users.name AS name FROM posts INNER JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC;");

// Inclusione della porzione iniziale della pagina, compresa di menus

?>

<div class="container mt-5 d-flex justify-content-around flex-wrap">
  <?php foreach ($query as $post) { ?>
    <?php
    $query2 = $pdo->prepare("SELECT * FROM `liked_post` WHERE post_id = :post_id AND user_id = :user_id;");
    $query2->bindParam(':user_id', $_SESSION['id']);
    $query2->bindParam(':post_id', $post['post_id']);
    $query2->execute();
    $result = $query2->rowCount();
    ?>
    <div class="card mt-3 p-2 post">
      <div class="card-body">
        <img src="<?php echo ($post['image']) ? $post['image'] : 'images/missing.jpg' ?>" class="card-img-top" alt="<?php echo $post['title'] ?>">
        <h3 class="card-title">
          <?php echo $post["title"]; ?>
        </h3>
        <p class="card-text">
          <?php echo $post["description"]; ?>
        </p>
        <small class="text-muted">
          <?php echo "Creato da: " . $post['name']; ?>
          <?php echo "il " . date('d/M/Y', strtotime($post["created_at"])); ?>
        </small>
        <form method="POST" action="<?php echo ($result > 0) ? "remove-liked.php" : "likes.php"; ?>">
          <!-- <?php if ($result) ?>  -->
          <input type="submit" value="<?php echo ($result > 0) ? "remove like" : "like"; ?>">
          <input type="hidden" value="<?php echo $post['post_id'] ?>" name="id">
        </form>
      </div>

      <div class="d-flex justify-content-center gap-2">
        <a href="show.php?id=<?php echo $post["post_id"]; ?>" class="btn btn-outline-success float-end">Visualizza</a>
        <?php if (logged_in() && $_SESSION["id"] == $post["user_id"] || $_SESSION["id"] == 5) { ?>
          <a href="delete.php?id=<?php echo $post["post_id"]; ?>" class="btn btn-outline-danger float-end me-1">Elimina</a>
          <a href="edit.php?id=<?php echo $post["post_id"]; ?>" class="btn btn-outline-warning float-end me-1">Modifica</a>
        <?php } ?>
      </div>
      <?php $comment_query = $pdo->prepare("SELECT * FROM comments, users WHERE post_id = :postid AND user_id = users.id");
      $comment_query->bindParam(":postid", $post["post_id"]);
      $comment_query->execute();
      foreach ($comment_query as $comment) { ?>
        <div>
          <div style="display:flex;">
            <img style="height:50px; width:50px;" src="<?php echo $comment["profile_image"]; ?>" alt="">
            <p><?php echo explode(" ",$comment["name"])[0]; ?></p>   <!-- in alternativa echo $comment["name"] -->
          </div>
          <p><?php echo $comment["content"]; ?></p>
          <small><?php echo $comment["created_at"]; ?></small>

        </div>
      <?php } ?>
      <form action="comment.php" method="POST" class="mt-3">

        <textarea type="text" name="comment"></textarea><br>
        <input type="submit" value="Invia"><br>
        <input type="hidden" value="<?php echo $post['post_id'] ?>" name="id">
      </form>

    </div>
  <?php } ?>
</div>
</body>

</html>