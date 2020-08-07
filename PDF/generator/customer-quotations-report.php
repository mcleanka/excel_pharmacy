<?php
//START MY SCRIPTS
require '../../init.php';
if(!isset($_GET['customer_id'])){
	die("No Selection");
}
$year = $_GET['year'];
$month = $_GET['month'];
$id    = $_GET['customer_id'];
$quotation_id    = $_GET['quotation_id'];
$organisation    = $_GET['organisation'];
$dateToday = date('d M, Y');
$data_array=[];

$qry = "SELECT tbl_quotation.quotation_id, tbl_quotation.quotation_date, tbl_user.first_name AS userfname, tbl_user.last_name AS usersname, tbl_q_customer.full_name, tbl_q_customer.organisation, (SELECT count(*) FROM tbl_quotation_items WHERE tbl_quotation_items.quotation_id = tbl_quotation.quotation_id) AS items,tbl_quotation.amount_total FROM tbl_quotation, tbl_user, tbl_q_customer WHERE tbl_q_customer.organisation = '$organisation' AND tbl_quotation.user_id = tbl_user.user_id AND tbl_quotation.customer_id = tbl_q_customer.customer_id AND tbl_q_customer.customer_id = $id AND YEAR(tbl_quotation.quotation_date) = '$year' AND MONTH(tbl_quotation.quotation_date) = '$month' ORDER BY tbl_quotation.quotation_date DESC;";

$res = $db->query($qry);
while ($row = mysqli_fetch_assoc($res)){
	$date = $row['quotation_date'];
	$customer = $row['full_name'];
	$amount_total = $row['amount_total'];
	$quotation_id 	    = $row['quotation_id'];
	$organisation = $row['organisation'];
	$items = $row['items'];
	$array = [$date, $quotation_id, $items ,$amount_total];
	array_push($data_array, $array);
}

$qry = "SELECT sum(amount_total) AS total FROM tbl_quotation WHERE tbl_quotation.customer_id = '$id' AND tbl_quotation.quotation_id = '$quotation_id' AND YEAR(quotation_date) = '$year' AND MONTH(quotation_date)='$month'";
$res = $db->query($qry) or die($qry);
while ($row = mysqli_fetch_assoc($res)) {
	$total = $row['total'];
}
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
		$w = array(45, 65, 45, 30);
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
			$this->Cell($w[0], 6, $row[0], 'LR', 0, 'C', $fill);
			$this->Cell($w[1], 6, $row[1], 'LR', 0, 'C', $fill);
			$this->Cell($w[2], 6, $row[2], 'LR', 0, 'C', $fill);
			$this->Cell($w[3], 6, "K".number_format($row[3]), 'LR', 0, 'C', $fill);
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
$pdf->SetAuthor(get_user_name());
$pdf->SetTitle('Quotation Report');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData("citi_logo.jpg", 15, "Citipharm", "Haile Selassie Road\nP.O. Box 731, Blantyre, Malawi +265 1 826 719 / +265 999 079 818");

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
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
$pdf->SetFont('helvetica', '', 11);

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
$dmonth = get_month_name($month);
$a_amount_total = number_format($total);

$html = <<<EOF
<!-- EXAMPLE OF CSS STYLE -->
<style>
	h2 {
		font-family: arial;
		font-size: 16pt;
		text-align:center;
		text-transform: uppercase;
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

<h2>$organisation QUOTATION</h2>
<br/>
<table>
	<tr>
		<td width="360">
			<table>
				<tr>
					<td align="left" width="80"><b>C/NAME: </b></td>
					<td width="280">$customer</td>
				</tr>
				
			</table>
		</td>
		<td align="right" width="220"><b>DATE: </b><span>$dateToday</span></td>
	</tr>
</table>
<br><br>
EOF;

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');



$pdf->SetFont('times', '', 10);
// column titles
$header = array('QUOTATION DATE', 'QUOTATION ID', 'NO PRODUCTS','TOTAL');

// print colored table
$pdf->ColoredTable($header, $data_array);



$html = <<<EOF
<!-- EXAMPLE OF CSS STYLE -->
<style>
	h2 {
		font-family: arial;
		font-size: 16pt;
		text-align:center;
	}
	td {
		border: 1px solid black;
		background-color: #ffffee;
	}
</style>

<h2></h2>

<table cellpadding="5" width = "77%">
<tr>
	<td width = "67%"></td>
	<td ><b>Total Amount</b></td>
	<td>MWK $a_amount_total</td>
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
$pdf->Output($organisation."_QUOTATION_REPORT_FOR".$dmonth."_".$year.".pdf", 'I');



//============================================================+
// END OF FILE
//============================================================+