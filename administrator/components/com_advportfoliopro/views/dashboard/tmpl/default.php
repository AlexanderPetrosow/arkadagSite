<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

$xml	= simplexml_load_file(JPATH_ROOT . '/administrator/components/com_advportfoliopro/advportfoliopro.xml');
?>
<?php if(!empty( $this->sidebar)): ?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>

<div id="j-main-container" class="span10">

<?php else : ?>
<div id="j-main-container">

<?php endif;?>
	<div class="row">
		<div class="span6 col-md-6">
			<div class="well well-small j-main-container card-body">
				<div class="module-title nav-header">
					<?php echo JText::_('COM_ADVPORTFOLIOPRO_SUBMENU_DASHBOARD'); ?>
				</div>
				<div class="row-striped">
					<div id="cpanel">
						<?php
						$this->_quickIcon('index.php?option=com_advportfoliopro&view=projects', 'icon-64-projects.png', 'COM_ADVPORTFOLIOPRO_SUBMENU_PROJECTS');
						$this->_quickIcon('index.php?option=com_categories&extension=com_advportfoliopro', 'icon-64-categories.png', 'COM_ADVPORTFOLIOPRO_SUBMENU_CATEGORIES');
						$this->_quickIcon('index.php?option=com_config&view=component&component=com_advportfoliopro&return=' . urlencode(base64_encode(JUri::getInstance())), 'icon-64-config.png', 'COM_ADVPORTFOLIOPRO_SUBMENU_CONFIG');
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="span6 col-md-6">
			<div class="card card-light">
				<div class="card-body">
					<?php echo $xml->description; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
echo AdvPortfolioProFactory::getFooter();