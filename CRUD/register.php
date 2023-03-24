
<?php 
  include_once("utils.php");            // Includi la funzione login e redirect_to

  $page = "register";                   // Identificatore univoco della pagina 
  $html_title = "Registrazione utente"; // Titolo della pagina

  $name = $last_name = $image = $email = $password = $verify_password = "";
  $name_error = $last_name_error = $image_error = $email_error = $password_error = $verify_password_error = "";

  if($_SERVER['REQUEST_METHOD'] == "POST") {

    if (!is_dir('images')) mkdir('images', 0777);

    if (isset($_FILES['image']) && ($_FILES['image']['name'] != "")) {
      $temp = $_FILES['image']['tmp_name'];
      $target_dir = "images/";
      $file = $_FILES['image']['name'];
      $path_info = pathinfo($file);
      $filename = $path_info['filename'];
      $ext = $path_info['extension'];
      $temp_name = $_FILES['image']['tmp_name'];
      $path_filename_ext = $target_dir . uniqid() . "." . $ext;
      move_uploaded_file($temp_name, $path_filename_ext);
      $image = htmlspecialchars(trim($path_filename_ext));
    }

    $name = htmlspecialchars($_POST["name"]);
    $last_name = htmlspecialchars($_POST["last-name"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);
    $verify_password = htmlspecialchars($_POST["verify_password"]);

    // Controlliamo che la password sia presente
    if(empty($password)) $password_error = "Questo campo è obbligatorio";

    // Controlliamo che verifica password sia presente
    if(empty($verify_password)) $verify_password_error = "Questo campo è obbligatorio";
    
    // Controlliamo che il nome sia presente
    if(empty($name)) $name_error = "Questo campo è obbligatorio";

    // Controlliamo che il nome sia presente
    if(empty($last_name)) $last_name_error = "Questo campo è obbligatorio";
    
    // Controlliamo che il nome sia presente
    if(empty($image)) $image_error = "Questo campo è obbligatorio";

    // Controlliamo che l'email sia presente
    if(empty($email)) $email_error = "Questo campo è obbligatorio";

    // Controlliamo che password e verifica password coincidano
    if($password != $verify_password) $verify_password_error = "Le password non coincidono";
    
    // Verifichiamo che la mail sia valida
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $email_error = "Questa non è una mail valida";

    // Se non ci sono errori, salviamo i dati nel DB
    if(!$verify_password_error && !$name_error && !$email_error) {
      $result = "La registrazione è andata a buon fine.";

      // Generiamo l'hash della password
      $password = password_hash($password, PASSWORD_DEFAULT);

      $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
      ];
      
      $pdo = new PDO("mysql:host=localhost;dbname=posts-likes;charset=utf8mb4;", "root", "", $options);
      $query = $pdo->prepare("INSERT INTO users(name, lastname, profile_image, email, password) VALUES (:name, :lastname, :image, :email, :password);");
      $query->bindParam(':name', $name);
      $query->bindParam(':lastname', $last_name);
      $query->bindParam(':image', $image);
      $query->bindParam(':email', $email);
      $query->bindParam(':password', $password);
      $query->execute();
      redirect_to("login.php");
    }
  }
  
  // Inclusione della porzione iniziale della pagina, compresa di menus
  include_once("header.php");
?>
    <div class="container">
      <form method="POST" enctype="multipart/form-data">
        <div class="form-group mb-2 has-validation">
          <label for="name">Nome:</label><br>
          <input type="text" class="form-control <?php if($name_error) echo "is-invalid" ?>" name="name" id="name" value="<?php echo $name ?>">
          <div class="invalid-feedback"><?php echo $name_error ?></div>
        </div>
        <div class="form-group mb-2 has-validation">
          <label for="last-name">Cognome:</label><br>
          <input type="text" class="form-control <?php if($last_name_error) echo "is-invalid" ?>" name="last-name" id="last-name" value="<?php echo $last_name ?>">
          <div class="invalid-feedback"><?php echo $last_name_error ?></div>
        </div>
        <div class="form-group mb-2 has-validation">
          <label for="email">Email:</label><br>
          <input type="text" class="form-control <?php if($email_error) echo "is-invalid" ?>" name="email" id="email" value="<?php echo $email ?>">
          <div class="invalid-feedback"><?php echo $email_error ?></div>
        </div>
        <div class="form-group mb-2 has-validation">
          <label for="image">Image:</label><br>
          <input type="file" class="form-control <?php if($image_error) echo "is-invalid" ?>" name="image" id="image" value="<?php echo $image ?>">
          <div class="invalid-feedback"><?php echo $image_error ?></div>
        </div>
        <div class="form-group mb-2 has-validation">  
          <label for="password">Password:</label><br>
          <input type="password" class="form-control <?php if($password_error) echo "is-invalid" ?>" name="password" id="password" value="<?php echo $password ?>">
          <div class="invalid-feedback"><?php echo $password_error ?></div>
        </div>
        <div class="form-group mb-2 has-validation">  
          <label for="verify_password">Verifica password:</label><br>
          <input type="password" class="form-control <?php if($verify_password_error) echo "is-invalid" ?>" name="verify_password" id="verify_password" value="<?php echo $verify_password ?>">
          <div class="invalid-feedback"><?php echo $verify_password_error ?></div>
        </div>
        <div class="form-group mb-2 has-validation">
          <input type="submit" class="btn btn-primary mt-3" value="Registrati">
        </div>
      </form>
    </div>
  </body>
</html>