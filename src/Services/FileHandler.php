<?php

namespace App\Services;

use Exception;

class FileHandler {

    private string $filename;

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


}