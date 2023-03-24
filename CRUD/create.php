<?php

  include_once("utils.php"); // Includo un file che contiene alcune funzioni utili come logged_in e redirect_to  
  session_start();           // Riga necessaria per poter attivare la session e leggere il suo contenuto

  if(!logged_in()) redirect_to("login.php"); // Se l'utente non è loggato, lo redirectiamo alla pagina di login

  $page = "create";                   // Identificatore univoco della pagina 
  $html_title = "Crea un nuovo POST"; // Titolo della pagina

  $options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
  ];

  $title = $description = $title_error = $description_error = "";

  if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recuperiamo i dati dalla richiesta
    $title = htmlspecialchars($_POST["title"]);
    $description = htmlspecialchars($_POST["description"]);
    // COntrolliamo che siano presenti e che soddisfino i nostri requisiti
    if(empty($title))  $title_error = "Campo obligatorio";
    if(empty($description)) $description_error = "Campo obligatorio";
    if(strlen($title) < 5 ) $title_error = "Campo è troppo corto";
    if(strlen($title) > 30 ) $title_error = "Campo è troppo lungo";
    // Se non ci sono errori
    if($title_error == "" && $description_error == "") {
      try { // Ci colleghiamo al DB e salviamo i dati all'interno di esso
        $pdo = new PDO("mysql:host=localhost;dbname=posts-likes;charset=utf8mb4;", "root", "", $options);
        $query = $pdo->prepare("INSERT INTO posts (title, description, user_id) VALUES (:title, :description, :user_id)");
        $query->bindParam(':title', $title);
        $query->bindParam(':description', $description);
        $query->bindParam(':user_id', $_SESSION["id"]);
        $query->execute();
        $_SESSION["success"] = "Post creato con successo";
        redirect_to("list.php");
      } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
      }
    }
  }

  // Inclusione della porzione iniziale della pagina, compresa di menus
  include_once("header.php");

?>

  <div class="container">
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="title" class="form-label">Titolo del post</label>
        <input  class="form-control <?php if($title_error) echo "is-invalid" ?>" type="text" id="title" name="title" placeholder="title" value="<?php echo $title ?>" aria-describedby="titleerror">
        <div class="invalid-feedback"><?php echo $title_error ?></span>
      <div id="emailHelp" class="form-text"></div>
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Contenuto del post</label>
        <textarea  class="form-control <?php if($description_error) echo "is-invalid" ?>" name="description" id="description" cols="30" rows="10" placeholder ="description" aria-describedby="descerror" ><?php echo $description ?></textarea>
        <div class="invalid-feedback"><?php echo $description_error ?></span>
      </div>
      <button type="submit" class="btn btn-primary mt-4">Submit</button>
    </form>
  </div>

</body>
</html>