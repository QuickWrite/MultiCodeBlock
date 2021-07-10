<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once 'CustomOptions.php';

use PHPHtmlParser\Dom;

class Description {
    public $texts = array();
    public $keys = array();
    private $parser;

    public function __construct($dom = null) {
        if($dom === null) {
            array_push($this->texts, '');
            array_push($this->keys, 1);
            return;
        }

        $this->setTexts($dom);
    }

    public function getNext($index) {
        if($index + 1 > sizeof($this->keys))
            return null;

        return $this->keys[$index] - 1;
    }

    public function setTexts(&$dom) {

        $content = new Dom();

        $content->loadStr($dom, getOptions());

        $positions = $content->getElementsByTag('position');
        $numberOfPos = sizeof($positions);
        
        $hasOne = false;

        for($i = 0; $i < $numberOfPos; ++$i) {
            $key = $positions[$i]->getAttribute('line');
            $content = $positions[$i]->innerHtml;

            if($key == 1)
                $hasOne = true;

            array_push($this->texts, $content);
            array_push($this->keys, $key);
        }

        if(!$hasOne) {
            array_push($this->texts, '');
            array_push($this->keys, 1);
        }

        array_multisort($this->keys, $this->texts);
    }
}
