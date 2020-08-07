<?php
//START MY SCRIPTS
require '../../init.php';
if(!isset($_GET['id'])){
	die("No Selection");
}
$id = $_GET['id'];

$data_array=[];

$qry = "SELECT tbl_sale.sale_id,tbl_sale.sale_date, tbl_customer.c_fname AS fname, tbl_customer.c_lname AS sname (SELECT count(*) FROM tbl_sale_drug WHERE tbl_sale_drug.sale_id = tbl_sale.sale_id) AS items, tbl_sale.amount_total FROM tbl_sale,tbl_customer WHERE tbl_sale.customer_id = '$id' AND tbl_customer.customer_id = '$id' ";
			
$res = $db->query($qry);

while ($row = mysqli_fetch_assoc($res)){
	$date = $row['sale_date'];
	$newdate = date("d, M, Y", strtotime($date));
	$customer = $row['fname'].' '.$row['sname'];
	$saleType = $row['sale_type'];
	$amount_total = $row['amount_total'];
	$array = [$newdate,$amount_total, $saleType];
	array_push($data_array, $array);
}

$qry = "SELECT sum(amount_total) AS total FROM tbl_sale WHERE customer_id = $id ";
$res = $db->query($qry) or die($qry);
while ($row = mysqli_fetch_assoc($res)) {
	$total = $row['total'];
}
//END MY SCRIPTS

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
		$w = array(40,45,43);
		$num_headers = count($header);
		for($i = 0; $i < $num_headers; ++$i) {
			$this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1); //table header outputz
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
			//$this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
			//$this->Cell($w[2], 6, $row[2], 'LR', 0, 'C', $fill);
			$this->Cell($w[1], 6, "K".number_format($row[1]), 'LR', 0, 'C', $fill);
			$this->Cell($w[3], 6, $row[6], 'LR', 0, 'C', $fill);
			// $this->Cell($w[2], 6, "K".number_format($row[2]), 'LR', 0, 'C', $fill);
			//$this->Cell($w[6], 6, "K".number_format($row[5]), 'LR', 0, 'R', $fill);
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
$pdf->SetTitle('Purchase Claims Report');
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


$qry = "SELECT * from tbl_customer where customer_id = $id"; 
$res = $db->query($qry);
$row = mysqli_fetch_assoc($res);
$customer = strtoupper($row['first_name']." ".$row['last_name']);

 
$a_amount_total = number_format($total);
// $a_discount = number_format($discount);
// $a_shortfall = number_format($total-$discount);
$html = <<<EOF
<!-- EXAMPLE OF CSS STYLE -->
<style>
	h2 {
		font-family: arial;
		font-size: 16pt;
		text-align:left;
		margin-bottom:50px;
	}
	td {
		border: 1px solid black;
		background-color: #ffffee;
	}
</style>

<h2 style="margin-bottom:50px">SALES REPORT FOR $customer &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h2>
<h2 style="margin-bottom:50px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h2>



EOF;

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->SetFont('times', '', 11);

// column titles
$header = array('SALE DATE','SALE TOTAL','ALL PURCHASES');

// print colored table
$pdf->ColoredTable($header, $data_array);




$qry = "SELECT * from tbl_customer where customer_id = $id"; 
$res = $db->query($qry);
$row = mysqli_fetch_assoc($res);
$customer = strtoupper($row['first_name']." ".$row['last_name']);

 
$a_amount_total = number_format($total);
$a_discount = number_format($discount);
$a_shortfall = number_format($total-$discount);
$html = <<<EOF
<!-- EXAMPLE OF CSS STYLE -->
<style>
	h2 {
		font-family: arial;
		font-size: 16pt;
		text-align:center;
		margin-bottom:50px;
	}
	td {
		border: 1px solid black;
		background-color: #ffffee;
	}
</style>

<h2 style="margin-bottom:50px"> </h2>

<table cellpadding="5" width = "250">
<tr>
	<td width = "60%"><b>Total Sales Amount</b></td>
	<td>MWK $a_amount_total</td>
</tr>
</table>

EOF;

$pdf->writeHTML($html, true, false, true, false, '');
// ---------------------------------------------------------

// close and output PDF document


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

$pdf->lastPage();

//Close and output PDF document
$pdf->Output("SALES_REPORT_".$customer.".pdf", 'I');



//============================================================+
// END OF FILE
//============================================================+