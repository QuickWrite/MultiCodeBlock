<?php
/**
 * This file is part of MultiCodeBlock.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Protect against register_globals vulnerabilities.
 * This line must be present before any global variable is referenced.
 */
if( !defined( 'MEDIAWIKI' ) ) {
	echo( "This is an extension to the MediaWiki package and cannot be run standalone.\n" );
	die( -1 );
}

require_once 'MultiCodeBlockBuilder.php';

/**
 * The main class for the MultiCodeBlock MediaWiki-Extension.
 * 
 * @author QuickWrite
 */
class MultiCodeBlock {
	public static function onParserFirstCallInit( Parser &$parser ) {
		$parser->setHook( 'multicodeblock', [ self::class, 'renderMultiCodeBlock' ] );
	}

	/**
	 * Returns a string based on the MultiCodeBlock HTML-Element
	 * 
	 * @param string $input The content of the MultiCodeBlock HTML-Element
	 * @param array $args The arguments of the MultiCodeBlock HTML-Element
	 * @param Parser $parser The MediaWiki syntax parser
	 * @param PPFrame $frame MediaWiki frame
	 */
	public static function renderMultiCodeBlock( string $input, array $args, Parser $parser, PPFrame $frame ) {
		$out = $parser->getOutput();
		$out->addModuleStyles( [ 'ext.multicodeblock.styles' ] );
		$out->addModules( [ 'ext.multicodeblock.js' ] );

		$code = findCodeBlocks($input);
    
		$replaced = str_replace($code, 'test', $input);
		$dom = getDOM($replaced);
	
		$codevariants = $dom->getElementsbyTagName('codeblock');

		$descriptions = [];
		foreach($codevariants as $codevariant) {
			array_push($descriptions, $codevariant->getElementsbyTagName('desc'));
		}
		$codeArr = [];
		foreach($codevariants as $codevariant) {
			array_push($codeArr, $codevariant->getElementsbyTagName('code'));
		}
		
		$size = sizeof($codevariants);
		$return = "";
		$languages = array();

		$h1 = new \Highlight\Highlighter();
	
		$last = 0;
		for($i = 0; $i < $size; ++$i) {
			$length = sizeof($codeArr[$i]);
			$codeBlocks = array_slice($code, $last, $length);

			$last += $length;

			$codeblock = createCodeBlock($codeBlocks, $descriptions[$i], $codevariants[$i]->getAttribute('lang'), $parser, $h1);
			$return .= createTab($codeblock[0], $i);
			array_push($languages, $codeblock[1]);
		}
	
		return array(createFrame($languages, $return), 'markerType' => 'nowiki');
	}
}