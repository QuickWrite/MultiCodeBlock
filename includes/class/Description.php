<?php
/**
 * This file is part of MultiCodeBlock.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Stores the description of the code.
 * 
 * @author QuickWrite
 * 
 * @global array $texts An array of all the different descriptions.
 * @global array $keys The lines where the descriptions should start.
 * 
 * @param string $dom A string that has the content of the <desc>-element.
 */
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

    /**
     * Returns the last line the current description.
     * 
     * @param int $index The index of the textblock.
     * 
     * @return int The last line of the textblock (based on $index).
     */
    public function getNext($index) {
        if($index + 1 > sizeof($this->keys))
            return null;

        return $this->keys[$index] - 1;
    }

    /**
     * Inserts the description into the attrributes.
     * 
     * @param string $dom A string that has the content of the <desc>-element.
     */
    public function setTexts(&$dom) {
        $positions = $dom->getElementsByTagName('position');
        $numberOfPos = sizeof($positions);
        
        $hasOne = false;

        for($i = 0; $i < $numberOfPos; ++$i) {
            $key = $positions[$i]->getAttribute('line');
            $content = $positions->item($i)->nodeValue;

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
