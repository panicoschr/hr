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

class HrModelApply_employee_absence extends JModelAdmin {

    protected $text_prefix = 'COM_HR';

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

    protected function canEditState1($record) {
        $user = JFactory::getUser();
        //    if (!empty($record->catid)) {
        //         return $user->authorise('core.edit.state', 'com_folio.category.' . (int) $record->catid);
        //    } else {
        //        return parent::canEditState($record);
        //    }
    }

    

    
    
    public function getTable($type = 'Employee_absence', $prefix = 'HrTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true) {

        $app = JFactory::getApplication();

        $form = $this->loadForm('com_hr.apply_employee_absence', 'apply_employee_absence', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function loadFormData() {
        //   jexit();

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
            $query->where($db->quoteName('employee_id') . ' IN ' .
                    '(SELECT DISTINCT ep.employee_id as empid
                       FROM  #__hr_employee_position As ep INNER JOIN
                       #__hr_positioncat As pc ON pc.position_id = ep.position_id 
                         where pc.refcatid IN (' . '\'' . "$stringofarrayofcatandsubcats" . '\'' . ') and ' .
                    "'$nowstring'" . ' between pc.datetime_from and pc.datetime_to and ' .
                    "'$nowstring'" . ' between ep.datetime_from and ep.datetime_to)'
            );
        }

        $db->setQuery($query);
        $results = $db->loadObjectList();
        

        foreach ($results as $result) {
            $idresults[] = $result->id;
        }


        
        
        $data = JFactory::getApplication()->getUserState('com_hr.edit.apply_employee_absence.data', array());



        if (empty($data)) {

            $data = $this->getItem();
                 
           
         
                if (isset($data->id) ) {
                                         if (($data->approval_status1 != 'Pending') && ($data->approval_status1 != null)) {
                                            $canEditAbsence = 0;
                                        } else {
                                            $canEditAbsence = 1;
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
                   // echo 'in';
                 return false;;
            }
           } else {
              $data->employee_id = $userid;
               
                    return $data;
          //      echo 'out';
          //     jexit();
            }
        }
    }

    protected function prepareTable($table) {
        
        $db = $this->getDbo();

        
       
        //COPIED
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        $now = new JDate('now', 'UTC');
        $nowstring = $now->format('Y-m-d H:i:s');
        $table->title = $nowstring;

  
        $table->charge_status = 'Pending';
        
        $table->language = '*';

       $table->employee_id = $user->id;

        if ($table->measureunit == 'Days') {
            $datetime_to = $table->datetime_to;
            $datetime_to_new = strtotime(date("Y-m-d H:i", strtotime($datetime_to)) . " +" . 1 . " day");
            $datetime_to_new = date('Y-m-d H:i', $datetime_to_new);
            $table->datetime_to = $datetime_to_new;
        } else {
            $datetime_to = $table->datetime_to;
            $datetime_to_new = strtotime(date("Y-m-d H:i", strtotime($datetime_to)));
            $datetime_to_new = date('Y-m-d H:i', $datetime_to_new);
            $table->datetime_to = $datetime_to_new;
        }





        if ($table->id) {
            // Existing item
            $table->modified = $date->toSql();
            $table->modified_by = $user->get('id');
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

        if ($table->state == 1 && (int) $table->publish_up == 0) {
            $table->publish_up = JFactory::getDate()->toSql();
        }

        if ($table->state == 1 && intval($table->publish_down) == 0) {
            $table->publish_down = $db->getNullDate();
        }

        // Increment the content version number.
        //	$table->version++;
        // Reorder the employee_absences within the category so the new employee_absence is first
        if (empty($table->id)) {
            $table->reorder('catid = ' . (int) $table->catid . ' AND state >= 0');
        }



        $user = JFactory::getUser();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('absence_id', 'employee_id', 'approver1_id', 'approver2_id', 'approver3_id', 'approver4_id', 'approver5_id')));
        $query->from($db->quoteName('#__hr_employee_approver'));
//$abstype = $db->Quote($db->escape($table->abstype, true));
        $query->where('(absence_id = ' . $table->absence_id . ' AND employee_id = ' . $user->id . ')');
        $db->setQuery($query);
        $results = $db->loadObjectList();


      foreach ($results as $result) {



            if (($result->approver1_id != null) && ($result->approver1_id != 0)) {
                $table->approver1_id = $result->approver1_id;
                $approver_id = $result->approver1_id;
                $table->approval_status1 = 'Pending';
            
                
                    //send email to approver 1
       
$getprofilevalue = $db->getQuery(true);
$getprofilevalue->select($db->quoteName(array('u.profile_value')))
->from($db->quoteName('#__user_profiles', 'u'));
$getprofilevalue->where('u.profile_key = ' . '"profile.approver"'); 
$getprofilevalue->where ($db->quoteName('u.user_id') .  ' IN ( '. '\''. "$approver_id". '\''. ' )'); 
$db->setQuery($getprofilevalue);
$getprofilevalues = $db->loadObjectList();
foreach ($getprofilevalues as $getprofilevalue) {
$profile_value = $getprofilevalue->profile_value;
$profile_value_new =(int) substr($profile_value, 1, -1);
}                
                
              if   ($profile_value_new == 1) {    
                
                HrHelper::sendEmail($table, $approver_id, '...waiting for Approval');            
                
              }
                
                

            }



            if (($result->approver2_id != null) && ($result->approver2_id != 0)) {
                $table->approver2_id = $result->approver2_id;
                $table->approval_status2 = 'Pending';
            }



            if (($result->approver3_id != null) && ($result->approver3_id != 0)) {
                $table->approver3_id = $result->approver3_id;
                $table->approval_status3 = 'Pending';
            }



            if (($result->approver4_id != null) && ($result->approver4_id != 0)) {
                $table->approver4_id = $result->approver4_id;
                $table->approval_status4 = 'Pending';
            }



            if (($result->approver5_id != null) && ($result->approver5_id != 0)) {
                $table->approver5_id = $result->approver5_id;
                $table->approval_status5 = 'Pending';
            }
        }

        if ((($table->approver1_id == null) || ($table->approver1_id == 0)) And ( ($table->approver2_id == null) || ($table->approver2_id == 0)) And ( ($table->approver3_id == null) || ($table->approver3_id == 0)) And ( ($table->approver4_id == null) || ($table->approver4_id == 0)) And ( ($table->approver5_id == null) || ($table->approver5_id == 0))) {
            $table->final_approval_status = 'Pending';
        }

   
        //       $table->abstype = htmlspecialchars_decode($table->abstype, ENT_QUOTES);
    }
    



    public function checkDates($data) {

        // set the variables from the passed data
        $id = $data['id'];
        $date1 = $data['datetime_from'];
        $date2 = $data['datetime_to'];
        $measureunit = $data['measureunit'];

        if ($measureunit == 'Days') {
            if ($date1 > $date2) {
                return false;
            } else {
                return true;
            }
        } else {
            if ($date1 >= $date2) {
                return false;
            } else {
                return true;
            }
        }
    }

    public function checkOverlappingAbsences($data) {


        require_once JPATH_COMPONENT . '/helpers/hr.php';

        // set the variables from the passed data
        $id = $data['id'];
        $measureunitorecord = $data['measureunit'];
        $date1string = $data['datetime_from'];
        $date1 = new DateTime($date1string);
        $date2string = $data['datetime_to'];

         $db = $this->getDbo();
         $user = JFactory::getUser();
		 
		 
        if ($measureunitorecord == 'Days') {


            $date2string = strtotime(date("Y-m-d H:i", strtotime($date2string)) . " +" . 1 . " day");
            $date2string = date('Y-m-d H:i', $date2string);
        }

        $date2 = new DateTime($date2string);
      //  $employee_id = $data['employee_id'];
		 $employee_id = $user->id;
        $absence_id = $data['absence_id'];
        $final_approval_status = $data['final_approval_status'];


        $count = 0;
        $keepDatesTrack = '';

        if ($final_approval_status != "Rejected") {

            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('a.employee_id', 'a.datetime_from', 'a.datetime_to', 'b.abstype', 'a.absence_id', 'a.measureunit', 'a.final_approval_status')));
            $query->from($db->quoteName('#__hr_employee_absence') . ' AS a');
            $query->where('(a.employee_id = ' . $employee_id . ') ');
            $query->where('(a.id != ' . $id . ') ');
            $query->where('(final_approval_status NOT LIKE ' . '"Rejected"' . ') ');
            $query->join('INNER', $db->quoteName('#__hr_absence', 'b') . ' ON (' . $db->quoteName('a.absence_id') . ' = ' . $db->quoteName('b.id') . ')');

            $db->setQuery($query);
            $results = $db->loadObjectList();


            foreach ($results as $result) {
                $datetime_from_string = $result->datetime_from;

                $datetime_to_string = $result->datetime_to;



                //   $absence = $result->absence_id;
                $measureunit = $result->measureunit;
                $abstype = $result->abstype;
                $final_status_of_leave = $result->final_approval_status;


                $local_datetime_from = hrHelper::getLocalTime($datetime_from_string);
                $local_datetime_to = hrHelper::getLocalTime($datetime_to_string);



                if ($measureunit == 'Days') {

                    $datetime_from = strtotime(date("Y-m-d H:i", strtotime($local_datetime_from)));
                    $datetime_from_string = date('Y-m-d H:i', $datetime_from);

                    $datetime_to = strtotime(date("Y-m-d H:i", strtotime($local_datetime_to)));
                    $datetime_to_string = date('Y-m-d H:i', $datetime_to);

                    //only for printing the message we deduct one day                
                    $datetime_to_minus_one_day = strtotime(date("Y-m-d H:i", strtotime($local_datetime_to)) . " -" . 1 . " day");
                    $datetime_to_string_minus_one_day = date('Y-m-d H:i', $datetime_to_minus_one_day);

                    $formatedfromdate = substr($datetime_from_string, 0, 10);
                    $formatedtodate = substr($datetime_to_string_minus_one_day, 0, 10);
                } else {

                    $datetime_from = strtotime(date("Y-m-d H:i", strtotime($local_datetime_from)));
                    $datetime_from_string = date('Y-m-d H:i', $datetime_from);

                    $datetime_to = strtotime(date("Y-m-d H:i", strtotime($local_datetime_to)));
                    $datetime_to_string = date('Y-m-d H:i', $datetime_to);

                    $formatedfromdate = substr($datetime_from_string, 0, 16);
                    $formatedtodate = substr($datetime_to_string, 0, 16);
                }



                $datetime_from = new DateTime($datetime_from_string);
                $datetime_to = new DateTime($datetime_to_string);
                //        ////var_depr_dump($datetime_from);
                //      ////var_depr_dump($datetime_to);
                //       jexit();       

                if ($datetime_from != $datetime_to) {

                    //0
                    //       if ((($date1 < $datetime_from) &&
                    //             ($date1 < $datetime_to) &&
                    //       ($date2 == $datetime_from)) || 
                    //  //1
                    if ((($date1 < $datetime_from) &&
                            ($date2 < $datetime_to) &&
                            ($date2 > $datetime_from)) ||
                            //2

                            (($date1 < $datetime_from) &&
                            ($date2 == $datetime_to)) ||
                            //3                       
                            (($date1 < $datetime_from) &&
                            ($date2 > $datetime_to)) ||
                            //4
                            (($date1 == $datetime_from) &&
                            ($date2 == $datetime_from)) ||
                            //5                        
                            (($date1 == $datetime_from) &&
                            ($date2 < $datetime_to) &&
                            ($date2 > $datetime_from)) ||
                            //6
                            (($date1 == $datetime_from) &&
                            ($date2 == $datetime_to)) ||
                            //7
                            (($date1 == $datetime_from) &&
                            ($date2 > $datetime_to) &&
                            ($date2 > $datetime_from)) ||
                            //8
                            (($date1 > $datetime_from) &&
                            ($date1 < $datetime_to) &&
                            ($date2 < $datetime_to) &&
                            ($date2 > $datetime_from)) ||
                            //9
                            (($date1 > $datetime_from) &&
                            ($date1 < $datetime_to) &&
                            ($date2 == $datetime_to)) ||
                            //10 
                            (($date1 > $datetime_from) &&
                            ($date1 < $datetime_to) &&
                            ($date2 > $datetime_to))) {
                        //11
                        //             (($date1 == $datetime_to) &&
                        //           ($date2 == $datetime_to)) ||
                        //11a 
                        //             (($date1 == $datetime_to) &&
                        //            ($date2 > $datetime_to))) {

                        $count = $count + 1;
                        $keepDatesTrack = $keepDatesTrack . ' ' . ($formatedfromdate . '  ' . $formatedtodate . '  ' . $final_status_of_leave . ' ' . $abstype . ',    ');
                    }
                }
                if ($datetime_from == $datetime_to) {

                    //12
                    if ((($date1 < $datetime_from) &&
                            ($date2 == $datetime_to)) ||
                            //13
                            (($date1 < $datetime_from) &&
                            ($date2 > $datetime_to))) {
                        //14
                        //         (($date1 == $datetime_from) &&
                        //       ($date2 == $datetime_to)) ||
                        //15
                        //         (($date1 == $datetime_from) &&
                        //          ($date2 > $datetime_to))) {
                        $count = $count + 1;
                        $keepDatesTrack = $keepDatesTrack . ' ' . ($formatedfromdate . '  ' . $formatedtodate . '  ' . $final_status_of_leave . ' ' . $abstype . ',    ');
                    }
                }
            }
        }

        if ($count > 0) {

            //         ////var_depr_dump($data);
            //            echo 'false';
            //       jexit();
            return ($keepDatesTrack);
        } else {
            //           echo 'true';
            //                   ////var_depr_dump($data);
            //       echo 'true';
            //              jexit();
            return 0;
        }
    }

}
