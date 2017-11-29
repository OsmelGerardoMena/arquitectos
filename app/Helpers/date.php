<?php

if (!function_exists('get_locale'))
{
	/**
	 * Test
	 *
	 * @param int - user id
	 * @return object
	 */
	function get_locale($locale) {

		$locale = $locale;

		switch ($locale) {
			
			case 'en':
				$locale = 'en_EN';
				break;
			
			case 'es':
			default:
				$locale = 'es_MX.utf8';
				break;
		}

		return $locale;
	}
}