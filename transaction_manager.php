<?php include('common/header.php'); ?>

<?php  
  if($first_role!=1){
    $msg="You have not permission to access this page!";
    $msg_type="error";
    $url="index.php";
    $data_access->Redirect($msg, $msg_type, $url);
    exit();
  }
?>

<!--header start-->
<?php include('common/main_menu.php'); ?>
<!--header end-->
<!--sidebar start-->
<?php include('common/main_sidebar.php'); ?>
<!--sidebar end-->
      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">
          	<!-- page start-->
              <script type="text/javascript">
                var reload_window="";
                function drawStockComboBox(){
                  var tranType=document.getElementById('tran_type').value;
                  var data="tran_type="+tranType;
                  var xhttp = new XMLHttpRequest();
                  xhttp.onreadystatechange = function(){
                    if(xhttp.readyState==4 && xhttp.status==200){
                      var jData=JSON.parse(xhttp.responseText);
                      if(jData['trans_flag_stock_in']==1){
                        document.getElementById('trans_flag_stock_in').style.display="block";
                      }else{
                        document.getElementById('trans_flag_stock_in').style.display="none";
                      }
                      if(jData['trans_flag_stock_out']==1){
                        document.getElementById('trans_flag_stock_out').style.display="block";
                      }else{
                        document.getElementById('trans_flag_stock_out').style.display="none";
                      }
                    }
                  };
                  xhttp.open("POST", "transaction_proccess.php", true);
                  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); 
                  xhttp.send(data);
                }

                function checkQuantity(){
                  var tran_quantity_plus=parseFloat(document.getElementById('tran_type_quantity_plus').value.trim());
                  var tran_quantity_minus=parseFloat(document.getElementById('tran_type_quantity_minus').value.trim());
                  console.log(tran_quantity_plus);

                  if(parseFloat(tran_quantity_minus)<parseFloat(tran_quantity_plus)){
                    document.getElementById('exceeds_error').innerHTML="Quantity- must be equal or grater than Quantity+";
                  }else{
                    document.getElementById('exceeds_error').innerHTML="";
                  }
                }
                function isNumber(evt) {
                  evt = (evt) ? evt : window.event;
                  var charCode = (evt.which) ? evt.which : evt.keyCode;
                  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                      return false;
                  }
                  return true;
                }
                function assignCurrentDate(){
                  document.getElementById("tran_date").defaultValue = "<?php echo(date('Y-m-d')); ?>";
                }

                function resetWindowToNew(){
                  assignCurrentDate();
                  document.getElementById('window_title').innerHTML="<span class='fa fa-plus'></span> New Transaction";
                  // document.getElementById('tran_type').value="";
                  document.getElementById('trans_flag_stock_out').style.display="none";
                  document.getElementById('trans_flag_stock_in').style.display="none";
                }

                // function setTwoNumberDecimal(event) {
                //     this.value = parseFloat(this.value).toFixed(2);
                // }
              </script>

              <div class="row">
                  <div class="col-lg-12" ng-app="transaction_app" ng-controller="transaction_controller" ng-init="displayData();">
                      <section class="panel">
                          <div class="panel-body relative_behave">
                            <div class="col-sm-12" id="alert_box_timer"></div>
                          	<div class="row">
									            <div class="col-sm-12">
									              <div class="section_heading_text">
									                <h3 class="section_heading line_before pull-left"><span class="fa fa-tasks"></span> Transaction Manager</h3>
									                <div class="line pull-left"></div>
                                  <button class="btn btn-success pull-right f_right_btn" data-toggle="modal" data-target="#myModal" onclick="resetWindowToNew();" ng-click="changeBtnText();"> <span class="fa fa-plus"></span> New Transaction</button>
                                  <div class="clearfix"></div>
									              </div>
									            </div>
									         	</div>
                            
                            <div class="row">
                              <div class="col-sm-12">
                                <div class="form-group">
                                  <br/>
                                  <label for="tran_date" class="block">View records at a time</label>
                                  <select ng-model="viewby" ng-change="setItemsPerPage(viewby)" class="form-control display_transaction_no">
                                    <option>3</option>
                                    <option>5</option>
                                    <option>10</option>
                                    <option>20</option>
                                    <option>30</option>
                                    <option>40</option>
                                    <option>50</option>
                                  </select>
                                  <h3 class="pull-right pagination_pages">Page: {{currentPage}} / {{numPages}}</h3>
                                </div>
                              </div>
                            </div>

                            <table class="table table-striped table-advance table-hover">
                              <thead>
                                <tr>
                                  <th>ID</th>
                                    <th><span class="fa fa-calendar"></span> Date</th>
                                    <th><span class="fa fa-tasks"></span> Transaction</th>
                                    <th><span class="fa fa-cubes"></span> Stock In</th>
                                    <th><span class="fa fa-cube"></span> Stock Out</th>
                                    <th>Stock +</th>
                                    <th>Stock -</th>
                                    <th><span class="fa fa-gear"></span> Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="x in transactions_display.slice(((currentPage-1)*itemsPerPage), ((currentPage)*itemsPerPage))" class="gradeX"> 
                                  <td>{{x.id}}</td> 
                                  <td>{{x.trans_date}}</td>  
                                  <td>{{x.trans_type}}</td>  
                                  <td><span class="label label-warning label-mini">{{x.trans_stock_nr_in}}</span></td>  
                                  <td><span class="label label-info label-mini">{{x.trans_stock_nr_out}}</span></td>  
                                  <td><span class="label label-primary label-mini">{{x.trans_in}}</span></td>  
                                  <td><span class="label label-success label-mini">{{x.trans_out}}</span></td>
                                   <td>
                                        <button class="btn btn-primary btn-xs" ng-click="updateData(x.id, x.trans_date, 
                                        x.trans_type, 
                                        x.trans_stock_nr_in, x.trans_stock_nr_out, 
                                        x.trans_in, 
                                        x.trans_out);" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i></button>
                                        <button type="submit" class="btn btn-danger btn-xs" ng-click="deleteData(x.id, $index);" /><i class="fa fa-trash-o "></i></button>
                                    </td>
                                </tr>
                              </tbody>
                            </table>
                            
                            <div class="row">
                              <div class="col-sm-12">
                                <pagination total-items="total_transactions" ng-model="currentPage" max-size="maxSize" class="pagination-sm" boundary-links="true" rotate="false" num-pages="numPages" items-per-page="itemsPerPage"></pagination>
                              </div>
                            </div>

                          </div>
                      </section>

                      <!-- Modal -->
                      <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" data-keyboard="false" data-backdrop="static" id="myModal" class="modal fade ">
                          <div class="modal-dialog" style="width: 60%;">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                      <h4 class="modal-title" id="window_title"><span class="fa fa-plus"></span> New Transaction</h4>
                                  </div>
                                  <div >
                                    <div class="modal-body" >
                                      <div class="row">
                                        <div class="col-sm-12" id="alert_box"></div>
                                        <div class="col-sm-12">
                                          <div class="row">
                                            <div class="form-group col-md-4">
                                              <label for="tran_date">Date</label>
                                              <input type="date" name="trans_date" class="form-control" id="tran_date" ng-model="tran_date" />
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div class="space_20px"></div>
                                            <div class="form-group col-md-6">
                                              <label for="tran_type">Transaction Type</label>
                                              <select class="form-control" id="tran_type" onchange="drawStockComboBox();" ng-model="tran_type" name="trans_type">
                                                <option value="" selected>Select</option>
                                                <?php 
                                                  $query="SELECT * FROM transaction_type";
                                                  $execuet_query=$data_access->Execute($query);
                                                  $row_list="";
                                                  $flag_in= "";
                                                  while($row=mysqli_fetch_object($execuet_query)){
                                                    $row_list.="<option value='$row->id'>$row->trans_description</option>";
                                                    $flag_in=$row->trans_flag_stock_in;
                                                  }
                                                  echo($row_list);
                                                ?>
                                              </select>
                                            </div>
                                            <!-- <input type="text" name="" ng-model="flag_in" value=""> -->
                                            <div class="clearfix"></div>
                                            <div id="trans_flag_stock_in">
                                              <div class="form-group col-md-6" >
                                                <label for="tran_type">Stock +</label>
                                                <select class="form-control" ng-model="tran_nr_in" name="trans_stock_nr_in" id="trans_stock_nr_in" >
                                                  <option value="" selected>Select</option>
                                                  <?php 
                                                    $query="SELECT * FROM stock_description";
                                                    $execuet_query=$data_access->Execute($query);
                                                    $row_list="";
                                                    while($row=mysqli_fetch_object($execuet_query)){
                                                      $row_list.="<option value='$row->id'>$row->stock_description</option>";
                                                    }
                                                    echo($row_list);
                                                  ?>
                                                </select> 
                                              </div>
                                              <div class="form-group col-md-4 col-md-offset-1">
                                                <label for="tran_type_quantity_plus">Quantity +</label>
                                                <!-- for decimal  -->
                                                <input type="number" class="form-control" id="tran_type_quantity_plus"  step="0.25" value="0.00" ng-model="tran_in" name="trans_in"/>
                                               <!--  <input type="number" class="form-control" id="tran_type_quantity_plus" ng-model="tran_in" step="0.25" value="0.00" min="0" max="10" name="trans_in" > -->
                                              </div>
                                            </div>

                                            <div class="clearfix"></div>
                                            <div id="trans_flag_stock_out">
                                              <div class="form-group col-md-6">
                                                <label for="tran_type">Stock -</label>
                                                <select class="form-control" ng-model="tran_nr_out" name="trans_stock_nr_out" id="trans_stock_nr_out">
                                                  <option value="" selected>Select</option>
                                                  <?php 
                                                    echo($row_list);
                                                  ?>
                                                </select> 
                                              </div>
                                              <div class="form-group col-md-4 col-md-offset-1">
                                                <label for="tran_type_quantity_minus">Quantity -</label>
                                                <input type="number" class="form-control" id="tran_type_quantity_minus" onchange="return checkQuantity();"  step="0.25" value="0.00" ng-model="tran_out" name="trans_in" onkeyup="return checkQuantity();"/>

                                                <!-- <input type="number" class="form-control" id="tran_type_quantity_minus" onkeyup="return checkQuantity();" onchange="return checkQuantity();" ng-model="tran_out" name="trans_out" step="0.25" value="0.00" onkeypress="return isNumber(event);"> -->
                                                <span id="exceeds_error"></span>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="modal-footer">
                                      <button data-dismiss="modal" class="btn btn-danger" type="button" ng-click="unsetReloadEvent();"><span class="fa fa-remove" ></span> Cancel</button>

                                      <input type="hidden" ng-model="id" />
                                      <input type="hidden" ng-model="reload_window" />
                                      <input type="submit" class="btn btn-success" ng-click="addTransaction();" name="trans_add" value="{{btnName}}"/>
                                    </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <!-- modal -->
                  </div>
              </div>
              <!-- page end-->
          </section>
      </section>
      <script type="text/javascript">
        var app=angular.module("transaction_app", ['ui.bootstrap']);
        app.controller("transaction_controller", function($scope, $http){ 

          // insert data
          $scope.btnName = "Submit";
          $scope.addTransaction=function(){
            var tran_date=document.getElementById('tran_date');
            var tran_type=document.getElementById('tran_type');
            var trans_stock_nr_in=document.getElementById('trans_stock_nr_in');
            var tran_type_quantity_plus=document.getElementById('tran_type_quantity_plus');
            var trans_stock_nr_out=document.getElementById('trans_stock_nr_out');
            var tran_type_quantity_minus=document.getElementById('tran_type_quantity_minus');
            if(tran_date.value.length==""){
              document.getElementById('alert_box').style.display="block";
                document.getElementById('alert_box').innerHTML="<div class='alert alert-danger' role='alert'><strong>Error! </strong> Date field should not be empty</div>";
              return false;
            }
            if(parseInt(trans_stock_nr_in.value)==parseInt(trans_stock_nr_out.value)){
              document.getElementById('alert_box').style.display="block";
                document.getElementById('alert_box').innerHTML="<div class='alert alert-danger' role='alert'><strong>Error! </strong> Stock+ and Stock- not be same </div>";
              return false;
            }
            // if(parseFloat(tran_type_quantity_plus.value) < parseFloat(tran_type_quantity_minus.value)){
            //   document.getElementById('alert_box').style.display="block";
            //     document.getElementById('alert_box').innerHTML="<div class='alert alert-danger' role='alert'><strong>Error! </strong>Quantity- is grater or equal to Quantity+</div>";
            //   return false;
            // }
            if(tran_type.value.length==""){
              document.getElementById('alert_box').style.display="block";
                document.getElementById('alert_box').innerHTML="<div class='alert alert-danger' role='alert'><strong>Error! </strong> Transaction Type should not be empty</div>";
              return false;
            }
            if(tran_type.value==1 && trans_stock_nr_in.value.length==""){
              document.getElementById('alert_box').style.display="block";
                document.getElementById('alert_box').innerHTML="<div class='alert alert-danger' role='alert'><strong>Error! </strong> Stock plus should not be empty</div>";
              return false;
            }
            if(tran_type.value==1 && tran_type_quantity_plus.value.length==""){
              document.getElementById('alert_box').style.display="block";
                document.getElementById('alert_box').innerHTML="<div class='alert alert-danger' role='alert'><strong>Error! </strong> Stock plus quantity should not be empty</div>";
              return false;
            }
            if(tran_type.value==2 && trans_stock_nr_in.value.length==""){
              document.getElementById('alert_box').style.display="block";
                document.getElementById('alert_box').innerHTML="<div class='alert alert-danger' role='alert'><strong>Error! </strong> Stock plus should not be empty</div>";
              return false;
            }
            if(tran_type.value==2 && tran_type_quantity_plus.value.length==""){
              document.getElementById('alert_box').style.display="block";
                document.getElementById('alert_box').innerHTML="<div class='alert alert-danger' role='alert'><strong>Error! </strong> Stock plus quantity should not be empty</div>";
              return false;
            }
            if(tran_type.value==2 && trans_stock_nr_out.value.length==""){
              document.getElementById('alert_box').style.display="block";
                document.getElementById('alert_box').innerHTML="<div class='alert alert-danger' role='alert'><strong>Error! </strong> Stock minus should not be empty</div>";
              return false;
            }
            if(tran_type.value==2 && tran_type_quantity_minus.value.length==""){
              document.getElementById('alert_box').style.display="block";
                document.getElementById('alert_box').innerHTML="<div class='alert alert-danger' role='alert'><strong>Error! </strong> Stock minus quantity should not be empty</div>";
              return false;
            }
            if(tran_type.value==3 && trans_stock_nr_out.value.length==""){
              document.getElementById('alert_box').style.display="block";
                document.getElementById('alert_box').innerHTML="<div class='alert alert-danger' role='alert'><strong>Error! </strong> Stock minus should not be empty</div>";
              return false;
            }
            if(tran_type.value==3 && tran_type_quantity_minus.value.length==""){
              document.getElementById('alert_box').style.display="block";
                document.getElementById('alert_box').innerHTML="<div class='alert alert-danger' role='alert'><strong>Error! </strong> Stock minus quantity should not be empty</div>";
              return false;
            }

            $http.post(
              "transaction_proccess.php",
              {'btnName':$scope.btnName, 'tran_date':$scope.tran_date, 'tran_type':$scope.tran_type, 'tran_nr_in':$scope.tran_nr_in, 'tran_in':$scope.tran_in, 'tran_nr_out':$scope.tran_nr_out, 'tran_out':$scope.tran_out, 'id':$scope.id}
            ).success(function(data){
              if(data=="success"){
                document.getElementById('alert_box').style.display="block";
                document.getElementById('alert_box').innerHTML="<div class='alert alert-success' role='alert'><strong>Success! </strong> Transaction Done!</div>";
                $scope.tran_date = <?php echo(date('Y/m/d')); ?>; 
                alert($scope.tran_date);
                $scope.tran_type = null;  
                $scope.tran_nr_in = null;  
                $scope.tran_in = null;  
                $scope.tran_nr_out = null;  
                $scope.tran_out = null;
                if(reload_window=="update_event"){
                  setTimeout(function(){ location.reload(); }, 1000);
                }
              }else{
                document.getElementById('alert_box').style.display="block";
                document.getElementById('alert_box').innerHTML="<div class='alert alert-danger' role='alert'><strong>Opps! </strong> Some Error occurred please try again</div>";
              }
              $scope.btnName = "Submit"; 
              $scope.displayData();
              setTimeout(function(){ document.getElementById("alert_box").style.display="none"; }, 3000);
            });
          }

          // fetch data
          $scope.displayData=function(){  
            $http.get("select_transactions.php").success(function(data){  
              $scope.transactions_display=data;
              $scope.total_transactions = $scope.transactions_display.length;
            });  
          }

          // paginate work 
          $scope.viewby = 10;
          $scope.currentPage = 1;
          $scope.itemsPerPage = $scope.viewby;
          $scope.maxSize = 3; //Number of pager buttons to show

          $scope.setPage = function (pageNo) {
            $scope.currentPage = pageNo;
          };

          $scope.pageChanged = function() {
            console.log('Page changed to: ' + $scope.currentPage);
          };

          $scope.setItemsPerPage = function(num) {
            $scope.itemsPerPage = num;
            $scope.currentPage = 1; //reset to first page
          }

          // delete record
          $scope.deleteData = function(record_id, index){
            $http.get("transaction_proccess.php?id=" + record_id).success(function(data){
              $scope.transactions_display.splice(index, 1);
              if(data=="success"){
                document.getElementById('alert_box_timer').style.display="block";
                document.getElementById('alert_box_timer').innerHTML="<div class='alert alert-success' role='alert'><strong>Success! </strong> Transaction Deleted!</div>";
                setTimeout(function(){ document.getElementById("alert_box_timer").style.display="none"; }, 3000);
              }else{
                document.getElementById('alert_box_timer').style.display="block";
                document.getElementById('alert_box_timer').innerHTML="<div class='alert alert-danger' role='alert'><strong>Opps! </strong> occurred please try again</div>";
                setTimeout(function(){ document.getElementById("alert_box_timer").style.display="none"; }, 3000);
              }
            });
          } 

          // edit record
          $scope.updateData = function(id, trans_date, trans_type, trans_stock_nr_in, trans_stock_nr_out, trans_in, trans_out){
            document.getElementById('window_title').innerHTML="<span class='fa fa-edit'></span> Update Transaction";
            if(trans_type=="Harvesting"){
              trans_type_update="1";
            }else if(trans_type=="Dry"){
              trans_type_update="2";
            }else if(trans_type=="Sale"){
              trans_type_update="3";
            }
            $scope.id = id;  

            document.getElementById("tran_date").defaultValue = trans_date;  
            $scope.tran_type = trans_type_update;
            var el1 = document.getElementById("trans_stock_nr_in");
            for(var i=0; i<el1.options.length; i++) {
              if (el1.options[i].text == trans_stock_nr_in) {
                el1.selectedIndex = i;
                break;
              }
            }
            var el2 = document.getElementById("trans_stock_nr_out");
            for(var i=0; i<el2.options.length; i++) {
              if (el2.options[i].text == trans_stock_nr_out) {
                el2.selectedIndex = i;
                break;
              }
            }
            $scope.tran_in = trans_in; 
            $scope.tran_out = trans_out;  
            $scope.btnName = "Update"; 

            reload_window="update_event";

            if($scope.tran_type.trim()==1){
              document.getElementById('trans_flag_stock_in').style.display="block";
              document.getElementById('trans_flag_stock_out').style.display="none";
            }else if($scope.tran_type.trim()==2){
              document.getElementById('trans_flag_stock_in').style.display="block";
              document.getElementById('trans_flag_stock_out').style.display="block";
            }else if($scope.tran_type.trim()==3){
              document.getElementById('trans_flag_stock_in').style.display="none";
              document.getElementById('trans_flag_stock_out').style.display="block";
            }
          }

          // change btn text to Submit
          $scope.changeBtnText=function(){
            $scope.btnName = btn_text;
          } 

          $scope.unsetReloadEvent=function(){
            // $scope.btnName = "Submit";
            location.reload();
          }

        });
        
      </script>
      <!--main content end-->
<!--footer start-->
<?php include('common/footer.php'); ?>
<!--footer end-->
