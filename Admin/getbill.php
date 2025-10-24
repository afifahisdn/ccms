<?php
require "assets/plugin/pdf/fpdf.php";
include "../server/api.php";

class PDF extends FPDF
{
    // Load data based on customer_id or tracking_id
    function BasicTable()
    {
        if (isset($_REQUEST["customer_id"])) {
            $data = getBill($_REQUEST["customer_id"]);
        } elseif (isset($_REQUEST["tracking_id"])) {
            $data = getBillByTracking($_REQUEST["tracking_id"]);
        } else {
            // If neither customer_id nor tracking_id is provided, exit
            die("Invalid request. Please provide either customer_id or tracking_id.");
        }

        $sum = 0;
        while ($row = mysqli_fetch_assoc($data)) {
            $sum++;

            // Logo
            $this->Image('assets/images/logo.png', 10, 10, 40);

            // Title
            $this->SetFont("Arial", "B", 16);
            $this->Cell(0, 10, "Courier Receipt", 0, 1, "C");
            $this->Ln(5);

            // Customer Details Section
            $this->SetFont("Arial", "", 12);
            $this->Cell(0, 10, "Requested by:", 0, 1);
            $this->SetDrawColor(200, 200, 200);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->SetFillColor(240, 240, 240);
            $this->Cell(40, 6, "Name:", 0, 0, "L", true);
            $this->Cell(0, 6, $row["name"], 0, 1);
            $this->Cell(40, 6, "Email:", 0, 0, "L", true);
            $this->Cell(0, 6, $row["email"], 0, 1);
            $this->Cell(40, 6, "Phone Number:", 0, 0, "L", true);
            $this->Cell(0, 6, $row["phone"], 0, 1);
            $this->Ln(5);

            // Courier Details Section
            $this->Cell(0, 10, "Courier Details", 0, 1);
            $this->SetDrawColor(200, 200, 200);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->SetFillColor(240, 240, 240);
            $this->Cell(40, 6, "Tracking ID:", 0, 0, "L", true);
            $this->Cell(0, 6, "#" . $row["tracking_id"], 0, 1);
            $this->Cell(40, 6, "Requested Date:", 0, 0, "L", true);
            $this->Cell(0, 6, $row["date_updated"], 0, 1);
            $this->Cell(40, 6, "Weight:", 0, 0, "L", true);
            $this->Cell(0, 6, $row["weight"] . " kg", 0, 1);
            $this->Ln(5);

            // Sender Details Section
            $this->Cell(0, 10, "Sender Details", 0, 1);
            $this->SetDrawColor(200, 200, 200);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->SetFillColor(240, 240, 240);
            $this->Cell(40, 6, "Sender Name:", 0, 0, "L", true);
            $this->Cell(0, 6, $row["sender_name"], 0, 1);
            $this->Cell(40, 6, "Sender Address:", 0, 0, "L", true);
            $this->MultiCell(0, 6, $row["sender_address"], 0, 1);
            $this->Cell(40, 6, "Sender Phone:", 0, 0, "L", true);
            $this->Cell(0, 6, $row["sender_phone"], 0, 1);
            $this->Ln(5);

            // Receiver Details Section
            $this->Cell(0, 10, "Receiver Details", 0, 1);
            $this->SetDrawColor(200, 200, 200);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->SetFillColor(240, 240, 240);
            $this->Cell(40, 6, "Receiver Name:", 0, 0, "L", true);
            $this->Cell(0, 6, $row["rec_name"], 0, 1);
            $this->Cell(40, 6, "Receiver Address:", 0, 0, "L", true);
            $this->MultiCell(0, 6, $row["rec_address"], 0, 1);
            $this->Cell(40, 6, "Receiver Phone:", 0, 0, "L", true);
            $this->Cell(0, 6, $row["rec_phone"], 0, 1);
            $this->Ln(5);

            // Total Fee
            $this->Cell(0, 10, "Total Fee: RM " . $row["total_fee"], 0, 1);

            // Separator line
            $this->SetDrawColor(200, 200, 200);
            $this->Line(10, $this->GetY() + 10, 200, $this->GetY() + 10);
            $this->Ln(10);
        }
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont("Arial", "", 12);
$pdf->BasicTable();
$pdf->Output();