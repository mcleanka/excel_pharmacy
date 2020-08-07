<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "drugs.php";
require_once 'Includes/templates/header.php';
?>

<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#example').DataTable();
        $('#example tbody').on( 'click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            }
            else {
                table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });

        $('#button').click( function () {
            table.row('.selected').remove().draw( false );
        });

        $("#edit_drug").click(function(){
            drug = $(".selected").find("#drug_id").text();
            if(drug==''){
                swal("Invalid Selection", "Please Select a Drug", "error");
            }
            else{
                window.location.href='drugs-edit.php?id=' + drug;
            }
        });

        $("#view_drug").click(function(){
            drug = $(".selected").find("#drug_id").text();
            if(drug==''){
                swal("Invalid Selection", "Please Select a Drug", "error");
            }
            else{
                window.location.href='drugs-info.php?id=' + drug;
            }
        });

        $("#delete_row").click(function(){
            drug = $(".selected").find("#drug_id").text();
            if(drug==''){
                swal("Invalid Selection", "Please Select a Drug", "error");
            }
            else{
                swal({
                  title: "Confirm Action",
                  text: "Are you sure you want to Delete this?",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "Delete",
                  closeOnConfirm: false
              },
              function(){
                $.post('Ajax/delete_drug.php', {drug: drug}, function(data){
                    if(data=='success'){;
                        swal({
                            title: 'Action Completed Successfully',
                            text: 'The Drug has Been Removed',
                            type: 'success'
                        }, function(){
                            window.location.href='drugs.php';
                        });
                    }
                    else{
                        swal("Error", "Drug not Deleted", "error");
                    }
                });
            });
            }
        });
    });
</script>
<div class="container" id="main-content">
<div class="header">
    <label class="header-name">Products List</label>
    <span class="pull-right pr-3">
        <a href="drugs-add.php" class="btn btn-primary">Add Product</a>
        <a href="drugs-csv.php" class="btn btn-primary">Excel Upload</a>
    </span>
    <hr>
</div>

<div class="row">
	<div class="col-xs-12">
        <div class="action-btn mb-2">
           <button id="delete_row" class="btn btn-danger">Delete Product</button>
           <button id="edit_drug" class="btn btn-info">Edit Product</button>
        </div>
    <div>
		<table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Code</th>
                    <th>Unit Price</th>
                </tr>
            </thead>
            
            <tbody>
                <?php 
                $res = $db->query("SELECT * FROM tbl_drug WHERE state = 'active'");
                while ($row = mysqli_fetch_assoc($res)):
                    $drug_id = $row['drug_id'];
                    $drug_name = $row['name'];
                    $price = $row['price'];
                    $code = $row['code'];
                ?>
                <tr>
                    <td id="drug_id"><?php echo $drug_id; ?></td>
                    <td><?php echo $drug_name; ?></td>
                    <td><?php echo $code; ?></td>
                    <td>K<?php echo number_format($price); ?></td>
                </tr>
            <?php endwhile; ?>        
        </tbody>
    </table>

    <div style="margin-top: 20px;">

    </div>
</div>
</div>

<?php require_once 'Includes/templates/footer.php'; ?>
