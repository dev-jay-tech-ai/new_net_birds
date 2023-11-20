<?php 
  require_once 'php_action/core.php';

  if (isset($_SESSION['userId'])) {
    $user_id = $_SESSION['userId'];
    $sql = "SELECT * FROM users WHERE user_id = {$user_id}";
    $query = $connect->query($sql);
    $result = $query->fetch_assoc();
    $initial = strtoupper(substr($result['username'], 0, 1));
  }
?>


<!DOCTYPE html>
  <html>
  <head>

	  <title>Newnetbirds</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- bootstrap -->
    <link rel="stylesheet" href="assests/bootstrap/css/bootstrap.min.css">
    <!-- bootstrap theme-->
    <link rel="stylesheet" href="assests/bootstrap/css/bootstrap-theme.min.css">
    <!-- font awesome -->
    <script src="https://kit.fontawesome.com/07903077e4.js" crossorigin="anonymous"></script>

    <!-- custom css -->
    <link rel="stylesheet" href="custom/css/custom.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="assests/plugins/datatables/jquery.dataTables.min.css">

    <!-- file input -->
    <link rel="stylesheet" href="assests/plugins/fileinput/css/fileinput.min.css">

    <!-- jquery -->
    <script src="assests/jquery/jquery.min.js"></script>
    <!-- jquery ui -->  
    <link rel="stylesheet" href="assests/jquery-ui/jquery-ui.min.css">
    <script src="assests/jquery-ui/jquery-ui.min.js"></script>

    <!-- bootstrap js -->
    <script src="assests/bootstrap/js/bootstrap.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;600;700&family=Lato:wght@400;700&family=Montserrat&family=Ubuntu:wght@400;500&display=swap" rel="stylesheet">

    <link href="https://fonts.cdnfonts.com/css/copperplate" rel="stylesheet">

    <!-- Cookie Consent by FreePrivacyPolicy.com https://www.FreePrivacyPolicy.com -->
    <script type="text/javascript" src="//www.freeprivacypolicy.com/public/cookie-consent/4.1.0/cookie-consent.js" charset="UTF-8"></script>
    <script type="text/javascript" charset="UTF-8">
    document.addEventListener('DOMContentLoaded', function () {
    cookieconsent.run({"notice_banner_type":"interstitial","consent_type":"express","palette":"light","language":"en","page_load_consent_levels":["strictly-necessary"],"notice_banner_reject_button_hide":false,"preferences_center_close_button_hide":false,"page_refresh_confirmation_buttons":false,"website_name":"Newnetbirds"});
    });
    </script>

    <noscript>Cookie Consent by <a href="https://www.freeprivacypolicy.com/">Free Privacy Policy Generator</a></noscript>
    <!-- End Cookie Consent by FreePrivacyPolicy.com https://www.FreePrivacyPolicy.com -->
    <!-- Below is the link that users can use to open Preferences Center to change their preferences. Do not modify the ID parameter. Place it where appropriate, style it as needed. -->

    <!-- include summernote css/js-->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

  </head>
<body>
	<nav class="navbar navbar-static-top">
		<div class="container">  
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <!-- <a class="navbar-brand" href="#">Brand</a> -->
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">      
        <div class="nav navbar-nav navbar-left logo">
          <a href='/newnetbirds/dashboard.php'>
            <img class='logo_img' src='/newnetbirds/assets/images/logo/logo.png' alt='logo' />
            Newnetbirds
          </a>
        </div>
        <?php
          if (isset($_SESSION['userId'])) {
            // Display this link only when the user is not logged in
            echo '
            <ul id="logged-in-menu" class="nav navbar-nav navbar-right">
              <li class="notifications dropdown text-center hidden-xs" component="notifications">
              <a href="/notifications" title="" class="dropdown-toggle" data-toggle="dropdown" id="notif_dropdown" data-ajaxify="false" role="button" data-original-title="Notifications">
              <i component="notifications/icon" class="fa fa-fw fa-bell-o unread-count" data-content="0"></i>
              </a>
                <ul class="dropdown-menu" aria-labelledby="notif_dropdown">
                  <li>
                    <ul component="notifications/list" class="notification-list">
                      <li class="loading-text">
                      <a href="#"><i class="fa fa-refresh fa-spin"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">알림 로드 중</font></font></a>
                      </li>
                    </ul>
                  </li>
                  <li class="notif-dropdown-link">
                    <div class="btn-group btn-group-justified">
                      <a role="button" href="#" class="btn btn-secondary mark-all-read"><i class="fa fa-check-double"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">모두 읽은 것으로 표시</font></font></a>
                      <a class="btn btn-secondary" href="/notifications"><i class="fa fa-list"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">모든 알림</font></font></a>
                      </div>
                    </li>
                    </ul>
                  </li>
                  <li class="chats dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="/user/dev-jay-tech-ai/chats" title="" id="chat_dropdown" component="chat/dropdown" data-ajaxify="false" role="button" data-original-title="Chats">
                    <i component="chat/icon" class="fa fa-comment-o fa-fw unread-count" data-content="0"></i> <span class="visible-xs-inline"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">채팅</font></font></span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="chat_dropdown">
                      <li>
                        <ul component="chat/list" class="chat-list chats-list">
                          <li class="loading-text">
                            <a href="#"><i class="fa fa-refresh fa-spin"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">채팅 로드 중</font></font></a>
                          </li>
                        </ul>
                      </li>
                      <li class="notif-dropdown-link">
                      <div class="btn-group btn-group-justified">
                        <a class="btn btn-secondary mark-all-read" href="#" component="chats/mark-all-read"><i class="fa fa-check-double"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">모두 읽은 것으로 표시</font></font></a>
                        <a class="btn btn-secondary" href="/user/' . $result['username'] . '/chats"><i class="fa fa-comments"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">모든 채팅</font></font></a>
                      </div>
                      </li>
                    </ul>
                  </li>
                  <li id="user_label" class="dropdown">
                    <label for="user-control-list-check" class="dropdown-toggle" data-toggle="dropdown" id="user_dropdown" title="" role="button" data-original-title="Profile">
                      <span class="avatar  avatar-md avatar-rounded" alt="' . $result['username'] . '" title="' . $result['username'] . '" data-uid="2438" loading="lazy" component="avatar/icon" style="background-color: #f44336;"><font style="vertical-align: inherit;">' . $initial . '</font></span>
                      <span id="user-header-name" class="visible-xs-inline"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">' . $result['username'] . '</font></font></span>
                    </label>
                    <input type="checkbox" class="hidden" id="user-control-list-check" aria-hidden="true">
                    <ul id="user-control-list" component="header/usercontrol" class="dropdown-menu" aria-labelledby="user_dropdown">
                  <li>
                    <a component="header/profilelink" href="/user/' . $result['username'] . '">
                      <i component="user/status" class="fa fa-fw fa-circle status online"></i> 
                      <span component="header/username"><font style="vertical-align: inherit;">
                      <font style="vertical-align: inherit;">
                      ' . $result['username'] . '
                      </font></font></span>
                    </a>
                  </li>
                  <li role="presentation" class="divider"></li>
                  <li>
                    <a href="#" class="user-status" data-status="online">
                    <i class="fa fa-fw fa-circle status online"></i><span class="bold"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">온라인</font></font></span>
                    </a>
                  </li>
                  <li>
                    <a href="#" class="user-status" data-status="away">
                    <i class="fa fa-fw fa-circle status away"></i><span><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">떨어져 있는</font></font></span>
                    </a>
                  </li>
                  <li>
                    <a href="#" class="user-status" data-status="dnd">
                    <i class="fa fa-fw fa-circle status dnd"></i><span><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">방해하지 마</font></font></span>
                    </a>
                  </li>
                  <li>
                    <a href="#" class="user-status" data-status="offline">
                    <i class="fa fa-fw fa-circle status offline"></i><span><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">보이지 않는</font></font></span>
                  </a>
                  </li>
                  <li role="presentation" class="divider"></li>
                  <li>
                    <a component="header/profilelink/edit" href="/user/dev-jay-tech-ai/edit">
                    <i class="fa fa-fw fa-edit"></i> <span><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">프로필 수정</font></font></span>
                    </a>
                  </li>
                  <li>
                    <a component="header/profilelink/settings" href="/user/dev-jay-tech-ai/settings">
                    <i class="fa fa-fw fa-gear"></i> <span><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">설정</font></font></span>
                    </a>
                  </li>
                  <li role="presentation" class="divider"></li>
                  <li component="user/logout">
                    <a href="logout.php">Log out</a>
                  </li>
                </ul>
              </li>
            </ul>';
        }
        ?>
        <ul class="nav navbar-nav navbar-left">        
          <li id="navDashboard"><a href="index.php">Dashboard</a></li>        
          <li id="navBrand"><a href="agent.php">Agent</a></li>        
          <li id="navPrivate"><a href="private.php">Private</a></li>        
          <li id="navReivew"><a href="review.php">Review</a></li>       
          <li id="navContact"><a href="contact.php">Contact</a></li> 
        </ul>
        <ul class="nav navbar-nav navbar-right"> 
        <?php
          if ($result['status'] == 1) {
            // Display this link only when the user is not logged in
            echo '<li id="navMember"><a href="users.php">Member</a></li> ';
          }
  
          if (!isset($_SESSION['userId'])) {
            // Display this link only when the user is not logged in
            echo '<li id="navLogin"><a href="login.php">Login</a></li>';
          }
        ?>
        </ul>
      </div><!-- /.navbar-collapse -->
    
    </div><!-- /.container-fluid -->
	</nav>

	<div>