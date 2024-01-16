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
    <!-- jquery -->
    <script src="assets/jquery/jquery.min.js"></script>
    <!-- jquery ui -->  
    <link rel="stylesheet" href="assets/jquery-ui/jquery-ui.min.css">
    <script src="assets/jquery-ui/jquery-ui.min.js"></script>

    <!-- bootstrap js -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>

    <!-- custom css -->
    <link rel="stylesheet" href="custom/css/custom.css?v=<?php echo time(); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;600;700&family=Lato:wght@400;700&family=Montserrat&family=Ubuntu:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/copperplate" rel="stylesheet">
    <script src="assets/plugins/compressor.js"></script>
  </head>
<body class='d-flex flex-column min-vh-100'>
	<nav class="navbar navbar-static-top">
    <div class="container-fluid">
    <div class="navbar-header">
      <div class="logo">
        <a href='/dashboard.php'>
          <img class='logo_img' src='assets/images/logo/logo_w.png' alt='logo' />
        </a>
      </div>
    </div>
    <div class="collapse navbar-collapse in" id="navbar-collapse-1">      
      <ul class="nav navbar-nav">               
        <li id="navAgent"><a href="list.php?code=agent">伴游机构</a></li>        
        <li id="navPrivate"><a href="list.php?code=private">私密约会</a></li>        
        <li id="navReivew"><a href="list.php?code=review">评论区</a></li> 
        <li id="navJobs"><a href="list.php?code=jobs">招聘求职</a></li> 
        <li id="navProperty"><a href="list.php?code=property">房屋出租</a></li>       
        <li id="navContact"><a href="notice.php">网站声明</a></li> 
      <?php
        if ($result['status'] == 1) {
        echo 
        '<li id="navMember"><a href="users.php">Member</a></li>
         <li id="navMember"><a href="manage.php">Manage</a></li>';
        }
      ?>
      </ul>
      <ul id="logged-in-menu" class="nav navbar-nav navbar-right">
      <?php
        if (isset($_SESSION['userId'])) {
          echo '
            <li id="user_label" class="dropdown">
              <label for="user-control-list-check" class="dropdown-toggle" data-toggle="dropdown" id="user_dropdown" title="" role="button" data-original-title="Profile">
                <span class="avatar  avatar-md avatar-rounded" alt="' . $result['username'] . '" title="' . $result['username'] . '" data-uid="2438" loading="lazy" component="avatar/icon" style="background-color: #c65ac2;"><font style="vertical-align: inherit;">' . $initial . '</font></span>
                <span id="user-header-name" class="visible-xs-inline"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">' . $result['username'] . '</font></font></span>
              </label>
              <input type="checkbox" class="hidden" id="user-control-list-check" aria-hidden="true">
              <ul id="user-control-list" component="header/usercontrol" class="dropdown-menu" aria-labelledby="user_dropdown">
            <li>
              <a component="header/profilelink" href="/account.php?username=' . $result['username'] . '">
                <span component="header/username"><font style="vertical-align: inherit;">
                <font style="vertical-align: inherit;">我的账户</font></font></span>
              </a>
            </li>
            <li role="presentation" class="divider"></li>
            <li>
              <a component="header/profilelink" href="/activity.php?username=' . $result['username'] . '">
                <span component="header/username"><font style="vertical-align: inherit;">
                <font style="vertical-align: inherit;">我的活动</font></font></span>
              </a>
            </li>
            <li role="presentation" class="divider"></li>
            <li>
              <a component="header/profilelink/edit" href="/editProfile.php?username=' . $result['username'] . '">
              <span><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">编辑用户</font></font></span>
              </a>
            </li>
            <li role="presentation" class="divider"></li>
            <li component="user/logout">
              <a href="logout.php">退出登录</a>
            </li>
          </ul>
          </li>';
        }  else  {
          echo '<li id="navLogin"><a href="login.php">会员登录</a></li>';
        }
      ?>
      </ul>
    </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
	</nav>