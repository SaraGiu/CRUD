<?php 
  
  // Funzione che controlla se l'utente è loggato
  function logged_in() {
    return isset($_SESSION["logged"]) && $_SESSION["logged"] === true;
  }

  // Funzione che effettua un redirect a un determinato URL ricevuto come parametro
  function redirect_to($url) {
    header("location: " . $url);
    exit;
  }

?>