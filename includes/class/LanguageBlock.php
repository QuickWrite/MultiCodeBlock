<?php
/**
 * This file is part of MultiCodeBlock.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Saves the Description and the Code Objects
 * 
 * @global array $code The array of Code Objects
 * @global array $desc The array of Description Objects
 * @global int $size The size of the $code block.
 * 
 * @param array $codeblocks The codeblocks that should be added to $code
 * @param $descriptions The descriptions that should be added to $desc
 * @param string $lang The language of the Block
 */
class LanguageBlock {
    public array $code = [];
    public array $desc = [];

    public int $size = 0;
    private int $sizeDescription = 0;

    public function __construct(array &$codeblocks = null, &$descriptions = null, string &$lang = null) {
        if($codeblocks === null || $descriptions === null || $lang === null)
            return;

        $this->setLanguageBlock($codeblocks, $descriptions, $lang);
    }

    /**
     * Saves the Description and the Code Objects in the attributes.
     * 
     * @param array $codeblocks The codeblocks that should be added to $code
     * @param $descriptions The descriptions that should be added to $desc
     * @param string $lang The language of the Block
     */
    public function setLanguageBlock(array &$codeblocks, &$descriptions, string &$lang) {
        $sizeCode = 0;

        foreach($codeblocks as $code) {
            array_push($this->code, new Code($code, $lang));
            ++$sizeCode;
        }
        
        $sizeDesc = 0;
        foreach($descriptions as $description) {
            array_push($this->desc, new Description($description));
            ++$sizeDesc;
        }

        $this->size = $sizeCode;
        $this->sizeDescription = $sizeDesc;
    }

    /**
     * Returns the Description Object based on the index.
     * When $index is larger then the size of $desc a new Description object will be returned.
     * 
     * @param int $index The index of the requested Description Object
     * 
     * @return Description The Object from the location or a new Description object.
     */
    public function &getDescription(int $index) {
        if($this->sizeDescription < ($index + 1)) {
            $descr = new Description();
            return $descr;
        }
        return $this->desc[$index];
    }
}