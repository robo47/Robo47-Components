#!/usr/bin/env php
<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');

if ($_SERVER['argc'] < 3) {
    echo 'ERROR: wrong argument count';
    exit( - 1);

}

if ($_SERVER['argv'][1] != '-j' &&
    $_SERVER['argv'][1] != '-X') {
    echo 'ERROR: wrong argument count';
    exit(1);
} else {
    switch ($_SERVER['argv'][1]) {
        case '-j':
            echo json_encode($_SERVER['argv']);
            break;
        case '-X':
            $filename = $_SERVER['argv'][2];
            echo "
<?xml version='1.0' encoding='UTF-8'?>
<rdf:RDF xmlns:rdf='http://www.w3.org/1999/02/22-rdf-syntax-ns#'>
<rdf:Description rdf:about='$filename'
  xmlns:et='http://ns.exiftool.ca/1.0/' et:toolkit='Image::ExifTool 7.89'
  xmlns:ExifTool='http://ns.exiftool.ca/ExifTool/1.0/'
  xmlns:System='http://ns.exiftool.ca/File/1.0/'
  xmlns:File='http://ns.exiftool.ca/File/1.0/'
  xmlns:JFIF='http://ns.exiftool.ca/JFIF/JFIF/1.0/'
  xmlns:IFD0='http://ns.exiftool.ca/EXIF/IFD0/1.0/'
  xmlns:ExifIFD='http://ns.exiftool.ca/EXIF/ExifIFD/1.0/'
  xmlns:Canon='http://ns.exiftool.ca/MakerNotes/Canon/1.0/'
  xmlns:CanonCustom='http://ns.exiftool.ca/MakerNotes/CanonCustom/1.0/'
  xmlns:InteropIFD='http://ns.exiftool.ca/EXIF/InteropIFD/1.0/'
  xmlns:IFD1='http://ns.exiftool.ca/EXIF/IFD1/1.0/'
  xmlns:Composite='http://ns.exiftool.ca/Composite/1.0/'>
>
            ";
            foreach ($_SERVER['argv'] as $name => $value) {
                echo "<ExifTool:attr$name>$value</ExifTool:attr$name>" . PHP_EOL;
            }
            echo "
</rdf:Description>
</rdf:RDF>";
            break;
        default:
            echo 'ERROR: wrong value for argv[1]';
            exit(2);

    }
}
