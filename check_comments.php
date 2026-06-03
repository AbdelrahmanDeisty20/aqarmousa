<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$file = $argv[1] ?? 'lands-template (15).xlsx';

if (!file_exists($file)) {
    echo "File not found: $file\n";
    exit(1);
}

try {
    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $comments = $sheet->getComments();

    echo "Comments found in $file:\n";
    foreach ($comments as $coordinate => $comment) {
        echo "[$coordinate]: " . $comment->getText()->getPlainText() . "\n";
        echo "---------------------------------\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
