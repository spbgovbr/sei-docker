<?php

namespace TRF4\UI\Util;

class DirHash {

    public function __construct() { 
    }
    
    /**
     * Generate an MD5 hash string from the contents of a directory.
     *
     * @param string $directory
     * @return boolean|string
     */

    function hashDirectory($directory)
    {
        if (! is_dir($directory))
        {
            return false;
        }
     
        $files = array();
        $dir = dir($directory);
     
        while (false !== ($file = $dir->read()))
        {
            if ($file != '.' and $file != '..')
            {
                if (is_dir($directory . '/' . $file))
                {
                    $files[] = $this->hashDirectory($directory . '/' . $file);
                }
                else
                {
                    $files[] = md5_file($directory . '/' . $file);
                }
            }
        }
     
        $dir->close();
     
        return md5(implode('', $files));
    }
}
