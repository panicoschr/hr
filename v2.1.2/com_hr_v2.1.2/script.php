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

/**
 * Script file of hr component
 */
class com_hrInstallerScript {

    /**
     * method to install the component
     *
     * @return void
     */
    function install($parent) {
        // $parent is the class calling this method
        //redirect to component
        $parent->getParent()->setRedirectURL('index.php?option=com_hr');
    }

    /**
     * method to uninstall the component
     *
     * @return void
     */
    function uninstall($parent) {
        // $parent is the class calling this method
        //echo '<p>' . JText::_('COM_SPDIGITALGOODIRECTORY_SEPARATOR_UNINSTALL_TEXT') . '</p>';
    }

    /**
     * method to update the component
     *
     * @return void
     */
    function update($parent) {
        // $parent is the class calling this method
        //echo '<p>' . JText::_('COM_SPDIGITALGOODIRECTORY_SEPARATOR_UPDATE_TEXT') . '</p>';
    }

    /**
     * method to run before an install/update/uninstall method
     *
     * @return void
     */
    function preflight($type, $parent) {
        // $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)
        //echo '<p>' . JText::_('COM_SPDIGITALGOODIRECTORY_SEPARATOR_PREFLIGHT_' . $type . '_TEXT') . '</p>';
    }

    /**
     * method to run after an install/update/uninstall method
     *
     * @return void
     */
    function postflight($type, $parent) {
        // $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)
        //echo '<p>' . JText::_('COM_SPDIGITALGOODIRECTORY_SEPARATOR_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
        //Add initial directory
        $jAp = JFactory::getApplication();
        JTable::addIncludePath(JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_hr' . DIRECTORY_SEPARATOR . 'tables');

        //prepare categories
        if(!$this->prepareCaregories($parent)) 
            return true;
        
        if(!$this->prepareCpanels($parent)) 
            return true;        
        
        // Clear the component's cache
        $cache = JFactory::getCache('com_hr');
        $cache->clean();

        //Update client_id in extensions table to enable online upgrade
        $db = & JFactory::getDBO();
        
        $query = $db->getQuery(true);

        $fields_to_update = array(
            $db->quoteName('client_id') . ' = ' .  $db->quote('0') 
        );        
        $conditions_to_update = array(
            $db->quoteName('name') . ' = ' . "'com_hr'"
        );        

        

        
        $query->update($db->quoteName('#__extensions'))->set($fields_to_update)->where($conditions_to_update);        
        
        
        
   //     $query = "UPDATE '#__extensions' SET 'client_id' = 0  WHERE name =com_hr;";
        $db->setQuery($query);
        
        if (!$db->query()) {
            $jAp->enqueueMessage(nl2br($db->getErrorMsg()), 'error');
            return;
        }
        
        //Activate plugins
        /*
        $query = 'UPDATE ' . $db->quoteName('#__extensions');
        $query .= ' SET 'enabled' = ' . $db->quote('1');
        $query .= ' WHERE ' . $db->quoteName('element') . ' LIKE ' . $db->quote('hraddintransaction');
        $db->setQuery($query);
        if (!$db->query())
            JFactory::getApplication()->enqueueMessage($db->getErrorMsg(), 'error');
        
        $query = 'UPDATE ' . $db->quoteName('#__extensions');
        $query .= ' SET 'enabled' = ' . $db->quote('1');
        $query .= ' WHERE ' . $db->quoteName('element') . ' LIKE ' . $db->quote('hrshowintransaction');
        $db->setQuery($query);
        if (!$db->query())
            JFactory::getApplication()->enqueueMessage($db->getErrorMsg(), 'error');
         * 
         */
    }
    
    private $father_id;
    private $grandfather_id;

    
    
    
    protected function saveCategory($data, $parentCategory = 'child', $parent) {

        $table = JTable::getInstance('Category');

        switch ($parentCategory) {
            case 'grandfather':
                $data['parent_id'] = 1;
                break;
            case 'father':
                $data['parent_id'] = $this->grandfather_id;
                break;
            default:
                $data['parent_id'] = $this->father_id;
                break;
        }

        $data['id'] = 0;
        
        // Set the new parent id if parent id not matched OR while New/Save as Copy .
        if ($table->parent_id != $data['parent_id'] || $data['id'] == 0) {
            $table->setLocation($data['parent_id'], 'last-child');
        }

        // Bind the data.
        if (!$table->bind($data)) {
            $parent->setError($table->getError());
            return false;
        }

        // Check the data.
        if (!$table->check()) {
            $parent->setError($table->getError());
            return false;
        }

        // Store the data.
        if (!$table->store()) {
            $parent->setError($table->getError());
            return false;
        }

        // Rebuild the path for the category:
        if (!$table->rebuildPath($table->id)) {
            $parent->setError($table->getError());
            return false;
        }

        // Rebuild the paths of the category's children:
        if (!$table->rebuild($table->id, $table->lft, $table->level, $table->path)) {
            $parent->setError($table->getError());
            return false;
        }

        if ($parentCategory == 'grandfather') {
            $this->grandfather_id = $table->id;
        }
        if ($parentCategory == 'father') {
            $this->father_id = $table->id;
        }

        return true;
    }
    
    protected function prepareCaregories($parent) {

        //assign values
        $data = array();
        $data['level'] = 1;
        $data['extension'] = 'com_hr';
        $data['published'] = 1;
        $data['access'] = 3;
        $data['params'] = '{"target":"","image":""}';
        $data['metadata'] = '{"page_title":"","author":"","robots":"","tags":""}';
        $data['language'] = '*';

		
		 $data['title'] = 'HR Control Panel';
        if (!$this->saveCategory($data, 'grandfather', $parent))
            return false;    		
		 $data['title'] = 'Setup';
        if (!$this->saveCategory($data, 'grandfather', $parent))
            return false;         
            $data['title'] = 'Absences';
        if (!$this->saveCategory($data, 'father', $parent))
            return false;    
      $data['title'] = 'Absences of Employees';
        if (!$this->saveCategory($data, 'child', $parent))
            return false;   
                $data['title'] = 'Entitlement Plans';
        if (!$this->saveCategory($data, 'father', $parent))
            return false;
            $data['title'] = 'Entitlement Plans Structure';
        if (!$this->saveCategory($data, 'child', $parent))
            return false;          
            $data['title'] = 'Entitlement Plans to Departments/Sections';
        if (!$this->saveCategory($data, 'child', $parent))
            return false;       
        $data['title'] = 'Entitlements of Employees';
        if (!$this->saveCategory($data, 'father', $parent))
            return false;      
        
     
        $data['title'] = 'Rosters';
        if (!$this->saveCategory($data, 'father', $parent))
            return false; 
        $data['title'] = 'Rosters Patterns';
        if (!$this->saveCategory($data, 'child', $parent))
            return false;     
        $data['title'] = 'Rosters of Employees';
        if (!$this->saveCategory($data, 'child', $parent))
            return false;        
          $data['title'] = 'Rosters to Departments/Sections';
        if (!$this->saveCategory($data, 'child', $parent))
            return false;         
        $data['title'] = 'Roster Patterns';
        if (!$this->saveCategory($data, 'child', $parent))
            return false;          
            $data['title'] = 'Employee to Pattern Line';
        if (!$this->saveCategory($data, 'child', $parent))
            return false;  
  
        $data['title'] = 'Titles';
        if (!$this->saveCategory($data, 'father', $parent))
            return false;          
        $data['title'] = 'Titles of Employees';
        if (!$this->saveCategory($data, 'child', $parent))
            return false;   
        
          $data['title'] = 'Positions';
        if (!$this->saveCategory($data, 'father', $parent))
            return false;    
        
          $data['title'] = 'Positions to Departments/Sections';
        if (!$this->saveCategory($data, 'child', $parent))
            return false;           
        
          $data['title'] = 'Positions of Employees';
        if (!$this->saveCategory($data, 'child', $parent))
            return false;   
        
         $data['title'] = 'Attendance of Employees';
        if (!$this->saveCategory($data, 'father', $parent))
            return false;        
                $data['title'] = 'Approvers of Employees';
        if (!$this->saveCategory($data, 'father', $parent))
            return false;
       $data['title'] = 'Attendance Machines';
        if (!$this->saveCategory($data, 'father', $parent))
            return false;
              $data['title'] = 'Attendance Machines to Departments/Sections';
        if (!$this->saveCategory($data, 'child', $parent))
            return false;
         
        $data['title'] = 'Structure';
        if (!$this->saveCategory($data, 'grandfather', $parent))
            return false;
   
        
        
        
        
        
        
        

        return true;

    }
    
    
    
    protected function saveCpanel($data, $parent) {
   //         protected function saveCpanel($data, $parentCategory = 'child', $parent) {

    
        
        //$table = & JTable::getInstance('Cpanel');
	$table = JTable::getInstance('Cpanel', 'HrTable');  
        
        

        
/*
        switch ($parentCategory) {
            case 'grandfather':
                $data['parent_id'] = 1;
                break;
            case 'father':
                $data['parent_id'] = $this->grandfather_id;
                break;
            default:
                $data['parent_id'] = $this->father_id;
                break;
        }
*/
        $data['id'] = 0;
        
        // Set the new parent id if parent id not matched OR while New/Save as Copy .
        /*
        if ($table->parent_id != $data['parent_id'] || $data['id'] == 0) {
            $table->setLocation($data['parent_id'], 'last-child');
        }
*/
        // Bind the data.
        if (!$table->bind($data)) {
            $parent->setError($table->getError());
            return false;
        }

        // Check the data.
        if (!$table->check()) {
            $parent->setError($table->getError());
            return false;
        }

        // Store the data.
        if (!$table->store()) {
            $parent->setError($table->getError());
            return false;
        }

        
        /*
        // Rebuild the path for the category:
        if (!$table->rebuildPath($table->id)) {
            $parent->setError($table->getError());
            return false;
        }

        // Rebuild the paths of the category's children:
        if (!$table->rebuild($table->id, $table->lft, $table->level, $table->path)) {
            $parent->setError($table->getError());
            return false;
        }
*/
        /*
        if ($parentCategory == 'grandfather') {
            $this->grandfather_id = $table->id;
        }
        if ($parentCategory == 'father') {
            $this->father_id = $table->id;
        }
*/
        return true;
    }    
    
    
    protected function prepareCpanels($parent) {

        //assign values
        $data = array();
        $data['state'] = 1;
        $data['access'] = 3;
        $data['params'] = '{"target":"","image":""}';
        $data['metadata'] = '{"page_title":"","author":"","robots":"","tags":""}';
        $data['language'] = '*';
	$data['featured'] = 0;	
        
        
        
        //find catid of HR Control Panel
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('id')));
        $query->from($db->quoteName('#__categories'));
        $query->where($db->quoteName('title') . ' =  ' . "'HR Control Panel'");        
        $query->where($db->quoteName('extension') . ' =  ' . "'com_hr'");    
        $db->setQuery($query);
        $results = $db->loadObjectList();
        foreach ($results as $result) {
            $catid = $result->id;
        }  
        
             
       $data['catid'] = (int) $catid;    
        
      
       
       // $now = new DateTime();
     //   $nowstring=$now->format('Y-m-d h:i:s A');   
        
        $date =& JFactory::getDate();
        $nowstring=$date->format('Y-m-d h:i:s A');
        
        
        $data['publish_up'] = $nowstring;  
        
        
		 $data['title'] = 'Absences';
                 $data['url'] = 'index.php?option=com_hr&view=absences';
                 $data['featured'] = 0;
        if (!$this->saveCpanel($data, $parent))
            return false;       
        		 $data['title'] = 'Absences of Employees';
                 $data['url'] = 'index.php?option=com_hr&view=employee_absences';
                 $data['featured'] = 1;                 
        if (!$this->saveCpanel($data, $parent))
            return false; 
        
        
        
         $data['title'] = 'Approvers of Employees';
                 $data['url'] = 'index.php?option=com_hr&view=employee_approvers';
                 $data['featured'] = 0;                 
        if (!$this->saveCpanel($data, $parent))
            return false; 
        

        
  
                  $data['title'] = 'Attendance Machines';
            $data['url'] = 'index.php?option=com_hr&view=machines';
                 $data['featured'] = 0;            
        if (!$this->saveCpanel($data, $parent))
            return false;   
        
             $data['title'] = 'Attendance Machines to Departments/Sections';
            $data['url'] = 'index.php?option=com_hr&view=machinecats';
                 $data['featured'] = 0;            
        if (!$this->saveCpanel($data, $parent))
            return false;      
        
        
         $data['title'] = 'Attendance of Employees';
                 $data['url'] = 'index.php?option=com_hr&view=employee_attendances';
                 $data['featured'] = 1;                 
        if (!$this->saveCpanel($data, $parent))
            return false;                           
       

        
          $data['title'] = 'Entitlement Plans';
            $data['url'] = 'index.php?option=com_hr&view=plans';
                 $data['featured'] = 0;            
        if (!$this->saveCpanel($data, $parent))
            return false;           
        
  
       
           $data['title'] = 'Entitlement Plans Structure';
            $data['url'] = 'index.php?option=com_hr&view=plan_entitlements';
                 $data['featured'] = 0;            
        if (!$this->saveCpanel($data, $parent))
            return false;          
        
        
        
           $data['title'] = 'Entitlement Plans to Departments/Sections';
            $data['url'] = 'index.php?option=com_hr&view=plancats';
                 $data['featured'] = 0;            
        if (!$this->saveCpanel($data, $parent))
            return false;          
        
        
        
         $data['title'] = 'Entitlements Management';
            $data['url'] = 'index.php?option=com_hr&view=yearly_entitlement&layout=edit';
                 $data['featured'] = 1;            
        if (!$this->saveCpanel($data, $parent))
            return false;           
        
        $data['title'] = 'Entitlements of Employees';
                 $data['url'] = 'index.php?option=com_hr&view=employee_entitlements';
                 $data['featured'] = 0;                 
        if (!$this->saveCpanel($data, $parent))
            return false;     
   
        
      
          $data['title'] = 'Entitlements and Balances of Employees';
            $data['url'] = 'index.php?option=com_hr&view=emp_entl_bals';
                 $data['featured'] = 1;            
        if (!$this->saveCpanel($data, $parent))
            return false;          
        
           $data['title'] = 'Positions';
            $data['url'] = 'index.php?option=com_hr&view=positions';
                 $data['featured'] = 0;            
        if (!$this->saveCpanel($data, $parent))
            return false;           
        
           $data['title'] = 'Positions to Departments/Sections';
            $data['url'] = 'index.php?option=com_hr&view=positioncats';
                 $data['featured'] = 0;            
        if (!$this->saveCpanel($data, $parent))
            return false;    
        
        
         $data['title'] = 'Positions of Employees';
                 $data['url'] = 'index.php?option=com_hr&view=employee_positions';
                 $data['featured'] = 0;                 
        if (!$this->saveCpanel($data, $parent))
            return false;        
 
        
        
                 $data['title'] = 'Rosters';
            $data['url'] = 'index.php?option=com_hr&view=rosters';
                 $data['featured'] = 0;            
        if (!$this->saveCpanel($data, $parent))
            return false;       
        
          $data['title'] = 'Rosters Patterns';
            $data['url'] = 'index.php?option=com_hr&view=roster_patterns';
                 $data['featured'] = 0;            
        if (!$this->saveCpanel($data, $parent))
            return false;  
        
        
           $data['title'] = 'Rosters to Departments/Sections';
            $data['url'] = 'index.php?option=com_hr&view=rostercats';
                 $data['featured'] = 0;            
        if (!$this->saveCpanel($data, $parent))
            return false;   
        
              
        
       $data['title'] = 'Employee to Pattern Line';
                 $data['url'] = 'index.php?option=com_hr&view=employee_patternlines';
                 $data['featured'] = 0;                 
        if (!$this->saveCpanel($data, $parent))
            return false;         
         
           
 
        
        
         $data['title'] = 'Rosters of Employees';
                 $data['url'] = 'index.php?option=com_hr&view=employee_rosters';
                 $data['featured'] = 1;                 
        if (!$this->saveCpanel($data, $parent))
            return false;      
        
           $data['title'] = 'Rosters Management';
            $data['url'] = 'index.php?option=com_hr&view=roster_management&layout=edit';
                 $data['featured'] = 1;            
        if (!$this->saveCpanel($data, $parent))
            return false;           
        
        
        
          $data['title'] = 'Titles';
            $data['url'] = 'index.php?option=com_hr&view=titles';          
            $data['featured'] = 0;  
        if (!$this->saveCpanel($data, $parent))
            return false;        
        
        
       
         $data['title'] = 'Titles of Employees';
                 $data['url'] = 'index.php?option=com_hr&view=employee_titles';
              $data['featured'] = 0;                   
        if (!$this->saveCpanel($data, $parent))
            return false;           

                 
      
         
        
        return true;

    }
    

}
