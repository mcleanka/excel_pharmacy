<?php
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "new-sale.php";
require_once 'Includes/templates/header.php';
?>

<div class="container p-fixed" id="main-content">
	<div class="header">
		<label class="header-name">New Sale</label>
		<a href="sales.php" style="width: 110px;" class="btn btn-primary mr-0 pull-right">Sales History</a>
		<hr>
	</div>
<div class="">
	<div class="top-form pb-1000">
		<form role="form" id="top-form" autocomplete="off" onsubmit="return false;">
			<div class="row">
				<div class="col-xs-6">
					<div class="form-group">
					<label for="customer" class="text-center">Select Customer &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href ="customers-add.php">Click Here To Add New Customer</a></label>
						<input type="text" class="form-control" id="customer" name="customer" placeholder="Enter Organisation Name">
					</div>
				</div>
				<div class="col-xs-4">
					<div class="form-group">
						<label for="saleType"> Sale Type </label>
						<select name="saleType" id="saleType" class="form-control">
							<option value=""> - Select Type -</option>
							<option value="1">Cash</option>
							<option value="2">Credit</option>
						</select>
					</div>
				</div>
				<div class="col-xs-2">
					<button id="add" class="btn btn-info" style="margin-top: 25px;">Add Sale Items</button>
				</div>
			</div>
		</form>
	</div>

	<div class="mid-form" style="display: none">
		<div class="row">
			<div class="col-xs-4">
				<div class="form-heads">
					<b>Customer Name: </b><br><span id="dCName"></span>
				</div>
			</div>
			<div class="col-xs-3">
				<div class="form-heads">
					<b>National ID:</b> <br><span id='dCNATID'></span>
				</div>
			</div>
			<div class="col-xs-3">
				<div class="form-heads">
					<b>Organisation: </b><br><span id="dCOgr"></span>
				</div>
			</div>
			<div class="col-xs-2">
				<div class="form-heads">
					<b>Sale Type: </b><br><span id="selectedType"></span>
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

		<div class="mx-0 px-0">
			<div id="tcontainer" style="margin-top: -5px; display: none;">
				<table class='table table-bordered mx-0 px-0'>
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
		</div>
		<div class="mx-0 px-0">
			<div class="mx-0 px-0" id="drugtable" style="margin-left: 15px; display: none !important;">
			<button class='btn btn-primary' id='finish' style='width: 120px;'>Finish</button>
			<a href='new-sale.php' class='btn btn-danger' style='width: 120px;'>Reset</a>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		function commas(x) {
            var parts = x.toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join(".");
        }
		var druglist = new Array(), pos = 0, total = 0;
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
		//*****************************************************************
		var c_name = '';
		var c_nat_id = '';
		var cus_id = '';
		var c_org = '';
		$(function() {
			$.get('Ajax/getcustomers.php', function(data){
				var list = data;
				$("#customer").autocomplete({
					source:[list]
				});
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

		$('#add').click(function(){
			var customernameraw = $("#customer").val();
			// mclean kasambala -- (NAT ID: mw/j9847)

			var s_type = $("#saleType").find("option:selected").text();
			var beginID = customernameraw.lastIndexOf("ID:")+3;

			var endID	= customernameraw.lastIndexOf(")");

			var nationalID = customernameraw.substring(beginID, endID);
			var endName = customernameraw.lastIndexOf("--")-1;
			
			var customerName = customernameraw.substring(0, endName);

			var anonymous = true;
			$.post('Ajax/checkcustomer.php', {customerName: customerName, nationalID: nationalID}, function(data){
				if (data != 'Customer Not Found') {
					c_nat_id 	= data.substring(0, data.lastIndexOf("$"));
					c_name 		= data.substring(data.lastIndexOf("$")+1, data.lastIndexOf("*"));
					c_org 		= data.substring(data.lastIndexOf("*")+1, data.lastIndexOf("&"));
					cus_id 		= data.substring(data.lastIndexOf("&")+1, data.lastIndexOf("^"));
					$("#dCName").text(c_name);
					$("#dCNATID").text(c_nat_id);
					$("#dCOgr").text(c_org);
					$("#selectedType").text(s_type);
					$(".top-form").fadeOut(function(){
    					$(".mid-form").show();
    					$(".bottom-form").show();
					});
					anonymous = false;
				}
			});
		});

		$("#finish").click(function(){
			var customer_id    = cus_id;
			var amount_total   = total;
			var saleType = $("#selectedType").text();
			if(amount_total != 0){
				$.post('Ajax/add_sale.php', {customer_id: customer_id, amount_total: amount_total,saleType: saleType}, function(data){
					var sale_id = data;
					if(druglist.length==0){
						swal("Empty Purchase", "Please Add Items to the Purchase", "error");
						exit();
					}
					else{
						for(var i=0; i<druglist.length; i++){
							var drug_id 	= druglist[i][5];
							var quantity 	= druglist[i][3];
							var customer_id    = cus_id;
							var amount_total   = total;
							$.post('Ajax/add_transaction.php', {drug_id: drug_id, quantity: quantity});

							$.post('Ajax/add_sale_item.php', {sale_id: sale_id, drug_id: drug_id, quantity: quantity, saleType: saleType, amount_total: amount_total, customer_id: customer_id});
						}

						swal({
							title: 'Sale Information Recorded Successfully',
							text: 'Press OK to Continue',
							type: 'success'
						}
						, function(){
							window.location.href='new-sale.php';
						}
						);
					}
				});
			}
			else{

				swal("Empty Purchase", "Please Add Items to the Purchase", "error");

			}
		});

	});
</script>

<?php require_once 'Includes/templates/footer.php'; ?>
