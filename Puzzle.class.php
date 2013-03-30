<?php
class Puzzle {
    //
    public $gamen = array();
    public $souwaku = 0;
    public $waku = 0;
    public $kouho = array();
    public $status = 0;
    public $empty_no = 0;
    public $mode = 0;

    function __construct($in_num = 9) {
        $this->waku = floor(sqrt($in_num));
        $this->souwaku = $this->waku * $this->waku;
        for ($i = 1; $i <= $this->souwaku; $i++) {
            $this->gamen[$i] = $i;
        }
        $this->status = 0;
        $this->empty_no = $this->souwaku;
    }

    public function chk_complate() {
        foreach ($this->gamen as $key => $value) {
            if ($key != $value) {
                return False;
            }
        }
        return True;
    }

    public function set_move() {
        if($this->status==0){
            $this->kouho=array();
            return;
        }
        $this->kouho[1] = $this->empty_no - 1;
        $this->kouho[2] = $this->empty_no + 1;
        $this->kouho[3] = $this->empty_no + $this->waku;
        $this->kouho[4] = $this->empty_no - $this->waku;
        $tmp = $this->empty_no % $this->waku;
        if ($this->kouho[4] < 0) {
            $this->kouho[4] = 0;
        }
        if ($this->kouho[3] > $this->souwaku) {
            $this->kouho[3] = 0;
        }
        if ($tmp == 0) {
            $this->kouho[2] = 0;
        }
        if ($tmp == 1) {
            $this->kouho[1] = 0;
        }
    }

    public function random_move() {
        $this->set_move();
        for (;;) {
            $tmp = rand(1, 4);
            if ($this->kouho[$tmp] != 0) {
                //print($this->empty_no."‚ğ".$this->kouho[$tmp]."‚Æ“ü‚ê‘Ö‚¦<br>");
                $swap = $this->gamen[$this->kouho[$tmp]];
                $this->gamen[$this->kouho[$tmp]] = $this->gamen[$this->empty_no];
                $this->gamen[$this->empty_no] = $swap;
                $this->empty_no = $this->kouho[$tmp];
                return;
            }
        }
    }
    public function move($move_no) {
        if ($this->status != 3) {//Às’†ˆÈŠO‚ÍR‚é
            return;
        }
        //‹ó”’ƒZƒ‹‚ğ“ü‚ê‘Ö‚¦‚é
        if ($move_no != "" and $move_no != 0) {
            for ($i = 1; $i <= 4; ++$i) {
                if ($this->kouho[$i] == $move_no) {
                    
                    $swap = $this->gamen[$move_no];
                    $this->gamen[$move_no] = $this->gamen[$this->empty_no];
                    $this->gamen[$this->empty_no] = $swap;
                    $this->empty_no = $move_no;
                }
            }
        }
    }
}

