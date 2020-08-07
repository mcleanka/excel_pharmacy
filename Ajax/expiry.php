<?php
  require_once '../init.php';
  $expDetails      = $_POST['expDetails'];

  if (isset($expDetails)) {
    $drug_id = $expDetails["drug_id"];
    $drug_name = $expDetails["drug_name"];
    $shop_name = $expDetails["shop_name"];
    $shop_id = $expDetails["shop_id"];

    $qry = "SELECT state, last_updated, tbl_purchase_item.expiry_date, tbl_purchase_item.quantity FROM tbl_stock JOIN  tbl_purchase_item ON tbl_purchase_item.drug_id = tbl_stock.drug_id WHERE tbl_stock.drug_id = $drug_id AND shop_id = $shop_id";
    $res = $db->query($qry);
    $content = ' <div class="table-responsive">
                  <table  class="table tObject table-condensed table-bordered table-striped table-hover table-sm table-small-font compact dataTable no-footer" id="objectGrid">

 <thead>'; 
    $content .= '<tr>';  
    $content .= '<th> Quantity </th>';
    $content .= '<th style="color:brown"> Expiry Date </th>';
    $content .= '<th> Date Purchased </th>';
    $content .= '<th> Status </th>';
    $content .= '</tr>';
    $content .= '<thead>';       
    $content .= '</tbody>';
    while ($row = mysqli_fetch_assoc($res)):
    $expDate = $row['expiry_date'];
    $quantity = $row['quantity'];
    $last_updated = $row['last_updated'];
    $state = $row['state'];
    $content .= renderTable($expDate,$quantity,$last_updated,$state);
    endwhile;
    $content .= '</tbody></th></table> </div>';
    showExpirydrugs($drug_name, $content);
  }


  function showExpirydrugs($drug_name, $content)
  {
    echo '<!-- Modal -->
  <div class="modal fade"  id="expiry" role="dialog" style = "border-radius:0px; z-index: 999999999999 !important">

  <div class="modal-dialog" style = "border-radius:0px; ">

  <!-- Modal content-->
  <div class="modal-content" style = "border-radius:0px;">

  <div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4>Expiry Date(s) for '.$drug_name.'</h4>
  <b class="text-info">Note: These are details of previous punchases of this drug</b>
  </div>
  <div class="modal-body">
    '.$content.'
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal" style = "border-radius:0px" >cancel</button>
  </div>

  </div>

  </div>

  </div>';
  }
  function renderTable($expDate,$quantity,$last_updated,$state)
  {
    return '
      <tr>
        <td>'.$quantity.' </td>  <td style="color:brown" > '.$expDate.'</td>  <td>'.$last_updated.' </td>  <td>'.$state.'</td> 

      </tr>
    ';
  }
?>