<?php
//START MY SCRIPTS
require '../../init.php';
$year = $_GET['year'];
$month = $_GET['month'];

$data_array=[];

$qry = "select tbl_masm_claim.state, tbl_sale.sale_date, tbl_sale.sale_id, tbl_customer.first_name, tbl_customer.last_name, tbl_customer.masm_id,tbl_sale.amount_total,tbl_sale.discount_award from tbl_sale,tbl_masm_claim,tbl_customer WHERE tbl_masm_claim.sale_id=tbl_sale.sale_id AND tbl_sale.customer_id=tbl_customer.customer_id AND YEAR(tbl_masm_claim.added_on)='$year' AND MONTH(tbl_masm_claim.added_on)='$month';";
$res = $db->query($qry);

while ($row = mysqli_fetch_assoc($res)){
	$date = $row['sale_date'];
	$customer = $row['first_name'].' '.$row['last_name'];
	$masm_id = $row['masm_id'];
	$amount_total = $row['amount_total'];
	$discount_award = $row['discount_award'];
	$array = [$date,$customer,$masm_id,$amount_total,$discount_award,($amount_total-$discount_award)];
	array_push($data_array, $array);
}
$qry = "SELECT sum(tbl_masm_claim.amount) AS discount, sum(tbl_sale.amount_total) AS total FROM tbl_sale,tbl_masm_claim WHERE tbl_sale.sale_id=tbl_masm_claim.sale_id AND YEAR(added_on)='$year' AND MONTH(added_on)='$month'";
$res = $db->query($qry) or die($qry);
while ($row = mysqli_fetch_assoc($res)) {
	$total = $row['total'];
	$discount = $row['discount'];
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
		$w = array(25, 49, 25, 27, 27,27);
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
			$this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
			$this->Cell($w[2], 6, $row[2], 'LR', 0, 'C', $fill);
			$this->Cell($w[3], 6, "K".number_format($row[3]), 'LR', 0, 'R', $fill);
			$this->Cell($w[4], 6, "K".number_format($row[4]), 'LR', 0, 'R', $fill);
			$this->Cell($w[4], 6, "K".number_format($row[5]), 'LR', 0, 'R', $fill);
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
$pdf->SetHeaderData("citi_logo.jpg", 15, "Pharmaworld PHARMACEUTICALS", "Churchill Road, Realty House, Limbe\nP.O. Box 195, Blantyre, Malawi +265 888 920 930 / +265 999 920 930");

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
$pdf->SetFont('helvetica', '', 10);

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
$a_discount = number_format($discount);
$a_shortfall = number_format($total-$discount);
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

<h2>Pharmaworld DISCOUNT CLAIMS REPORT FOR $dmonth, $year</h2>

<table cellpadding="5">
<tr>
	<td><b>Aggregate Sales Amount Total</b></td>
	<td>MWK $a_amount_total</td>
</tr>

<tr>
	<td><b>Aggregate Discount Awarded</b></td>
	<td>MWK $a_discount</td>
</tr>

<tr>
	<td><b>Aggregate Shortfall</b></td>
	<td>MWK $a_shortfall</td>
</tr>
</table>
EOF;

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->SetFont('helvetica', '', 11);

// column titles
$header = array('SALE DATE', 'CUSTOMER NAME', 'MASM ID', 'TOTAL','DISCOUNT','SHORTFALL');

// print colored table
$pdf->ColoredTable($header, $data_array);

// ---------------------------------------------------------

// close and output PDF document


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

$pdf->lastPage();

//Close and output PDF document
$pdf->Output("MASM_CLAIMS_".$dmonth."_".$year.".pdf", 'D');



//============================================================+
// END OF FILE
//============================================================+