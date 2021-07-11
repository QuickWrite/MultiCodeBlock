<?php
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

	public static function onBeforePageDisplay( OutputPage &$out ) {
		$out->addModuleStyles( [ 'ext.multicodeblock.styles' ] );
		$out->addModules( [ 'ext.multicodeblock.js' ] );
	}

	/**
	 * Returns a string based on the MultiCodeBlock HTML-Element
	 * 
	 * @param string $input The content of the MultiCodeBlock HTML-Element
	 * @param array $args The arguments of the MultiCodeBlock HTML-Element
	 * @param Parser $parser The MediaWiki syntax parser
	 * @param PPFrame $frame MediaWiki frame
	 */
	public static function renderMultiCodeBlock( $input, array $args, Parser $parser, PPFrame $frame ) {
		$code = findCodeBlocks($input);
    
		$replaced = str_replace($code, '', $input);
		$dom = getDOM($replaced);
	
		$codevariants = $dom->getElementsbyTagName('codeblock');
		$descriptions = $dom->getElementsbyTagName('desc');
	
		$size = sizeof($codevariants);
		$return = "";
		$languages = array();

		$h1 = new \Highlight\Highlighter();
	
		for($i = 0; $i < $size; ++$i) {
			$desc = $descriptions->item($i);

			$codeblock = createCodeBlock($code[$i], $desc, $codevariants[$i]->getAttribute('lang'), $parser, $h1);
			$return .= '<div class="tab-content '.($i == 0 ? 'tc-active' : '').'" data-tab="'.$i.'">'.$codeblock[0].'</div>';
			array_push($languages, $codeblock[1]);
		}
	
		return createFrame($languages, $return);
	}
}