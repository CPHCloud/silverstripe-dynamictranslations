<?php

class DynamicTranslation extends DataObject implements PermissionProvider {

	/**
	 * @var array
	 */
	private static $db = array(
		'Entity' => 'Varchar(255)',
		'String' => 'Text'
	);

	/**
	 * @var array
	 */
	private static $has_one = array(
		'Category' => 'DynamicTranslationCategory'
	);

	/**
	 * @var array
	 */
	private static $summary_fields = array(
		'Entity' => 'Entity',
		'String' => 'String',
		'Category.Title' => 'Category'
	);

	/**
	 * @var array
	 */
	private static $searchable_fields = array(
		'Entity' => 'Entity',
		'String' => 'String',
		'CategoryID' => array(
			'title' => 'Category'
		)
	);

	/**
	 * @return array
	 */
	public function providePermissions() {
		return array(
			'DYNAMIC_TRANSLATION_MANAGE' => _t('DynamicTranslation.Manage', 'Manage dynamic translations')
		);
	}

	/**
	 * @param Member|int|null $member
	 * @return boolean
	 */
	public function canView($member = null) {
		return Permission::check('DYNAMIC_TRANSLATION_MANAGE', 'any', $member);
	}

	/**
	 * @param Member|int|null $member
	 * @return boolean
	 */
	public function canEdit($member = null) {
		return Permission::check('DYNAMIC_TRANSLATION_MANAGE', 'any', $member);
	}

	/**
	 * @param Member|int|null $member
	 * @return boolean
	 */
	public function canCreate($member = null) {
		return Permission::check('DYNAMIC_TRANSLATION_MANAGE', 'any', $member);
	}

	/**
	 * @param Member|int|null $member
	 * @return boolean
	 */
	public function canDelete($member = null) {
		return Permission::check('DYNAMIC_TRANSLATION_MANAGE', 'any', $member);
	}
	

	/**
	 * After writing a translation, update the item in the cache
	 * @todo Should we just wipe the item from the cache instead?
	 */
	public function onAfterWrite() {
		$key = md5($this->Entity);
		DynamicTranslationAdapter::get_cache()->save($this->String, $key);

		parent::onAfterWrite();
	}

	/**
	 * @return string|null
	 */
	public function getTitle() {
		return $this->Entity;
	}

	/**
	 * @return FieldList
	 */
	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->makeFieldReadonly('Entity');

		return $fields;
	}

}
