<?php

namespace App\Services;

class PdfGuideService
{
    private array $pages = [];
    private string $currentStream = '';
    private float $pageWidth = 595.28;  // A4 standard points
    private float $pageHeight = 841.89; // A4 standard points
    private float $margin = 40.0;
    private float $currentY = 800.0;

    public function __construct()
    {
        $this->startNewPage();
    }

    public function startNewPage(): void
    {
        if (!empty($this->currentStream)) {
            $this->pages[] = $this->currentStream;
        }
        $this->currentStream = '';
        $this->currentY = $this->pageHeight - $this->margin - 20;

        // Draw top subtle decorative border on new page
        $this->drawRect(0, $this->pageHeight - 6, $this->pageWidth, 6, 0.36, 0.08, 0.55); // SJUT purple
        $this->drawRect(0, $this->pageHeight - 9, $this->pageWidth, 3, 0.96, 0.62, 0.04); // Amber
    }

    public function addHeader(string $title, string $subtitle): void
    {
        // Purple header block
        $blockHeight = 70;
        $y = $this->currentY - $blockHeight;
        $this->drawRect($this->margin, $y, $this->pageWidth - ($this->margin * 2), $blockHeight, 0.23, 0.08, 0.44, true, 8);

        // Header text
        $this->drawText($title, $this->margin + 16, $y + 42, 16, true, 1.0, 1.0, 1.0);
        $this->drawText($subtitle, $this->margin + 16, $y + 22, 10, false, 0.95, 0.85, 0.4);
        $this->drawText("ST. JOHN'S UNIVERSITY OF TANZANIA  •  OFFICIAL OPERATIONAL GUIDE", $this->margin + 16, $y + 8, 7.5, true, 0.85, 0.8, 0.95);

        $this->currentY = $y - 20;
    }

    public function addSectionTitle(string $title, string $icon = ''): void
    {
        if ($this->currentY < 120) {
            $this->startNewPage();
        }

        $y = $this->currentY - 24;
        // Background banner
        $this->drawRect($this->margin, $y - 4, $this->pageWidth - ($this->margin * 2), 24, 0.95, 0.93, 0.98, true, 4);
        $this->drawRect($this->margin, $y - 4, 4, 24, 0.36, 0.08, 0.55, true, 0);

        $fullTitle = $icon ? "[$icon]  $title" : $title;
        $this->drawText(strtoupper($fullTitle), $this->margin + 12, $y + 3, 11, true, 0.23, 0.08, 0.44);

        $this->currentY = $y - 14;
    }

    public function addSubheading(string $text): void
    {
        if ($this->currentY < 90) {
            $this->startNewPage();
        }

        $y = $this->currentY - 14;
        $this->drawText($text, $this->margin + 4, $y, 10, true, 0.15, 0.15, 0.2);
        $this->currentY = $y - 8;
    }

    public function addParagraph(string $text, float $fontSize = 8.5): void
    {
        $lines = $this->wrapText($text, 92);
        foreach ($lines as $line) {
            if ($this->currentY < 60) {
                $this->startNewPage();
            }
            $y = $this->currentY - 12;
            $this->drawText($line, $this->margin + 6, $y, $fontSize, false, 0.25, 0.25, 0.3);
            $this->currentY = $y;
        }
        $this->currentY -= 6;
    }

    public function addStepBox(string $stepNum, string $title, string $desc, array $highlights = []): void
    {
        if ($this->currentY < 130) {
            $this->startNewPage();
        }

        $lines = $this->wrapText($desc, 80);
        $boxHeight = 36 + (count($lines) * 11) + (count($highlights) * 12);
        $y = $this->currentY - $boxHeight;

        // Card border & fill
        $this->drawRect($this->margin, $y, $this->pageWidth - ($this->margin * 2), $boxHeight, 0.98, 0.98, 1.0, true, 6);
        $this->drawRectBorder($this->margin, $y, $this->pageWidth - ($this->margin * 2), $boxHeight, 0.82, 0.82, 0.9, 0.8);

        // Step number badge
        $this->drawRect($this->margin + 10, $y + $boxHeight - 24, 20, 16, 0.36, 0.08, 0.55, true, 4);
        $this->drawText($stepNum, $this->margin + 16, $y + $boxHeight - 20, 9, true, 1.0, 1.0, 1.0);

        // Step title
        $this->drawText($title, $this->margin + 36, $y + $boxHeight - 19, 10, true, 0.1, 0.1, 0.2);

        // Description lines
        $textY = $y + $boxHeight - 34;
        foreach ($lines as $line) {
            $this->drawText($line, $this->margin + 36, $textY, 8.2, false, 0.3, 0.3, 0.35);
            $textY -= 11;
        }

        // Highlights bullets
        foreach ($highlights as $hl) {
            $this->drawText("•  $hl", $this->margin + 42, $textY, 7.8, false, 0.2, 0.4, 0.2);
            $textY -= 11;
        }

        $this->currentY = $y - 10;
    }

    public function addNoteBox(string $title, string $message): void
    {
        if ($this->currentY < 90) {
            $this->startNewPage();
        }

        $lines = $this->wrapText($message, 86);
        $boxHeight = 24 + (count($lines) * 11);
        $y = $this->currentY - $boxHeight;

        $this->drawRect($this->margin, $y, $this->pageWidth - ($this->margin * 2), $boxHeight, 0.99, 0.96, 0.89, true, 4);
        $this->drawRectBorder($this->margin, $y, $this->pageWidth - ($this->margin * 2), $boxHeight, 0.92, 0.75, 0.3, 0.8);
        $this->drawRect($this->margin, $y, 4, $boxHeight, 0.95, 0.65, 0.1, true, 0);

        $this->drawText($title, $this->margin + 12, $y + $boxHeight - 14, 8.5, true, 0.6, 0.3, 0.0);

        $textY = $y + $boxHeight - 26;
        foreach ($lines as $line) {
            $this->drawText($line, $this->margin + 12, $textY, 8, false, 0.35, 0.3, 0.15);
            $textY -= 11;
        }

        $this->currentY = $y - 12;
    }

    public function addFooter(int $pageNum, int $totalPages): void
    {
        $y = $this->margin - 16;
        $this->drawRect($this->margin, $y + 16, $this->pageWidth - ($this->margin * 2), 0.6, 0.8, 0.8, 0.85);
        $this->drawText("SJUT Lost & Found System • Dodoma, Tanzania • Security Office Helpline: +255 26 239 0044", $this->margin, $y + 4, 7, false, 0.5, 0.5, 0.55);
        $this->drawText("Page $pageNum of $totalPages", $this->pageWidth - $this->margin - 50, $y + 4, 7, true, 0.4, 0.4, 0.45);
    }

    private function drawRect(float $x, float $y, float $w, float $h, float $r, float $g, float $b, bool $fill = true, float $radius = 0): void
    {
        $r = sprintf("%.2F", $r);
        $g = sprintf("%.2F", $g);
        $b = sprintf("%.2F", $b);
        $x = sprintf("%.2F", $x);
        $y = sprintf("%.2F", $y);
        $w = sprintf("%.2F", $w);
        $h = sprintf("%.2F", $h);

        $this->currentStream .= "q\n$r $g $b rg\n$x $y $w $h re\nf\nQ\n";
    }

    private function drawRectBorder(float $x, float $y, float $w, float $h, float $r, float $g, float $b, float $lineWidth = 1): void
    {
        $r = sprintf("%.2F", $r);
        $g = sprintf("%.2F", $g);
        $b = sprintf("%.2F", $b);
        $x = sprintf("%.2F", $x);
        $y = sprintf("%.2F", $y);
        $w = sprintf("%.2F", $w);
        $h = sprintf("%.2F", $h);
        $lineWidth = sprintf("%.2F", $lineWidth);

        $this->currentStream .= "q\n$r $g $b RG\n$lineWidth w\n$x $y $w $h re\nS\nQ\n";
    }

    private function drawText(string $text, float $x, float $y, float $size = 10, bool $isBold = false, float $r = 0, float $g = 0, float $b = 0): void
    {
        $font = $isBold ? '/F1' : '/F2';
        $safeText = $this->escapePdfString($text);
        $r = sprintf("%.2F", $r);
        $g = sprintf("%.2F", $g);
        $b = sprintf("%.2F", $b);
        $x = sprintf("%.2F", $x);
        $y = sprintf("%.2F", $y);
        $size = sprintf("%.2F", $size);

        $this->currentStream .= "BT\n$font $size Tf\n$r $g $b rg\n$x $y Td\n($safeText) Tj\nET\n";
    }

    private function escapePdfString(string $str): string
    {
        // Normalize UTF-8 characters to standard ASCII for Helvetica
        $str = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $str) ?: $str;
        $str = str_replace(['\\', '(', ')', "\r"], ['\\\\', '\\(', '\\)', ''], $str);
        return $str;
    }

    private function wrapText(string $text, int $maxChars = 85): array
    {
        $words = explode(' ', $text);
        $lines = [];
        $currentLine = '';

        foreach ($words as $word) {
            if (strlen($currentLine . ' ' . $word) <= $maxChars) {
                $currentLine = empty($currentLine) ? $word : $currentLine . ' ' . $word;
            } else {
                if (!empty($currentLine)) {
                    $lines[] = $currentLine;
                }
                $currentLine = $word;
            }
        }
        if (!empty($currentLine)) {
            $lines[] = $currentLine;
        }
        return $lines;
    }

    public function generate(): string
    {
        if (!empty($this->currentStream)) {
            $this->pages[] = $this->currentStream;
            $this->currentStream = '';
        }

        $totalPages = count($this->pages);
        // Add footer to each page stream
        for ($i = 0; $i < $totalPages; $i++) {
            $this->currentStream = $this->pages[$i];
            $this->addFooter($i + 1, $totalPages);
            $this->pages[$i] = $this->currentStream;
        }

        $objects = [];
        $objIndex = 1;

        // Object 1: Catalog
        $catalogIndex = $objIndex++;
        $pagesTreeIndex = $objIndex++;
        $fontBoldIndex = $objIndex++;
        $fontRegularIndex = $objIndex++;

        $pageObjIndices = [];
        $streamObjIndices = [];

        for ($i = 0; $i < $totalPages; $i++) {
            $pageObjIndices[] = $objIndex++;
            $streamObjIndices[] = $objIndex++;
        }

        // 1: Catalog
        $objects[$catalogIndex] = "<< /Type /Catalog /Pages $pagesTreeIndex 0 R >>";

        // 2: Pages Tree
        $kids = implode(' ', array_map(fn($idx) => "$idx 0 R", $pageObjIndices));
        $objects[$pagesTreeIndex] = "<< /Type /Pages /Kids [$kids] /Count $totalPages >>";

        // 3: Font Bold
        $objects[$fontBoldIndex] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>";

        // 4: Font Regular
        $objects[$fontRegularIndex] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>";

        // Page and Stream objects
        for ($i = 0; $i < $totalPages; $i++) {
            $pIndex = $pageObjIndices[$i];
            $sIndex = $streamObjIndices[$i];
            $streamContent = $this->pages[$i];
            $streamLen = strlen($streamContent);

            $objects[$pIndex] = "<< /Type /Page /Parent $pagesTreeIndex 0 R /MediaBox [0 0 {$this->pageWidth} {$this->pageHeight}] /Resources << /Font << /F1 $fontBoldIndex 0 R /F2 $fontRegularIndex 0 R >> >> /Contents $sIndex 0 R >>";
            $objects[$sIndex] = "<< /Length $streamLen >>\nstream\n" . $streamContent . "\nendstream";
        }

        // Build PDF string with Cross-Reference Table
        $output = "%PDF-1.4\n";
        $xrefOffsets = [];

        foreach ($objects as $num => $content) {
            $xrefOffsets[$num] = strlen($output);
            $output .= "$num 0 obj\n$content\nendobj\n";
        }

        $xrefStart = strlen($output);
        $totalObjs = count($objects) + 1;
        $output .= "xref\n0 $totalObjs\n";
        $output .= "0000000000 65535 f \n";

        for ($i = 1; $i < $totalObjs; $i++) {
            $offset = $xrefOffsets[$i] ?? 0;
            $output .= sprintf("%010d 00000 n \n", $offset);
        }

        $output .= "trailer\n<< /Size $totalObjs /Root $catalogIndex 0 R >>\n";
        $output .= "startxref\n$xrefStart\n%%EOF\n";

        return $output;
    }
}
