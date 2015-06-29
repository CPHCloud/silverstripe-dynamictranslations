<?php

class DynamicTranslationProvider implements TemplateGlobalProvider {

	/**
	 * @var DynamicTranslationAdapter
	 */
	protected static $translation_adapter;

	/**
	 * @param DynamicTranslationAdapter
	 */
	public static function set_translation_adapter($adapter) {
		self::$translation_adapter = $adapter;
	}

	/**
	 * @return DynamicTranslationAdapter
	 */
	public static function get_translation_adapter() {
		if (!self::$translation_adapter) {
			self::$translation_adapter = Injector::inst()->get('DynamicTranslationAdapter');
		}

		return self::$translation_adapter;
	}

	/**
	 * @return array
	 */
	public static function get_template_global_variables() {
		return array(
			'dt'
		);
	}

	/**
	 * @param string $entity
	 * @param string $string
	 * @param array $injectionArray
	 * @return string
	 */
	public static function dt($entity, $string = '', $injectionArray = array()) {
		$adapter = static::get_translation_adapter();
		$translated = $adapter->translate($entity, $string, $injectionArray);

		// Don't escape HTML tags
		// TODO
		$field = DBField::create_field('HTMLText', $translated);
		$field->setOptions(array('shortcodes' => false));
		return $field;
	}

}
