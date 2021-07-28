<?php
/**
 * This file is part of MultiCodeBlock.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once 'require.php';

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
