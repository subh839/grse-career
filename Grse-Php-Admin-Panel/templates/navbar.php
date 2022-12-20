<?php


if(isset($_POST['logout'])){
    session_destroy();
    header('Location: '. ROOT_URL_CAREER);
}

?>

<nav class="navbar navbar-expand-lg navbar-dark w-100" style="background-color: #121b6a">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">CAREER Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarColor01">
      <ul class="navbar-nav me-auto">

      </ul>
      <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <button class="btn btn-secondary my-2 my-sm-0" name="logout" type="submit">Logout</button>
      </form>
    </div>
  </div>
</nav>