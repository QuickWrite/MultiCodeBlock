<?php
/**
 * This file is part of MultiCodeBlock.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Returns the DOM-Parser with custom options and the HTML-Tree
 * 
 * @param string $content The content that should be parsed by the DOM-Parser
 * 
 * @return DOMDocument The DOM-Element that has the HTML-Tree
 */
function getDOM(string &$content) {
    $dom = new DOMDocument();

    $dom->validateOnParse = false;

    libxml_use_internal_errors(true);
    $dom->loadHTML($content);
    libxml_clear_errors();

    return $dom;
}

/**
 * Returns all of the blocks with code inside.
 * They are determined by having the 
 * `<code>{code}</code>`-structure.
 * 
 * @param string $codeblock The object where all of the codeblocks should be removed.
 * 
 * @return array An array of the code.
 */
function findCodeBlocks(string &$codeblock) {
    preg_match_all("(< *code *>((.|\n)*?)<\/code *>)", $codeblock, $array);

    return $array[1];
}
