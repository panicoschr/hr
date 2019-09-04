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

class HrModelApprove_employee_absence extends JModelAdmin
{
	protected $text_prefix = 'COM_HR';

        
        
        
	protected function canDelete($record)
	{
		if (!empty($record->id))
		{
			if ($record->state != -2)
			{
				return;
			}
			$user = JFactory::getUser();

			if ($record->catid)
			{
				return $user->authorise('core.delete', 'com_hr.category.'.(int) $record->catid);
			}
			else
			{
				return parent::canDelete($record);
			}
		}
	}

	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->catid))
		{
			return $user->authorise('core.edit.state', 'com_hr.category.'.(int) $record->catid);
		}
		else
		{
			return parent::canEditState($record);
		}
	}

	public function getTable($type = 'Employee_absence', $prefix = 'HrTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		$app = JFactory::getApplication();

		$form = $this->loadForm('com_hr.approve_employee_absence', 'approve_employee_absence', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	protected function loadFormData()
	{
		
            
            $idresults = array();
        $edit_validity = false;

        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $user = JFactory::getUser();
        $userid = $user->id;
        $now = new DateTime();
        $nowstring = $now->format('Y-m-d H:i:s');
        $mynoofmethodandmycategory = HrHelper::GetMyCategory();
        $no_of_case = substr($mynoofmethodandmycategory, 0, 1);
        $mycatid = substr($mynoofmethodandmycategory, 1);
        $catarray = array();
        $catarray[0] = $mycatid;
        $arrayofcatandsubcats = HrHelper::GetCategoriesWithChildren($catarray);
        $stringofarrayofcatandsubcats = implode("' , '", $arrayofcatandsubcats);
        
      
          $query->select($db->quoteName(array('id')));
        $query->from($db->quoteName('#__hr_employee_absence'));

        
        
     
        
        
        
            if (($no_of_case == 1) || ($no_of_case == 2)) {
                $query->where(($db->quoteName('employee_id') . ' IN ' .
                        '(SELECT DISTINCT ep.employee_id as empid
                       FROM  #__hr_employee_position As ep INNER JOIN
                       #__hr_positioncat As pc ON pc.position_id = ep.position_id 
                         where pc.refcatid IN (' . '\'' . "$stringofarrayofcatandsubcats" . '\'' . ') and ' .
                        "'$nowstring'" . ' between pc.datetime_from and pc.datetime_to and ' .
                        "'$nowstring'" . ' between ep.datetime_from and ep.datetime_to)'
                ). ' OR '.
                        
                        ('approver1_id' .' = '. $user->id. ' OR '.
        '(approver2_id' .' = '. $user->id. " AND ".'approval_status1'. ' = '. "'Approved')" . ' OR '.
        '(approver3_id' .' = '. $user->id. " AND ".'approval_status2'. ' = '. "'Approved')" . ' OR '.
        '(approver4_id' .' = '. $user->id. " AND ".'approval_status3'. ' = '. "'Approved')" . ' OR '.
        '(approver5_id' .' = '. $user->id. " AND ".'approval_status4'. ' = '. "'Approved')" 
        ));
                         
            }
   
            
  $db->setQuery($query);
  
        $results = $db->loadObjectList();
        

        
        foreach ($results as $result) {
            $idresults[] = $result->id;
        }           
            

        
            $data = JFactory::getApplication()->getUserState('com_hr.edit.approve_employee_absence.data', array());



        if (empty($data)) {

            $data = $this->getItem();
                 
           
         
                if (isset($data->id) ) {

                    
                    
                    
                                        $user = JFactory::getUser();
                                        $querycaneditabsence = $db->getQuery(true);
                                        $querycaneditabsence->select($db->quoteName(array('id', 'absence_id', 'employee_id', 'approver1_id', 'approval_status1', 'approver2_id', 'approval_status2', 'approver3_id', 'approval_status3', 'approver4_id', 'approval_status4', 'approver5_id', 'approval_status5')));
                                        $querycaneditabsence->from($db->quoteName('#__hr_employee_absence'));
//$abstype = $db->Quote($db->escape($item->absence_id, true));
                                        $querycaneditabsence->where('(absence_id = ' . $data->absence_id . ' AND employee_id = ' . $data->employee_id . ')');
                                        $querycaneditabsence->where('(id = ' . $data->id . ')');
                                        $db->setQuery($querycaneditabsence);
                                        $caneditabsenceresults = $db->loadObjectList();
                                  

                                        $position = 100;
                                        $next_approval_status_position = 0;

                                        foreach ($caneditabsenceresults as $caneditabsenceresult) {
                                            if ($caneditabsenceresult->approver1_id == $user->id) {
                                                $position = 1;
                                                $next_approval_status_position= $caneditabsenceresult->approval_status2;
                                            } elseif ($caneditabsenceresult->approver2_id == $user->id) {
                                                 $position = 2;
                                                $next_approval_status_position= $caneditabsenceresult->approval_status3;
                                            } elseif ($caneditabsenceresult->approver3_id == $user->id) {
                                                $position = 3;
                                                $next_approval_status_position= $caneditabsenceresult->approval_status4;
                                            } elseif ($caneditabsenceresult->approver4_id == $user->id) {
                                                $position = 4;
                                                $next_approval_status_position= $caneditabsenceresult->approval_status5;
                                            } elseif ($caneditabsenceresult->approver5_id == $user->id) {
                                                $position = 5;
                                            } else {
                                                $position = 100;
                                            }
                                        }
                                        if (($position != 1) &&
                                                ($position != 2) &&
                                                ($position != 3) &&
                                                ($position != 4) &&
                                                ($position != 5)) {

                                            $position = 100;
                                        }

                                        

                                //        $approval_status_position = "approval_status" . $position;
                                        $next_position = $position + 1;
                                     //   $next_approval_status_position = "approval_status" . $next_position;
                                        
                                       
                                        
                                //        $next_approver = "approver" . $next_position . "_id";

           
                  
                                        if (($position == 5) ||
                                                (
                                                ($position == 1) && ($caneditabsenceresult->approver2_id == 0) ||
                                              ($position == 2) && ($caneditabsenceresult->approver3_id ==  0) ||   
                                               ($position == 3) && ($caneditabsenceresult->approver4_id ==  0) ||  
                                               ($position == 4) && ($caneditabsenceresult->approver5_id ==  0)  
                                                
                                                ) 
                                                
                                                          ||
                                                (($next_approval_status_position == "Pending") && ($next_position != "101"))) {

                                            $canEditAbsence = 1;
                                        } else {

                                            $canEditAbsence = 0;
                                        }                
                    

                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
            $id = $data->id;
           
            foreach ($idresults as $item) {
                if ($item == $id) {
                    $edit_validity = true;
                 
                }
            }

         
            
            
            
      
                   if (($edit_validity == true) && ($canEditAbsence == 1))  {
                            
                            //      $data->employee_id = $userid;
                          
                    $data->datetime_from = hrHelper::getLocalTime($data->datetime_from);
                    $data->datetime_to = hrHelper::getLocalTime($data->datetime_to);


                    if ($data->measureunit == 'Days') {

                        $datetime_from = $data->datetime_from;
                        $datetime_from_new = strtotime(date("Y-m-d H:i", strtotime($datetime_from)));
                        $datetime_from_new = date('Y-m-d H:i', $datetime_from_new);
                        $data->datetime_from = $datetime_from_new;

                        $datetime_to = $data->datetime_to;
                        $datetime_to_new = strtotime(date("Y-m-d H:i", strtotime($datetime_to)) . " -" . 1 . " day");
                        $datetime_to_new = date('Y-m-d H:i', $datetime_to_new);
                        $data->datetime_to = $datetime_to_new;
                    } else {
                        $datetime_from = $data->datetime_from;
                        $datetime_from_new = strtotime(date("Y-m-d H:i", strtotime($datetime_from)));
                        $datetime_from_new = date('Y-m-d H:i', $datetime_from_new);
                        $data->datetime_from = $datetime_from_new;

                        $datetime_to = $data->datetime_to;
                        $datetime_to_new = strtotime(date("Y-m-d H:i", strtotime($datetime_to)));
                        $datetime_to_new = date('Y-m-d H:i', $datetime_to_new);
                        $data->datetime_to = $datetime_to_new;
                    }
                     return $data;
                } else {
           //       echo 'in';
                return false;
            }
           } else {
             // $data->employee_id = $userid;
               
                    return $data;
          //    echo 'out';
                  //   jexit();
            }
        }
    }

    protected function prepareTable($table) {
        
  

         $db = $this->getDbo();
                
                //COPIED
              	$date	= JFactory::getDate();
		$user	= JFactory::getUser();
                
        $now = new JDate('now', 'UTC');
       $nowstring = $now->format('Y-m-d H:i:s');
       $table->title =  $nowstring;
           
        //here
       
      
       
       if ($table->measureunit == 'Days') {
            $datetime_to = $table->datetime_to;
            $datetime_to_new = strtotime(date("Y-m-d H:i", strtotime($datetime_to)) . " +" . 1 . " day");
            $datetime_to_new = date('Y-m-d H:i', $datetime_to_new);
            $table->datetime_to = $datetime_to_new;
            
        }      else
        
        
        {
             $datetime_to = $table->datetime_to;
            $datetime_to_new = strtotime(date("Y-m-d H:i", strtotime($datetime_to)));
            $datetime_to_new = date('Y-m-d H:i', $datetime_to_new);
            $table->datetime_to = $datetime_to_new;
            
        }           
        

      

    $emailSent = 0;  
    
    

    
     //get the setting from the profile hr for approver and applicanr  
        
   $getprofilevalue = $db->getQuery(true);
$getprofilevalue->select($db->quoteName(array('u.profile_value')))
->from($db->quoteName('#__user_profiles', 'u'));


 
        
     if    (($table->approval_status1 == 'Rejected') Or 
            ($table->approval_status2 == 'Rejected') Or
            ($table->approval_status3 == 'Rejected') Or
            ($table->approval_status4 == 'Rejected') Or
            ($table->approval_status5 == 'Rejected'))
    {  
        $table->final_approval_status = 'Rejected';
        
        if ($emailSent == 0) {
                //send email to user for rejection 
            
$getprofilevalue->where('u.profile_key = ' . '"profile.applicant"'); 
$getprofilevalue->where ($db->quoteName('u.user_id') .  ' IN ( '. '\''. "$table->employee_id". '\''. ' )'); 
$db->setQuery($getprofilevalue);
$getprofilevalues = $db->loadObjectList();
foreach ($getprofilevalues as $getprofilevalue) {
$profile_value = $getprofilevalue->profile_value;
$profile_value_new =(int) substr($profile_value, 1, -1);
}                


              if   ($profile_value_new == 1) {    
                 
         HrHelper::sendEmail($table, $table->employee_id, 'is Rejected');
        $emailSent= 1;        
              }   
        }
    }
    
      elseif (($table->approval_status1 == null)  And 
              ($table->approval_status2 == null) And
              ($table->approval_status3 == null) And
              ($table->approval_status4 == null) And
              ($table->approval_status5 == null))
          
    {  
        $table->final_approval_status = 'Pending';
        
    
        
    }   
    
    
     elseif ((($table->approval_status1 == 'Approved') Or ($table->approval_status1 == null))  And 
            (($table->approval_status2 == 'Approved')  Or ($table->approval_status2 == null)) And
            (($table->approval_status3 == 'Approved')  Or ($table->approval_status3 == null)) And
            (($table->approval_status4 == 'Approved')  Or ($table->approval_status4 == null)) And
            (($table->approval_status5 == 'Approved')  Or ($table->approval_status5 == null)))
    {  
        $table->final_approval_status = 'Approved';
        
        if ($emailSent== 0){
            
              //send email to user for approval
       
            

$getprofilevalue->where('u.profile_key = ' . '"profile.applicant"'); 
$getprofilevalue->where ($db->quoteName('u.user_id') .  ' IN ( '. '\''. "$table->employee_id". '\''. ' )'); 
$db->setQuery($getprofilevalue);
$getprofilevalues = $db->loadObjectList();
foreach ($getprofilevalues as $getprofilevalue) {
$profile_value = $getprofilevalue->profile_value;
$profile_value_new =(int) substr($profile_value, 1, -1);
}                
                
              if   ($profile_value_new == 1) {           
               HrHelper::sendEmail($table, $table->employee_id, 'is Approved'); 
              $emailSent= 1;    
              }
            
        }
    }
    
    elseif (($table->approval_status1 == 'Pending') Or 
            ($table->approval_status2 == 'Pending') Or
            ($table->approval_status3 == 'Pending') Or
            ($table->approval_status4 == 'Pending') Or
            ($table->approval_status5 == 'Pending'))
    {  
        $table->final_approval_status = 'Pending';
        
        
         //send email to approvers for approval
 if ((($table->approval_status4 == 'Approved') And ($table->approval_status5 == 'Pending')) And 
     ($emailSent== 0)){
     //send email to approver 5
    // $approver_id = $table->approver5_id;
     
     
     
 $getprofilevalue->where('u.profile_key = ' . '"profile.approver"'); 
$getprofilevalue->where ($db->quoteName('u.user_id') .  ' IN ( '. '\''. "$table->approver5_id". '\''. ' )'); 
$db->setQuery($getprofilevalue);
$getprofilevalues = $db->loadObjectList();
foreach ($getprofilevalues as $getprofilevalue) {
$profile_value = $getprofilevalue->profile_value;
$profile_value_new =(int) substr($profile_value, 1, -1);
}                
              if   ($profile_value_new == 1)      
              {
      HrHelper::sendEmail($table, $table->approver5_id, '...waiting for Approval');
     $emailSent= 1;
              }
 } elseif
  ((($table->approval_status3 == 'Approved') And ($table->approval_status4 == 'Pending')) And 
     ($emailSent== 0)){
     //send email to approver 4
     //$approver_id = $table->approver4_id;
     
     
     
     
$getprofilevalue->where('u.profile_key = ' . '"profile.approver"'); 
$getprofilevalue->where ($db->quoteName('u.user_id') .  ' IN ( '. '\''. "$table->approver4_id". '\''. ' )'); 
$db->setQuery($getprofilevalue);
$getprofilevalues = $db->loadObjectList();
foreach ($getprofilevalues as $getprofilevalue) {
$profile_value = $getprofilevalue->profile_value;
$profile_value_new =(int) substr($profile_value, 1, -1);
}                
                
              if   ($profile_value_new == 1)       
              {
     
     HrHelper::sendEmail($table, $table->approver4_id, '...waiting for Approval');
     $emailSent= 1;
              }
 }  elseif
  ((($table->approval_status2 == 'Approved') And ($table->approval_status3 == 'Pending')) And 
     ($emailSent== 0)){
     //send email to approver 3
      //    $approver_id = $table->approver3_id;
     
     
$getprofilevalue->where('u.profile_key = ' . '"profile.approver"'); 
$getprofilevalue->where ($db->quoteName('u.user_id') .  ' IN ( '. '\''. "$table->approver3_id". '\''. ' )'); 
$db->setQuery($getprofilevalue);
$getprofilevalues = $db->loadObjectList();
foreach ($getprofilevalues as $getprofilevalue) {
$profile_value = $getprofilevalue->profile_value;
$profile_value_new =(int) substr($profile_value, 1, -1);
}                
                
              if   ($profile_value_new == 1)      
     
              { 
     HrHelper::sendEmail($table, $table->approver3_id, '...waiting for Approval');
     $emailSent= 1;
              }
 }   elseif
  ((($table->approval_status1 == 'Approved') And ($table->approval_status2 == 'Pending')) And 
     ($emailSent== 0)){
     //send email to approver 2
    // $approver_id = $table->approver2_id;
     
     
 
     
$getprofilevalue->where('u.profile_key = ' . '"profile.approver"'); 
$getprofilevalue->where ($db->quoteName('u.user_id') .  ' IN ( '. '\''. "$table->approver2_id". '\''. ' )'); 
$db->setQuery($getprofilevalue);
$getprofilevalues = $db->loadObjectList();
foreach ($getprofilevalues as $getprofilevalue) {
$profile_value = $getprofilevalue->profile_value;
$profile_value_new =(int) substr($profile_value, 1, -1);
}                
        
              if   ($profile_value_new == 1)         
              {
     HrHelper::sendEmail($table, $table->approver2_id, '...waiting for Approval');     
      $emailSent= 1;
              }
 }    
        
        
        
        
        
        
    }
    
    

    
    
    
    
    
    
    
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
	//	$table->version++;

		// Reorder the employee_absences within the category so the new employee_absence is first
		if (empty($table->id))
		{
			$table->reorder('catid = ' . (int) $table->catid . ' AND state >= 0');
		}        
         
    
   
 //   $table->abstype = htmlspecialchars_decode($table->abstype, ENT_QUOTES);
    
}
}