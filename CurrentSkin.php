<?php
class CurrentSkin {

	/**
	 * Register magic word ID
	 */
	public static function onMagicWordwgVariableIDs( array &$variableIds ): bool {
		$variableIds[] = 'CURRENTSKIN';
		return true;
	}

	/**
	 * Magic word processing logic
	 */
	public static function onParserGetVariableValueSwitch(
		Parser $parser,
		array &$cache,
		string $magicWordId,
		&$ret,
		PPFrame $frame
	): bool {
		// Early return if not our magic word
		if ( $magicWordId !== 'CURRENTSKIN' ) {
			return true;
		}

		$services = \MediaWiki\MediaWikiServices::getInstance();
		$userOptionsLookup = $services->getUserOptionsLookup();
		$user = $parser->getUserIdentity();
		
		// Fetch user's skin preference
		$skin = $userOptionsLookup->getOption( $user, 'skin' );
		
		// Disable page caching (user-specific data)
		$parser->getOutput()->updateCacheExpiry(0);
		
		$ret = $skin;
		return true;
	}
}
