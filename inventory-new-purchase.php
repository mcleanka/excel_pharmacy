<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "inventory-purchases.php";
require_once 'Includes/templates/header.php';

$todayDate =  date('Y/m/d');

if (isset($_POST['submit'])) {
	$errors = "";
}
?>
<div class="container" id="main-content">
	<div class="header">
		<label class="header-name">New Purchase</label>
		<span class="mr-0 pull-right">
			<?php include 'Includes/components/inventory-top-buttons.php'; ?>
		</span>
		<hr>
	</div>
<div class="row">
	<div class="col-xs-12">
		<?php 
			if(!isset($_POST['submit'])):
		?>
				
		<?php else: if($errors!=""): ?>

		<?php echo $errors ?>

		<?php endif; endif;?>
		
		<form role="form" id="top-form" autocomplete="off">
			<div class="row">
				<div class="col-xs-3">
					<div class="form-group">
						<label for="type">Purchase Type</label>
						<select name="type" id="type" class="form-control">
							<option value="cash">Cash</option>
							<option value="credit">Credit</option>
						</select>
					</div>
				</div>

				<div class="col-xs-3">
					<div class="form-group">
						<label for="date">Purchase Date</label>
						<input type="text" class="form-control" name="date" id="date" value="<?php echo $todayDate; ?>">
					</div>
					<script type="text/javascript">
					$("#date").datepicker({dateFormat:'yy/mm/dd', autoOpen: false, maxDate: '0'})
					</script>
				</div>


				<div class="col-xs-6">
					<div class="form-group">
						<label for="supplier">Purchase Supplier [ <a href="inventory-add-supplier.php">New Supplier</a> ]</label>
						<select name="supplier" id="supplier" class="suppliers form-control">
						<?php 
							$res = $db->query("SELECT * FROM tbl_supplier WHERE state='active' ORDER BY name");
							while ($row = mysqli_fetch_assoc($res)): 
							$sid 	= $row['supplier_id'];
							$sname 	= $row['name'];
						?>

						<option value="<?php echo $sid ?>"><?php echo $sname ?></option>
	
						<?php endwhile;?>
						</select>

						<script type="text/javascript">
					      	$(document).ready(function() {
					        	$(".suppliers").select2();
					    	});
					   </script>
					</div>
				</div>
			</div>			
		</form>

		<button id="add-items" class="btn btn-primary" style="">Add Purchase Items</button>

		<div id="bottom" style="display: none;">
			<form role="form" style="" id="top-form" autocomplete="off" onsubmit="return false;">
				<div class="row">
					<div class="col-xs-4">
						<div class="form-group">
							<label for="drugname">Product Name</label>
							<input type="text" class="form-control" id="drugname" name="drugname" placeholder="Enter Product Name" value="<?php if(isset($_POST['name'])){ echo $_POST['name']; } ?>">
						</div>
					</div>

					<div class="col-xs-2">
						<div class="form-group">
							<label for="price">Unit Price [ MWK ]</label>
							<input type="text" class="form-control" id="price" name="price">
						</div>
					</div>

					<div class="col-xs-2">
						<div class="form-group">
							<label for="quantity">Quantity</label>
							<input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1">
						</div>
					</div>

					<div class="col-xs-2">
						<div class="form-group">
							<label for="date">Expiry Date</label>
							<input type="text" class="form-control" name="expDate" id="expDate" value="<?php echo $todayDate; ?>">
						</div>
						<script type="text/javascript">
						$("#expDate").datepicker({dateFormat:'yy/mm/dd', autoOpen: false})
						</script>
					</div>	
					<div class="col-xs-2">
						<button id="add" class="btn btn-info" style="margin-top: 25px;">Add to List</button>
					</div>
				</div>		
			</form>
			<div id="tcontainer" style="display: none;">
				<table class='table table-bordered'> 
					<thead> 
						<tr> 
							<th>Product Name</th> 
							<th>Unit Price [ MWK ]</th> 
							<th>Quantity</th> 
							<th>Line Total [ MWK ]</th> 
							<th>Expiry Date</th> 
						</tr> 
					</thead> 
					<tbody id="tbody">

					</tbody> 
				</table>
				<div id="drugtable" style="display: none">
					<button class='btn btn-primary' id='finish' style='width: 120px;'>Finish</button>
					<a href='inventory-new-purchase.php' class='btn btn-danger' style='width: 120px;'>Cancel</a>
				</div>
			</div>
			
								
		</div>

		<script type="text/javascript">
				function commas(x) { var parts = x.toString().split("."); parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ","); return parts.join("."); }
			var druglist = new Array();
			var pos=0;
			var purchasetype;
			var supplier;
			var purchasedate;
			var total = 0;

			function addToList(drugid,drugname,price,quantity,linetotal,expDate){
				druglist[pos]=[drugid,drugname,price,quantity,linetotal,expDate];
				pos++;
				render();
			}

			function render(){
				total = 0;
				$("#tbody").html("");
				$("#drugtable").show();
				$("#tcontainer").show();
				for(var i=0; i<druglist.length;i++){
					$("#tbody").append("<tr> <td>"+druglist[i][1]+"</td> <td>"+commas(druglist[i][2])+"</td> <td>"+druglist[i][3]+"</td> <td>"+commas(druglist[i][4])+"</td> <td>"+druglist[i][5]+"</td> </tr>");
					total+=(druglist[i][4] * 1);
				}
				$("#tbody").append("<tr> <td></td> <td></td> <td><b>Total:</b></td> <td>"+commas(total)+"</td> </tr>");

			}

			$(function() {
				$.get('Ajax/alldrugs.php', {drugname: "set"}, function(data){
					var list=data;
					$("#drugname").autocomplete({
						source:[list]
					}); 
				});	
			});
			$(document).ready(function(){
				$("#add-items").click(function(){
					purchasetype 	= $("#type").val();
					supplier 		  = $("#supplier").val();
					purchasedate 	= $("#date").val();
					if(supplier==""){
						swal("Incomplete Form", "Please Select a Supplier", "error");
					}
					else{
						$("#add-items").hide();
						$("#top-form").slideUp();
						$("#bottom").fadeIn(1000);
					}
				});

				function returnTodayDate(){
					var date_today = new Date();
					var day   = date_today.getDate();
  					var month = date_today.getMonth();
  					month     = month+1;
  					var year  = date_today.getFullYear();
  					if(month < 10 ){
  						month = '0'+month;
  					}
  					if (day < 10) {
  						day = '0'+day;
  					}
  					return year+'/'+month+'/'+day;
				}

				$("#add").click(function(){
					var dname 		= $("#drugname").val();
					var dprice 		= $("#price").val();
					var dquantity 	= $("#quantity").val();
					var expDate      	= $("#expDate").val();

				  var date_today =  returnTodayDate();
				  console.log(expDate+" " +date_today);
					if ( expDate < date_today) {
						swal("Expiry Date Error", "Expiry date can not be less than " + date_today, "error");
					  return false;
					}

					if(dprice==""){
						swal("Empty Unit Price Field", "Please Enter the Unit Price", "error");
					}
					else{
						$("#drugname").val("");
						$("#price").val("");
						$("#quantity").val("1");

						$.get('Ajax/getdrug.php', {drugname: dname}, function(data){
							if(data=="[]"){
								swal("Drug Name Error", "The drug name cannot be found in the database", "error");
							}
							else{
								var obj=JSON.parse(data);
								var drugid 		= obj[0].drug_id;
								var drugname 	= obj[0].name;
								var linetotal = dprice * dquantity;
								addToList(drugid,drugname,dprice,dquantity,linetotal,expDate);
							}				
						});						
					}

					
				});

				$("#finish").click(function(){

					$.post('Ajax/add_purchase.php', {purchasetype: purchasetype, purchasedate: purchasedate, supplier: supplier, total: total}, function(data){
	          var purchase_id = data;
	          if(druglist.length==0){
							swal("Empty Purchase", "Please Add Items to the Purchase", "error");
	          }else{
            	for(var i=0; i<druglist.length;i++){
              	var drug_id 	= druglist[i][0];
              	var unit_price 	= druglist[i][2];
              	var quantity 	= druglist[i][3];
              	console.log(quantity)
              	var expDate   = druglist[i][5];
              	var x  = $.post('Ajax/add_purchase_item.php', {purchase_id: purchase_id, drug_id: drug_id, supplier_id: supplier, unit_price: unit_price, quantity: quantity, expDate: expDate});

             }

		            swal({
								title: 'Purchase Recorded Successfully',
								text: 'Press OK to Continue',
								type: 'success'
							}, function(){
								window.location.href='inventory-purchases.php';
							});	
		                }
	                });
				});
			});
		</script>
	</div>

<?php require_once 'Includes/templates/footer.php'; ?>
