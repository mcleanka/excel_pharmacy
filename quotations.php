<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "quotations.php";
require_once 'Includes/templates/header.php';
?>
<div class="container" id="main-content">
	<div class="header">
		<label class="header-name">New Quatation</label>
		<a href="all-quotations.php" style="width: 110px;" class="btn btn-primary mr-0 pull-right">Recent Quotations</a>
		<hr>
	</div>
<div class="row">
	<div class="top-form">
		<form role="form" style="" id="top-form" autocomplete="no" onsubmit="return false;">
				<div class="col-xs-4">
					<div class="form-group">
					<label for="customer" class="text-center">Add Customer <span>*</span></label>
						<input type="text" class="form-control" id="customer" name="customer" placeholder="Enter Customer Full Name" required="required">
					</div>
				</div>
				<div class="col-xs-4">
					<div class="form-group">
					<label for="organisation" class="text-center">Organisation <span>*</span></label>
						<input type="text" class="form-control" id="organisation" name="organisation" placeholder="Enter Organisation Name" required="required">
					</div>

				</div>
				<div class="col-xs-4">
					<div class="form-group">
					<label for="location" class="text-center">Location <span>*</span></label>
						<input type="text" class="form-control" id="location" name="location" placeholder="Enter Location" required="required">
					</div>
				</div>
				<div class="col-xs-4">
					<div class="form-group">
					<label for="email" class="text-center">Email <span>*</span></label>
						<input type="email" class="form-control" id="email" name="email" placeholder="Enter Customer's Email" required="required">
					</div>

				</div>
				<div class="col-xs-4">
					<div class="form-group">
					<label for="phone" class="text-center">Phone Number <span>*</span></label>
						<input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Mobile Phone No" required="required">
					</div>

				</div>
				<div class="col-xs-4">
					<div class="form-group">
					<label for="date" class="text-center">Date <span>*</span></label>
						<input type="date" class="form-control" id="date" name="date">
					</div>
				</div>
				<div class="col-xs-5">
					<div class="form-group">
						<label for="address">Address</label>
						<textarea class="form-control" id="address" name="address"></textarea>
					</div>
				</div>
				<div class="col-xs-7">
					<button type="submit" class="btn btn-info pull-right" id="addInfo" style="margin-top: 60px !important;"> Submit </button>
				</div>
		</form>
	</div>

	<div class="mid-form" style="display: none;">
		<div class="row">
			<div class="col-xs-4">
				<div class="form-heads">
					<b>Customer Name: </b><br><span id="diaplay-full-name"></span>					
				</div>				
			</div>
			<div class="col-xs-3">
				<div class="form-heads">
					<b>Phone No:</b><br><span id='diaplay-phone'></span>
				</div>			
			</div>

			<div class="col-xs-3">
				<div class="form-heads">
					<b>Organisation: </b><br><span id="diaplay-organization"></span>					
				</div>				
			</div>
			<div class="col-xs-2">
				<div class="form-heads">
					<b>Location: </b><br><span id="diaplay-location"></span>					
				</div>				
			</div>
		</div>
	</div>

	<div class="bottom-form pb-1000" style="display: none;"><br>
		<form role="form" style="" id="top-form" autocomplete="off" onsubmit="return false;">
			<div class="row">
				<div class="col-xs-7">
					<div class="form-group">
						<label for="drugname">Product Name</label>
						<input type="text" class="form-control" id="drugname" name="drugname" placeholder="Enter Product Name" value="<?php if(isset($_POST['name'])){ echo $_POST['name']; } ?>">
					</div>
				</div>
				<div class="col-xs-3">
					<div class="form-group">
						<label for="quantity">Quantity</label>
						<input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1">
					</div>
				</div>
				<div class="col-xs-2">
					<button id="addToList" class="btn btn-info" style="margin-top: 25px;">Add Product to List</button>
				</div>
			</div>
		</form>
		<div id="tcontainer" style="padding: 15px; margin-top: -5px; display: none;">
			<table class='table table-bordered'> 
				<thead> 
					<tr> 
						<th>Product Name</th> 
						<th>Product Code</th>
						<th>Unit Price</th>
						<th>Quantity</th>
						<th>Total Price</th>
					</tr> 
				</thead> 
				<tbody id="tbody">

				</tbody> 
			</table>	
		</div>
		<div id="drugtable" style="margin-left: 15px; display: none !important;">
			<button class='btn btn-primary' id='finish' style='width: 120px;'>Finish</button>
			<a href='quotations.php' class='btn btn-danger' style='width: 120px;'>Reset</a>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		function commas(x) { 
            var parts = x.toString().split("."); 
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ","); 
            return parts.join(".");
        }
		var druglist = new Array(), pos = 0, total = 0, c_name = '', c_org = '', c_loc = '', c_phone = '', c_id = '';

		function addToList(dname, dcode, dunitprice, dqty, dprice, drug_id){
			druglist[pos]=[dname, dcode, dunitprice, dqty, dprice, drug_id];
			pos++;
			render();
		}

		function render(){
			total = 0;
			$("#tbody").html("");
			$("#drugtable").show();
			$("#tcontainer").show();
			for(var i=0; i<druglist.length;i++){
				$("#tbody").append("<tr> <td>"+druglist[i][0]+"</td> <td>"+druglist[i][1]+"</td> <td>K"+commas(druglist[i][2])+"</td> <td>"+druglist[i][3]+"</td> <td>K"+commas(druglist[i][4].toFixed(2))+"</td></tr>");
				var totalprice = druglist[i][4];
				total+=totalprice;
			}
			$("#tbody").append("<tr> <td></td> <td></td> <td></td> <td><b>Total:</b></td> <td>K"+commas(total.toFixed(2))+"</td></tr>");
		}

		$("#addToList").click(function(){
			var dname 		= $("#drugname").val();
			var dquantity 	= $("#quantity").val();
			$.get('Ajax/getsalesdrug.php', {drugname: dname, dquantity: dquantity}, function(data){
				if(data=="[]"){
					swal("Product Name Error", "The Product name cannot be found in the database", "error");
				}else if(data.substring(0,10)=='unvailable'){
					var available_qty = data.substring(8, (data.length));
					swal("Product unvailable in Our Stock", "No order made for "+$("#drugname").val()+" drug", "error");
				}
                else if(data.substring(0,8)=='shortage'){
                    var available_qty = data.substring(8,(data.length));
                    swal("Inventory Shortage to Make Product Sale", ""+ available_qty +" units available for "+$("#drugname").val(), "error");
                }
				else{
					var obj 		= JSON.parse(data);
					var dname 		= obj[0].name;
					var dcode 		= obj[0].code;
					var dunitprice	= obj[0].price;
					var dqty 		= $("#quantity").val();
					var dprice 		= dunitprice*dqty;					
					var tariff 		= obj[0].tariff;
					var drug_id		= obj[0].drug_id;	
					addToList(dname, dcode, dunitprice, dqty, dprice, drug_id);
					$("#drugname").val("");
					$("#quantity").val("1");
				}				
			});
		});

		$(function() {
			$.get('Ajax/alldrugs.php', {drugname: "set"}, function(data){
				var list=data;
				$("#drugname").autocomplete({
					source:[list]
				}); 
			});	
		});

		$('#addInfo').click(function(){
			var customerName = $("#customer").val();
			var organisation = $("#organisation").val();
			var phone = $("#phone").val();
			var email = $("#email").val();
			var address = $("#address").val();
			var location = $("#location").val();
			var date = $("#date").val();
			if(customerName!='' && organisation != '' && phone != '' && location != '' && email != ''){
				$.get('Ajax/add-quotation-customer.php', {customerName: customerName, organisation: organisation, location: location, phone: phone, email: email, address: address, date: date}, function(data){
					if(data=="[]"){
						swal("Error", "The proccess couldnot be completed", "error");
					}else{
						var obj 		= JSON.parse(data);

						c_phone 	= obj[0].phone;
						c_name 		= obj[0].full_name;
						c_org 		= obj[0].organisation;
						c_loc 		= obj[0].location;
						c_id 		= obj[0].customer_id;

						$("#diaplay-full-name").text(c_name);
						$("#diaplay-organization").text(c_org);
						$("#diaplay-location").text(c_loc);
						$("#diaplay-phone").text(c_phone);
						$(".top-form").fadeOut(function(){
	    					$(".mid-form").show();
	    					$(".bottom-form").show();
						});
					}				
				});
			}else{
				swal("Error", "All fields are required", "error");
				exit();
			}						
		});

		$("#finish").click(function(){
			var cus_id = c_id, amount_total = total;
			if(amount_total != 0){
				$.post('Ajax/add-quotation.php', {cus_id: cus_id, amount_total: amount_total}, function(data){
					var quotation_id = data;
					if(druglist.length==0){
						swal("Empty Quotation", "Please Add Items to the Quotations", "error");
						exit();
					}
					else{
						for(var i=0; i<druglist.length; i++){
							var drug_id 	= druglist[i][5];
							var quantity 	= druglist[i][3];
							var customer_id    = c_id;
							var amount_total   = total;
							$.post('Ajax/add-quotation-item.php', {quotation_id: quotation_id, drug_id: drug_id, quantity: quantity});
						}
						swal({
							title: 'Quotation Information Recorded Successfully',
							text: 'Press OK to Continue',
							type: 'success'
						}, function(){
							window.location.href='quotations.php';
						}
						);	
					}
				});
			}else{
				swal("Empty Quotation", "Please Add Items to the Quotation", "error");
			}
		});
	});
</script>

<?php require_once 'Includes/templates/footer.php'; ?>
