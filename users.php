<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "users.php";
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
                swal("Invalid Selection", "Please Select a User", "error");
            }
            else{
                window.location.href='users-edit.php?id=' + item;
            }
        });


       $("#delete_row").click(function(){
            item = $(".selected").find("#row_id").text();
            if(item==''){
                swal("Invalid Selection", "Please Select a User", "error");
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
                    $.post('Ajax/delete_user.php', {item: item}, function(data){
                        if(data=='success'){;
                            swal({
                                title: 'Action Completed Successfully',
                                text: 'The User has Been Removed',
                                type: 'success'
                            }, function(){
                                window.location.href='users.php';
                            });
                        }
                        else{
                            swal("Error", "User not Deleted", "error");
                        }
                    });
                });
            }
       });

       $("#reset_row").click(function(){
            item = $(".selected").find("#row_id").text();
            if(item==''){
                swal("Invalid Selection", "Please Select a User", "error");
            }
            else{
                swal({
                  title: "Confirm Password Reset",
                  text: "Are you sure you want to Reset Password for this account?",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "Delete",
                  closeOnConfirm: false
                },
                function(){
                    $.post('Ajax/reset_password.php', {item: item}, function(data){
                        if(data=='success'){;
                            swal({
                                title: 'Password Reset Successfully',
                                text: 'The Password has Been Resetted',
                                type: 'success'
                            }, function(){
                                window.location.href='users.php';
                            });
                        }
                        else{
                            swal("Error", "User not Deleted", "error");
                        }
                    });
                });
            }
       });


   });
</script>
<div class="container" id="main-content">
    <div class="header">
        <label class="header-name">System Users</label>
        <a href="users-add.php" style="width: 110px;" class="btn btn-primary mr-0 pull-right">Add User</a>
        <hr>
    </div>
<div class="row">
    <div class="col-xs-12">
        <div class="action-btn pb-3">
           
           <button id="edit_row" class="btn btn-info">Edit User</button>
           <button style="width: 130px;" id="reset_row" class="btn btn-warning">Reset Password</button>
           <button id="delete_row" class="btn btn-danger">Delete User</button>
        </div>
        
        <table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Surname</th>
                    <th>Username</th>
                    <th>Account Type</th>
                    <th>Shop</th>                    
                    <th>Contact</th>
                    <th>Email</th>                    
                    <th>Last Login</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                $res = $db->query("SELECT tbl_user.*,tbl_shop.name AS sname FROM tbl_user,tbl_shop WHERE tbl_user.shop=tbl_shop.shop_id AND tbl_user.state='ACTIVE'");                
                while ($row = mysqli_fetch_assoc($res)):
                $user_id = $row['user_id'];
                $firstname = $row['first_name'];
                $surname = $row['last_name'];
                $username = $row['username'];
                $acctype = $row['account_type'];
                $shopname = $row['sname'];
                $phone = $row['phone_number'];
                $email = $row['email_address'];
                $lastlogin = date('jS F, Y h:i a', strtotime($row['last_login']));
                ?>
                <tr>
                    <td id="row_id"><?php echo $user_id; ?></td>
                    <td style="text-transform: uppercase;"><?php echo $firstname; ?></td>
                    <td style="text-transform: uppercase;"><?php echo $surname; ?></td>
                    <td><?php echo $username; ?></td>
                    <td style="text-transform: uppercase;"><?php echo $acctype; ?></td>
                    <td style="text-transform: uppercase;"><?php echo $shopname; ?></td>
                    <td><?php echo $phone; ?></td>
                    <td><?php echo $email; ?></td>
                    <td><?php echo $lastlogin; ?></td>
                    
                </tr>
            <?php endwhile; ?>        
        </tbody>
    </table>

    <div style="margin-top: 20px;">

    </div>
</div>
</div>

<?php require_once 'Includes/templates/footer.php'; ?>
