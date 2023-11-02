<?php

namespace App\Services;

use Exception;

class FileHandler {

    private string $filename;
    public const EXCLUDE_DIR = 1;
    public const INCLUDE_DIR = 0;

    public function __construct()  { }

    public function setFilename($filename) {
        $this->filename = $filename;   
    }
    public function getFileContent($fname = null) {
        if($fname === null && $this->filename === null)
        {
            throw new Exception("No file to load");
        }
        if(!file_exists($fname)) {
            throw new Exception("$fname File not found");
        }
        $this->filename = $fname;   
        $thefile = fopen($this->filename, "r") or die("Unable to open file $this->filename !");
        $content = fread($thefile, filesize($this->filename));
        fclose($thefile);
        return $content;
    }
    // Scan directory containing images
    public function getFilesList($directory, $dirflag = self::EXCLUDE_DIR ) {
        $list = scandir($directory, SCANDIR_SORT_ASCENDING);
        $filteredarray = [];
        $formattedsize = 0;
        foreach($list as $file) {
            if($dirflag) {
                if(is_file($directory.'/'.$file)) {
                    $fsize = filesize($directory.'/'.$file);
                    $formattedsize = number_format($fsize, 0, ',', '.');
                    $fobj = [ 'name' => $file, 'size' => $formattedsize];
                    array_push($filteredarray, $fobj);
                }
            } 
            else {
                if(is_file($directory.'/'.$file)) {
                    $fsize = filesize($directory.'/'.$file);
                    $formattedsize = number_format($fsize, 0, ',', '.');
                }
                array_push($filteredarray, $file . ' Size: '. $formattedsize);
            }
        }
        return $filteredarray;
    }
}