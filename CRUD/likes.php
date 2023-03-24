<?php
  include_once("utils.php"); // Includo un file che contiene alcune funzioni utili come logged_in e redirect_to  
  session_start();           // Riga necessaria per poter attivare la session e leggere il suo contenuto

  if(!logged_in()) redirect_to("login.php"); // Se l'utente non è loggato, lo redirectiamo alla pagina di login


    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ];

    
  $id = htmlspecialchars(trim($_POST["id"]));
  $user_id = htmlspecialchars(trim($_SESSION['id']));
  $pdo = new PDO("mysql:host=localhost;dbname=posts-likes;charset=utf8mb4;", "root", "", $options);
  $query = $pdo->prepare("INSERT INTO liked_post(post_id, user_id) VALUES (:post_id, :user_id);");
  $query->bindParam(':post_id', $id);
  $query->bindParam(':user_id', $user_id);
  $query->execute();   
  $_SESSION["success"] = "Aggiunto a 'Mi Piace'"; 
  header('Location: list.php');
  exit;


?>