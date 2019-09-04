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
 class JFormFieldMycategoryandchildren extends JFormFieldList
 { 
     /** 
      * The form field type. 
      */ 
     public $type = 'Mycategoryandchildren'; 
  
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
                
 // $user = JFactory::getUser();       

  //      $userid = $user->id;
 // $isroot = $user->authorise('core.admin');
         
     $now = new DateTime();
 //    $nowstring=$now->format('Y-m-d');   
  
  
    
    
  
   $mynoofmethodandmycategory = HrHelper::GetMyCategory();  
    $no_of_case = substr($mynoofmethodandmycategory, 0, 1);
    $mycatid = substr($mynoofmethodandmycategory, 1);
   
     
$catarray = array();

$catarray[0]=$mycatid;
 $arrayofcatandsubcats=HrHelper::GetCategoriesWithChildren($catarray);
$stringofarrayofcatandsubcats = implode("' , '", $arrayofcatandsubcats);





  $querycatidofstructure  = $db->getQuery(true); 
    $querycatidofstructure
               ->select($db->quoteName(array('c.id', )))
                ->from($db->quoteName('#__categories', 'c'))
                        ->where($db->quoteName('c.parent_id') . ' = '. "'1'" )
                ->where($db->quoteName('c.extension') . ' = '. "'com_hr'" )
                ->where($db->quoteName('c.title') . ' = '. "'Structure'" );
$db->setQuery($querycatidofstructure);
  $querycatidofstructures = $db->loadObjectList();
foreach ($querycatidofstructures as $querycatidofstructure) {
            $cat_id_of_structure = $querycatidofstructure->id;
}

$structurearray = array();
$structurearray[0]=$cat_id_of_structure;
 $structurearrayandsubcats=HrHelper::GetCategoriesWithChildren($structurearray);
$stringofstructurearrayandsubcats = implode("' , '", $structurearrayandsubcats);




$fieldlistcats = array('c.id', 'c.title'); // add the field names to an array
 $fieldlistcats[0] = 'distinct ' . $fieldlistcats[0]; //prepend the distinct keyword to the first field name  

$querymycategoryandchildren  = $db->getQuery(true);

$querymycategoryandchildren->select($fieldlistcats);




$querymycategoryandchildren
          //      ->select($db->quoteName(array('c.id', 'c.title')))
                ->from($db->quoteName('#__categories', 'c'))
                ->where($db->quoteName('c.extension') . ' = '. "'com_hr'" );
       //         ->order('rp.roster_id, rp.no_of_day');


  if (($no_of_case == 1) || ($no_of_case == 2)) {
$querymycategoryandchildren->where($db->quoteName('c.id') . ' IN (' . '\''. "$stringofarrayofcatandsubcats". '\''. ')' );
//$querymycategoryandchildren->where($db->quoteName('c.id') . ' != '  .  "'$mycatid'"); 
  }
    if (($no_of_case == 3) ) {
$querymycategoryandchildren->where($db->quoteName('c.id') . ' IN (' . '\''. "$stringofstructurearrayandsubcats". '\''. ')' );        
        
    }
    
  
  if ($no_of_case == 4) {
      $querymycategoryandchildren->where($db->quoteName('c.id') . ' IN (' . '\''. "$stringofstructurearrayandsubcats". '\''. ')' );  
      $querymycategoryandchildren->where($db->quoteName('c.id') . ' != '  .  "'$mycatid'"); 

  }      
    
    
  
  
$querymycategoryandchildren->where($db->quoteName('c.title') . ' != '. "'Structure'" );

$querymycategoryandchildren->order($db->quoteName('c.title'));

$querymycategoryandchildren->select('c.id AS refcatid');


		// Set the query and load the options.
		$db->setQuery($querymycategoryandchildren);
		$options = $db->loadObjectList();
		$lang = JFactory::getLanguage();
                
   //$i=1;
           
            
                
		foreach ($options as $i=>$option) {
			$options[$i]->value = JText::_($option->refcatid);
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
     
     
     
     
     
     