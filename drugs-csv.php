<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "drugs.php";
require_once 'Includes/templates/header.php';
?>
<div class="container p-fixed" id="main-content">
<div class="header">
    <label class="header-name">Upload Product from CSV</label>
    <span class="pull-right pr-3">
		<a href="drugs.php" class="btn btn-primary">Product List</a>
        <a href="drugs-add.php" class="btn btn-primary">Add Product</a>
    </span>
    <hr>
</div>

<div class="row" style="margin-top: 15%">
	<div class="col-xs-12">
		<center>
			<div id="status" style="background-color: #009688; width: 96px; height: 96px;">
				<img src="Assets/images/icons/upload48.png" class="img-circle">	
			</div>			
			<h1 id="feedback">Upload Product from the Excel Product Master File</h1>

			<button id="upload" class="btn btn-primary">Start Upload</button>
		</center>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("#upload").click(function(){
			$("#status").css("background-color","#FFFFFF");
			$("#status").html('<img src="Assets/images/loading.gif" class="img-circle">');
			$("#feedback").html("Uploading in Progress. Please Wait...");
			$("#upload").hide();

			$.post('Ajax/upload_drug.php', {token: 'true'}, function(data){
				if(data=='success'){
					$("#status").html('');
					$("#feedback").html("Upload Completed Successfully");
					swal({
						title: 'Product Added Successfully',
						text: 'Press OK to Continue',
						type: 'success'
					}, function(){
						window.location.href='drugs.php';
					});					
				}
				else{
					alert("failed");
					window.location.href='dashboard.php';
				}				
			});
		});
	});
</script>

<?php require_once 'Includes/templates/footer.php'; ?>
			