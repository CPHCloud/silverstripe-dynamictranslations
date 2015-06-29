<?php

class DynamicTranslationAdapter {

	/**
	 * Translate the given entity, or fall back to the default string if no matching entity exists 
	 * in the database
	 * @param string $entity
	 * @param string $string
	 * @param array $injectionArray
	 * @return string
	 */
	public function translate($entity, $string = '', $injectionArray = array()) {
		$string = $this->findOrCreateString($entity, $string);

		if ($injectionArray) {
			$this->inject($string, $injectionArray);
		}

		return $string;
	}

	/**
	 * Find a string matching the given entity in the cache, or create one and add it.
	 * @param string $entity
	 * @param string $string
	 * @return string
	 */
	protected function findOrCreateString($entity, $string) {
		$cache = static::get_cache();
		$key = md5($entity);
		
		// It's not in the cache, which means it might have expired or might be a new entity
		if (!$result = $cache->load($key)) {
			$translation = DynamicTranslation::get()->filter(array('Entity' => $entity))->first();

			// If no translation exists for this entity, create it
			if (!$translation) {
				$translation = DynamicTranslation::create(array('Entity' => $entity, 'String' => $string));
				$translation->write();
			}
			
			$cache->save($translation->String, $key);
			$result = $translation->String;
		}

		return $result;
	}

	/**
	 * @var Zend_Cache_Frontend
	 */
	protected static $cache;

	/**
	 * @return Zend_Cache_Frontend
	 */
	public static function get_cache() {
		if (!self::$cache) {
			self::$cache = SS_Cache::factory('DynamicTranslations', 'Output', array('automatic_serialization' => true));
		}

		return self::$cache;
	}

	/**
	 * Inject the given parameters into the string. This is lifted directly from {@link i18n::_t()}.
	 * @param string $string
	 * @param array $injectionArray
	 * @return string
	 */
	protected function inject($string, $injectionArray) {
		$regex = '/\{[\w\d]*\}/i';
		if (!preg_match($regex, $string)) {
			// Legacy mode: If no injection placeholders are found,
			// replace sprintf placeholders in fixed order.
			// Fail silently in case the translation is outdated
			preg_match_all('/%[s,d]/', $string, $returnValueArgs);
			if ($returnValueArgs) foreach($returnValueArgs[0] as $i => $returnValueArg) {
				if ($i >= count($injectionArray)) {
					$injectionArray[] = '';
				}
			}
			$replaced = vsprintf($string, array_values($injectionArray));
			if ($replaced) $string = $replaced;
		} else if (!ArrayLib::is_associative($injectionArray)) {
			// Legacy mode: If injection placeholders are found,
			// but parameters are passed without names, replace them in fixed order.
			$string = preg_replace_callback(
				$regex,
				function($matches) use(&$injectionArray) {
					return $injectionArray ? array_shift($injectionArray) : '';
				},
				$string
			);
		} else {
			// Standard placeholder replacement with named injections and variable order.
			foreach ($injectionArray as $variable => $injection) {
				$placeholder = '{'.$variable.'}';
				$string = str_replace($placeholder, $injection, $string, $count);
				if (!$count) {
					SS_Log::log(sprintf(
						"Couldn't find placeholder '%s' in translation string '%s' (id: '%s')",
						$placeholder,
						$string,
						$entity
					), SS_Log::NOTICE);
				}
			}
		}

		return $string;
	}

}
