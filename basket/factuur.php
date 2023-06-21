<?php
session_start();
require "../pdf/fpdf.php";
require "../database_conn.php";

class myPDF extends FPDF
{
	function header()
	{
		$reservering = $_GET['reservering'];
		$date = date("d/m/Y");
		$this->Image('../logo.png', 5, 3, 35, 35);
		$this->SetFont('Arial', 'B', 14);
		$this->Cell(100, 5, 'Excellent Taste', 0, 0, 'C');
		$this->Ln();
		$this->SetFont('Times', '', 12);
		$this->Cell(101, 10, 'Maalderij 37, 1185 ZC Amstelveen', 0, 0, 'C');
		$this->Cell(-140, 22, "Tafel id :  $reservering", 0, 0, 'C');
		$this->Cell(200, 22, "Datum : $date", 0, 0, 'C');
		$this->Ln(25);
	}
	function footer()
	{
		$this->SetY(-15);
		$this->SetFont('Arial', '', 0);
		$this->Cell(0, 10, 'Page' . $this->PageNo() . '/{nb}', 0, 0, 'C');
	}
	function headerTable()
	{
		$this->SetFont('Times', 'B', 12, 'C');
		$this->Cell(50, 10, 'Gerecht naam', 1, 0, 'C');
		$this->Cell(50, 10, 'Gerecht prijs ', 1, 0, 'C');
		$this->Ln();
	}
	function viewTable($db_conn)
	{
		$this->SetFont('Times', '', 12);
		$reservering = $_GET['reservering'];
		$stmt = $db_conn->query("SELECT * from menu INNER JOIN menulijst ON menu.menu_id = menulijst.menu_id WHERE menulijst.tafel_id = $reservering ");
		while ($data = $stmt->fetch(PDO::FETCH_OBJ)) {

			$this->Cell(50, 10, $data->gerecht_naam, 1, 0, 'C');
			$this->Cell(50, 10, "$ $data->gerecht_prijs", 1, 0, 'C');
			$this->Ln();
		}
	}
	function SUMbtw($db_conn)
	{
		$reservering = $_GET['reservering'];
		$stmt = $db_conn->query("SELECT SUM(gerecht_prijs) as `prijs` from menu INNER JOIN menulijst ON menu.menu_id = menulijst.menu_id WHERE menulijst.tafel_id = $reservering ");
		while ($data = $stmt->fetch(PDO::FETCH_OBJ)) {
			$BTW = $data->prijs * 21 / 100;
			$this->Cell(100, 10, "BTW : $ $BTW", 0, 1);
		}
	}
	function SUM($db_conn)
	{
		$reservering = $_GET['reservering'];
		$stmt = $db_conn->query("SELECT SUM(gerecht_prijs) as `prijs` from menu INNER JOIN menulijst ON menu.menu_id = menulijst.menu_id WHERE menulijst.tafel_id = $reservering ");
		while ($data2 = $stmt->fetch(PDO::FETCH_OBJ)) {
			$BTW = $data2->prijs * 21 / 100;
			$TOTAL = $BTW + $data2->prijs;
			$this->Cell(100, 10, "Totaal (Inc BTW) : $ $TOTAL", 'B', 0, 1);
		}
	}
}

$pdf = new myPDF();
$pdf->AliasNbPages();
$pdf->SetLeftMargin(100);
$pdf->AddPage('C', 'A4', 0);
$pdf->headerTable();
$pdf->viewTable($db_conn);
$pdf->SUMbtw($db_conn);
$pdf->SUM($db_conn);
$pdf->Output();
