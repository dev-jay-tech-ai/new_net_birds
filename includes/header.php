<?php 
  require_once 'php_action/core.php';

  if (isset($_SESSION['userId'])) {
    $user_id = $_SESSION['userId'];
    $sql = "SELECT * FROM users WHERE user_id = {$user_id}";
    $query = $connect->query($sql);
    $result = $query->fetch_assoc();
    $initial = strtoupper(mb_substr($result['username'], 0, 1, 'UTF-8'));
  }
?>

<!DOCTYPE html>
  <html>
  <head>
	  <title>Newnetbirds</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- bootstrap -->
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <!-- bootstrap theme-->
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-theme.min.css">
    <!-- font awesome -->
    <script src="https://kit.fontawesome.com/07903077e4.js" crossorigin="anonymous"></script>

    <!-- custom css -->
    <link rel="stylesheet" href="custom/css/custom.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="assets/plugins/datatables/jquery.dataTables.min.css">

    <!-- file input -->
    <link rel="stylesheet" href="assets/plugins/fileinput/css/fileinput.min.css">

    <!-- jquery -->
    <script src="assets/jquery/jquery.min.js"></script>
    <!-- jquery ui -->  
    <link rel="stylesheet" href="assets/jquery-ui/jquery-ui.min.css">
    <script src="assets/jquery-ui/jquery-ui.min.js"></script>

    <!-- bootstrap js -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;600;700&family=Lato:wght@400;700&family=Montserrat&family=Ubuntu:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/copperplate" rel="stylesheet">

    <!-- include summernote css/js-->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://app.enzuzo.com/apps/enzuzo/static/js/__enzuzo-cookiebar.js?uuid=106ed868-8917-11ee-958e-5fed1e6d4d07"></script>
   
    <!-- Include image-compressor.js -->
    <script src="assets/plugins/compressor.js"></script>

  </head>
<body class='d-flex flex-column min-vh-100'>
	<nav class="navbar navbar-static-top">
    <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <div class="logo">
        <a href='/dashboard.php'>
          <img class='logo_img' src='/assets/images/logo/logo_w.png' alt='logo' />
        </a>
      </div>
    </div>
    <div class="collapse navbar-collapse in" id="navbar-collapse-1">      
      <ul class="nav navbar-nav">               
        <li id="navAgent"><a href="agent.php">Club</a></li>        
        <li id="navPrivate"><a href="private.php">Private</a></li>        
        <li id="navReivew"><a href="review.php">Review</a></li>       
        <li id="navContact"><a href="notice.php">Notice</a></li> 
      <?php
        if ($result['status'] == 1) {
        // Display this link only when the user is not logged in
        echo '<li id="navMember"><a href="users.php">Member</a></li>';
        }
      ?>
      </ul>
      <ul id="logged-in-menu" class="nav navbar-nav navbar-right">
      <?php
        if (isset($_SESSION['userId'])) {
          // Display this link only when the user is not logged in
          echo '
            <li id="user_label" class="dropdown">
              <label for="user-control-list-check" class="dropdown-toggle" data-toggle="dropdown" id="user_dropdown" title="" role="button" data-original-title="Profile">
                <span class="avatar  avatar-md avatar-rounded" alt="' . $result['username'] . '" title="' . $result['username'] . '" data-uid="2438" loading="lazy" component="avatar/icon" style="background-color: #c65ac2;"><font style="vertical-align: inherit;">' . $initial . '</font></span>
                <span id="user-header-name" class="visible-xs-inline"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">' . $result['username'] . '</font></font></span>
              </label>
              <input type="checkbox" class="hidden" id="user-control-list-check" aria-hidden="true">
              <ul id="user-control-list" component="header/usercontrol" class="dropdown-menu" aria-labelledby="user_dropdown">
            <li>
              <a component="header/profilelink" href="/profile.php?username=' . $result['username'] . '">
                <i component="user/status" class="fa fa-fw fa-circle status online"></i> 
                <span component="header/username"><font style="vertical-align: inherit;">
                <font style="vertical-align: inherit;">
                ' . $result['username'] . '
                </font></font></span>
              </a>
            </li>
            <li role="presentation" class="divider"></li>
            <li>
              <a component="header/profilelink/edit" href="/editProfile.php?username=' . $result['username'] . '">
              <i class="fa fa-fw fa-edit"></i> <span><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Edit Profile</font></font></span>
              </a>
            </li>
            <li role="presentation" class="divider"></li>
            <li component="user/logout">
              <a href="logout.php">Log out</a>
            </li>
          </ul>
          </li>';
        }  else  {
          // Display this link only when the user is not logged in
          echo '<li id="navLogin"><a href="login.php">Login</a></li>';
        }
      ?>
      </ul>
    </div><!-- /.navbar-collapse -->
  
    </div><!-- /.container-fluid -->
	</nav>