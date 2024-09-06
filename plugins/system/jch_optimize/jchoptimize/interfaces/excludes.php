<?php

/**
 * JCH Optimize - Aggregate and minify external resources for optmized downloads
 *
 * @author    Samuel Marshall <sdmarshall73@gmail.com>
 * @copyright Copyright (c) 2010 Samuel Marshall
 * @license   GNU/GPLv3, See LICENSE file
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */

namespace JchOptimize\Interfaces;

defined('_JEXEC') or die('Restricted access');

interface ExcludesInterface
{
	/**
	 *
	 * @return string
	 */
	public static function extensions();

	/**
	 * @param   string  $type
	 * @param   string  $section
	 *
	 * @return array
	 */
	public static function head($type, $section = 'file');

	/**
	 * @param   string  $type
	 * @param   string  $section
	 *
	 * @return array
	 */
	public static function body($type, $section = 'file');

	/**
	 * @param   string  $url
	 *
	 * @return boolean
	 */
	public static function editors($url);
}
