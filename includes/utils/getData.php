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

    /**
     * mb_convert_encoding() is needed as loadHTML itself
     * does not use UTF-8 and characters like ä are
     * returned as a wrong character.
     */
    $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_BIGLINES | LIBXML_COMPACT | LIBXML_NOERROR );

    return $dom;
}

/**
 * Returns a human-readable-version of the language.
 * 
 * @param string $lang The specific language token
 * 
 * @return string The replaced language.
 */
function replaceLang(string $lang) {
    $file = file_get_contents(__DIR__ . '/../languages/languages.json');
    $languages = json_decode($file, true);

    $return = '';

    if(array_key_exists($lang, $languages)) {
        $return = $languages[$lang];
    } else {
        $return = $lang;
    }

    return $return;
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
