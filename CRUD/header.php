<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $html_title ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <style>
    .post {
      width: 100%;
      max-width: 20em;
    }

    .personal-information {
      padding: 0.5em;
      border: 1px solid grey;
      width: 13em;
      border-radius: 10px;
    }
  </style>
</head>

<body class="container">
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0 ">
            <li class="nav-item">
              <a class="nav-link <?php if ($page == "create") echo "active"; ?>" aria-current="page" href="create.php">Create</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php if ($page == "list") echo "active"; ?>" href="list.php">List</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php if ($page == "list") echo "active"; ?>" href="user.php">User</a>
            </li>
            <?php if (!isset($_SESSION["logged"])) { ?>
              <li class="nav-item">
                <a class="nav-link <?php if ($page == "register") echo "active"; ?>" href="register.php">Register</a>
              </li>
            <?php } ?>
          </ul>
          <p class="mt-3 me-3"><?php if (logged_in()) echo $_SESSION["name"] ?></p>
          <form method="POST" class="d-flex">
            <input class="form-control me-2" name="searchbar" type="search" placeholder="Search" aria-label="Search">
            <input class="btn btn-outline-success" type="submit" value="search">
          </form>
          <?php if (isset($_SESSION["logged"])) { ?>
            <a href="logout.php" class="btn btn-sm btn-primary">log out</a>
          <?php } else { ?>
            <a href="login.php" class="btn btn-sm btn-primary">log in</a>
          <?php } ?>
        </div>
      </div>
    </nav>
    <?php if (isset($_SESSION["success"]) && $_SESSION["success"]) { ?>
      <br>
      <div class="alert alert-success" role="alert">
        <?php
        echo $_SESSION["success"];
        $_SESSION["success"] = "";
        ?>
      </div>
    <?php } ?>
    <h1><?php echo $html_title ?></h1>
  </div>