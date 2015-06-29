<?php

class DynamicTranslationAdmin extends ModelAdmin {

	private static $managed_models = array(
		'DynamicTranslation'
	);

	private static $url_segment = 'translations';

	private static $menu_title = 'Translations';

}
