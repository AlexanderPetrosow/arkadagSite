<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;
use Joomla\CMS\Application\CmsApplication;
/**
 * Helper for Advanced Portfolio Pro Module.
 *
 * @package		Joomla.Site
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class ModAdvPortfolioProHelper {

	public static function getList($params) {
		JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_advportfoliopro/models');
		require_once JPATH_ROOT . '/components/com_advportfoliopro/helpers/query.php';

		// Get an instance of the project model
		$model = JModelLegacy::getInstance('Projects', 'AdvPortfolioproModel', array('ignore_request' => true));

		// Set application parameters in model
		$app		= JFactory::getApplication();
		$appParams	= $app->getParams();
		$orderby = $params->get('orderby', 'rdate');
		$orderdate = ' a.created ';
		$model->setState('params', $appParams);

		$model->setState('list.select', 'a.*');

		$model->setState('filter.state', 1);
		$model->setState('filter.access', 1);

		$catids	= $params->get('catids', array());

		$model->setState('filter.category_id', $catids);

		$model->setState('list.start', 0);
		$model->setState('list.limit', (int) $params->get('limit', 5));
		$orderCol = AdvPortfolioProHelperQuery::orderbyProject($orderby, $orderdate);
		$model->setState('list.ordering', $orderCol);
		$model->setState('list.direction', '');

		// Filter by language
		$model->setState('filter.language',$app->getLanguageFilter());

		$items = $model->getItems();

		$displayType = $params->get('display_type', 1);

		if ($displayType == 0) {
			foreach ($items as &$item) {
				$item->tags	= array();
				$tags 		= new JHelperTags();
				$tags		= $tags->getItemTags('com_advportfoliopro.project', $item->id);

				foreach ($tags as $tag) {
					$tmp			= new stdClass();
					$tmp->title		= $tag->title;
					$tmp->alias		= $tag->alias;
					$tmp->ordering	= $tag->lft;
					$item->tags[]	= $tmp;
				}
			}
		}

		return $items;
	}

	public static function gettotalProjects($catids) {

		$total =  JModelLegacy::getInstance('Projects', 'AdvPortfolioproModel', array('ignore_request' => true));
		$total->setState('filter.category_id', $catids);
		$total->setState('filter.state', 1);
		$total->setState('list.limit', 0);

		$number_item = count($total->getItems());
		return $number_item;
	}

	public static function getProjectsAjax() {
		require_once JPATH_ROOT . '/components/com_advportfoliopro/helpers/route.php';
		require_once JPATH_ROOT . '/components/com_advportfoliopro/helpers/query.php';

		JHtml::addIncludePath(JPATH_ROOT . '/administrator/components/com_advportfoliopro/helpers/html/');
		$app = JFactory::getApplication();
		$input  = $app->input;

		$limit							= $input->getInt('limit');
		$numberload						= $input->getInt('numberload');
		$page							= $input->getInt('page');
		$catids							= $input->getString('catids');
		$orderby						= $input->getString('orderby');
		$orderdate						= ' a.created ';
		$columns						= $input->getInt('columns');
		$show_filter					= $input->getInt('show_filter');
		$click_thumbnail_to				= $input->getInt('click_thumbnail_to');
		$link_of_project				= $input->getInt('link_of_project');
		$show_info						= $input->getInt('show_info');
		$show_info_project_details		= $input->getInt('show_info_project_details');
		$show_info_project_link			= $input->getInt('show_info_project_link');
		$show_info_project_gallery		= $input->getInt('show_info_project_gallery');
		$show_info_title				= $input->getInt('show_info_title');
		$show_info_category				= $input->getInt('show_info_category');
		$show_title_list				= $input->getInt('show_title_list');
		$show_category					= $input->getInt('show_category');
		$show_short_description			= $input->getInt('show_short_description');
		$str_target						= $input->getString('str_target');

		$categories 		= explode(', ', $catids);
		JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_advportfoliopro/models');

		$number_item = self::gettotalProjects($categories);
		$total_page = ceil(($number_item - $limit)/ $numberload);

		// Get an instance of the project model
		$model = JModelLegacy::getInstance('Projects', 'AdvPortfolioproModel', array('ignore_request' => true));
		$model->setState('filter.category_id', $categories);

		$current_page = isset($page) ? $page : 1;

		$start = $limit + (($current_page - 1) * $numberload);

		if ($current_page < $total_page) {
			$model->setState('list.limit', $numberload);
		} else {
			$model->setState('list.limit', $number_item - $start);
		}
		$orderCol = AdvPortfolioProHelperQuery::orderbyProject($orderby, $orderdate);
		$model->setState('filter.state', 1);
		$model->setState('list.start', ($start));
		$model->setState('list.ordering', $orderCol);
		$model->setState('list.direction', '');


		$items = $model->getItems();

		$html = '';
		$item_class			 = ' col-md-' . ($columns);
		foreach ($items as $item) {
			$link			= AdvPortfolioProHelperRoute::getProjectRoute($item->slug, $item->catslug);
			$cat_link		= AdvPortfolioProHelperRoute::getCategoryRoute($item->catslug);
			$class			= '';
			if ($item->featured) {
				$item_class			 = ' col-md-' . (($columns)*2);
			}

			if ($show_filter == 1) {
				foreach ($item->tags as $tag) {
					//$class .= ' ' . JApplicationHelper::stringURLSafe($tag);
					$class .= ' ' . str_replace(' ', '-', $tag->alias);
				}
			} elseif ($show_filter != 0) {
				//$class	.= ' ' . JApplicationHelper::stringURLSafe($item->category_title);
				$class	.= ' ' . str_replace(' ', '-', $item->category_alias);
			}

			$img_html = JHtml::_('advportfoliopro.image', $item->thumbnail, null, null, $item->thumbnail, false);

			$html .= '<div class="col-12 col-sm-6 isotope-item project-' . $item->id .  $class . $item_class .'">';
			$html .= '<div class="project-inner">';
			if ($item->thumbnail) {
				$html .= '<div class="project-img">';
				if ($click_thumbnail_to == 1) {
					if (($link_of_project) == 0 && $item->link) {
						$html .= '<a class="link-detail" ' . $str_target . ' href="' . $item->link . '"  >' . $img_html . '</a>';
					} else {
						$html .= '<a class="link-detail" ' . $str_target . ' href="' . $link . '"  >' . $img_html . '</a>';
					}
				} else {
					$html .= '<a class="link-detail gallery-popup" data-project-id="' . $item->id .'" href="' . $link . '"  >' . $img_html . '</a>';

				}
				if ($show_info) {
					$html .= '<div class="project-img-extra">';
					if ($click_thumbnail_to == 1) {
						if (($link_of_project) == 0 && $item->link) {
							$html .= '<a class="link-detail" href="' . $item->link . '" ' . $str_target . ' ></a>';
						} else {
							$html .= '<a class="link-detail" href="' . $link . '"  ' . $str_target . '></a>';
						}
					} else {
						$html .= '<a class="link-detail gallery-popup" data-project-id="' . $item->id .'" href="' . $link . '"  >' . $img_html . '</a>';

					}
					$html .= '<div class="project-img-extra-content">';
					if ($show_info_project_details) {
						$html .= '<a class="project-icon" href="' . $link . '" ' . $str_target . ' ><span class="fa fa-link"></span></a>';
					}
					if ($show_info_project_link) {
						$html .= '<a class="project-icon link-icon" href="' . $item->link . '"  ' . $str_target . '><span class="fa fa-external-link"></span></a>';
					}
					if ($show_info_project_gallery) {
						$html .= '<a class="project-icon gallery-icon" data-project-id="' .$item->id .'" href="' . $link . '"  ><span class="fa fa-picture-o"></span></a>';
					}
					if ($show_info_title) {
						$html .= '<h4>' . $item->title . '</h4>';
					}
					if ($show_info_category) {
						$html .= '<h5><a href="' . $cat_link . '"' . $str_target . '>' .$item->category_title . '</a></h5>';
					}
					$html .= '</div>';

					$html .= '</div>';
				}

				$html .= '</div>';
			}

			if ($show_title_list || $show_category || $show_short_description) {
				$html .= '<div class="project-item-meta">';
				if ($show_title_list) {
					$html .= '<h4>';
					if (($link_of_project) == 0 && $item->link) {
						$html .= '<a rel="bookmark" ' . $str_target . ' title="' . $item->title . '" href="' . $item->link . '">' . $item->title . '</a>';

					} else {
						$html .= '<a rel="bookmark" ' . $str_target . ' title="' . $item->title . '" href="' . $link . '">' . $item->title . '</a>';

					}
					$html .= '</h4>';
				}
				if ($show_category) {
					$html .= '<h5><a href="' . $cat_link . '" ' . $str_target . '>' . $item->category_title . '</a></h5>';

				}
				if ($show_short_description) {
					$html .= $item->short_description;
				}
				$html .= '</div>';
			}
			$html .= '</div>';
			$html .= '</div>';
		}


		$response	= array(
			'html'	=> $html,
			'max_page'	=> $total_page
		);

		echo json_encode($response);

		$app->close();
	}
}