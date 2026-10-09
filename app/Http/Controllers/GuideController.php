<?php

namespace App\Http\Controllers;

use App\Services\PdfGuideService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GuideController extends Controller
{
    /**
     * Display the online user guide view.
     */
    public function index()
    {
        return view('guide.index');
    }

    /**
     * Download the official SJUT Lost and Found System User Guide as a PDF.
     */
    public function download(): Response
    {
        $pdf = new PdfGuideService();

        // ---------------- PAGE 1 ----------------
        $pdf->addHeader(
            "SJUT LOST PROPERTY RECOVERY SYSTEM",
            "OFFICIAL HOW THE SYSTEM WORKS & USER OPERATIONAL GUIDE"
        );

        $pdf->addSectionTitle("1. System Overview & Core Purpose", "INFO");
        $pdf->addParagraph(
            "The St. John's University of Tanzania (SJUT) Lost & Found Management System is a centralized, digital property recovery platform engineered to connect students, academic staff, administrative personnel, and Campus Security. It replaces unstructured social media notices with a safe, verified, institutional recovery registry."
        );

        $pdf->addSectionTitle("2. End-to-End Operational Workflow", "STEPS");

        $pdf->addStepBox(
            "1",
            "Property Occurrence & Rapid Report Submission",
            "Whether you have misplaced personal property or found an unattended item on campus grounds, log in to your account and submit an initial report immediately.",
            [
                "Specify item category, brand, model, color, and unique identification marks",
                "Pinpoint campus location (e.g., Library 2nd Floor, Mazengo Hostel, Science Labs)",
                "Upload a clear reference photograph when possible to assist identification"
            ]
        );

        $pdf->addStepBox(
            "2",
            "Institutional Security Intake & Quality Review",
            "Every report enters the Security Review Queue where trained personnel inspect submissions to prevent fraudulent, duplicate, or abusive entries.",
            [
                "Approved reports are immediately listed on the Active Public Directory",
                "Sensitve details remain concealed to protect proof of true ownership",
                "You receive an instant In-System Alert as soon as your report is approved"
            ]
        );

        $pdf->addStepBox(
            "3",
            "Automated Heuristic Matching & In-System Notifications",
            "The system continuously executes cross-referencing algorithms comparing lost property against newly logged found property across category, timestamp, and location.",
            [
                "Instant In-System Notifications are dispatched to both reporter and finder",
                "Direct in-platform communication is activated to discuss details safely",
                "No private phone numbers or personal social media handles are exposed"
            ]
        );

        // ---------------- PAGE 2 ----------------
        $pdf->startNewPage();

        $pdf->addStepBox(
            "4",
            "Formal Ownership Claim & Proof Verification",
            "When a potential match is identified or a found item belongs to you, submit a formal Ownership Claim via the system interface.",
            [
                "Provide verifiable ownership evidence (e.g., serial number, device lock pattern, invoice)",
                "Campus Security officers review and evaluate the claim against intake records",
                "Once approved by Security, a secure digital QR Release Token is minted"
            ]
        );

        $pdf->addStepBox(
            "5",
            "Physical Handover at Campus Security Desk & Resolution",
            "The claimant presents their digital QR Release Pass along with their valid SJUT Student or Staff ID Card at the Security Desk for final handover.",
            [
                "Security officers scan the QR Token to confirm claim validity and record identity",
                "Property is officially handed over and custody log is stamped with date/time",
                "Case is closed and archived into the University Recovery Audit Trail"
            ]
        );

        $pdf->addSectionTitle("3. How to Report a Lost Item (Step-by-Step)", "GUIDE");
        $pdf->addParagraph(
            "1. Click 'I Lost Something' on the landing page or navigate to 'Report Lost' in the menu.\n" .
            "2. Select the matching Category (Electronics, Identification/Cards, Books, Bags, Clothing, Keys, Other).\n" .
            "3. Enter the Item Name and full descriptive details (color, case style, serial number if known).\n" .
            "4. Specify the precise Location where the property was last seen and Date/Time lost.\n" .
            "5. Submit the report. Track progress anytime under 'My Reports' in My Workspace."
        );

        $pdf->addSectionTitle("4. How to Report a Found Item (Step-by-Step)", "GUIDE");
        $pdf->addParagraph(
            "1. Click 'I Found Something' on the landing page or in My Workspace.\n" .
            "2. Fill in the intake details: Item name, Category, Location where found, and Date found.\n" .
            "3. Choose Property Custody Status: specify whether you are holding the item temporarily or have surrendered it directly to the Central Campus Security Desk.\n" .
            "4. Submit the report. You can review all items you have turned in under My Workspace."
        );

        // ---------------- PAGE 3 ----------------
        $pdf->startNewPage();

        $pdf->addSectionTitle("5. Important System Policies & Safety Standards", "RULES");

        $pdf->addNoteBox(
            "SECURITY & OWNERSHIP INTEGRITY POLICY",
            "Never attempt to claim property that does not belong to you. All claims, IP addresses, and digital actions are logged. False claims violate the SJUT Student Code of Conduct and will result in disciplinary and legal action."
        );

        $pdf->addNoteBox(
            "PHYSICAL SURRENDER TIMEFRAME",
            "All found items should be surrendered to the SJUT Security Central Office within 24 to 48 hours of discovery to ensure immediate safety and custody accountability."
        );

        $pdf->addNoteBox(
            "IN-SYSTEM ALERTS & MESSAGING PROTOCOL",
            "All updates regarding your reports, matches, and claim status are delivered directly via In-System Alerts. Check the bell icon regularly when logged in to your account."
        );

        $pdf->addSectionTitle("6. Key Campus Contacts & Location", "CONTACT");
        $pdf->addParagraph(
            "• Main Campus Security Office: Administration Block, Ground Floor (Near East Gate)\n" .
            "• Operating Hours: Monday to Friday: 08:00 - 18:00 | Saturday: 09:00 - 13:00\n" .
            "• Security Desk Helpline: +255 26 239 0044\n" .
            "• Support Email: security@sjut.ac.tz | lostandfound@sjut.ac.tz\n" .
            "• University Address: St. John's University of Tanzania, P.O. Box 47, Dodoma, Tanzania"
        );

        $pdfContent = $pdf->generate();

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="SJUT_Lost_and_Found_System_User_Guide.pdf"',
            'Content-Length' => strlen($pdfContent),
            'Cache-Control' => 'no-cache, must-revalidate',
            'Pragma' => 'public',
        ]);
    }
}
