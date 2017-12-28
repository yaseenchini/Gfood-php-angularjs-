<?php include('common/header.php'); ?>
<?php  
  if($third_role!=1){
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
                                  <h3 class="section_heading line_before pull-left"><span class="fa fa-cubes"></span> Stock Situation</h3>
                                  <div class="line pull-left"></div>
                                  <div class="clearfix"></div>
                                </div>
                              </div>
                            </div>
                            <div class="small_space"></div>
                            <div class="row not_display_in_p">
                              <div class="col-sm-12" id="alert_box"></div>
                              <div class="row">
                                <div class="col-md-6">
                                <div class="form-group col-md-6">
                                  <label for="stock_type">Stock Type</label>
                                  <select class="form-control" id="stock_type" ng-model="stock_type" name="stocks_type">
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
                                  <label for="to_date">To Date</label>
                                  <input type="date" name="to_date" class="form-control" id="to_date" ng-model="to_date" />
                                </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="row">
                                    <div class="form-group" style="margin: -3px 0 0 15px;">
                                      <label>&nbsp;</label><br/>
                                      <input type="button" class="btn btn-success"  ng-click="searchRecord();" value="Apply" />
                                      <button type="button" class="btn btn-danger" onclick="javascript:window.print();"> Print</button>
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
                                      <th ng-show="stock_value_field"><span class="fa fa-cubes"></span> Stock Description</th>
                                      <th ng-show="stock_value_field"><span class="fa fa-tasks"></span> Transaction</th>
                                      <th ng-show="stock_value"><span class="fa fa-cubes"></span> Stock In</th>
                                      <th ng-show="stock_value" ><span class="fa fa-cube"></span> Stock Out</th>
                                      <th class="ra_inp">Stock +</th>
                                      <th class="ra_inp">Stock -</th>
                                      <th class="ra_inp">Balance</th>
                                  </tr>
                                  </thead>

                                  <tbody>
                                  <tr ng-repeat="x in transactions_display" class="gradeX"> 
                                    <td>{{x.id}}</td> 
                                    <td>{{x.trans_date}}</td> 
                                    <td ng-show="stock_value_field"><span class="label label-warning label-mini">{{x.stock_value}}</span></td>  
                                    <td ng-show="stock_value_field">{{x.trans_type}}</td>  
                                    <td ng-show="stock_value" class="not_display_in_search"><span class="label label-warning label-mini">{{x.trans_stock_nr_in}}</span></td>  
                                    <td ng-show="stock_value" class="not_display_in_search"><span class="label label-info label-mini">{{x.trans_stock_nr_out}}</span></td>  
                                    <td class="ra_inp"><span class="label label-primary label-mini">{{x.trans_in}}</span></td>  
                                    <td class="ra_inp"><span class="label label-success label-mini">{{x.trans_out}}</span></td>
                                    <td class="ra_inp">{{x.trans_in-x.trans_out}}</td>
                                  </tr>
                                  <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="ra_inp">{{fn}}</td>
                                    <td class="ra_inp">{{basheer}}</td>
                                  </tr>
                                </tbody>
                                <tfoot ng-show="stock_value_field_values">
                                    <tr>
                                      <th>&nbsp;</th>
                                      <th>&nbsp;</th>
                                      <th>&nbsp;</th>
                                      <th>Total</th>
                                      <th>{{ tranInTotal }}</th>
                                      <th>{{ tranOutTotal }}</th>
                                      <th>{{ tranInTotal-tranOutTotal }}</th>
                                    </tr>


                                  </tfoot>
                              </table>
                              <div id="data_ab">
                                
                              </div>
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
          
          // fetch data
          $scope.displayData=function(){  
            $http.get("select_stock_situation.php").success(function(data){
              var stock_data = [];
              var stock_data2 = [];
              var array_of_stock_in = [];
              var count=0;
              var count1=0;
              for (var i =0; i< data.length ; i++) {
                stock_data.push(data[i]["trans_stock_nr_in"]);
                stock_data2.push(data[i]["trans_stock_nr_out"]);
                }

                Array.prototype.unique = function () {
                  var r = new Array();
                  o:for(var i = 0, n = this.length; i < n; i++)
                  {
                    for(var x = 0, y = r.length; x < y; x++)
                    {
                      if(r[x]==this[i])
                      {
                        continue o;
                      }
                    }
                    r[r.length] = this[i];
                  }
                  return r;
              }
              var unique = stock_data.unique();
              var unique2 = stock_data2.unique();

             for (var i =0; i<unique.length  ; i++) { 
               if (unique[i] != null) {
                for (var j =0 ;j<stock_data.length ;j++) {

                  if(stock_data[j]==unique[i]){
                    count=count+parseInt(data[j]["trans_in"]);
                    //alert(stock_data[j] + ' ' + j);
                  }
                }
               // alert(count + "" + unique[i]);
                 
                array_of_stock_in={"stock_description":unique[i],"stock_in":count};
                                       
                if (i==0) {
                    $scope.fn=count + "" + unique[i];

                }else{
                     $scope.basheer=count + "" + unique[i];
                }
                count=0;
              }
            } 
             document.getElementById('data_ab').innerHTML=array_of_stock_in;
            


            for (var i =0; i<unique2.length  ; i++) { 
               if (unique2[i] != null) {
                for (var j =0 ;j<stock_data2.length ;j++) {
                  if(stock_data2[j]==unique2[i]){
                    count1=count1+parseInt(data[j]["trans_out"]);
                      //alert(stock_data2[j] + ' ' + j);
                  }
                }
              if (i==0) {
                  alert(count1 + "" + unique2[i]);

                }
              if (i==1) {
                alert(count1 + "" + unique2[i]);

              }
              if (i==2) {
                alert(count1 + "" + unique2[i]);

              }
              if (i==3) {
                  alert(count1 + "" + unique2[i]);

              }
                count1=0;            
               }
            }

            

               $scope.stock_value = true;
               $scope.stock_value_field= false;
               $scope.stock_value_field_values=false;
              $scope.transactions_display=data;
            });  
          }

          $scope.searchRecord = function(){
            $http.post("search_record_stock.php" , {'stock_type':$scope.stock_type, 'to_date':$scope.to_date}).success(function(data){
              var stock_type=document.getElementById('stock_type');
              var to_date=document.getElementById('to_date');
              if(stock_type.value.length=="" && to_date.value.length==""){
                document.getElementById('alert_box_timer').style.display="block";
                document.getElementById('alert_box_timer').innerHTML="<div class='alert alert-danger' role='alert'><strong>Error! </strong> Please select stock type or date to filter record</div>";
                setTimeout(function(){ document.getElementById("alert_box_timer").style.display="none"; }, 3000);

                return false;
              }

              if(data==""){
                document.getElementById('alert_box_timer').style.display="block";
                document.getElementById('alert_box_timer').innerHTML="<div class='alert alert-danger' role='alert'><strong>Opps </strong> No Record Found!</div>";
                setTimeout(function(){ document.getElementById("alert_box_timer").style.display="none"; }, 3000);
              }

              if(data[0].stock_value==""){
                $scope.stock_value = true;
                $scope.stock_value_field= false;
                $scope.transactions_display = data;
              }else{
                $scope.stock_value = false;
                $scope.stock_value_field= true;
                $scope.transactions_display = data;
              }
              
              $scope.stock_value_field_values=true;


              var total_in = 0;
              for(var count_in=0; count_in<data.length; count_in++){
                if(isNaN(parseInt(data[count_in].trans_in))==false){
                  total_in += parseInt(data[count_in].trans_in, 10);
                }
              }
              $scope.tranInTotal=total_in;

              var total_out = 0;
              for(var count_in=0; count_in<data.length; count_in++){
                if(isNaN(parseInt(data[count_in].trans_out))==false){
                  total_out += parseInt(data[count_in].trans_out, 10);
                }
              }
              $scope.tranOutTotal=total_out;
              
           });   
          }

        });
        
      </script>
      <script language="javascript">
        function Clickheretoprint(){ 
          var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
              disp_setting+="scrollbars=yes,width=800, height=400, left=100, top=25"; 
          var content_vlue = document.getElementById("stock_content").innerHTML; 
          
          var docprint=window.open("","",disp_setting); 
           docprint.document.open(); 
           docprint.document.write('</head><body onLoad="self.print()" style="width: 800px; font-size: 13px; font-family: arial;">');          
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
    downloadLink.download = "Stock Situation.csv";

   
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
