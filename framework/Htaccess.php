<?php

declare (strict_types = 1);

namespace Phantom;

class Htaccess
{
    private $text;
    private $file = ".htaccess";

    public function __construct()
    {
        $this->text = file_get_contents($this->file);
    }

    # Method writes a unique line to the .htaccess file
    public function write($line)
    {
        $line .= " [QSA,L] \n";

        if (strpos($this->text, $line) === false) {
            file_put_contents($this->file, $line, FILE_APPEND);
        }
    }
}
