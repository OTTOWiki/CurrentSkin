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
	 * Hook handler for MagicWordwgVariableIDs.
	 * Registers the {{CURRENTSKIN}} magic word.
	 *
	 * @param array &$variableIds The list of magic word IDs.
	 * @return bool Always true.
	 */
	public static function onMagicWordwgVariableIDs( array &$variableIds ): bool {
		$variableIds[] = 'CURRENTSKIN';
		return true;
	}

	/**
	 * Hook handler for ParserGetVariableValueSwitch.
	 * Processes the {{CURRENTSKIN}} magic word and returns the current user's skin.
	 *
	 * @param Parser $parser The parser object.
	 * @param array &$cache The cache object.
	 * @param string $magicWordId The ID of the magic word being processed.
	 * @param mixed &$ret The return value, to be set by the hook.
	 * @param PPFrame $frame The preprocessor frame.
	 * @return bool True to continue processing, false to stop.
	 */
	public static function onParserGetVariableValueSwitch(
		Parser $parser,
		array &$cache,
		string $magicWordId,
		&$ret,
		PPFrame $frame
	): bool {
		// Early return if it's not our magic word.
		if ( $magicWordId !== 'CURRENTSKIN' ) {
			return true;
		}

		// This is the most critical part for correctness.
		// Since the output depends on the current user, we must disable
		// the parser cache for any page that uses this magic word.
		// Be aware of the performance implications of this action.
		$parser->getOutput()->updateCacheExpiry( 0 );

		$user = $parser->getUserIdentity();
		$services = MediaWikiServices::getInstance();

		// Handle anonymous users by returning the site's default skin.
		if ( $user->isAnon() ) {
			$ret = $services->getMainConfig()->get( 'DefaultSkin' );
			return true;
		}

		// For logged-in users, look up their specific skin preference.
		$userOptionsLookup = $services->getUserOptionsLookup();
		$skin = $userOptionsLookup->getOption( $user, 'skin' );

		$ret = $skin;
		return true;
	}
}		$ret = $skin;
		return true;
	}
}
