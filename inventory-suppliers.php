<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "inventory-purchases.php";
require_once 'Includes/templates/header.php';
?>

<script type="text/javascript">
    $(document).ready(function(){
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
        
        $("#edit_row").click(function(){
            item = $(".selected").find("#row_id").text();
            if(item==''){
                swal("Invalid Selection", "Please Select a Supplier", "error");
            }
            else{
                window.location.href='inventory-edit-supplier.php?id=' + item;
            }
        });

        $("#info_row").click(function(){
            item = $(".selected").find("#row_id").text();
            if(item==''){
                swal("Invalid Selection", "Please Select a Supplier", "error");
            }
            else{
                window.location.href='inventory-supplier-drug.php?id=' + item;
            }
        });


       $("#delete_row").click(function(){
            item = $(".selected").find("#row_id").text();
            if(item==''){
                swal("Invalid Selection", "Please Select a Supplier", "error");
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
                    $.post('Ajax/delete_supplier.php', {item: item}, function(data){
                        if(data=='success'){;
                            swal({
                                title: 'Action Completed Successfully',
                                text: 'The Supplier has Been Removed',
                                type: 'success'
                            }, function(){
                                window.location.href='inventory-suppliers.php';
                            });
                        }
                        else{
                            swal("Error", "Supplier not Deleted", "error");
                        }
                    });
                });
            }
       });


   });
</script>
<div class="container" id="main-content">
    <div class="header">
        <label class="header-name">Suppliers List</label>
        <span class="mr-0 pull-right">
            <?php include 'Includes/components/inventory-top-buttons.php'; ?>
        </span>
        <hr>
    </div>
<div class="row">
    <div class="col-xs-12">
        <div class="action-btn pb-3">
            <a href="inventory-add-supplier.php" class="btn btn-primary">Add Supplier</a>
           <button id="delete_row" class="btn btn-danger">Delete</button>
           <button id="edit_row" class="btn btn-info">Edit</button>
           <button id="info_row" class="btn btn-warning">Supplier Drugs</button>
        </div>
        
        <table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Email Address</th>
                    <th>Country</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                $res = $db->query("SELECT * FROM tbl_supplier WHERE state='active'");
                while ($row = mysqli_fetch_assoc($res)):
                $supplier_id = $row['supplier_id'];
                $name = $row['name'];
                $phone = $row['phone_number'];
                $email = $row['email_address'];
                $country = $row['country'];
                ?>
                <tr>
                    <td id="row_id"><?php echo $supplier_id; ?></td>
                    <td><?php echo $name; ?></td>
                    <td><?php echo $phone; ?></td>
                    <td><?php echo $email; ?></td>
                    <td><?php echo $country; ?></td>
                </tr>
            <?php endwhile; ?>        
        </tbody>
    </table>

    <div style="margin-top: 20px;">

    </div>
</div>
</div>

<?php require_once 'Includes/templates/footer.php'; ?>
