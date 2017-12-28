<aside>
  <div id="sidebar"  class="nav-collapse ">
    <!-- sidebar menu start-->
    <ul class="sidebar-menu" id="nav-accordion">
      <li>
        <a href="index.php">
          <i class="fa fa-dashboard"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <?php if($first_role==1){ ?>
      <li>
        <a href="transaction_manager.php">
          <i class="fa fa-tasks"></i>
          <span>Transaction Manager</span>
        </a>
      </li>
      <?php }?>
      <?php  if($second_role==1){ ?>
      <li>
        <a href="report.php">
          <i class="fa fa-file-text-o"></i>
          <span>Reports</span>
        </a>
      </li>
      <?php } ?>
      <?php  if($third_role==1){ ?>
      <li>
        <a href="stock_situation.php">
          <i class="fa fa-cubes"></i>
          <span>Stock Situation</span>
        </a>
      </li>
      <?php }?>
    </ul>
    <!-- sidebar menu end-->
  </div>
</aside>