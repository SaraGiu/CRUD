<?php
  include_once("utils.php"); // Includo un file che contiene alcune funzioni utili come logged_in e redirect_to
  
  session_start();       // Riga necessaria per poter attivare la session e leggere il suo contenuto

  $page = "login";       // Identificatore univoco della pagina 
  $html_title = "Login"; // Titolo della pagina

  $email = $password = $email_error = $password_error = "";

  if(logged_in()) redirect_to("list.php"); // Se l'utente è loggato lo redirectiamo alla pagina list.php 
  else if($_SERVER["REQUEST_METHOD"] == "POST") { // Se non è loggato e ha sottoscritto il form...
    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false
    ];
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);
    
    if(empty($email)) $email_error = "Campo obbligatorio";
    if(empty($password)) $password_error = "Campo obbligatorio";

    // Trovare l'utente che ha la password inserita nel campo email

    $pdo = new PDO("mysql:host=localhost;dbname=posts-likes;charset=utf8mb4;", "root", "", $options);
    $query = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $query->bindParam(":email", $email);
    $query->execute();
    
    $result = $query->fetch();
    // Se si effettuiamo il login
    if($result) {
      // Confrontiamo la password digitata dall'utente con l'hash salvato nel db 
      if(password_verify($password, $result["password"])) {
        $_SESSION["logged"] = true;
        $_SESSION["name"] = $result["name"];
        $_SESSION["id"] = $result["id"];
        $_SESSION["email"] = $result["email"];
        // Redirezioniamo l'utente verso la lista dei post
        redirect_to("create.php");
      } else $password_error = "Password errata";
    } else $email_error = "Email errata.";
    
  }

  // Inclusione della porzione iniziale della pagina, compresa di menus
  include_once("header.php");

?>
<div class="container mt-5">
  <form method="POST">
    <div class="form-group mb-2 has-validation">
      <label for="email" class="form-label">Email</label>
      <input type="email" name="email" id="email" class="form-control <?php if($email_error) echo "is-invalid" ?>" value="<?php echo $email ?>">
      <div class="invalid-feedback"><?php echo $email_error ?></div>
    </div>
    <div class="form-group mb-2 has-validation">
      <label for="password" class="form-label">Password</label>
      <input type="password" name="password" id="password" class="form-control <?php if($password_error) echo "is-invalid" ?>" value="<?php echo $password ?>">
      <div class="invalid-feedback"><?php echo $password_error ?></span>
    </div>
    <div class="form-group">
      <input type="submit" class="btn btn-primary mt-2" value="Login">
    </div>
  </form>
</div>
</body>

</html>