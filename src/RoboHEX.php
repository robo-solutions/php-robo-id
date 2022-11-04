<?php
namespace Robo\RoboID;

class RoboHEX extends RoboJSON{

    /*
    * render id in the specific format
    */
    function format() {
        $time = substr($this->time, 5);
        $rand = $this->rand;
        if(!$this->long) {
            $rand = hex2bin($rand);
            // use last four bytes in short version
            // remove last two bits to get 30 bits entropy
            $rand = substr($rand, -4);
            $rand[3] = chr(ord($rand[3]) & 0xFC);
            $rand = bin2hex($rand);
        }
        return "$time-$rand";
    }

    /*
    * parse id from the specific format
    */
    function parse($id) {
        list($time, $rand) = explode('-', $id, 2);
        $rand = str_pad($rand, 20, '0', STR_PAD_LEFT);
        $time = str_pad($time, 16, '0', STR_PAD_LEFT);

        $rand = $this->setUuidBits($rand);

        $this->rand = $rand;
        $this->time = $time;
        $this->long = $long;
    }
}
