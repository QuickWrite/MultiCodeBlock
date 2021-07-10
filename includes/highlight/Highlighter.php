<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once 'CustomOptions.php';

require_once 'Description.php';
require_once 'Code.php';

use PHPHtmlParser\Dom;

function getDOM(&$content) {
    $dom = new Dom();
    $dom->loadStr($content, getOptions());

    return $dom;
}

function createFrame($lang, &$code) {
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

function replaceLang($lang) {
    $file = file_get_contents(__DIR__ . '/languages/languages.json');
    $languages = json_decode($file, true);

    return $languages[$lang];
}

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

function combineCodeDescription($code, Description &$desc, Parser &$parser) {
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

function findCodeBlocks(&$codeblock) {
    preg_match_all("(< *code *>((.|\n)*?)<\/code *>)", $codeblock, $array);

    return $array[1];
}
