<?php
class CurrentSkin {

	/**
	 * 注册魔术字ID
	 */
	public static function onMagicWordwgVariableIDs( &$variableIds ) {
		$variableIds[] = 'CURRENTSKIN';
		return true;
	}

	/**
	 * 处理魔术字逻辑 - 使用正确的UserOptionsLookup服务
	 */
	public static function onParserGetVariableValueSwitch(
		$parser,
		&$cache,
		$magicWordId,
		&$ret,
		$frame
	) {
		if ( $magicWordId === 'CURRENTSKIN' ) {
			// 获取服务容器
			$services = \MediaWiki\MediaWikiServices::getInstance();
			
			// 获取用户选项服务
			$userOptionsLookup = $services->getUserOptionsLookup();
			
			// 获取当前用户
			$user = $parser->getUserIdentity();
			
			// 获取当前皮肤
			$skin = $userOptionsLookup->getOption( $user, 'skin' );
			
			// 禁用页面缓存（因内容按用户变化）
			$parser->getOutput()->updateCacheExpiry(0);
			
			$ret = $skin;
		}
		return true;
	}
}