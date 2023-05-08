<?php

class DirectZipEntry
{
    public $offset;
    public $pointer;

    public $name;
    public $crc;
    public $size;
    public $mtime;

    public function __construct($name, $offset)
    {
        $this->offset = $offset;
        $this->name = $name;
    }

    public function open($filename)
    {
        $this->pointer = @fopen($filename, 'rb');
        if ($this->pointer === false) {
            return false;
        }

        list(, $this->crc) = unpack('N', hash_file('crc32b', $filename, true));

        $fstat = fstat($this->pointer);
        $this->size = $fstat['size'];

        $mtime = $filename == 'php://temp' ? time() : $fstat['mtime'];
        $this->mtime = self::toFAT32Timestamp($mtime);
    }

    public function close()
    {
        fclose($this->pointer);
    }

    private static function toFAT32Timestamp($unixTimestamp)
    {
        list($y, $m, $d, $h, $i, $s) = explode('-', @date('Y-m-d-H-i-s', $unixTimestamp));
        return ($y - 1980) << 25 | $m << 21 | $d << 16
            | $h << 11 | $i << 5 | $s >> 1;
    }
}