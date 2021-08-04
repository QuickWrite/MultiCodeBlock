<?php
/**
 * This file is part of MultiCodeBlock.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * The hooks class for the MultiCodeBlock MediaWiki-Extension.
 * 
 * @author QuickWrite
 */
class MultiCodeBlockHooks {
	/**
	 * Sets a hook for the MediaWiki parser to be able to use the <multicodeblock>-Tag in the MediaWiki syntax.
	 * 
	 * @param Parser $parser The Parser Element as a reference.
	 */
	public static function onParserFirstCallInit( Parser &$parser ) {
		$parser->setHook( 'multicodeblock', [ self::class, 'renderMultiCodeBlock' ] );
	}

	/**
	 * Returns the full MultiCodeBlock with differnt flags and adds specific Modules to the parser.
	 * 
	 * @param string $input The content of the MultiCodeBlock HTML-Element
 	 * @param array $args The arguments of the MultiCodeBlock HTML-Element
 	 * @param Parser $parser The MediaWiki syntax parser
	 * @param PPFrame $frame MediaWiki frame
	 * 
	 * @return array The MultiCodeBlock with flags as an array.
	 */
	public static function renderMultiCodeBlock(string $input, array $args, Parser $parser, PPFrame $frame) {
		require_once __DIR__ . '/../MultiCodeBlock.php';

		$out = $parser->getOutput();
    	$out->addModuleStyles(['ext.multicodeblock.styles']);
    	$out->addModules(['ext.multicodeblock.js']);

		return [ createMultiCodeBlock($input, $parser), 'noparse' => true, 'isHTML' => true, 'markerType' => 'nowiki' ];
	}
}