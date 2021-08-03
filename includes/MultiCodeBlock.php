<?php
/**
 * This file is part of MultiCodeBlock.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__ . '/vendor/autoload.php';

require_once 'class/Description.php';
require_once 'class/Code.php';
require_once 'class/LanguageBlock.php';

require_once 'utils/HTMLFramework.php';
require_once 'utils/getData.php';

/**
 * Returns a string based on the MultiCodeBlock HTML-Element
 * 
 * @param string $input The content of the MultiCodeBlock HTML-Element
 * @param Parser $parser The MediaWiki syntax parser
 * 
 * @return string The MultiCodeBlock
 */
function createMultiCodeBlock(string &$input, Parser &$parser) {
    $code = findCodeBlocks($input);

    $replaced = str_replace($code, 'test', $input);
    $dom = getDOM($replaced);

    $codevariants = $dom->getElementsbyTagName('codeblock');

    $descriptions = [];
    foreach ($codevariants as $codevariant) {
        array_push($descriptions, $codevariant->getElementsbyTagName('desc'));
    }
    $codeArr = [];
    foreach ($codevariants as $codevariant) {
        array_push($codeArr, $codevariant->getElementsbyTagName('code'));
    }

    $size = sizeof($codevariants);
    $return = "";
    $languages = [];

    $h1 = new \Highlight\Highlighter();

    $last = 0;
    for ($i = 0; $i < $size; ++$i) {
        $length = sizeof($codeArr[$i]);
        $codeBlocks = array_slice($code, $last, $length);

        $last += $length;

        $codeblock = createCodeBlock($codeBlocks, $descriptions[$i], $codevariants[$i]->getAttribute('lang'), $parser, $h1);
        $return .= createTab($codeblock[0], $i);
        array_push($languages, $codeblock[1]);
    }

    return createFrame($languages, $return);
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
 * Returns the combined version of the code and the description.
 * 
 * @param string $code The code inside the `<code>` element
 * @param Description $desc The description as a Description object
 * @param Parser $parser The parser object by MediaWiki
 * 
 * @return string A combined version of the code and the description with the MediaWiki syntax.
 */
function &combineCodeDescription(string $code, Description &$desc, Parser &$parser) {
    $arr = explode("\n", $code);
    $size = sizeof($arr);

    $keysSize = sizeof($desc->keys);

    $return = '<table class="code-table">
        <tr class="table-header">
            <th>Code</th>
            <th>Description</th>
        </tr>
    ';

    $isFirst = ($arr[0] === '' ? true : false);

    for ($i = (!$isFirst ? 0 : 1), $j = 0; $i < $size; ++$j) {
        $return .= '<tr><th class="first"><pre><ol start="' . ($i + 1 - $isFirst) . '">';

        $nextKey = 0;
        if ($keysSize > $j + 1) {
            $nextKey = $desc->keys[$j + 1] - 1 + $isFirst;

            if ($nextKey > $size) {
                $nextKey = $size;
            }
        } else {
            $nextKey = $size;
        }

        while ($i < $nextKey) {
            if (!($i + 1 == $size && $arr[$i] === ''))
                $return .= '<li><span class="line">' . ($arr[$i] !== '' ? $arr[$i] : '&nbsp;') . '</span></li>';

            $i++;
        }

        $return .= '</pre></ol></th><th class="second">' . $parser->recursiveTagParse($desc->texts[$j]) . '</td>';
    }

    $return .= '</table>';

    return $return;
}
