<?php
include_once("utils.php"); 
session_start();           

if(!logged_in()) redirect_to("login.php"); 


  $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false
  ];
  
  $id = htmlspecialchars(trim($_POST["id"]));
  $user_id = htmlspecialchars(trim($_SESSION['id']));
  $comment =  htmlspecialchars($_POST["comment"]);
  $pdo = new PDO("mysql:host=localhost;dbname=posts-likes;charset=utf8mb4;", "root", "", $options);

  if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(isset($_POST["comment"])){
        $query = $pdo->prepare("INSERT INTO comments(post_id, user_id, content) VALUES (:post_id, :user_id, :content);");
        $query->bindParam(':post_id', $id);
        $query->bindParam(':user_id', $user_id);
        $query->bindParam(':content', $comment);
        $query->execute();   
        $_SESSION["success"] = "Commento aggiunto con successo!"; 
        header('Location: list.php');
        exit;
    }
}
