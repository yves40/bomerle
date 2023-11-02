<?php

namespace App\Services;

use Exception;

class FileHandler {

    private string $filename;
    public const EXCLUDE_DIR = 1;
    public const INCLUDE_DIR = 0;
    private int $webpratio = 30;  // 1 to 100 : The bigger the less compression
    // --------------------------------------------------------------------------
    public function __construct()  { }

    // --------------------------------------------------------------------------
    public function setFilename($filename) {
        $this->filename = $filename;   
    }
    // --------------------------------------------------------------------------
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
    // --------------------------------------------------------------------------
    // Scan directory containing images
    public function getFilesList($directory, $dirflag = self::EXCLUDE_DIR ) {
        $list = scandir($directory, SCANDIR_SORT_ASCENDING);
        $filteredarray = [];
        $formattedsize = 0;
        foreach($list as $file) {
            if($dirflag) {
                if(is_file($directory.'/'.$file)) {
                    $extension = pathinfo($directory.'/'.$file, PATHINFO_EXTENSION);
                    $fsize = filesize($directory.'/'.$file);
                    $formattedsize = number_format($fsize, 0, ',', '.');
                    $fobj = [ 'name' => $file, 
                                'size' => $formattedsize,
                                'extension' => $extension];
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
    // --------------------------------------------------------------------------
    public function convertToWebp($dirpath, $imagepath): string {
        // Convert to a webp format
        // 1    IMAGETYPE_GIF
        // 2    IMAGETYPE_JPEG
        // 3    IMAGETYPE_PNG
        // 6    IMAGETYPE_BMP
        // 15   IMAGETYPE_WBMP
        // 16   IMAGETYPE_XBM
        // 18	IMAGETYPE_WEBP
        $file_type = exif_imagetype($dirpath .'/'.$imagepath);
        $imagename = pathinfo($dirpath .'/'.$imagepath, PATHINFO_FILENAME );
        switch($file_type) {
            case '2':   $image = imagecreatefromjpeg($dirpath .'/'.$imagepath);
                        break;
            case '3':
                        // The @ suppress warnings on some files
                        $image = @imagecreatefrompng($dirpath .'/'.$imagepath);
                        imagepalettetotruecolor($image);
                        imagealphablending($image, true);
                        imagesavealpha($image, true);
                        break;
            default:    $image = null;
                        break;
        }
        if($image !== null){
            $result = imagewebp($image, $dirpath .'/'.$imagename.'.'.'webp', $this->webpratio);
            imagedestroy($image);
            unlink($dirpath .'/'.$imagepath);   // Remove previous file
            return $imagename.'.'.'webp';
        }
        else {
            return null;
        }    
    }

}