<?php

namespace MediaWiki\Extension\CurrentSkin;

use MediaWiki\Hook\ParserFirstCallInitHook;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserIdentity;
use Parser;
use PPFrame;

/**
 * Class containing the hook handlers for the CurrentSkin extension.
 */
class Hooks {

	/**
	 * Registers the 'CURRENTSKIN' magic word.
	 * Hook: MagicWordwgVariableIDs
	 *
	 * @param array &$variableIds
	 */
	public static function onMagicWordwgVariableIDs( array &$variableIds ): bool {
		$variableIds[] = 'CURRENTSKIN';
		return true;
	}

	/**
	 * Sets the value for the 'CURRENTSKIN' magic word.
	 * Hook: ParserGetVariableValueSwitch
	 *
	 * @param Parser $parser
	 * @param array &$cache
	 * @param string $magicWordId
	 * @param mixed &$ret
	 * @param PPFrame $frame
	 * @return bool
	 */
	public static function onParserGetVariableValueSwitch(
		Parser $parser,
		array &$cache,
		string $magicWordId,
		&$ret,
		PPFrame $frame
	): bool {
		if ( $magicWordId !== 'CURRENTSKIN' ) {
			return true;
		}

		// The output depends on the user, so the parser cache must be disabled.
		$parser->getOutput()->updateCacheExpiry( 0 );

		$services = MediaWikiServices::getInstance();
		$user = $parser->getUserIdentity();

		if ( $user->isAnon() ) {
			$ret = $services->getMainConfig()->get( 'DefaultSkin' );
		} else {
			$userOptionsLookup = $services->getUserOptionsLookup();
			$ret = $userOptionsLookup->getOption( $user, 'skin' );
		}

		return true;
	}
}
