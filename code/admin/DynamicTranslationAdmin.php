<?php

class DynamicTranslationAdmin extends ModelAdmin {

	/**
	 * @var array
	 */
	private static $managed_models = array(
		'DynamicTranslation',
		'DynamicTranslationCategory'
	);

	/**
	 * @var string
	 */
	private static $url_segment = 'translations';

	/**
	 * @var string
	 */
	private static $menu_title = 'Translations';

}
