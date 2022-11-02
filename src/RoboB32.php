<?php
namespace Robo\RoboID;

use SKleeschulte\Base32;

class RoboB32 {

    /*
    * render id in the specific format
    */
    function format() {

        $time = str_pad($this->time, 20, '0', STR_PAD_LEFT);
        $time = Base32::encodeByteStrToCrockford(hex2bin($time));
        $time = substr($time, 8);

        $rand = Base32::encodeByteStrToCrockford(hex2bin($this->rand));
        $rand = substr($rand, 0, $this->long ? 16 : 6);

        return "$time-$rand";
    }

}
