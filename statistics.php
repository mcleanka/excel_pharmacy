<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "statistics.php";
require_once 'Includes/templates/header.php';



$year   = isset($_GET['year']) ? $_GET['year'] : date('Y');
$month  = isset($_GET['month']) ? $_GET['month'] : str_replace("0", "", date('m'));



?>

<script type="text/javascript">
	$(document).ready(function(){
        var table = $('#example').DataTable({
            "order": [[ 0, "desc" ]]
        });

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
        $("#info_row").click(function(){
            drug = $(".selected").find("#row_id").text();
            if(drug==''){
                swal("Invalid Selection", "Please Select a Sale Record", "error");
            }
            else{
                window.location.href='sale-details.php?id=' + drug;
            }
        });

        $("#download_row").click(function(){
            drug = $(".selected").find("#row_id").text();
            if(drug==''){
                swal("Invalid Selection", "Please Select a Sale Record", "error");
            }
            else{
                window.location.href='PDF/generator/receipt.php?id=' + drug;
            }
        });
   });
</script>
<div class="container" id="main-content">
<div class="header">
    <label class="header-name">Sales Statistics For : <?php echo get_month_name($month).', '.$year ?></label>
    <span class="pull-right mt-0 ml-2">
        <a href="new-sale.php" style="width: 110px;" class="btn btn-primary">New Sale</a>
    </span>
    <span class="mr-2 pull-right">
        <form class="form-inline" role="form"> 
            <span class="filter">Filter</span> 
            <div class="form-group">  
                <label class="" for="name">Year</label>  
                <select class="form-control syear" name="year">
                    <?php 
                    $currentyr = date('Y');
                    for($i=$currentyr;$i>2001;$i--):
                    ?>
                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                    <?php endfor; ?>
                </select>

                <script type="text/javascript">
                    $(".syear > option[value=<?php echo $year ?>]").attr("selected","true");
                </script>
            </div>

            <div class="form-group"> 
                <label class="" for="name" style="margin-left: 10px;">Month</label>  
                <select class="form-control smonth" name="month">
                    <option value="1">JAN</option>
                    <option value="2">FEB</option>
                    <option value="3">MAR</option>
                    <option value="4">APR</option>
                    <option value="5">MAY</option>
                    <option value="6">JUN</option>
                    <option value="7">JUL</option>
                    <option value="8">AUG</option>
                    <option value="9">SEP</option>
                    <option value="10">OCT</option>
                    <option value="11">NOV</option>
                    <option value="12">DEC</option>
                </select>

                <script type="text/javascript">
                    $(".smonth > option[value=<?php echo $month ?>]").attr("selected","true");
                </script>
            </div>
            <button type="submit" class="btn btn-info">Go!</button>
            <input type="hidden" value="<?php echo $year ?>" id="post_year"/>
            <input type="hidden" value="<?php echo $month ?>" id="post_month"/>  
        </form>
    </span>
    <hr>
</div>

<div class="row">
	<div class="col-xs-12">
        <div class="action-btn pb-3"> 
            <a href="PDF/generator/statistics2.php?year=<?php echo $year ?>&month=<?php echo $month?>" class="btn btn-info">Print Monthly Sales Report</a>
            <a href="PDF/generator/statistics.php?year=<?php echo $year ?>" class="btn btn-primary">Print Yearly Sales Report</a>
			
			
	   </div>
       <table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Product Code</th>
                    <th>Quantity Sold</th>
                    
                </tr>
            </thead>

            <tbody>
                <?php
                $qry = " SELECT a.name, a.code, b.sale_id,b.quantity,c.sale_id,c.sale_date from tbl_drug a, tbl_sale_drug b, tbl_sale c where a.drug_id = b.drug_id and b.sale_id = c.sale_id and c.sale_date between '$year-$month-01' and '$year-$month-31'";
                $res = $db->query($qry);
                while ($row = mysqli_fetch_assoc($res)):
                	$drugname 	= $row['name'];
                	$code 		= $row['code'];
                	$quantity	= $row['quantity'];
					
                ?>
                <tr>
                    
                    <td><?php echo $drugname; ?></td>
                    <td><?php echo $code; ?></td>
                    <td><?php echo $quantity ?></td>
                    
                </tr>
            <?php endwhile; ?>        
        </tbody>
    </table>
</div>
</div>
<div class="header">
    <label class="header-name text-center" id="drug-stats">Graphical Product Sales Statistics</label>
    <hr>
</div>

<div class="row">
    <div class="top-form">
	   <div class="col-xs-12">
			<form role="form" class="form-horizontal" id="top-form" autocomplete="off" onsubmit="return false;">
					<div class="col-xs-10 pb-3">
						<div class="form-group">
							<input type="text" class="form-control" id="drugname" name="drugname" placeholder="Enter Product Name" value="<?php if(isset($_POST['name'])){ echo $_POST['name']; } ?>">
						</div>
					</div>

					<div class="col-xs-2">
						<button id="add" class="btn btn-info" style="">Get Statistics</button>
					</div>
			</form>
		</div>
	</div>
</div>
	<?php 
		if(isset($_GET['drugid'])):
			$drugid = $_GET['drugid'];
			$res = $db->query("SELECT name FROM tbl_drug WHERE drug_id=$drugid");
			while ($row = mysqli_fetch_assoc($res)) {
				$drugname = $row['name'];
			}
	?>
	<div class="row">
		<div class="col-xs-12">
			<div id="line-chart"></div>
			<script type="text/javascript">

				var line01 = <?php echo get_line_chart_data($year, 1, $drugid); ?>;
				var line02 = <?php echo get_line_chart_data($year, 2, $drugid); ?>;
				var line03 = <?php echo get_line_chart_data($year, 3, $drugid); ?>;
				var line04 = <?php echo get_line_chart_data($year,4,$drugid); ?>;
				var line05 = <?php echo get_line_chart_data($year,5,$drugid); ?>;
				var line06 = <?php echo get_line_chart_data($year,6,$drugid); ?>;
				var line07 = <?php echo get_line_chart_data($year,7,$drugid); ?>;
				var line08 = <?php echo get_line_chart_data($year,8,$drugid); ?>;
				var line09 = <?php echo get_line_chart_data($year,9,$drugid); ?>;
				var line10 = <?php echo get_line_chart_data($year,10,$drugid); ?>;
				var line11 = <?php echo get_line_chart_data($year,11,$drugid); ?>;
				var line12 = <?php echo get_line_chart_data($year,12,$drugid); ?>;
				$(function () {
					$('#line-chart').highcharts({
						title: {
							text: 'Monthly Sales: <?php echo $drugname; ?>',
				            x: -20 //center
				        },
				        xAxis: {
				        	categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
				        	'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
				        },
				        yAxis: {
				        	title: {
				        		text: 'Number of Units Sold'
				        	},
				        	plotLines: [{
				        		value: 0,
				        		width: 2,
				        		color: '#808080'
				        	}]
				        },
				        legend: {
				        	layout: 'vertical',
				        	align: 'right',
				        	verticalAlign: 'middle',
				        	borderWidth: 0
				        },
				        series: [{
				        	name: 'Sales',
				        	data: [line01,line02,line03,line04,line05,line06,line07,line08,line09,line10,line11,line12]
				        }]
				    });
				});
			</script>
		</div>
	</div>
</div>
<?php endif; ?>
<div style="margin-bottom: 20px;"></div>
<script type="text/javascript">
	$(document).ready(function(){
		$(function() {
			$.get('Ajax/alldrugs.php', {drugname: "set"}, function(data){
				var list=data;
				$("#drugname").autocomplete({
					source:[list]
				}); 
			});	
		});
		$("#add").click(function(){
			var dname = $("#drugname").val();			
			$.get('Ajax/getdrug.php', {drugname: dname}, function(data){
				if(data=="[]"){
					swal("Product Name Error", "The product name cannot be found in the database", "error");
				}
				else{
					var obj=JSON.parse(data);
					var drugid 		= obj[0].drug_id;
					
					window.location.href='statistics.php?drugid='+drugid+'#drug-stats';
				}				
			});			
		});
	});
</script>

<?php require_once 'Includes/templates/footer.php'; ?>