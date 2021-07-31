<?php
/**
 * This file is part of MultiCodeBlock.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Saves the code and the language as well as highlights the code.
 * 
 * @author QuickWrite
 * @global string $code The code stored in raw text format.
 * @global string $lang The language that was inputted.
 * 
 * @param string $code The code that should be highlighted.
 * @param string $lang The language the code should be highlighted in.
 */
class Code {
    public string $code = '';
    public string $lang = '';
    
    public function __construct(string &$code, string $lang) {
        $this->setCode($code, $lang);
    }

    /**
     * Returns a highlighted version of the code.
     * 
     * @param Highlighter $h1 The highlighter class og Highlight.php to highlight the code.
     * @return string The highlighted version the the $code.
     */
    public function &highlight(\Highlight\Highlighter &$hl) {
        $highlighted = null;

        try {
            $highlighted = $hl->highlight($this->lang, $this->code);
        } catch(DomainException $e) {
            $highlighted = htmlentities($this->code);
        }

        return $highlighted;
    }

    /**
     * Inserts the code into the attributes.
     * 
     * @param string $code The code that should be inserted into the class
     * @param string $lang The language the code is written in.
     */
    public function setCode(string &$code, string &$lang) {
        $this->code = $code;
        $this->lang = $lang;
    }
}