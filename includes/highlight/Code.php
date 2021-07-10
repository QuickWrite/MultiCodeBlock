<?php

class Code {
    public $code;
    public $lang;

    public function __construct(&$code, $lang) {
        $this->setCode($code, $lang);
    }

    public function &highlight(\Highlight\Highlighter &$hl) {
        $highlighted;

        try {
            $highlighted = $hl->highlight($this->lang, $this->code);
        } catch(DomainException $e) {
            $highlighted = htmlentities($this->code);
        }

        return $highlighted;
    }

    public function setCode($code, $lang) {
        $this->code = $code;
        $this->lang = $lang;
    }
}