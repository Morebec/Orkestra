<?php


namespace Morebec\Orkestra\ProjectCompilation\Application\Console\Util;

class BytesFormatter
{
    /**
     * Nicely formats memory usage
     * @param $bytes
     * @param bool $binaryPrefix
     * @return string
     */
    public static function formatFileSizeForHumans($bytes, $binaryPrefix = true): string
    {
        $base = 1000;
        $units = ['B','KB','MB','GB','TB','PB'];
        if ($binaryPrefix) {
            $base = 1024;
            $units = ['B','KiB','MiB','GiB','TiB','PiB'];
        }
        if ($bytes === 0) {
            return '0 ' . $units[0];
        }

        return @round($bytes/pow($base, ($i=floor(log($bytes, $base)))), 2) .' '. (isset($units[$i]) ? $units[$i] : 'B');
    }
}
