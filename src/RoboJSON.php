<?php
namespace Robo\RoboID;

class RoboJSON {
    protected $time;        // hex string // 16 hex characters
    protected $rand;        // hex string // 20 hex characters
    protected $long = false; // short => last 32 bits of $rand

    /*
    * create new id by setting $timed and $random
    */
    function init() {
        $time = floor(microtime(true) * 1000);
        $this->time = bin2hex(pack('J', $time));
        $rand = random_bytes(10); // 80 bits entropy
        $rand = $this->setUuidBits($rand);
        $this->rand = bin2hex($rand);
    }

    /*
    * export id as json
    */
    function export($encoding='hex') {
        $time = $this->time;
        $rand = $this->rand;

        // apply encoding
        // TODO: implement B32 and UUID support
        $encoding = 'hex';

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
        $json = json_decode($json);
        // TODO: implement B32 and UUID support
        $rand = $json->r;
        $time = $json->t;
        $long = ($json->v !== 'S');

        $rand = str_pad($rand, 20, '0', STR_PAD_LEFT);
        $time = str_pad($time, 16, '0', STR_PAD_LEFT);

        $rand = $this->setUuidBits($rand);

        $this->rand = $rand;
        $this->time = $time;
        $this->long = $long;
    }

    /*
    * render id in the specific format
    */
    function format() {
        return $this->export();
    }

    /*
    * parse id from the specific format
    */
    function parse($id) {
        $this->import($id);
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

    /* ********** helper functions ********** */

    protected function setUuidBits($bin) {
        // $bin holds the 80 least significant bits of the UUID
        $bin[0] = chr((ord($bin[0]) & 0x0F) | 0xB0);  // uuid version
        $bin[2] = chr((ord($bin[2]) & 0x3F) | 0x80);  // uuid variant
        return $bin;
    }
}
