<?php
class CurrentSkin {

	/**
	 * Register magic word ID
	 */
	public static function onMagicWordwgVariableIDs( &$variableIds ) {
		$variableIds[] = 'CURRENTSKIN';
		return true;
	}

	/**
	 * Magic word processing logic
	 */
	public static function onParserGetVariableValueSwitch(
		$parser,
		&$cache,
		$magicWordId,
		&$ret,
		$frame
	) {
		if ( $magicWordId === 'CURRENTSKIN' ) {
			$services = \MediaWiki\MediaWikiServices::getInstance();
			$userOptionsLookup = $services->getUserOptionsLookup();
			$user = $parser->getUserIdentity();
			$skin = $userOptionsLookup->getOption( $user, 'skin' );
			
			// Disable page caching (user-specific data)
			$parser->getOutput()->updateCacheExpiry(0);
			
			$ret = $skin;
		}
		return true;
	}
}			
			$ret = $skin;
		}
		return true;
	}
}
