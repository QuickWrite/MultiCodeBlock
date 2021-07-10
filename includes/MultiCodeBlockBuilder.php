<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once 'CustomOptions.php';

require_once 'Description.php';
require_once 'Code.php';

use PHPHtmlParser\Dom;

/**
 * Returns the DOM-Parser with custom options and the HTML-Tree
 * 
 * @param string $content The content that should be parsed by the DOM-Parser
 * 
 * @return PHPHtmlParser\Dom The DOM-Element that has the HTML-Tree
 */
function getDOM(&$content) {
    $dom = new Dom();
    $dom->loadStr($content, getOptions());

    return $dom;
}

/**
 * Returns the MultiCodeBlock as a whole.
 * 
 * @param array $lang All of the languages of the different codeblocks.
 * @param string $code The codeblocks as a whole.
 * 
 * @return string The whole MultiCodeBlock.
 */
function createFrame(array $lang, string &$code) {
    $size = sizeof($lang);
    $copyIcon = '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg>';

    $return = '<div class="multicodeblock">
    <div class="copy" title="Copy code to clipboard">'.$copyIcon.'<span class="tooltip">Failed to copy!</span>
    </div>
    <div class="tabs">
    <div class="tab-sidebar">
    ';
    for($i = 0; $i < $size; $i++) {
        $return .= '<button class="tab-button '.($i == 0 ? 'tb-active' : '').'" data-for-tab="'.$i.'">'.$lang[$i].'</button>';
    }
    $return .= '</div>'.$code.'</div></div>';

    return $return;
}

/**
 * Returns a human-readable-version of the language.
 * 
 * @param string $lang The specific language token
 * 
 * @return string The replaced language.
 */
function replaceLang(string $lang) {
    $file = file_get_contents(__DIR__ . '/languages/languages.json');
    $languages = json_decode($file, true);

    return $languages[$lang];
}

/**
 * Returns a single codeblock
 * 
 * @param DOMElement $codevariant The content of the `<codevariant>` element.
 * @param string $code The code inside the `<codevariant>` block.
 * @param DOMElement $description The content of the `<desc>` element.
 * @param Parser $parser The parser object by MediaWiki
 * @param Highlighter $h1 The highlighter object
 * 
 * @return array The codeblock as the first element and the language as the second element.
 */
function createCodeBlock(&$codevariant, &$code, &$description, Parser &$parser, \Highlight\Highlighter &$h1) {

    $lang = $codevariant->getAttribute("lang");
    if($lang == null) {
        return array('<span style="color: red; font-size: 700;">No Lang Attribute</span>', 'No lang');
    }
    
    $lang = strtolower($lang);

    $code = new Code($code, $lang);
    $desc = new Description(($description == null ? null : $description->innerHtml));
    $highlight = $code->highlight($h1);

    $isObject = true;
    if(!isset($highlight->value)) {
        $isObject = false;
    }

    return array(combineCodeDescription(($isObject ? $highlight->value : $highlight), $desc, $parser), ($isObject ? replaceLang($highlight->language) : $lang));
}

/**
 * Returns the combined version of the code and the description.
 * 
 * @param string $code The code inside the `<code>` element
 * @param Description $desc The description as a Description object
 * @param Parser $parser The parser object by MediaWiki
 * 
 * @return string A combined version of the code and the description with the MediaWiki syntax.
 */
function combineCodeDescription(string $code, Description &$desc, Parser &$parser) {
    $arr = explode("\n", $code);
    $size = sizeof($arr);

    $keysSize = sizeof($desc->keys);

    $return = '<table class="code-table">';

    $isFirst = ($arr[0] === '' ? true : false);

    for($i = (!$isFirst ? 0 : 1), $j = 0; $i < $size; ++$j) {
        $return .= '<tr><th class="first"><pre><ol start="'.($i + 1 - $isFirst).'">';

        $nextKey = 0;
        if($keysSize > $j + 1) {
            $nextKey = $desc->keys[$j + 1] - 1 + $isFirst;

            if($nextKey > $size) {
                $nextKey = $size;
            }
        } else {
            $nextKey = $size;
        }

        while($i < $nextKey) {
            if(!($i + 1 == $size && $arr[$i] === ''))
                $return .= '<li><span class="line">'.($arr[$i] !== '' ? $arr[$i] : '&nbsp;').'</span></li>';
            
            $i++;
        }

        $return .= '</pre></ol></th><th class="second mw-body-content">'.$parser->recursiveTagParse($desc->texts[$j]).'</td>';
    }

    $return .= '</table>';

    return $return;
}

/**
 * Returns the code in the `<code>` tags.
 * 
 * @param string $codeblock The object where all of the codeblocks should be removed.
 * 
 * @return array An array of the code.
 */
function findCodeBlocks(string &$codeblock) {
    preg_match_all("(< *code *>((.|\n)*?)<\/code *>)", $codeblock, $array);

    return $array[1];
}
