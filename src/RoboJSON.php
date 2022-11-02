<?php
namespace Robo\RoboID;

class RoboJSON {
    protected $time;  // hex string
    protected $rand;  // hex string
    protected $long = false;

    /*
    * create new id by setting $timed and $random
    */
    function init() {
        $time = floor(microtime(true) * 1000);
        $this->time = bin2hex(pack('J', $time));
        $rand = random_bytes(10); // 80 bits entropy
        $rand[0] = chr((ord($rand[0]) & 0x0F) | 0xB0);  // uuid version
        $rand[2] = chr((ord($rand[2]) & 0x3F) | 0x80);  // uuid variant
        $this->rand = bin2hex($rand);
    }

    /*
    * export id as json
    */
    function export($encoding='hex') {
        $time = hex2bin($this->time);
        $rand = hex2bin($this->rand);
        if(!$this->long) {
            $rand = substr($rand, -4);
            // TODO: hier evtl. den Bereich anpassen
            $rand[3] = chr(ord($rand[3]) & 0xFC);
        }
        // TODO: encoding entsprechend berücksichtigen
        $json = [
            'e' => $encoding,
            'v' => $this->long ? 'L' : 'S',
            't' => $time,
            'r' => $rand,
        ];
        return json_encode($json);
    }

    /*
    * import id from json
    */
    function import($json) {

    }

    /*
    * render id in the specific format
    */
    function format() {
        return $this->export();
    }

    /*
    * generate new id and render it in the specific format
    */
    function genID() {
        $this->init();
        return $this->format();
    }

    /*
    * enable long version
    */
    function setLong($bool) {
        $this->long = (bool) $bool;
    }
}
