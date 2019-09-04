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
 class JFormFieldMyApprovalHierarchy extends JFormFieldList
 { 
     /** 
      * The form field type. 
      */ 
     public $type = 'MyApprovalHierarchy'; 
  
     /** 
      * Overrides parent's getinput method 
      * 
      */ 
     
     
     
     
     
     
     
     
     
 public function getOptions()
	{
		// Get the database object and a new query object.
		
		// Build the query.


//jexit();
    
 // $options = array();

                
   //$i=1;
        $options[1] = new StdClass;
        $options[1]->value = 'employee';
        $options[1]->text = 'Employee';
        $options[2] = new StdClass;
        $options[2]->value = 'supervisor';
        $options[2]->text = 'Supervisor';
        $options[3] = new StdClass;
        $options[3]->value = 'approver';
        $options[3]->text = 'Approver';
  //      $options[4] = new StdClass;
   //     $options[4]->value = 'supervisorandapprover';
   //     $options[4]->text = 'Supervisor/Approver';              
  

        // Build the query.
    $db =JFactory::getDbo(); 


		// Check for a database error.
		if ($db->getErrorNum()) {
			JFactory::getApplication()->enqueueMessage($db->getErrorMsg(), 'warning');
		}

		// Merge any additional options in the XML definition.
		//$options = array_merge(parent::getOptions(), $options);

	return $options;
	}    
     
     
 }  
     
     
     
      ?> 
     
     
     
     
     
     