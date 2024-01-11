<?php

namespace App\Http\Helpers;

use Exception;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class SerialHelper
{
    public function __construct($com)
    {
        $com = $com;
        $path = "..\app\Comms\InterfaceSerial.py";
        $arr = ["python", $path, $com, "115200"];
        $process = new Process($arr);
        $process->run();
    }
    public static function reader($name)
    {
        $name = app_path("\Comms\__pipe\\" . $name);
        $file = fopen($name, "rb");
        $arr = [];
        while (($buffer = fgets($file, 8)) !== false) {
            array_push($arr, $buffer);
        }
        fclose($file);
        return $arr;
    }
    public static function write($com, $mess)
    {
        $name = app_path("\Comms\__pipe\\" . $com . "-pipe-" . date("d") . "_" . date("n") . "_" . date("Y") . ".txt");
        $file = fopen($name, "a");
        fwrite($file, $mess . "\n");
        fclose($file);
    }
}
