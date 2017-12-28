<header class="header white-bg">
  <!-- <div class="sidebar-toggle-box">
      <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
  </div> -->
    <!--logo start-->
    <!-- <a href="index.html" class="logo">GFOOD<span>INVENTORY</span></a> -->
    <a href="index.php">
        <img src="img/GFood_logo.png" class="main_logo" />
    </a>
    <!--logo end-->
    <div class="top-nav">
        <ul class="nav pull-right top-menu">
            <li class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#" style="padding: 10px;">
                    
                    <span class="username"><?php if(isset($_SESSION['user_name'])){echo($_SESSION['user_name']);} ?></span>
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu extended logout">
                    <div class="log-arrow-up"></div>
                    <li><a href="logout.php"><i class="fa fa-key"></i> Log Out</a></li>
                </ul>
            </li>
            <!-- user login dropdown end -->
        </ul>
        <!--search & user info end-->
    </div>
</header>