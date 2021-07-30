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

/**
 * The hooks class for the MultiCodeBlock MediaWiki-Extension.
 * 
 * @author QuickWrite
 */
class MultiCodeBlockHooks {
	/**
	 * Sets a hook for the MediaWiki parser to be able to use the <multicodeblock>-Tag in the MediaWiki syntax.
	 * 
	 * @param Parser &$parser The Parser Element as a reference.
	 */
	public static function onParserFirstCallInit( Parser &$parser ) {
		$parser->setHook( 'multicodeblock', [ self::class, 'renderMultiCodeBlock' ] );
	}

	public static function renderMultiCodeBlock(string $input, array $args, Parser $parser, PPFrame $frame) {
		require_once __DIR__ . '/../MultiCodeBlock.php';

		return createMultiCodeBlock($input, $parser);
	}
}