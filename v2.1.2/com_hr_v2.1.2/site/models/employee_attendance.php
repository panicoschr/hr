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

class HrModelEmployee_attendance extends JModelAdmin {

    protected $text_prefix = 'COM_HR';

    public function authorityOk($data) {

        $db = JFactory::getDbo();
        require_once JPATH_COMPONENT . '/helpers/hr.php';

        $mynoofmethodandmycategory = HrHelper::GetMyCategory();
        $no_of_case = substr($mynoofmethodandmycategory, 0, 1);
        $mycatid = substr($mynoofmethodandmycategory, 1);

        //  $user = '274' ;  



        //  $search_ip = $db->Quote('%' . $db->escape($ipupto3rddot, true) . '%');


        if (($no_of_case == 1) || ($no_of_case == 2)) {

            $ip = HrHelper::getRealIpAddr();

            $querygetauthorisedmachineid = $db->getQuery(true);
            $querygetauthorisedmachineid->select($db->quoteName(array('m.ip', 'mc.catid')))
                    ->from($db->quoteName('#__hr_machine', 'm'))
                    ->join('LEFT', $db->quoteName('#__hr_machinecat', 'mc') . ' ON (' . $db->quoteName('m.id') . ' = ' . $db->quoteName('mc.machine_id') . ')')
                    ->where($db->quoteName('mc.refcatid') . ' = ' . "'$mycatid'")
                    ->where($db->quoteName('m.ip') . ' = ' . "'$ip'");


            /*
              if (strlen($ip) != '3')  {

              $querygetauthorisedmachineid->where('(m.ip LIKE ' . $search_ip . ')');
              }
              else {
              $querygetauthorisedmachineid->where($db->quoteName('m.ip') . ' = ' . "'$ip'")  ;
              }

             */


            $db->setQuery($querygetauthorisedmachineid);


            $replyAG = $db->query();
            $countrows = $db->getNumRows();

            //  jexit();
// if (mysql_num_rows($queryabsencecharge)!=0)  


            if ($countrows > 0) {

                return true;

                // do      
            } else
            if ($countrows == 0) {

           
                //  jexit();
                return false;
                //message unauthorised  
            }
        } else {
            return true;
        }
    }

    public function recordLogDateTime($data) {
        
        
        require_once JPATH_COMPONENT . '/helpers/hr.php';
        
        
    $insertattendancelogindata = array();
    $insertattendancelogoutdata = array();

    JTable::addIncludePath(JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_hr' . DIRECTORY_SEPARATOR . 'tables');
     

                

        // set the variables from the passed data
        $id = $data['id'];
        $log_action = $data['log_action'];

        $db = JFactory::getDbo();
        //    $now = new DateTime();

        $now = new JDate('now', 'UTC');


        $catid = $data['catid'];

 
    //    jexit();


        //COPIED
        $date = JFactory::getDate();
        $user = JFactory::getUser();
        $user_id = $user->id;
        
  
  //     jexit();

        $now = new JDate('now', 'UTC');
        $nowstring = $now->format('Y-m-d H:i:s:u');
        $zerostring = '0000-00-00 00:00';
        
        
        

     //   jexit();
        
 //       $created = $data['created'];
  //      $created_by = $data['created_by'];
  //      $state = 0;
   //     $access = 1;   
       // $asset_id = $data['asset_id'];
    //    $publish_up = $data['publish_up'];
    //    $publish_down  = $data['publish_down'];
    //    $title = $nowstring;
   //     $data['title'] = $nowstring;
    //    $alias = $nowstring;
    //    $modified = '0000-00-00 00:00:00:00';
    //    $modified_by = 0;
        
      //  $language = '*';
        

    //    jexit();
        
        

                
                //UNTIL HERE



		// Increment the content version number.
	//	$table->version++;

		// Reorder the employee_absences within the category so the new employee_absence is first
      
        

   


        if (($log_action == 'IN') || ($log_action == 'OUT')) {



            if ($log_action == 'IN') {

                
                /*
                $queryinsertlogin = $db->getQuery(true);
                $insertcolumns = array('employee_id', 'log_in', 'catid', 'created', 'created_by', 'state', 
                     'publish_up', 'publish_down', 'title',  'language', 'access');
                $queryinsertlogin->columns($db->quoteName($insertcolumns));
                $values = array("'$user_id'", "'$nowstring'", "'$catid'", "'$created'", "'$created_by'", "'$state'", 
                     "'$publish_up'", "'$publish_down'", "'$title'",  "'$language'", "'$access'");
                $queryinsertlogin->insert($db->quoteName('#__hr_employee_attendance'));
                $queryinsertlogin->values(implode(',', $values));
                $db->setQuery($queryinsertlogin);
                $db->execute();
                
                */
                //    ////var_depr_dump($queryinsertlogin);
                // jexit();
                
                
                
	$table = JTable::getInstance('Employee_attendance', 'HrTable');      
                
        
                
                    $insertattendancelogindata['id'] = '';
                    $insertattendancelogindata['employee_id'] = $user_id;
                    $insertattendancelogindata['log_in'] = $nowstring;
                    $insertattendancelogindata['log_out'] = $zerostring;
                    
                    $insertattendancelogindata['catid'] = $catid;

                    $insertattendancelogindata['state'] = 0;
                    $insertattendancelogindata['access'] = 1;
                    $insertattendancelogindata['params'] = '{"target":"","image":""}';
                    $insertattendancelogindata['metadata'] = '{"page_title":"","author":"","robots":"","tags":""}';
                    $insertattendancelogindata['language'] = '*';
                    $insertattendancelogindata['featured'] = 0;
                    
                   $date =JFactory::getDate();
                   $nowstring=$date->format('Y-m-d h:i:s:u A');
           //        $insertattendancelogindata['publish_up'] = $nowstring;  
   
                  $insertattendancelogindata['title'] = $nowstring;
                $insertattendancelogindata['alias'] = $nowstring;


                          if (!$table->save($insertattendancelogindata)) {
            $table->setError($this->getError());
            return false;
        }      
                    
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
            }


            if ($log_action == 'OUT') {


                $modified = $nowstring;
                $modified_by = $user_id;
                
                $queryfindmaxidwithoutlogout = $db->getQuery(true);
                $queryfindmaxidwithoutlogout = "SELECT et.id, et.log_in
                  FROM  #__hr_employee_attendance as et
                  inner join
                  (
                    select Max(log_in) as log_in
                    FROM #__hr_employee_attendance 
                          WHERE (employee_id = " . $user_id . " )

                  ) as et2 
                    on (employee_id = " . $user_id . " AND log_out = '0000-00-00 00:00:00' AND et.log_in = et2.log_in)";


                $db->setQuery($queryfindmaxidwithoutlogout);


                $replyAG = $db->query();
                $countrows = $db->getNumRows();

                if ($countrows > 0) {
                    $queryfindmaxidwithoutlogouts = $db->loadObjectList();

                    foreach ($queryfindmaxidwithoutlogouts as $queryfindmaxidwithoutlogout) {

                        $id = $queryfindmaxidwithoutlogout->id;
                    }



                    $queryupdatelogout = $db->getQuery(true);
                    // Fields to update.
                    $fields_to_update = array(
                        $db->quoteName('log_out') . ' = ' . "'$nowstring'",
                        $db->quoteName('modified') . ' = ' . "'$modified'",
                        $db->quoteName('modified_by') . ' = ' . "'$modified_by'"
                    );
// Conditions for which records should be updated.
                    $conditions_to_update = array(
                        $db->quoteName('id') . ' = ' . "'$id'"
                    );

                    $queryupdatelogout->update($db->quoteName('#__hr_employee_attendance'))->set($fields_to_update)->where($conditions_to_update);
                    $db->setQuery($queryupdatelogout);
                    $result = $db->execute();
                    
                    
               
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                } else {
                    
                    /*
                    $queryinsertlogout = $db->getQuery(true);
                    $insertcolumns = array('employee_id', 'log_out', 'catid', 'created', 'created_by', 'state', 
                     'publish_up', 'publish_down', 'title', 'language', 'access');
                    $queryinsertlogout->columns($db->quoteName($insertcolumns));
                    $values = array("'$user_id'", "'$nowstring'", "'$catid'", "'$created'", "'$created_by'", "'$state'", 
                     "'$publish_up'", "'$publish_down'", "'$title'", "'$language'", "'$access'");
                    $queryinsertlogout->insert($db->quoteName('#__hr_employee_attendance'));
                    $queryinsertlogout->values(implode(',', $values));
                    $db->setQuery($queryinsertlogout);
                    $db->execute();
                    */
                    
$table = JTable::getInstance('Employee_attendance', 'HrTable');      
                
        
                
                    $insertattendancelogoutdata['id'] = '';
                    $insertattendancelogoutdata['employee_id'] = $user_id;
                    $insertattendancelogoutdata['log_out'] = $nowstring;
                    $insertattendancelogoutdata['catid'] = $catid;

                    $insertattendancelogoutdata['state'] = 0;
                    $insertattendancelogoutdata['access'] = 1;
                    $insertattendancelogoutdata['params'] = '{"target":"","image":""}';
                    $insertattendancelogoutdata['metadata'] = '{"page_title":"","author":"","robots":"","tags":""}';
                    $insertattendancelogoutdata['language'] = '*';
                    $insertattendancelogoutdata['featured'] = 0;
                    
                   $date =JFactory::getDate();
                   $nowstring=$date->format('Y-m-d h:i:s:u A');
           //        $insertattendancelogoutdata['publish_up'] = $nowstring;  
   
                  $insertattendancelogoutdata['title'] = $nowstring;
                $insertattendancelogoutdata['alias'] = $nowstring;


                          if (!$table->save($insertattendancelogoutdata)) {
            $table->setError($this->getError());
            return false;
        }                          
                    
                    
                    
                    
                    
                    
                }
            }


            //   ////var_depr_dump($data);
            //    echo 'false';
            //     jexit();
            return true;
        } else {
            //      echo 'true';
            //      jexit();
            return false;
        }
    }
    
    
    
    
    
    
    
    

    protected function canDelete($record) {
        if (!empty($record->id)) {
            if ($record->state != -2) {
                return;
            }
            $user = JFactory::getUser();

            if ($record->catid) {
                return $user->authorise('core.delete', 'com_hr.category.' . (int) $record->catid);
            } else {
                return parent::canDelete($record);
            }
        }
    }

    protected function canEditState($record) {
        $user = JFactory::getUser();

        if (!empty($record->catid)) {
            return $user->authorise('core.edit.state', 'com_hr.category.' . (int) $record->catid);
        } else {
            return parent::canEditState($record);
        }
    }

    public function getTable($type = 'Employee_attendance', $prefix = 'HrTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true) {
        $app = JFactory::getApplication();

        $form = $this->loadForm('com_hr.employee_attendance', 'employee_attendance', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        return $form;
    }

   protected function loadFormData() {
        //   jexit();

        $edit_validity = false;

       
        $data = JFactory::getApplication()->getUserState('com_hr.edit.employee_attendance.data', array());



        if (empty($data)) {

            $data = $this->getItem();
                if (isset($data->id) ) {
     
            $id = $data->id;
           
    
            
                   if ($edit_validity == true)   {
                            
                     return $data;
                } else {
                   // echo 'in';
                jexit();
            }
           } else {
       //       $data->employee_id = $userid;
               
                    return $data;
          //      echo 'out';
          //     jexit();
            }
        }
    }

    protected function prepareTable($table) {
        // Set the publish date to now
		$db = $this->getDbo();
                
                //COPIED
              	$date	= JFactory::getDate();
		$user	= JFactory::getUser();

              $now = new JDate('now', 'UTC');
                  $nowstring = $now->format('Y-m-d H:i:s');
                  $table->title =  $nowstring;
                                             
                
		if ($table->id) {
			// Existing item
			$table->modified	= $date->toSql();
			$table->modified_by	= $user->get('id');
                                           
		} else {
			// New entry. An entry created and created_by field can be set by the user,
			// so we don't touch either of these if they are set.
			if (!intval($table->created)) {
				$table->created = $date->toSql();
			}

			if (empty($table->created_by)) {
				$table->created_by = $user->get('id');
			}
		}
                
                //UNTIL HERE

		if ($table->state == 1 && (int) $table->publish_up == 0)
		{
			$table->publish_up = JFactory::getDate()->toSql();
		}

		if ($table->state == 1 && intval($table->publish_down) == 0)
		{
			$table->publish_down = $db->getNullDate();
		}

		// Increment the content version number.
		//$table->version++;

		// Reorder the employee_attendances within the category so the new employee_attendance is first
		if (empty($table->id))
		{
			$table->reorder('catid = ' . (int) $table->catid . ' AND state >= 0');
		}
        
        
       
    }

}
