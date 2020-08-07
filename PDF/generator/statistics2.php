<?php
//START MY SCRIPTS
require '../../init.php';
$year = $_GET['year'];
$month = $_GET['month'];


$data_array=[];

 $qry = "SELECT a.name, a.code, b.sale_id,b.quantity,c.sale_id,c.sale_date FROM tbl_drug a, tbl_sale_drug b, tbl_sale c WHERE a.drug_id = b.drug_id AND b.sale_id = c.sale_id AND c.sale_date BETWEEN '$year-$month-01' AND '$year-$month-31' ORDER BY b.quantity DESC";
 
$res = $db->query($qry);

while ($row = mysqli_fetch_assoc($res)){
	$drugname 	= $row['name'];
    $code 		= $row['code'];
	$quantity	= $row['quantity'];
	$array = [$drugname, $code, $quantity];
	array_push($data_array, $array);
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
		$w = array(55, 50, 50);
		$num_headers = count($header);
		for($i = 0; $i < $num_headers; ++$i) {
			$this->Cell($w[$i], 3, $header[$i], 1, 0, 'C', 1);
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
$pdf->SetTitle('Statistcs Monthly Report');
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

$html = <<<EOF
<!-- EXAMPLE OF CSS STYLE -->
<style>
	h2 {
		font-family: arial;
		font-size: 16pt;
		text-align:center;
		margin-bottom:20px;
	}
	td {
		border: 1px solid black;
		background-color: #ffffee;
	}
</style>

<h2>PRODUCT SALE STATISTICS FOR $dmonth, $year</h2>

EOF;

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->SetFont('times', '', 10);

// column titles
$header = array('PRODUCT NAME', 'PRODUCT CODE', 'TOTAL QUANTITY SOLD');

// print colored table
$pdf->ColoredTable($header, $data_array);

// ---------------------------------------------------------

// close and output PDF document


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

$pdf->lastPage();

//Close and output PDF document
$pdf->Output("DRUG_DEMAND_".$year.".pdf", 'D');



//============================================================+
// END OF FILE
//============================================================+