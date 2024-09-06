<?php

// Set flag that this is a parent file
define('_JEXEC', 1);

// No direct access.
defined('_JEXEC') or die;

define( 'DS', DIRECTORY_SEPARATOR );

define('JPATH_BASE', dirname(__FILE__).DS.'..'.DS.'..'.DS.'..' );

if (file_exists(JPATH_BASE . '/defines.php'))
{
	include_once JPATH_BASE . '/defines.php';
}

if (!defined('_JDEFINES'))
{
	require_once JPATH_BASE . '/includes/defines.php';
}

require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

$app = JFactory::getApplication('site');
$app->initialise();

$user = JFactory::getUser();

$plugin	= JPluginHelper::getPlugin('content', 'extravote');

$params = new JRegistry;
$params->loadString($plugin->params);

$app   = JFactory::getApplication();

if(!empty($_POST['ratingPoints'])){
	$article_id   = $_POST['postID'];
    $ratingPoints = $_POST['ratingPoints'];	
    $alreadyvoted = $_POST['alreadyvoted'];	
    $firstvote = $_POST['firstvote'];	
    $thanksmessage = $_POST['thanksmessage'];	
    // $average_rating = $_POST['average_rating'];	
    // $numberofvotes = $_POST['numberofvotes'];	
    // $average_rating = $_POST['average_rating'];	
    // $rating_count = $_POST['rating_count'];	
	$current_ip   = $_SERVER['REMOTE_ADDR'];

	$db       = JFactory::getDbo();
	$query = $db->getQuery(true);			
	$query->select('`content_id`, `rating_sum`, `rating_count`, `lastip`');
	$query->from($db->quoteName('#__content_rating'));
	$query->where($db->quoteName('content_id')." = ".$db->quote($article_id));	 
	$db->setQuery($query);
	$result = $db->loadObject();
	if(!empty($result)){
		$rating_sum   = $result->rating_sum;
		$rating_count = $result->rating_count;
		$lastip       = $result->lastip;
		$average_rating = round($rating_sum / $rating_count, 1);
	}
	else
	{
		$query2 = $db->getQuery(true);			
		$query2 = "INSERT INTO #__content_rating (`content_id`, `rating_sum`, `rating_count`, `lastip`) VALUES(".$article_id.",'".$ratingPoints."','1','".$current_ip."')";
		$db->setQuery($query2);		
		$db->execute();
	    $mail = $firstvote;
	}
    
    if(!empty($result) && $current_ip != $lastip):
		$query5 = $db->getQuery(true);			
				$query5 = "UPDATE #__content_rating"
				. "\n SET rating_count = rating_count + 1, rating_sum = rating_sum + " .   $ratingPoints . ", lastip = " . $db->Quote( $current_ip )
				. "\n WHERE content_id = ".$article_id;
		$db->setQuery($query5);		
		$db->execute();
	    $mail = $thanksmessage;
	elseif($current_ip == $lastip):
	    $mail = $alreadyvoted;
    endif;
	
	$query = $db->getQuery(true);			
	$query->select('`content_id`, `rating_sum`, `rating_count`, `lastip`');
	$query->from($db->quoteName('#__content_rating'));
	$query->where($db->quoteName('content_id')." = ".$db->quote($article_id));
	 
	$db->setQuery($query);
	$result = $db->loadObject();
	$rating_sum   = $result->rating_sum;
	$rating_count = $result->rating_count;
	$average_rating = round($rating_sum / $rating_count, 1);
}
        
echo json_encode(array("db"=>$mail));


?>