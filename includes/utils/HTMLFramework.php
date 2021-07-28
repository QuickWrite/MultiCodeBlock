<?php
/**
 * This file is part of MultiCodeBlock.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Creates an HTML-Element to warp around the content.
 * 
 * @param string $code The code where the Element should wrap around
 * @param int $index The index of the element
 * @param string $extra Another class to be added to the element.
 * 
 * @return string The full object.
 */
function createTab(string &$code, int $index, string $extra = 'outer') {
    return '<div class="'.$extra.' tab-content '.($index == 0 ? 'tc-active' : '').'" data-tab="'.$index.'">'.$code.'</div>';
}

/**
 * Returns the MultiCodeBlock as a whole.
 * 
 * @param array $lang All of the languages of the different codeblocks.
 * @param string $code The codeblocks as a whole.
 * @param bool $addCopy If a copy button should be added to the element.
 * @param string $extra Another class to be added to the element.
 * 
 * @return string The whole MultiCodeBlock.
 */
function createFrame(array &$lang, string &$code, bool $addCopy = true, string $extra = 'outer') {
    $size = sizeof($lang);
    $copyIcon = '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg>';

    $return = '<div class="multicodeblock">
    '.($addCopy ? '<div class="copy" title="Copy code to clipboard">'.$copyIcon.'<span class="tooltip">Failed to copy!</span></div>': '').'
    <div class="'.$extra.' tabs">
    <div class="'.$extra.' tab-sidebar">
    ';
    for($i = 0; $i < $size; $i++) {
        $return .= '<button class="'.$extra.' tab-button '.($i == 0 ? 'tb-active' : '').'" data-for-tab="'.$i.'">'.$lang[$i].'</button>';
    }
    $return .= '</div>'.$code.'</div></div>';

    return $return;
}

/**
 * Returns a single codeblock
 * 
 * @param string $codeTags The code inside the `<codevariant>` block.
 * @param DOMElement $descriptions The content of the `<desc>` element.
 * @param string $lang The language of the `<codevariant>`.
 * @param Parser $parser The parser object by MediaWiki.
 * @param Highlighter $h1 The highlighter object.
 * 
 * @return array The codeblock as the first element and the language as the second element.
 */
function createCodeBlock(array &$codeTags, DOMNodeList &$descriptions, $lang, Parser &$parser, \Highlight\Highlighter &$h1) {
    if($lang == null) {
        return array('<span style="color: red; font-size: 700;">No Lang Attribute</span>', 'No lang');
    }
    
    $lang = strtolower($lang);

    $languageBlock = new LanguageBlock($codeTags, $descriptions, $lang);
    
    $return = '';

    $versions = [];

    for($i = 0;$i < $languageBlock->size; ++$i) {
        if($languageBlock->code[$i] === null) {
            continue;
        }

        $highlight = $languageBlock->code[$i]->highlight($h1);

        $isObject = true;
        if(!isset($highlight->value)) {
            $isObject = false;
        }
    
        array_push($versions, 'Version #'.($i + 1));
        $return .= createTab(combineCodeDescription(($isObject ? $highlight->value : $highlight), $languageBlock->getDescription($i), $parser), $i, 'inner');
    }
    
    $return = createFrame($versions, $return, false, 'inner');

    return array($return, replaceLang($lang));
}