<?php
/**
 * PHP Hex Viewer
 * Copyright (c) 2016 Robert Lerner http://www.robert-lerner.com
 *
 * Output a hex-viewer style view of a file.
 *
 * v0.1.0
 *
 */


// Source File to Open
$sourceFile = '';

// Output File Name, or if blank, outputs to php://output (stdout is less dependable) (probably the screen, barring output buffering)
// Large files may crash your browser.
$outputFile = "";

// Chunk size. Only relevant for visual cases. 16 is typical, and the default.
$chunkSize = 16;

// End of line character used. PHP_EOL is the default, though you can change it to whatever you want \r\n, LOL, etc.
$eolString = PHP_EOL;

// Allows segments to be split up visually (16 byte rows, split at 4 bytes). False to disable.
$visualSplit = 8;


// ------------------------ \\
// | End of Configuration | \\
// ------------------------ \\


// Param Defaults
if ($outputFile=='') {
	$outputFile = 'php://output';
}
$position = $innerIterator = $maxRowLen = 0;
$rawView = '';

if ($visualSplit>=$chunkSize) { // Disable when it doesn't make sense.
	$visualSplit = false;
}
if ($chunkSize<1) {
	$chunkSize = 16;
}

// If we're not on the command line, emit header to prevent broken formatting and file execution.
if (PHP_SAPI!='cli') {
	header("Content-Type: text/plain");
}

// General File Checks
if (!is_readable($sourceFile)) {
	die("Cannot read source file.");
}
if (!is_writable($outputFile) && $outputFile!='php://output') {
	die("Cannot write to output file.");
}

$sourceFileSize = filesize($sourceFile);
if ($sourceFileSize<1) {
	die("Source File Blank -- Nothing to do.");
}

// Auto Left Pad, prepends a zero for elegance.
$leftPad = strlen(dechex($sourceFileSize/$chunkSize))+1;

// Get File Handles
$sourceFileHandle = fopen($sourceFile,'rb');
$outputFileHandle = fopen($outputFile,'wb');


// Draw Header table with ruler
$xHeader = $xHeaderRule = str_repeat(' ',$leftPad).'   ';
for ($i=0;$i<$chunkSize;$i++) {
	$xHeader .= str_pad(strtoupper(dechex($i)),2,'0',STR_PAD_LEFT) . ' ';
	$xHeaderRule .= "-- ";
	
	if ($visualSplit!=false) {
		if (($i+1)%$visualSplit==0) {
			$xHeader .= '  ';
			$xHeaderRule .= '  ';
		}
	}
}
fwrite($outputFileHandle,"$xHeader$eolString$xHeaderRule$eolString");

// Process file
for ($i=0;$i<$sourceFileSize;$i+=$chunkSize) {
	
	// Grab next relevant chunk
	fseek($sourceFileHandle,$i);
	$chunk = fread($sourceFileHandle,$chunkSize);
	
	// Provide byte-range label.
	$row = str_pad(dechex($i),$leftPad,'0',STR_PAD_LEFT) . ' | ';
	
	// Chunk stream to size
	$thisChunk = str_split($chunk,1);
	foreach ($thisChunk as $v) {
		
		// Convert to hex, pad and upper.
		$row .= str_pad(strtoupper(dechex(ord($v))),2,'0',STR_PAD_LEFT) . ' ';
		
		// Generate raw visual output of printable chars
		if (ctype_print($v)) {
			$rawView .= $v;
		} else {
			$rawView .= '.';
		}
		
		// Process Visual Split
		if ($visualSplit!=false) {
			$innerIterator++;
			if (($innerIterator)%$visualSplit==0) {
				$row .= "  ";
				$rawView .= ' ';
			}
		}
	}
	
	// Used for spacing of last row if file isn't divisible by chunk size.
	if (strlen($row)>$maxRowLen) {
		$maxRowLen = strlen($row);
	}
	fwrite($outputFileHandle,str_pad($row,$maxRowLen,' ').$rawView.$eolString);
	
	$rawView = '';
}
