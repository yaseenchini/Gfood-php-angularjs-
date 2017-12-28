<?php include('common/header.php'); ?>
<?php  
  if($second_role!=1){
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
              <div class="row">
                  <div class="col-lg-12" ng-app="transaction_app" ng-controller="transaction_controller" ng-init="displayData();">
                      <section class="panel">
                          <div class="panel-body relative_behave">
                            <div class="col-sm-12" id="alert_box_timer"></div>
                            <div class="row">
                              <div class="col-sm-12">
                                <div class="section_heading_text">
                                  <h3 class="section_heading line_before pull-left"><span class="fa fa-file-text-o"></span> Report</h3>
                                  <div class="line pull-left"></div>
                                  <div class="clearfix"></div>
                                </div>
                              </div>
                            </div>


                            <div class="small_space"></div>

                            <div class="row">
                              <div class="col-sm-12" id="alert_box"></div>
                              <div class="row">
                                <div class="col-md-6">
                                <div class="form-group col-md-6">
                                  <label for="tran_type">Transaction Type</label>
                                  <select class="form-control" id="tran_type" ng-model="tran_type" name="trans_type">
                                    <option value="" selected>Select</option>
                                    <?php 
                                      $query="SELECT * FROM transaction_type";
                                      $execuet_query=$data_access->Execute($query);
                                      $row_list="";
                                      while($row=mysqli_fetch_object($execuet_query)){
                                        $row_list.="<option value='$row->id'>$row->trans_description</option>";
                                      }
                                      echo($row_list);
                                    ?>
                                  </select>
                                </div>
                                <div class="form-group col-md-6">
                                  <label for="stock_type_minus">Stock -</label>
                                  <select class="form-control" id="stock_type_minus" ng-model="stock_type_minus" name="stock_type_minus">
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
                                <div class="form-group col-md-6">
                                  <label for="stock_type_plus">Stock +</label>
                                  <select class="form-control" id="stock_type_plus" ng-model="stock_type_plus" name="stocks_type">
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
                                <div class="form-group col-md-6">
                                  <label for="tran_date">From Date</label>
                                  <input type="date" name="from_date" class="form-control" id="from_date" ng-model="from_date" />
                                </div>
                                <div class="form-group col-md-6">
                                  <label for="to_date">To Date</label>
                                  <input type="date" name="to_date" class="form-control" id="to_date" ng-model="to_date" />
                                </div>
                                </div>
                                <div class="col-md-6" >
                                  <div class="row">
                                    <div class="form-group col-md-12" id="cons_status">
                                      <label>Report sort by </label><br>
                                      <input id="by_date" type="radio" value="1" name="sort_by" ng-model="sort_by" ng-value="trans_date" ><label for="by_date"><span><span></span></span>Date</label>

                                      <input id="by_stock" type="radio" name="sort_by" ng-model="sort_by" ng-value='"trans_stock_nr_in"'><label for="by_stock"><span><span></span></span>Stock</label>

                                      <input id="by_trans_type" type="radio" name="sort_by" ng-model="sort_by" ng-value='"trans_type"'><label for="trans_type"><span><span></span></span>Transaction type</label>
                                    </div>
                                    <div class="form-group" style="margin: -3px 0 0 15px;">
                                      <label>&nbsp;</label><br/>
                                      <input type="button" class="btn btn-success"  ng-click="search_record();" value="Apply" />
                                      <!-- <a href="javascript:window.print();" class="btn btn-danger" >Print</a> -->
                                      <button type="button" class="btn btn-danger" onclick="javascript:Clickheretoprint();"> Print</button>
                                      <input type="button" class="btn btn-info" value="Export" id="export" />
                                    </div>
                                  </div>
                                </div>
                                
                              </div>
                            </div>
                            <div id="report_content">
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
                                      <th><span class="fa fa-dollar"></span></th>
                                      <th><span class="fa fa-money"></span> Amount</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  <tr ng-repeat="x in transactions_display" class="gradeX"> 
                                    <td>{{x.id}}</td> 
                                    <td>{{x.trans_date}}</td>  
                                    <td>{{x.trans_type}}</td>  
                                    <td><span class="label label-warning label-mini">{{x.trans_stock_nr_in}}</span></td>  
                                    <td><span class="label label-info label-mini">{{x.trans_stock_nr_out}}</span></td>  
                                    <td><span class="label label-primary label-mini">{{x.trans_in}}</span></td>  
                                    <td><span class="label label-success label-mini">{{x.trans_out}}</span></td>
                                    <td>{{x.trans_value}}</td>
                                    <td>{{x.trans_out*x.trans_value}}</td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                      </section>
                  </div>
              </div>
              <!-- page end-->
          </section>
      </section>
      <script type="text/javascript">
        var app=angular.module("transaction_app", []);
        app.controller("transaction_controller", function($scope, $http){ 
          $scope.sort_by = 1;
          // fetch data
          $scope.displayData=function(){  
            $http.get("select_report.php").success(function(data){  
              $scope.transactions_display=data;
            });  
          }

          $scope.search_record = function(){
            $http.post("search_report.php" , {'tran_type':$scope.tran_type,'stock_type_minus':$scope.stock_type_minus, 'stock_type_plus':$scope.stock_type_plus, 'from_date':$scope.from_date, 'to_date':$scope.to_date, 'sort_by':$scope.sort_by}).success(function(data){  
                $scope.transactions_display = data;
           });   
          }

        });
        
      </script>
      <script language="javascript">
        // function Clickheretoprint(){ 
        //   var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
        //       disp_setting+="scrollbars=yes,width=800, height=400, left=100, top=25"; 
        //   var content_vlue = document.getElementById("report_content").innerHTML; 
          
        //   var docprint=window.open("","",disp_setting); 
        //    docprint.document.open(); 
        //    docprint.document.write('</head><body onLoad="self.print()" style="width: 800px; font-size: 13px; font-family: arial; word-spacing: 100px;">');          
        //    docprint.document.write(content_vlue); 
        //    docprint.document.close(); 
        //    docprint.focus(); 
        // }

        function Clickheretoprint()
        { 
          var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
              disp_setting+="scrollbars=yes,width=800, height=400, left=100, top=25"; 
          var content_vlue = document.getElementById("report_content").innerHTML; 
          
          var docprint=window.open("","",disp_setting); 
           docprint.document.open(); 
           docprint.document.write('</head><body onLoad="self.print()" style="width: 800px; font-size: 13px; font-family: arial;margin:30px;"><td style="border:4px solid #efa5a5;">');          
           docprint.document.write(content_vlue); 
           docprint.document.close(); 
           docprint.focus(); 
        }
      </script>

      <!--main content end-->
<!--footer start-->
<?php include('common/footer.php'); ?>
<script type="text/javascript">
 $('#export').click(function() {
    var titles = [];
    var data = [];
    
    $('.table-striped th').each(function() {
      titles.push($(this).text());
    });

    $('.table-striped td').each(function() {
      data.push($(this).text());
    });
    
   
    var CSVString = prepCSVRow(titles, titles.length, '');
    CSVString = prepCSVRow(data, titles.length, CSVString);

   
    var downloadLink = document.createElement("a");
    var blob = new Blob(["\ufeff", CSVString]);
    var url = URL.createObjectURL(blob);
    downloadLink.href = url;
    downloadLink.download = "Report.csv";

   
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
  });


  function prepCSVRow(arr, columnCount, initial) {
    var row = ''; 
    var delimeter = ','; 
    var newLine = '\r\n'; 

    
    function splitArray(_arr, _count) {
      var splitted = [];
      var result = [];
      _arr.forEach(function(item, idx) {
        if ((idx + 1) % _count === 0) {
          splitted.push(item);
          result.push(splitted);
          splitted = [];
        } else {
          splitted.push(item);
        }
      });
      return result;
    }
    var plainArr = splitArray(arr, columnCount);
    
    plainArr.forEach(function(arrItem) {
      arrItem.forEach(function(item, idx) {
        row += item + ((idx + 1) === arrItem.length ? '' : delimeter);
      });
      row += newLine;
    });
    return initial + row;
  }
</script>
<!--footer end-->
