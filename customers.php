<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "customers.php";
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
                swal("Invalid Selection", "Please Select a Customer", "error");
            }
            else{
                window.location.href='customers-edit.php?id=' + item;
            }
        });

        $("#info_row").click(function(){
            item = $(".selected").find("#row_id").text();
            if(item==''){
                swal("Invalid Selection", "Please Select a Customer", "error");
            }
            else{
                window.location.href='PDF/generator/all-purchases-report.php?id=' + item;
            }
        });


       $("#delete_row").click(function(){
            item = $(".selected").find("#row_id").text();
            if(item==''){
                swal("Invalid Selection", "Please Select a Customer", "error");
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
                    $.post('Ajax/delete_customer.php', {item: item}, function(data){
                        if(data=='success'){;
                            swal({
                                title: 'Action Completed Successfully',
                                text: 'The Customer has Been Removed',
                                type: 'success'
                            }, function(){
                                window.location.href='customers.php';
                            });
                        }
                        else{
                            swal("Error", "Customer not Deleted", "error");
                        }
                    });
                });
            }
       });


   });
</script>
<div class="container" id="main-content">
<div class="header">
    <label class="header-name">Customers List</label>
    <label class="header-name pull-right"><span id='Time'> </span></label>&nbsp;
    <hr>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="action-btn mb-3">
            <a href="customers-add.php" class="btn btn-primary">Add Customer</a>
            <button id="edit_row" class="btn btn-info">Edit Customer</button>
            <button id="delete_row" class="btn btn-danger">Delete Customer</button>
            <script type="text/javascript">
                $(document).ready(function(){
                    $("#cb").change(function(){
                        var status = $("#cb").val();
                        if ($("#cb").is(":checked")) {
                            window.location.href='customers.php?masm=on';
                        }
                        else{
                            window.location.href='customers.php';
                        }
                    });
                });
            </script>
        </div>
        
        <table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Organisation</th>
                    <th>Location</th>
                    <th>First Name</th>
                    <th>Surname</th>                    
                    <th>Contact</th>
                    <th>Email</th>
                    <th>National ID</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                    $res = $db->query("SELECT * FROM tbl_customer WHERE state='active'");
                    while ($row = mysqli_fetch_assoc($res)):
                    $customer_id = $row['customer_id'];
                    $firstname = $row['c_fname'];
                    $surname = $row['c_lname'];
                    $phone = $row['phone_number'];
                    $email = $row['email_address']; 
                    $org_name = $row['org_name'];
                    $org_location = $row['org_location'];
                    $national_id = $row['national_id'];
                ?>
                    <tr>
                        <td id="row_id"><?php echo $customer_id; ?></td>
                        <td><?php echo $org_name; ?></td>
                        <td><?php echo $org_location; ?></td>
                        <td><?php echo $firstname; ?></td>
                        <td><?php echo $surname; ?></td>
                        <td><?php echo $phone; ?></td>
                        <td><?php echo $email; ?></td>
                        <td><?php echo $national_id; ?></td>
                        
                    </tr>
            <?php endwhile; ?>        
        </tbody>
    </table>

    <div style="margin-top: 20px;">
</div>

<?php require_once 'Includes/templates/footer.php'; ?>
