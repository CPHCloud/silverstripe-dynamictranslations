<?php

/**
 * Dynamic Translations module for SilverStripe
 */

// Dynamic translations should never time-out
SS_Cache::set_cache_lifetime('DynamicTranslations', null, 100);
