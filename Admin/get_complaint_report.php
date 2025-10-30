<?php
/*
* admin/get_complaint_report.php
*
* Generates a PDF report for a single complaint.
*/

// Include FPDF library and the API functions
require "assets/plugin/pdf/fpdf.php";
include "../server/api.php"; // Includes connection.php and get.php

// --- PDF Class Definition ---
class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        if (file_exists('assets/images/logo.png')) {
            $this->Image('assets/images/logo.png', 10, 6, 30);
        }
        // Arial bold 15
        $this->SetFont('Arial', 'B', 16);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30, 10, 'Complaint Report', 0, 1, 'C'); // Centered cell, new line
        // Line break
        $this->Ln(20);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Complaint Data Table
    function ComplaintDetails($complaint_id)
    {
        // Fetch data using the function from get.php
        $data_result = getComplaintReportData($complaint_id);

        if ($data_result && $row = mysqli_fetch_assoc($data_result)) {

            // --- Complaint Info ---
            $this->SetFont("Arial", "B", 12);
            $this->Cell(0, 8, "Complaint Information", 0, 1);
            $this->SetFont("Arial", "", 10);
            $this->SetFillColor(240, 240, 240); // Light grey background for labels

            $this->Cell(40, 7, "Complaint ID:", 0, 0, "L", true);
            $this->Cell(0, 7, "#" . htmlspecialchars($row["complaint_id"]), 0, 1);

            // Map status number to text
            $status_text = match ((int)$row['complaint_status']) {
                1 => 'Open',
                2 => 'In Progress',
                3 => 'Resolved',
                4 => 'Closed',
                5 => 'Withdrawn by Student',
                default => 'Unknown',
            };
            $this->Cell(40, 7, "Current Status:", 0, 0, "L", true);
            $this->Cell(0, 7, htmlspecialchars($status_text), 0, 1);

            $this->Cell(40, 7, "Date Submitted:", 0, 0, "L", true);
            $this->Cell(0, 7, date("Y-m-d H:i", strtotime($row["created_at"])), 0, 1);

            if ($row['date_resolved']) {
                $this->Cell(40, 7, "Date Resolved:", 0, 0, "L", true);
                $this->Cell(0, 7, date("Y-m-d H:i", strtotime($row["date_resolved"])), 0, 1);
            }

            $this->Ln(5); // Section break

            // --- Submitted By (Student Details) ---
            $this->SetFont("Arial", "B", 12);
            $this->Cell(0, 8, "Submitted By (Student Details)", 0, 1);
            $this->SetFont("Arial", "", 10);

            $this->Cell(40, 7, "Student Name:", 0, 0, "L", true);
            $this->Cell(0, 7, htmlspecialchars($row["student_name"] ?? 'N/A'), 0, 1);

            $this->Cell(40, 7, "Student ID:", 0, 0, "L", true);
            $this->Cell(0, 7, htmlspecialchars($row["student_id_number"] ?? 'N/A'), 0, 1);

            $this->Cell(40, 7, "Email:", 0, 0, "L", true);
            $this->Cell(0, 7, htmlspecialchars($row["student_email"] ?? 'N/A'), 0, 1);

            $this->Cell(40, 7, "Phone:", 0, 0, "L", true);
            $this->Cell(0, 7, htmlspecialchars($row["student_phone"] ?? 'N/A'), 0, 1);

            $this->Ln(5);

            // --- Complaint Location & Details ---
            $this->SetFont("Arial", "B", 12);
            $this->Cell(0, 8, "Complaint Location & Details", 0, 1);
            $this->SetFont("Arial", "", 10);

            $this->Cell(40, 7, "Dormitory:", 0, 0, "L", true);
            $this->Cell(0, 7, htmlspecialchars($row["dormitory_name"] ?? 'N/A'), 0, 1);

            $this->Cell(40, 7, "Room Number:", 0, 0, "L", true);
            $this->Cell(0, 7, htmlspecialchars($row["room_number"]), 0, 1);

            $this->Cell(40, 7, "Category:", 0, 0, "L", true);
            $this->Cell(0, 7, htmlspecialchars(ucfirst(str_replace('_', ' ', $row["complaint_category"]))), 0, 1);

            $this->Cell(40, 7, "Urgency:", 0, 0, "L", true);
            $this->Cell(0, 7, htmlspecialchars(ucfirst($row["urgency_level"])), 0, 1);

            $this->Cell(40, 7, "Title:", 0, 0, "L", true);
            $this->Cell(0, 7, htmlspecialchars($row["complaint_title"]), 0, 1);

            $this->Ln(2);
            $this->SetFont("Arial", "B", 10);
            $this->Cell(0, 7, "Description:", 0, 1);
            $this->SetFont("Arial", "", 10);
            // Use MultiCell for text that may wrap
            $this->MultiCell(0, 5, htmlspecialchars_decode($row["complaint_description"])); // Decode special chars for PDF

            if (!empty($row["photo"])) {
                $this->Ln(2);
                $this->SetFont("Arial", "B", 10);
                $this->Cell(0, 7, "Attached Photo:", 0, 1);
                $this->SetFont("Arial", "", 10);
                // FPDF doesn't support relative web paths well. Just print the path.
                $this->Cell(0, 5, "Photo stored at: " . htmlspecialchars($row["photo"]));
                $this->Ln(5);
            } else {
                $this->Ln(5);
            }

            // --- Assignment & Resolution ---
            $this->SetFont("Arial", "B", 12);
            $this->Cell(0, 8, "Assignment & Resolution", 0, 1);
            $this->SetFont("Arial", "", 10);

            $this->Cell(40, 7, "Assigned Staff:", 0, 0, "L", true);
            $this->Cell(0, 7, htmlspecialchars($row["staff_name"] ?? 'Not Assigned Yet'), 0, 1);

            $this->Ln(2);
            $this->SetFont("Arial", "B", 10);
            $this->Cell(0, 7, "Resolution Notes:", 0, 1);
            $this->SetFont("Arial", "", 10);
            $notes = !empty($row["resolution_notes"]) ? htmlspecialchars_decode($row["resolution_notes"]) : 'No resolution notes added yet.';
            $this->MultiCell(0, 5, $notes);
        } else {
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, 'Error: Complaint Not Found', 0, 1, 'C');
        }
    }
}

// --- PDF Generation ---
$complaint_id = isset($_REQUEST["complaint_id"]) ? (int)$_REQUEST["complaint_id"] : 0;

if ($complaint_id > 0) {
    $pdf = new PDF();
    $pdf->AliasNbPages(); // Enable page numbering footer '{nb}'
    $pdf->AddPage();
    $pdf->ComplaintDetails($complaint_id); // Call the function to populate details
    // 'D' forces download, 'I' displays in browser
    $pdf->Output('I', 'Complaint_Report_' . $complaint_id . '.pdf');
} else {
    die("Invalid Complaint ID provided.");
}
?>