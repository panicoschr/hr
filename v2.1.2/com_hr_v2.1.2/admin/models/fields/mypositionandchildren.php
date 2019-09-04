<?php
/**
 * @package	HR
 * @subpackage	Components
 * @copyright	WWW.MEPRO.CO - All rights reserved.
 * @author	MEPRO SOFTWARE SOLUTIONS
 * @link	http://www.mepro.co
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/

// No direct access
defined('_JEXEC') or die;
jimport('joomla.form.helper');
jimport('joomla.form.formfield');
jimport('joomla.html.html');
JFormHelper::loadFieldClass('List');




  
 /** 
  * Supports an HTML select list of options driven by SQL 
  */ 
 class JFormFieldMypositionandchildren extends JFormFieldList
 { 
     /** 
      * The form field type. 
      */ 
     public $type = 'Mypositionandchildren'; 
  
     /** 
      * Overrides parent's getinput method 
      * 
      */ 
     
     
     
     
     
     
     
     
     
 protected function getOptions()
	{
		// Get the database object and a new query object.
		
		// Build the query.
    $db =JFactory::getDbo(); 
         
        require_once JPATH_COMPONENT.'/helpers/hr.php';
                
  $user = JFactory::getUser();       

        $userid = $user->id;
  $isroot = $user->authorise('core.admin');
         
     $now = new DateTime();
     $nowstring=$now->format('Y-m-d H:i');   
  
     
  //$findusergroupofuser=HrHelper::getGroupsByUser($userid, false);

 // ////var_depr_dump($isroot);
  //jexit();

 
   $mynoofmethodandmycategory = HrHelper::GetMyCategory();  
    $no_of_case = substr($mynoofmethodandmycategory, 0, 1);
    $mycatid = substr($mynoofmethodandmycategory, 1);
     
$catarray = array();

$catarray[0]=$mycatid;
 
 
 $arrayofcatandsubcats=HrHelper::GetCategoriesWithChildren($catarray);
  
 
$stringofarrayofcatandsubcats = implode("' , '", $arrayofcatandsubcats);






$fieldlistpositions = array('p.id', 'p.title'); // add the field names to an array
 $fieldlistpositions[0] = 'distinct ' . $fieldlistpositions[0]; //prepend the distinct keyword to the first field name  

$querymypositionandchildren  = $db->getQuery(true);

$querymypositionandchildren->select($fieldlistpositions);




$querymypositionandchildren
          //      ->select($db->quoteName(array('c.id', 'c.title')))
                ->from($db->quoteName('#__hr_position', 'p'));
       //         ->order('rp.roster_id, rp.no_of_day');

  if (($no_of_case == 1) || ($no_of_case == 2)) {
      $querymypositionandchildren->join('LEFT', $db->quoteName('#__hr_positioncat', 'pc') . ' ON (' . $db->quoteName('pc.position_id') . ' = ' . $db->quoteName('p.id') . ')');

$querymypositionandchildren->where('('. "'$nowstring'" . ' BETWEEN ' . $db->quoteName('pc.datetime_from'). ' AND '. $db->quoteName('pc.datetime_to').')' );
$querymypositionandchildren->where($db->quoteName('pc.refcatid') . ' IN (' . '\''. "$stringofarrayofcatandsubcats". '\''. ')' );
//$querymypositionandchildren->where($db->quoteName('pc.refcatid') . ' != '  .  "'$mycatid'");  
//$querymypositionandchildren->where($db->quoteName('p.title') . ' != ' . "'hosting position for structure'");
  }
  
  if ($no_of_case == 4) {
//$querymyuserandchildren->join('LEFT', $db->quoteName('#__hr_position', 'p') . ' ON (' . $db->quoteName('pc.position_id') . ' = ' . $db->quoteName('p.position_id') . ')');
//$querymypositionandchildren->join('LEFT', $db->quoteName('#__hr_position', 'p') . ' ON (' . $db->quoteName('pc.position_id') . ' = ' . $db->quoteName('p.id') . ')');
//$querymyuserandchildren->where($db->quoteName('pc.refcatid') . ' != '  .  "'$mycatid'");  
$querymypositionandchildren->where($db->quoteName('p.title') . ' != ' . "'hosting position for structure'");

  }            
     

$querymypositionandchildren->order($db->quoteName('p.title'));



		// Set the query and load the options.
		$db->setQuery($querymypositionandchildren);
		$options = $db->loadObjectList();
		$lang = JFactory::getLanguage();
                
   //$i=1;
           
            
                
		foreach ($options as $i=>$option) {
			$options[$i]->value = JText::_($option->id);
                        $options[$i]->text = JText::_($option->title);
		}

		// Check for a database error.
		if ($db->getErrorNum()) {
			JFactory::getApplication()->enqueueMessage($db->getErrorMsg(), 'warning');
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

	return $options;
	}    
     
     
 }  
     
     
     
      ?> 
     
     
     
     
     
     