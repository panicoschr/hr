<?php 
/**
 * @package	HR
 * @subpackage	Components
 * @copyright	WWW.MEPRO.CO - All rights reserved.
 * @author	MEPRO SOFTWARE SOLUTIONS
 * @link	http://www.mepro.co
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/
  
defined('_JEXEC') or die;
jimport('joomla.form.helper');
jimport('joomla.form.formfield');
jimport('joomla.html.html');
JFormHelper::loadFieldClass('List');


//test

  
 /** 
  * Supports an HTML select list of options driven by SQL 
  */ 
 class JFormFieldMyuserandchildren extends JFormFieldList
 { 
     /** 
      * The form field type. 
      */ 
     public $type = 'Myuserandchildren'; 
  
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
  
 
   $mynoofmethodandmycategory = HrHelper::GetMyCategory();  
    $no_of_case = substr($mynoofmethodandmycategory, 0, 1);
    $mycatid = substr($mynoofmethodandmycategory, 1);
   
       
                    
                    
$catarray = array();

$catarray[0]=$mycatid;
 
 
 $arrayofcatandsubcats=HrHelper::GetCategoriesWithChildren($catarray);
  
 
$stringofarrayofcatandsubcats = implode("' , '", $arrayofcatandsubcats);



$fieldlistusers = array('u.id', 'u.name'); // add the field names to an array
 $fieldlistusers[0] = 'distinct ' . $fieldlistusers[0]; //prepend the distinct keyword to the first field name  

 $querymyuserandchildren  = $db->getQuery(true);

$querymyuserandchildren->select($fieldlistusers);




$querymyuserandchildren
         //       ->select($db->quoteName(array('u.id', 'u.name')))
                ->from($db->quoteName('#__users', 'u'));
       //         ->order('rp.roster_id, rp.no_of_day');



 //$querymyuserandchildren->where($db->quoteName('p.title') . ' != ' . "'hosting position for structure'");  
 
// $querymyuserandchildren->where($db->quoteName('p.title') . ' != ' . "'hosting position for structure'");  
 
 
  if (($no_of_case == 1) || ($no_of_case == 2)) {
$querymyuserandchildren->join('LEFT', $db->quoteName('#__hr_employee_position', 'ep') . ' ON (' . $db->quoteName('ep.employee_id') . ' = ' . $db->quoteName('u.id') . ')');
$querymyuserandchildren->join('LEFT', $db->quoteName('#__hr_positioncat', 'pc') . ' ON (' . $db->quoteName('ep.position_id') . ' = ' . $db->quoteName('pc.position_id') . ')');
$querymyuserandchildren->where('('. "'$nowstring'" . ' BETWEEN ' . $db->quoteName('pc.datetime_from'). ' AND '. $db->quoteName('pc.datetime_to').')' );
$querymyuserandchildren->where('('. "'$nowstring'" . ' BETWEEN ' . $db->quoteName('ep.datetime_from'). ' AND '. $db->quoteName('ep.datetime_to').')' );
$querymyuserandchildren->where($db->quoteName('pc.refcatid') . ' IN (' . '\''. "$stringofarrayofcatandsubcats". '\''. ')' );
//$querymyuserandchildren->where($db->quoteName('pc.refcatid') . ' != '  .  "'$mycatid'");  


  }

  
  
  if ($no_of_case == 4) {
//$querymyuserandchildren->join('LEFT', $db->quoteName('#__hr_employee_position', 'ep') . ' ON (' . $db->quoteName('ep.employee_id') . ' = ' . $db->quoteName('u.id') . ')');
//$querymyuserandchildren->join('LEFT', $db->quoteName('#__hr_positioncat', 'pc') . ' ON (' . $db->quoteName('ep.position_id') . ' = ' . $db->quoteName('pc.position_id') . ')');
//$querymyuserandchildren->join('LEFT', $db->quoteName('#__hr_position', 'p') . ' ON (' . $db->quoteName('pc.position_id') . ' = ' . $db->quoteName('p.id') . ')');
//$querymyuserandchildren->where($db->quoteName('pc.refcatid') . ' != '  .  "'$mycatid'");  
//$querymyuserandchildren->where($db->quoteName('p.title') . ' != ' . "'hosting position for structure'");
$querymyuserandchildren->where($db->quoteName('u.name') . ' != ' . "'Super User'");
$querymyuserandchildren->where($db->quoteName('u.name') . ' != ' . "'admin'");

  }  
  


$querymyuserandchildren->order($db->quoteName('u.name'));




		// Set the query and load the options.
		$db->setQuery($querymyuserandchildren);
		$options = $db->loadObjectList();
		$lang = JFactory::getLanguage();
                
   //$i=1;
           
            
                
		foreach ($options as $i=>$option) {
			$options[$i]->value = JText::_($option->id);
                        $options[$i]->text = JText::_($option->name);
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
     
     
     
     
     
     