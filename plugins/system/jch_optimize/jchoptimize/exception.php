<?php

/**
 * JCH Optimize - Aggregate and minify external resources for optmized downloads
 * 
 * @author Samuel Marshall <sdmarshall73@gmail.com>
 * @copyright Copyright (c) 2010 Samuel Marshall
 * @license GNU/GPLv3, See LICENSE file
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

namespace JchOptimize\Core;

defined('_JEXEC') or die('Restricted access');

class Exception extends \Exception
{

        public function __construct($message = null, $code = 0)
        {
                if (!$message)
                {
			$this->message = 'Unknown Exception';
                }

		$error_message = get_class($this) . " '{$message}' in {$this->getFile()}({$this->getLine()})\n"
		       . "{$this->getTraceAsString()}";	

                parent::__construct($error_message, $code);
        }
}

