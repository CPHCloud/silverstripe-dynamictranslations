<?php

class DynamicTranslationCategory extends DataObject {

	/**
	 * @var array
	 */
	private static $db = array(
		'Title' => 'Varchar(255)'
	);

	/**
	 * @var array
	 */
	private static $has_many = array(
		'Translations' => 'DynamicTranslation'
	);

	/**
	 * Create default categories from YAML configuration
	 */
	public function requireDefaultRecords() {
		parent::requireDefaultRecords();

		// Only create default records if no records are present
		if (!self::get()->count()) {
			$categories = $this->config()->default_categories;

			foreach ($categories as $name) {
				self::create(array('Title' => $name))->write();
			}
		}
	}

}
