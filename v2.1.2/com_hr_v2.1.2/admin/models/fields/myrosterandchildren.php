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
 class JFormFieldMyrosterandchildren extends JFormFieldList
 { 
     /** 
      * The form field type. 
      */ 
     public $type = 'Myrosterandchildren'; 
  
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


 $fieldlist = array('r.id', 'r.title'); // add the field names to an array
 $fieldlist[0] = 'distinct ' . $fieldlist[0]; //prepend the distinct keyword to the first field name  

 
 $querymyrosterandchildren  = $db->getQuery(true);
$querymyrosterandchildren->select($fieldlist);

$querymyrosterandchildren
             //   ->select("DISTINCT 'pl.id', 'pl.title'")
      //        ->select("DISTINCT ".$db->quoteName(array('pl.id', 'pl.title')))
        
       //        ->select("DISTINCT ".$db->quoteName('pl.id'), $db->quoteName('pl.title'))
        
                ->from($db->quoteName('#__hr_roster', 'r'));

  if (($no_of_case == 1) || ($no_of_case == 2)) {
      $querymyrosterandchildren->join('LEFT', $db->quoteName('#__hr_rostercat', 'rc') . ' ON (' . $db->quoteName('rc.roster_id') . ' = ' . $db->quoteName('r.id') . ')');
$querymyrosterandchildren->where('('. "'$nowstring'" . ' BETWEEN ' . $db->quoteName('rc.datetime_from'). ' AND '. $db->quoteName('rc.datetime_to').')' );
$querymyrosterandchildren->where($db->quoteName('rc.refcatid') . ' IN (' . '\''. "$stringofarrayofcatandsubcats". '\''. ')' );
//$querymyrosterandchildren->where($db->quoteName('rc.refcatid') . ' != '  .  "'$mycatid'"); 
  }
$querymyrosterandchildren->order($db->quoteName('r.title'));
$db->setQuery($querymyrosterandchildren);

		$options = $db->loadObjectList();
		$lang = JFactory::getLanguage();
                
          $querymyrosterandchildren->select('r.id AS id');       
                  $querymyrosterandchildren->select('r.title AS title');   
          
          
          
                
   //$i=1;
  // ////var_depr_dump($options);
  //jexit();
            
                
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
     
     
     
     
     
     