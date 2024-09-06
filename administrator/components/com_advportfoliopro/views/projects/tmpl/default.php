<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('dropdown.init');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$archived	= $this->state->get('filter.state') == 2 ? true : false;
$trashed	= $this->state->get('filter.state') == -2 ? true : false;
$canOrder	= $user->authorise('core.edit.state', 'com_advportfoliopro.category');
$saveOrder	= $listOrder == 'a.ordering';

if ($saveOrder) {
	$saveOrderingUrl = 'index.php?option=com_advportfoliopro&task=projects.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'projectList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();

?>

<form action="<?php echo JRoute::_('index.php?option=com_advportfoliopro&view=projects'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty($this->sidebar)) : ?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10  j-main-container">

<?php else : ?>
	<div id="j-main-container" class="j-main-container">
<?php endif; ?>

	<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
	<div class="clearfix"></div>

	<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else : ?>
		<table class="table table-striped table-list" id="projectList">
			<thead>
				<tr>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
					</th>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th width="1%" style="min-width:55px" class="nowrap center">
						<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
					</th>
					<th class="title">
						<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
					</th>
					<th width="5%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort', 'JAUTHOR', 'a.created_by', $listDirn, $listOrder); ?>
					</th>
					<th width="5%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort', 'JDATE', 'a.created', $listDirn, $listOrder); ?>
					</th>
					<th width="5%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'a.language', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tbody>

			<?php foreach ($this->items as $i => $item) :
				$canCreate		= $user->authorise('core.create',			'com_advportfoliopro' . '.category.' . $item->catid);
				$canEdit		= $user->authorise('core.edit',				'com_advportfoliopro' . '.category.' . $item->catid);
				$canCheckin		= $user->authorise('core.manage',			'com_checkin') || $item->checked_out == $user->get('id') || $item->checked_out == 0;
				$canChange		= $user->authorise('core.edit.state',		'com_advportfoliopro' . '.category.' . $item->catid) && $canCheckin;
				?>

				<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->catid; ?>">
					<td class="order nowrap center hidden-phone">
					<?php if ($canChange) :
						$disableClassName	= '';
						$disabledLabel		= '';

						if (!$saveOrder) :
							$disabledLabel		= JText::_('JORDERINGDISABLED');
							$disableClassName	= ' inactive tip-top';
						endif; ?>

						<span class="sortable-handler hasTooltip<?php echo $disableClassName; ?>" title="<?php echo $disabledLabel; ?>">
							<i class="icon-menu"></i>
						</span>
						<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order" />
					<?php else : ?>
						<span class="sortable-handler inactive">
							<i class="icon-menu"></i>
						</span>
					<?php endif; ?>

					</td>
					<td class="center hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<td class="center">
						<div class="btn-group">
							<?php echo JHtml::_('jgrid.published', $item->state, $i, 'projects.', $canChange, 'cb'); ?>
							<?php echo JHtml::_('advportfoliopro.projectfeatured', $item->featured, $i, $canChange); ?>
						</div>
					</td>
					<td class="nowrap has-context">
						<div class="pull-left">
							<?php if ($item->checked_out) : ?>
							<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'projects.', $canCheckin); ?>
							<?php endif; ?>

							<?php if ($canEdit) : ?>
							<a href="<?php echo JRoute::_('index.php?option=com_advportfoliopro&task=project.edit&id=' . (int) $item->id); ?>">
								<?php echo $this->escape($item->title); ?>
							</a>
							<?php else : ?>
							<?php echo $this->escape($item->title); ?>
							<?php endif; ?>
							<span class="small">
								<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>
							</span>
							<div class="small">
								<?php echo JText::_('JCATEGORY') . ': ' . $this->escape($item->category_title); ?>
							</div>
						</div>
						<div class="pull-left">
						<?php
							// Create dropdown items
							JHtml::_('dropdown.edit', $item->id, 'project.');

							JHtml::_('dropdown.divider');
							if ($item->state) :
								JHtml::_('dropdown.unpublish', 'cb' . $i, 'projects.');
							else :
								JHtml::_('dropdown.publish', 'cb' . $i, 'projects.');
							endif;

							JHtml::_('dropdown.divider');
							if ($archived) :
								JHtml::_('dropdown.unarchive', 'cb' . $i, 'projects.');
							else :
								JHtml::_('dropdown.archive', 'cb' . $i, 'projects.');
							endif;

							if ($item->checked_out) :
								JHtml::_('dropdown.checkin', 'cb' . $i, 'projects.');
							endif;

							if ($trashed) :
								JHtml::_('dropdown.untrash', 'cb' . $i, 'projects.');
							else :
								JHtml::_('dropdown.trash', 'cb' . $i, 'projects.');
							endif;

							// Render dropdown list
							echo JHtml::_('dropdown.render');
							?>
						</div>
					</td>
					<td class="small hidden-phone">
						<?php echo $this->escape($item->access_level); ?>
					</td>
					<td class="small hidden-phone">
						<?php echo $this->escape($item->author_name); ?>
					</td>
					<td class="nowrap small hidden-phone">
						<?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?>
					</td>
					<td class="center nowrap hidden-phone">
						<?php if ($item->language == '*') : ?>
						<?php echo JText::alt('JALL', 'language'); ?>
						<?php else : ?>
						<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
						<?php endif; ?>
					</td>
					<td class="center hidden-phone">
						<?php echo (int) $item->id; ?>
					</td>
				</tr>

				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>

	<?php echo $this->pagination->getListFooter(); ?>
	<?php // Load the batch processing form. ?>
	<?php echo $this->loadTemplate('batch'); ?>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>

<?php
echo AdvPortfolioProFactory::getFooter();