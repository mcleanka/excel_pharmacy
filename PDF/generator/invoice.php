<?php
//START MY SCRIPTS
require '../../init.php';
if(!isset($_GET['id'])){
	die("No Selection");
}
$id = $_GET['id'];

$res = $db->query("SELECT * FROM tbl_payment WHERE payment_id=$id");
while($row=mysqli_fetch_assoc($res)){
	$purchase_id 	= $row['purchase_id'];
	$payment_date	=  date('jS F, Y', strtotime($row['payment_date']));
	$payment_amount = $row['amount_paid'];
}
$res = $db->query("SELECT tbl_supplier.name AS suppliername, tbl_supplier.email_address, tbl_supplier.phone_number,tbl_purchase.amount_total FROM tbl_purchase,tbl_supplier WHERE tbl_purchase.supplier_id = tbl_supplier.supplier_id AND purchase_id=$purchase_id");
while ($row = mysqli_fetch_assoc($res)) {
	$suppliername 	= strtoupper($row['suppliername']);
	$email_address	= $row['email_address'];
	$phone_number 	= $row['phone_number'];
}
$dpay = number_format($payment_amount);

//END MY SCRIPTS
require_once('tcpdf_include.php');

class MYPDF extends TCPDF {

	// Load table data from file
	public function LoadData($file) {
		// Read file lines
		$lines = file($file);
		$data = array();
		foreach($lines as $line) {
			$data[] = explode(';', chop($line));
		}
		return $data;
	}

	// Colored table
	public function ColoredTable($header,$data) {
		// Colors, line width and bold font
		$this->SetFillColor(0, 150, 136);
		$this->SetTextColor(255);
		$this->SetDrawColor(0, 77, 64);
		$this->SetLineWidth(0.3);
		$this->SetFont('', 'B');
		// Header
		$w = array(105, 25, 20, 30);
		$num_headers = count($header);
		for($i = 0; $i < $num_headers; ++$i) {
			$this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
		}
		$this->Ln();
		// Color and font restoration
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetFont('');
		// Data
		$fill = 0;
		foreach($data as $row) {
			$this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
			$this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
			$this->Cell($w[2], 6, $row[2], 'LR', 0, 'R', $fill);
			$this->Cell($w[3], 6, $row[3], 'LR', 0, 'L', $fill);
			$this->Ln();
			$fill=!$fill;
		}
		$this->Cell(array_sum($w), 0, '', 'T');
	}
}
// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Yamikani Kalinde');
$pdf->SetTitle('MASM Claims Report');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData("citi_logo.jpg", 15, "CITIPHARM PHARMACEUTICALS", "Churchill Road, Realty House, Limbe\nP.O. Box 195, Blantyre, Malawi +265 888 920 930 / +265 999 920 930");

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(23, PDF_MARGIN_TOP, 23);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 12);

// add a page
$pdf->AddPage();

/* NOTE:
 * *********************************************************
 * You can load external XHTML using :
 *
 * $html = file_get_contents('/path/to/your/file.html');
 *
 * External CSS files will be automatically loaded.
 * Sometimes you need to fix the path of the external CSS.
 * *********************************************************
 */

// define some HTML content with style
$html = <<<EOF
<!-- EXAMPLE OF CSS STYLE -->
<style>
	h2 {
		font-family: arial;
		font-size: 16pt;
		text-align:center;
	}
	table.withb td{
		border: 1px solid black;
		background-color: #ffffee;
	}
	table.withb{
		padding:5px;
	}
	.underline{
		text-decoration: underline;
	}
</style>

<h2>PAYMENT RECEIPT</h2>
<br/>
<table>
	<tr>
		<td width="360">
			<table>
				<tr>
					<td align="left" width="80"><b>SUPPLIER: </b></td>
					<td width="280"><b>$suppliername</b><br><i>$email_address / $phone_number</i></td>
				</tr>	

				<tr>
					<td align="left" width="80"><b>AMOUNT: </b></td>
					<td width="280"><b>K$dpay</b></td>
				</tr>			
			</table>
		</td>
		<td align="right" width="220"><b>PAYMENT DATE: </b><span>$payment_date</span></td>
	</tr>
</table>
<br><br>

EOF;
$pdf->SetFont('helvetica', '', 10);
$html.=<<<EOF

<table class="withb">
	<tr>
		<td width="330"><b>DESCRIPTION</b></td>
		<td width="90"><b>UNIT PRICE</b></td>
		<td width="80"><b>QUANTITY</b></td>
		<td width="90"><b>LINE TOTAL</b></td>

	</tr>
EOF;

$purchaset = 0;
$res = $db->query("SELECT tbl_drug.name, tbl_drug.code, tbl_purchase_item.* FROM tbl_purchase_item,tbl_drug WHERE tbl_purchase_item.drug_id=tbl_drug.drug_id AND tbl_purchase_item.purchase_id=$purchase_id");

while ($row = mysqli_fetch_assoc($res)):
$dname 		= $row['name'];
$dcode 		= $row['code'];
$price 		= $row['unit_price'];
$quantity 	= $row['quantity'];
$linetotal	= $price*$quantity;
$purchaset 	+= $linetotal;


$html.="
	<tr>
		<td width='330'>$dname</td>
		<td width='90' align='right'>K".number_format($price)."</td>
		<td width='80' align='right'>$quantity</td>
		<td width='90' align='right'>K".number_format($linetotal)."</td>
	</tr>
";

endwhile;

$ptotal = number_format($purchaset);

$res = $db->query("SELECT sum(amount_paid) AS paid FROM tbl_payment WHERE purchase_id=$purchase_id AND payment_id<=$id");
while ($row = mysqli_fetch_assoc($res)) {
	$paid = $row['paid'];
}
$balance = number_format($purchaset - $paid);
$paid = number_format($paid);

$html.=<<<EOF
<tr>
	<td width="330" align="right"></td>
	<td width='90' align='right'></td>
	<td width='80' align='right'><b>TOTAL</b></td>
	<td width="90"><b>K$ptotal</b></td>
</tr>
</table>
<br><br>

<table>
	<tr>
		<td align="left"><b>TOTAL PAYMENTS MADE: </b><span>K$paid</span></td>
		<td align="right"><b>OUTSTANDING BALANCE: </b><span>K$balance</span></td>
	</tr>
</table>
EOF;



// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// ---------------------------------------------------------

// close and output PDF document


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

$pdf->lastPage();

//Close and output PDF document
$currentdate = date('Y_m_d');
$pdf->Output("PAYMENT_RECEIPT_".$currentdate.".pdf", 'D');



//============================================================+
// END OF FILE
//============================================================+