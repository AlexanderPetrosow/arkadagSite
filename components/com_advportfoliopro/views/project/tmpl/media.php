<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;


?>

<?php if ($this->item->type == 1 && $this->item->video_link) : ?>
	<?php echo $this->loadTemplate('video'); ?>
<?php elseif ($this->item->type == 0 && count($this->item->images)) : ?>
	<?php echo $this->loadTemplate('images'); ?>
<?php endif; ?>