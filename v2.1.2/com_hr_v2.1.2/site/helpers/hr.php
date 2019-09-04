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

class HrHelper {
    /*
      static function  getLocalTime($nowstring)    {


      //    $now = new DateTime($datetime);
      //    $nowstring=$now->format('Y-m-d H:i:s');
      $userobj =JFactory::getUser();
      $timezone=$userobj->getParam('timezone','UTC');

      $time_object = new DateTime($nowstring, new DateTimeZone('UTC'));
      $time_object->setTimezone(new DateTimeZone($timezone));

      $user_datetime=$time_object->format('Y-m-d H:i:s');

      return $user_datetime;
      }
     */

    public static function getLocalTime($nowstring) {
        $userTz = JFactory::getUser()->getParam('timezone');
        //$timeZone = JFactory::getConfig()->getValue('offset');
        $config = JFactory::getConfig();
        $timeZone = $config->get('offset');
        if ($userTz) {
            $timeZone = $userTz;
        }
        //      return new DateTimeZone($timeZone);
        $time_object = new DateTime($nowstring, new DateTimeZone('UTC'));
        $time_object->setTimezone(new DateTimeZone($timeZone));
        $user_datetime = $time_object->format('Y-m-d H:i:s');

        return $user_datetime;
    }

    /*

      public static function getGmtTime($nowstring) {
      $userTz = JFactory::getUser()->getParam('timezone');
      //$timeZone = JFactory::getConfig()->getValue('offset');
      $config = JFactory::getConfig();
      $timeZone = $config->get('offset');
      if($userTz) {
      $timeZone = $userTz;
      }
      //      return new DateTimeZone($timeZone);


      $time_object = new DateTime($nowstring, new DateTimeZone('UTC'));
      $time_object->setTimezone(new DateTimeZone($timeZone));
      $user_datetime=$time_object->format('Y-m-d H:i:s');

      return $user_datetime;

      }

     */

    static function getGmtTime($nowstring) {

        $userTz = JFactory::getUser()->getParam('timezone');
        //$timeZone = JFactory::getConfig()->getValue('offset');
        $config = JFactory::getConfig();
        $timeZone = $config->get('offset');
        if ($userTz) {
            $timeZone = $userTz;
        }
        $local_timezone = $timeZone;



        // $userobj =JFactory::getUser();
        $system_timezone = date_default_timezone_get();

        //  $local_timezone =$userobj->getParam('timezone','UTC');
        date_default_timezone_set($local_timezone);
        $local = date("Y-m-d h:i:s A");

        date_default_timezone_set("GMT");
        $gmt = date("Y-m-d h:i:s A");


        date_default_timezone_set($system_timezone);
        $diff = (strtotime($gmt) - strtotime($local));

        $date = new DateTime($nowstring);
        $date->modify("+$diff seconds");
        $timestamp = $date->format("Y-m-d H:i:s");
        return $timestamp;
    }

    static function getRealIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    static function validateDate($date) {
        $format = 'Y-m-d H:i'; // Eg : 2014-09-24 10:19 PM
        $dateTime = DateTime::createFromFormat($format, $date);
        ////var_depr_dump($dateTime);

        if ($dateTime instanceof DateTime && $dateTime->format('Y-m-d H:i') == $date) {
            //  return $dateTime->getTimestamp();
            //    ////var_depr_dump('ok');
            return true;
        } else {

            return false;
        }
    }

    public static function userIsInAdminGroups($uid = 0) {
        $groups_of_user = JAccess::getGroupsByUser($uid, false);

        if (in_array(7, $groups_of_user)) {
            return true;
        } else {
            return false;
        }
    }

    static function GetMyCategory() {

        $mycatid = 0;
        $no_of_case = 0;

        //  $db	= $this->getDbo();
        $db = JFactory::getDbo();
        $user = JFactory::getUser();

        $userid = $user->id;
        $isroot = $user->authorise('core.admin');

        $now = new DateTime();
        $nowstring = $now->format('Y-m-d H:i');

        if ($isroot) {
            
        } else {

            $isroot = HrHelper::userIsInAdminGroups($userid);
        }

        $queryfindcatofuser = $db->getQuery(true);
        $queryfindcatofuser
                ->select($db->quoteName(array('refcatid')))
                ->from($db->quoteName('#__hr_admmgrcat', 'amc'))
                //         ->order('rp.roster_id, rp.no_of_day');
                ->where('amc.employee_id = ' . "'$userid'");
        // ->where ep.employee_id = '. "'$userid'"
        $db->setQuery($queryfindcatofuser);
        $replyAG = $db->query();
        $countrows = $db->getNumRows();

        if ($countrows > 0) {
            $no_of_case = 1;
            $queryfindcatofusers = $db->loadObjectList();
            foreach ($queryfindcatofusers as $queryfindcatofuser) {
                $mycatid = $queryfindcatofuser->refcatid;
            }
        }
        if ($countrows == 0) {

            if (!$isroot) {




                $queryfindcatofadmin = $db->getQuery(true);
                $queryfindcatofadmin
                        ->select($db->quoteName(array('c.id', 'c.title', 'p.title', 'u.name')))
                        ->from($db->quoteName('#__hr_employee_position', 'ep'))

                        //         ->order('rp.roster_id, rp.no_of_day');
                        ->join('LEFT', $db->quoteName('#__hr_position', 'p') . ' ON (' . $db->quoteName('p.id') . ' = ' . $db->quoteName('ep.position_id') . ')')
                        ->join('LEFT', $db->quoteName('#__users', 'u') . ' ON (' . $db->quoteName('ep.employee_id') . ' = ' . $db->quoteName('u.id') . ')')
                        ->join('LEFT', $db->quoteName('#__hr_positioncat', 'pc') . ' ON (' . $db->quoteName('p.id') . ' = ' . $db->quoteName('pc.position_id') . ')')
                        ->join('LEFT', $db->quoteName('#__categories', 'c') . ' ON (' . $db->quoteName('pc.refcatid') . ' = ' . $db->quoteName('c.id') . ')')
                        ->select('p.title AS position_title')
                        ->select('c.title AS category_title')
                        ->where($db->quoteName('u.id') . ' = ' . "'$userid'")
                        ->where($db->quoteName('p.title') . ' = ' . "'hosting position for structure'")
                        ->where($db->quoteName('c.extension') . ' = ' . "'com_hr'")
                        ->where($db->quoteName('c.title') . ' = ' . "'Structure'");




                $db->setQuery($queryfindcatofadmin);
                $queryfindcatofadmins = $db->loadObjectList();
                foreach ($queryfindcatofadmins as $queryfindcatofadmin) {
                    $mycatid = $queryfindcatofadmin->id;
                    $mycattitle = $queryfindcatofadmin->category_title;
                    $positiontitle = $queryfindcatofadmin->position_title;
                    $myname = $queryfindcatofadmin->name;


                    if (($positiontitle == 'hosting position for structure') && ($myname == 'admin') && ($mycattitle == 'Structure')) {


                        $no_of_case = 4;
                    }
                }



                if (($no_of_case == null) || ($no_of_case == 0)) {


                    $no_of_case = 2;
                    $query2findcatofuser = $db->getQuery(true);
                    $query2findcatofuser
                            ->select($db->quoteName(array('pc.refcatid')))
                            ->from($db->quoteName('#__hr_positioncat', 'pc'))
                            //         ->order('rp.roster_id, rp.no_of_day');
                            ->join('LEFT', $db->quoteName('#__hr_employee_position', 'ep') . ' ON (' . $db->quoteName('pc.position_id') . ' = ' . $db->quoteName('ep.position_id') . ')')
                            ->where('ep.employee_id = ' . "'$userid'")
                            ->where('(' . "'$nowstring'" . ' BETWEEN ' . $db->quoteName('pc.datetime_from') . ' AND ' . $db->quoteName('pc.datetime_to') . ')')
                            ->where('(' . "'$nowstring'" . ' BETWEEN ' . $db->quoteName('ep.datetime_from') . ' AND ' . $db->quoteName('ep.datetime_to') . ')');


                    $db->setQuery($query2findcatofuser);
                    $query2findcatofusers = $db->loadObjectList();
                    foreach ($query2findcatofusers as $query2findcatofuser) {
                        $mycatid = $query2findcatofuser->refcatid;
                    }
                }
            }

            if ($isroot) {
                $no_of_case = 3;
                $queryfindcatofroot = $db->getQuery(true);
                $queryfindcatofroot
                        ->select($db->quoteName(array('c.id')))
                        ->from($db->quoteName('#__categories', 'c'))
                        //         ->order('rp.roster_id, rp.no_of_day');
                        ->where($db->quoteName('c.extension') . ' = ' . "'com_hr'")
                        ->where($db->quoteName('c.title') . ' = ' . "'Structure'");




                $db->setQuery($queryfindcatofroot);
                $queryfindcatofroots = $db->loadObjectList();
                foreach ($queryfindcatofroots as $queryfindcatofroot) {
                    $mycatid = $queryfindcatofroot->id;
                }
            }
        }

        //         ////var_depr_dump($mycatid);
        //   jexit();


        if ($mycatid == null) {
            $mycatid = 0;
        }
        if ($no_of_case == null) {
            $no_of_case = 0;
        }

        $results = $no_of_case . $mycatid;


        return $results;
    }

    static function GetCategoriesWithChildren($categories) {
        $results = array();
        $db = JFactory::getDbo();
        foreach ($categories as $baseCategory) {
            $query = $db->getQuery(true);
            $query->select('c.path');
            $query->from('#__categories AS c');
            $query->where('c.published > 0');
            $query->where('c.id = ' . $baseCategory);
            $db->setQuery($query);
            $fathersList = $db->loadObjectList();
            foreach ($fathersList as $father) {
                $results[] = $baseCategory; // This adds the father only if it is published
                $query = $db->getQuery(true);
                $query->select('c.id');
                $query->from('#__categories AS c');
                $query->where('c.published > 0');
                $query->where($db->quoteName('c.extension') . ' = ' . "'com_hr'");
                $query->where('c.path LIKE \'' . $father->path . '/%\'');
                $db->setQuery($query);
                $children = $db->loadObjectList();
                foreach ($children as $category) {
                    $results[] = $category->id;
                }
            }
        }
        return $results;
    }

    public static function datetimesCompare($date1_string, $date2_string, $date3_string, $date4_string) {
        //checking  $date1, $date2 against $date3, $date4 
//used by the absence charge

        $date1 = date_create($date1_string);
        $date2 = date_create($date2_string);
        $date3 = date_create($date3_string);
        $date4 = date_create($date4_string);





        if (($date1 < $date3) && ($date2 > $date3) && ($date2 < $date4)) {
            $date1 = $date3;
            $date2 = $date2;
        } else
        //C
        if (($date1 < $date3) && ($date2 == $date4)) {
            $date1 = $date3;
            $date2 = $date4;
        } else
        //D
        if (($date1 < $date3) && ($date2 > $date4)) {
            $date1 = $date3;
            $date2 = $date4;
        } else

        if (($date1 == $date3) && ($date2 > $date3) && ($date2 < $date4)) {
            $date1 = $date3;
            $date2 = $date2;
        } else
        //G
        if (($date1 == $date3) && ($date2 == $date4)) {
            $date1 = $date3;
            $date2 = $date4;
        } else
        //H
        if (($date1 == $date3) && ($date2 > $date4)) {
            $date1 = $date3;
            $date2 = $date4;
        } else
        //I
        if (($date1 > $date3) && ($date1 < $date4) && ($date2 > $date3) && ($date2 < $date4)) {
            $date1 = $date1;
            $date2 = $date2;
        } else
        //J
        if (($date1 > $date3) && ($date1 < $date4) && ($date2 == $date4)) {
            $date1 = $date1;
            $date2 = $date4;
        } else
        //K
        if (($date1 > $date3) && ($date1 < $date4) && ($date2 > $date4)) {
            $date1 = $date1;
            $date2 = $date4;
        }

        //to return the value  as one concatenated string  
        $date1_converted_to_string = $date1->format("Y-m-d H:i:s");
        $date2_converted_to_string = $date2->format("Y-m-d H:i:s");

        //    



        return ($date1_converted_to_string . ' ' . $date2_converted_to_string);
    }

    public static function datesCompare($date1_string, $date2_string, $date3_string, $date4_string) {
        //checking  $date1, $date2 against $date3, $date4 


        $date1 = date_create($date1_string);
        $date2 = date_create($date2_string);
        $date3 = date_create($date3_string);
        $date4 = date_create($date4_string);




        if (($date1 < $date3) &&
                ($date2 < $date3)) {
            $date1 = date_create('1970-01-01');
            $date2 = date_create('1970-01-01');
        } else

        if (($date1 < $date3) &&
                ($date2 == $date3)) {
            $date1 = $date3;
            $date2 = $date3;
        } else
        //B
        if (($date1 < $date3) && ($date2 > $date3) && ($date2 < $date4)) {
            $date1 = $date3;
            $date2 = $date2;
        } else
        //C
        if (($date1 < $date3) && ($date2 == $date4)) {
            $date1 = $date3;
            $date2 = $date4;
        } else
        //D
        if (($date1 < $date3) && ($date2 > $date4)) {
            $date1 = $date3;
            $date2 = $date4;
        } else
        //E
        if (($date1 == $date3) && ($date2 == $date3)) {
            $date1 = $date3;
            $date2 = $date3;
        }
//F
        else
        if (($date1 == $date3) && ($date2 > $date3) && ($date2 < $date4)) {
            $date1 = $date3;
            $date2 = $date2;
        } else
        //G
        if (($date1 == $date3) && ($date2 == $date4)) {
            $date1 = $date3;
            $date2 = $date4;
        } else
        //H
        if (($date1 == $date3) && ($date2 > $date4)) {
            $date1 = $date3;
            $date2 = $date4;
        } else
        //I
        if (($date1 > $date3) && ($date1 < $date4) && ($date2 > $date3) && ($date2 < $date4)) {
            $date1 = $date1;
            $date2 = $date2;
        } else
        //J
        if (($date1 > $date3) && ($date1 < $date4) && ($date2 == $date4)) {
            $date1 = $date1;
            $date2 = $date4;
        } else
        //K
        if (($date1 > $date3) && ($date1 < $date4) && ($date2 > $date4)) {
            $date1 = $date1;
            $date2 = $date4;
        } else
        //L
        if (($date1 == $date4) && ($date2 == $date4)) {
            $date1 = $date4;
            $date2 = $date4;
        } else
        //M
        if (($date1 == $date4) && ($date2 > $date4)) {
            $date1 = $date4;
            $date2 = $date4;
        } else
        if (($date1 > $date4) && ($date2 > $date4)) {
            $date1 = date_create('1970-01-01');
            $date2 = date_create('1970-01-01');
        }


        //to return the value  as one concatenated string  
        $date1_converted_to_string = $date1->format("Y-m-d");
        $date2_converted_to_string = $date2->format("Y-m-d");

        //    




        return ($date1_converted_to_string . ' ' . $date2_converted_to_string);
    }

    public function sendEmail($table, $recipient_id, $absence_status) {

        // set the variables from the passed data
        $id = $table->id;
        $measureunit = $table->measureunit;
        $datetime_from = $table->datetime_from;
        $datetime_to = $table->datetime_to;


        if ($measureunit == 'Days') {
            $datetime_from_new = strtotime(date("Y-m-d H:i", strtotime($datetime_from)) . " -" . 0 . " day");
            $datetime_from = date('Y-m-d', $datetime_from_new);
            $datetime_to_new = strtotime(date("Y-m-d H:i", strtotime($datetime_to)) . " -" . 1 . " day");
            $datetime_to = date('Y-m-d', $datetime_to_new);
        } else {
            $datetime_from_new = strtotime(date("Y-m-d H:i", strtotime($datetime_from)) . " -" . 0 . " day");
            $datetime_from = date('Y-m-d H:i', $datetime_from_new);
            $datetime_to_new = strtotime(date("Y-m-d H:i", strtotime($datetime_to)) . " -" . 0 . " day");
            $datetime_to = date('Y-m-d H:i', $datetime_to_new);
        }



        $applicant_id = $table->employee_id;
        $user = JFactory::getUser($applicant_id);
        $applicant_name = $user->name;




        $db = JFactory::getDbo();
        $queryabstype = $db->getQuery(true);
        $queryabstype->select($db->quoteName(array('abstype')));
        $queryabstype->from($db->quoteName('#__hr_absence'));
//$abstype = $db->Quote($db->escape($table->abstype, true));
        $queryabstype->where('(id = ' . $table->absence_id . ')');
        $db->setQuery($queryabstype);
        $results = $db->loadObjectList();
        foreach ($results as $result) {
            $absence_type = $result->abstype;
        }



        $mailer = JFactory::getMailer();
        $config = JFactory::getConfig();

//create the sender      
        $sender = array(
            $config->get('mailfrom'),
            $config->get('fromname')
        );

        $mailer->setSender($sender);



//create the recipient
        if (($recipient_id != null) && ($recipient_id != 0)) {
            $user = JFactory::getUser($recipient_id);
        }
        $recipient_email = $user->email;
        $mailer->addRecipient($recipient_email);


//create the body
//$body   = $applicant_name. '    ' . $datetime_from. '   '. $datetime_to. '   ' . $absence_type. '   '. $absence_status;

        $body = $applicant_name . ' request for ' . $absence_type . ' from ' . $datetime_from . ' to ' . $datetime_to . ' ' . $absence_status;


        $bodyandlink = $body;



        $mailer->setSubject('Absence Application for Approval');


        if ($absence_status == '...waiting for Approval') {
            $uri = JUri::getInstance();
            $base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
            $link_address = $base . JRoute::_('index.php?option=com_hr&view=approve_employee_absences');

            $bodyandlink = <<<EOD
<div><a href='$link_address'>$body</a></div>
EOD;
            $mailer->isHtml(true);
            $mailer->Encoding = 'base64';
        }

        $mailer->setBody($bodyandlink);




//send the email
        $send = $mailer->Send();
        if ($send !== true) {
            echo 'Error sending email: ';
        } else {
            echo 'Mail sent';
        }
    }

    public function absenceCharge() {

        $data_employee_id = '';
        $data_absence_id = '';
        $data_effective_datetime = '';


        $db = JFactory::getDbo();

        $sumcharge = 0;
        $keep_employee_absence = '';
        $keep_employee_absence_type = '';
        $keep_employee_absence_roster = '';
        $keep_employee_absence_validity_from_validity_to = '';
        $keep_employee_absence_startdatetime = '';
        $keep_employee_absence_startdatetime_enddatetime = '';
        $keep_employee_absence_validity_from_validity_to_for_insert = '';
        $keep_employee = '';
        $charged = 0;
        $charge = 0;
        $count_no_of_days = 0;
        $keep_enddatetime = '';
        $count_charged_days = 0;
        $sum_of_periods = 0;
        $sum_of_charged_periods = 0;
        $sum_charge = 0;
        $employee_remaining_balance = 0;


        if ($data_absence_id == '') {
            $data_absence_id = $db->quoteName('ea.absence_id');
        }


        if ($data_employee_id == '') {
            $data_employee_id = $db->quoteName('ea.employee_id');
        }


        if ($data_effective_datetime == '') {
            $data_effective_datetime = $db->quoteName('1970-01-01 00:00:00');
        }



        //to delete all the charges  of the pending or processing absences

        $querydeleteabsencecharge = $db->getQuery(true);
        $querydeleteabsencecharge = "DELETE `ac`.* FROM `#__hr_absence_charge` AS `ac`
LEFT JOIN `#__hr_employee_absence` AS `ea` ON `ea`.`id` = `ac`.`id_of_the_absence` 
WHERE ((`ea`.`charge_status` != 'Completed' && `ea`.`final_approval_status` = 'Approved') ||
      (`ea`.`final_approval_status` != 'Approved'))";
        $db->setQuery($querydeleteabsencecharge);
        $db->execute();

//jexit();
        //  $querycompletedabsencecharge = $db->getQuery(true);

        $queryabsencecharge = $db->getQuery(true);
        $queryabsencecharge
                ->select($db->quoteName(array('ea.id', 'ea.datetime_from', 'ea.datetime_to', 'ea.employee_id', 'ea.absence_id', 'ea.measureunit', 'ea.final_approval_status', 'ea.charge_status')))
                ->select($db->quoteName(array('er.id', 'er.datetime_from', 'er.datetime_to', 'er.employee_id', 'er.work_status')))
                ->select($db->quoteName(array('u.id', 'u.name')))
                ->select($db->quoteName(array('ee.employee_id', 'ee.absence_id', 'ee.ref_year', 'ee.entitlement', 'ee.taken', 'ee.datetime_from', 'ee.datetime_to', 'ee.validity_datetime_from', 'ee.validity_datetime_to')))
                ->from($db->quoteName('#__hr_employee_absences', 'ea'))
                ->join('LEFT', $db->quoteName('#__hr_employee_roster', 'er') . ' ON (' . $db->quoteName('ea.employee_id') . ' = ' . $db->quoteName('er.employee_id') . ')')
                ->join('LEFT', $db->quoteName('#__users', 'u') . ' ON (' . $db->quoteName('ea.employee_id') . ' = ' . $db->quoteName('u.id') . ')')
                ->join('LEFT', $db->quoteName('#__hr_employee_entitlement', 'ee') . ' ON ((' . $db->quoteName('ee.employee_id') . ' = ' . $db->quoteName('ea.employee_id') . ')' . ' AND ' . '(' . $db->quoteName('ee.absence_id') . ' = ' . $db->quoteName('ea.absence_id') . ')' . ')');


        $queryabsencecharge->where('(er.state = 1)');
        $queryabsencecharge->where('(ee.state = 1)');


        $queryabsencecharge->select('ea.id AS employee_absence_id');
        $queryabsencecharge->select('ea.datetime_from AS employee_absence_datetime_from');
        $queryabsencecharge->select('ea.datetime_to AS employee_absence_datetime_to');
        $queryabsencecharge->select('ea.employee_id AS employee_absence_employee_id');
        $queryabsencecharge->select('ea.absence_id AS employee_absence_absence_id');
        $queryabsencecharge->select('ea.measureunit AS employee_absence_measureunit');
        $queryabsencecharge->select('ea.final_approval_status AS employee_absence_final_approval_status');
        $queryabsencecharge->select('ea.charge_status AS employee_absence_charge_status');

        $queryabsencecharge->select('er.id AS employee_roster_id');
        $queryabsencecharge->select('er.datetime_from AS employee_roster_datetime_from');
        $queryabsencecharge->select('er.datetime_to AS employee_roster_datetime_to');
        $queryabsencecharge->select('er.employee_id AS employee_roster_employee_id');
        $queryabsencecharge->select('er.work_status AS employee_roster_work_status');

        $queryabsencecharge->select('u.name AS user_name');


        $queryabsencecharge->select('ee.employee_id AS employee_entitlement_employee_id');
        $queryabsencecharge->select('ee.absence_id AS employee_entitlement_absence_id');
        $queryabsencecharge->select('ee.ref_year AS employee_entitlement_ref_year');
        $queryabsencecharge->select('ee.entitlement AS employee_entitlement_entitlement');
        $queryabsencecharge->select('ee.taken AS employee_entitlement_taken');
        $queryabsencecharge->select('ee.datetime_from AS employee_entitlement_datetime_from');
        $queryabsencecharge->select('ee.datetime_to AS employee_entitlement_datetime_to');
        $queryabsencecharge->select('ee.validity_datetime_from AS employee_entitlement_validity_datetime_from');
        $queryabsencecharge->select('ee.validity_datetime_to AS employee_entitlement_validity_datetime_to');




        $queryabsencecharge->where($db->quoteName('ea.final_approval_status') . ' = ' . "'Approved'");


        //  $queryabsencecharge->where($db->quoteName('ea.charge_status') . ' =  ' . "'Pending'");



        $queryabsencecharge->where
                ("(`ea`.`charge_status` = 'Pending' OR `ea`.`charge_status` = 'Processing')");






        $queryabsencecharge->where
                ("(((`ea`.`datetime_from` < `er`.`datetime_from`) AND (`ea`.`datetime_to` > `er`.`datetime_from`) AND (`ea`.`datetime_to` < `er`.`datetime_to`)) OR
             ((`ea`.`datetime_from` < `er`.`datetime_from`) AND (`ea`.`datetime_to` = `er`.`datetime_to`)) OR
             ((`ea`.`datetime_from` < `er`.`datetime_from`) AND (`ea`.`datetime_to` > `er`.`datetime_to`)) OR

             ((`ea`.`datetime_from` = `er`.`datetime_from`) AND (`ea`.`datetime_to` > `er`.`datetime_from`) AND (`ea`.`datetime_to` < `er`.`datetime_to`)) OR
             ((`ea`.`datetime_from` = `er`.`datetime_from`) AND (`ea`.`datetime_to` = `er`.`datetime_to`)) OR 
             ((`ea`.`datetime_from` = `er`.`datetime_from`) AND (`ea`.`datetime_to` > `er`.`datetime_to`)) OR
             ((`ea`.`datetime_from` > `er`.`datetime_from`) AND (`ea`.`datetime_from` < `er`.`datetime_to`) AND (`ea`.`datetime_to` > `er`.`datetime_from`) AND (`ea`.`datetime_to` < `er`.`datetime_to`))  OR 
             ((`ea`.`datetime_from` > `er`.`datetime_from`) AND (`ea`.`datetime_from` < `er`.`datetime_to`) AND (`ea`.`datetime_to` = `er`.`datetime_to`)) OR
             ((`ea`.`datetime_from` > `er`.`datetime_from`) AND (`ea`.`datetime_from` < `er`.`datetime_to`) AND (`ea`.`datetime_to` > `er`.`datetime_to`)))");



        $queryabsencecharge->where
                ("(((`ea`.`datetime_from` < `ee`.`validity_datetime_from`)   AND (`ea`.`datetime_to` > `ee`.`validity_datetime_from`) AND (`ea`.`datetime_to` < `ee`.`validity_datetime_to`)) OR
             ((`ea`.`datetime_from` < `ee`.`validity_datetime_from`) AND (`ea`.`datetime_to` = `ee`.`validity_datetime_to`)) OR
             ((`ea`.`datetime_from` < `ee`.`validity_datetime_from`) AND (`ea`.`datetime_to` > `ee`.`validity_datetime_to`)) OR

             ((`ea`.`datetime_from` = `ee`.`validity_datetime_from`) AND (`ea`.`datetime_to` > `ee`.`validity_datetime_from`) AND (`ea`.`datetime_to` < `ee`.`validity_datetime_to`)) OR
             ((`ea`.`datetime_from` = `ee`.`validity_datetime_from`) AND (`ea`.`datetime_to` = `ee`.`validity_datetime_to`)) OR 
             ((`ea`.`datetime_from` = `ee`.`validity_datetime_from`) AND (`ea`.`datetime_to` > `ee`.`validity_datetime_to`)) OR
             ((`ea`.`datetime_from` > `ee`.`validity_datetime_from`) AND (`ea`.`datetime_from` < `ee`.`validity_datetime_to`) AND (`ea`.`datetime_to` > `ee`.`validity_datetime_from`) AND (`ea`.`datetime_to` < `ee`.`validity_datetime_to`))  OR 
             ((`ea`.`datetime_from` > `ee`.`validity_datetime_from`) AND (`ea`.`datetime_from` < `ee`.`validity_datetime_to`) AND (`ea`.`datetime_to` = `ee`.`validity_datetime_to`)) OR
             ((`ea`.`datetime_from` > `ee`.`validity_datetime_from`) AND (`ea`.`datetime_from` < `ee`.`validity_datetime_to`) AND (`ea`.`datetime_to` > `ee`.`validity_datetime_to`)))");





        $queryabsencecharge->where($db->quoteName('ea.employee_id') . ' =  ' . $data_employee_id);
        $queryabsencecharge->where($db->quoteName('ea.absence_id') . ' =  ' . $data_absence_id);
        $queryabsencecharge->where($db->quoteName('er.work_status') . ' =  ' . "'WORK'");
        $queryabsencecharge->order($db->quoteName('u.name'));
        $queryabsencecharge->order($db->quoteName('ee.absence_id'));
        $queryabsencecharge->order($db->quoteName('ee.validity_datetime_to'));
        $queryabsencecharge->order($db->quoteName('ee.validity_datetime_from'));
        $queryabsencecharge->order($db->quoteName('er.datetime_from'));
        $queryabsencecharge->order($db->quoteName('ea.datetime_from'));





        //$queryabsencecharge->where($db->quoteName('datetime_from') . ' BETWEEN ' . "'$data_roster_datetime_from_for_delete'" . ' AND ' . "'$data_roster_datetime_to_for_delete'");
        $db->setQuery($queryabsencecharge);

        $replyAG = $db->query();
        $countrows = $db->getNumRows();


        if ($countrows > 0) {






// Load the results as a list of stdClass objects (see later for more options on retrieving data).
            $queryabsencecharges = $db->loadObjectList();


            $count_query_rows = 0;


            $queryinsertabsencecharge = $db->getQuery(true);
            $insertcolumns = array('id_of_the_absence', 'employee_id', 'absence_id', 'ref_year', 'datetime_from', 'datetime_to', 'charge');
            $queryinsertabsencecharge->columns($db->quoteName($insertcolumns));

            foreach ($queryabsencecharges as $queryabsencecharge) {


                $employee_absence_id = $queryabsencecharge->employee_absence_id;
                $employee_absence_datetime_from = $queryabsencecharge->employee_absence_datetime_from;
                $employee_absence_datetime_to = $queryabsencecharge->employee_absence_datetime_to;

                //        $employee_absence_datetime_to_new = new DateTime($employee_absence_datetime_to);
                //       $employee_absence_datetime_to_new->modify('+1 day');
                //       $employee_absence_datetime_to=date_format($employee_absence_datetime_to_new, 'Y-m-d H:i:s');  
                //      $current_date_datetime = new DateTime($current_date);
                //      $current_date_datetime->modify('+1 day');
                //    $current_date=date_format($current_date_datetime, 'Y-m-d'); 

                $employee_absence_employee_id = $queryabsencecharge->employee_absence_employee_id;
                $employee_absence_absence_id = $queryabsencecharge->employee_absence_absence_id;
                $employee_absence_measureunit = $queryabsencecharge->employee_absence_measureunit;
                $employee_absence_charge_status = $queryabsencecharge->employee_absence_charge_status;


                $employee_roster_id = $queryabsencecharge->employee_roster_id;
                $employee_roster_datetime_from = $queryabsencecharge->employee_roster_datetime_from;
                $employee_roster_datetime_to = $queryabsencecharge->employee_roster_datetime_to;
                $employee_roster_employee_id = $queryabsencecharge->employee_roster_employee_id;
                $employee_roster_work_status = $queryabsencecharge->employee_roster_work_status;
                $employee_roster_datetime_from_for_charge = $queryabsencecharge->employee_roster_datetime_from;

                $user_name = $queryabsencecharge->name;


                $employee_entitlement_employee_id = $queryabsencecharge->employee_entitlement_employee_id;
                $employee_entitlement_absence_id = $queryabsencecharge->employee_entitlement_absence_id;
                $employee_entitlement_ref_year = $queryabsencecharge->employee_entitlement_ref_year;
                $employee_entitlement_entitlement = $queryabsencecharge->employee_entitlement_entitlement;
                $employee_entitlement_taken = $queryabsencecharge->employee_entitlement_taken;

                $employee_entitlement_datetime_from = $queryabsencecharge->employee_entitlement_datetime_from;
                $employee_entitlement_datetime_to = $queryabsencecharge->employee_entitlement_datetime_to;
                $employee_entitlement_validity_datetime_from = $queryabsencecharge->employee_entitlement_validity_datetime_from;
                $employee_entitlement_validity_datetime_to = $queryabsencecharge->employee_entitlement_validity_datetime_to;

                $count_query_rows = $count_query_rows + 1;

                $current_employee = $employee_absence_employee_id;

                $current_employee_absence_type = $employee_absence_employee_id . $employee_absence_absence_id;





                $current_employee_absence = $employee_absence_employee_id . $employee_absence_absence_id . $employee_absence_datetime_from . $employee_absence_datetime_to;
                $current_employee_absence_roster = $employee_absence_employee_id . $employee_absence_absence_id . $employee_roster_datetime_from;

                ////var_depr_dump('$current_employee_absence' . $current_employee_absence);
                ////var_depr_dump('$current_employee_absence_roster' . $current_employee_absence_roster);



                $current_employee_absence_validity_from_validity_to = $employee_absence_employee_id .
                        $employee_absence_absence_id .
                        $employee_entitlement_validity_datetime_from .
                        $employee_entitlement_validity_datetime_to;





                $requesteddates = HrHelper::datetimesCompare($employee_absence_datetime_from, $employee_absence_datetime_to, $employee_roster_datetime_from, $employee_roster_datetime_to);



                $start_absence_roster_datetime = substr($requesteddates, 0, -20);
                $end_absence_roster_datetime = substr($requesteddates, 20);

                $employee_entitlement_validity_datetime_from = new DateTime($employee_entitlement_validity_datetime_from);
                $employee_entitlement_validity_datetime_from = date_format($employee_entitlement_validity_datetime_from, 'Y-m-d H:i:s');
//
                //      $employee_entitlement_validity_datetime_to = new DateTime($employee_entitlement_validity_datetime_to);
                //     $employee_entitlement_validity_datetime_to->modify('+1 day');
                //          $employee_entitlement_validity_datetime_to = date_format($employee_entitlement_validity_datetime_to, 'Y-m-d H:i:s');
//
                $requesteddates = HrHelper::datetimesCompare($start_absence_roster_datetime, $end_absence_roster_datetime, $employee_entitlement_validity_datetime_from, $employee_entitlement_validity_datetime_to);



                $startdatetime = substr($requesteddates, 0, -20);
                $enddatetime = substr($requesteddates, 20);

                $current_employee_absence_startdatetime = $employee_absence_employee_id . $employee_absence_absence_id . $startdatetime;
                $current_employee_absence_startdatetime_enddatetime = $employee_absence_employee_id . $employee_absence_absence_id . $startdatetime . $enddatetime;


                $date_startdatetime = new DateTime($startdatetime);
                //    $startdatetime_to_date_formatted  = date_format($date_startdatetime , 'Y-m-d H:i:s');

                $date_enddatetime = new DateTime($enddatetime);
                //    $enddatetime_to_date_formatted  = date_format($date_enddatetime , 'Y-m-d H:i:s');
//
                // to check the charged values againsta all the calls, hours           
// Load the results as a list of stdClass objects (see later for more options on retrieving data).

                /*
                  while ($current_date <= $end_date) {


                  if  ($employee_absence_measureunit == 'Days'){

                  }

                  if  ($employee_absence_measureunit == 'Hours_minutes'){

                  }


                  $current_date_datetime = new DateTime($current_date);
                  $current_date_datetime->modify('+1 day');
                  $current_date=date_format($current_date_datetime, 'Y-m-d');
                  }


                 */


                /*
                  $deleteconditions = array(
                  ' ( '. $db->quoteName('id_of_the_absence') . ' = ' . "'$employee_absence_id'" . ' AND '
                  . "'$employee_absence_charge_status'" . ' != ' . "'Completed'". ' ) ' );


                  $querydeleteabsencecharge->where($deleteconditions, 'OR');

                 */


                if ($current_employee != $keep_employee) {
                    $sumcharge = 0;
                    $count_no_of_days = 0;
                    $sum_of_periods = 0;
                    $count_charged_days = 0;
                    $sum_of_charged_periods = 0;
                    $keep_employee = $current_employee;
                }


                if ($current_employee_absence_validity_from_validity_to != $keep_employee_absence_validity_from_validity_to) {
                    $sumcharge = 0;
                    $count_no_of_days = 0;
                    $sum_of_periods = 0;
                    $keep_employee_absence_validity_from_validity_to = $current_employee_absence_validity_from_validity_to;
                }








                if ($current_employee_absence_type != $keep_employee_absence_type) {

                    if (($count_no_of_days != $count_charged_days) || ($sum_of_periods != $sum_of_charged_periods)) {



//insufficient balancev for the absence
                    } else {
                        $count_no_of_days = 0;
                        $count_charged_days = 0;
                        $sum_of_periods = 0;
                        $sum_of_charged_periods = 0;

                        $sum_charge = 0;
                        $employee_remaining_balance = 0;
                    }

                    $sumcharge = 0;

                    $keep_last_inserted = '1980-01-01 00:00:00';
                    $keep_enddatetime = '';
                    $keep_last_inserted_date = new DateTime($keep_last_inserted);
                    $keep_last_inserted_date_formatted = date_format($keep_last_inserted_date, 'Y-m-d H:i:s');
                    $keep_employee_absence_type = $current_employee_absence_type;
                }





//to count and compare charged against total calls and hours

                if ($current_employee_absence != $keep_employee_absence) {


                    $keep_employee_absence = $current_employee_absence;
                }


                $querycompletedabsencecharge = $db->getQuery(true);


                $querycompletedabsencecharge
//               ->select($db->quoteName(array('ac.id_of_the_absence', 'ac.employee_id', 'ac.datetime_from', 'ac.datetime_to', 'ac.charge', 'ac.ref_year')))
                        //          ->select($db->quoteName(array(SUM('ac.charge'))))
                        ->select(array('ac.id_of_the_absence', 'SUM(ac.charge)'))
                        ->from($db->quoteName('#__hr_absence_charge', 'ac'))
                        ->group($db->quoteName('ac.id_of_the_absence'))
                        //        ->group($db->quoteName('ac.absence_id'))
                        //->join('LEFT', $db->quoteName('#__hr_employee_absence', 'ea') . ' ON ((' . $db->quoteName('ac.employee_id') . ' = ' . $db->quoteName('ea.employee_id') . ')' . ' AND ' . '(' . $db->quoteName('ac.absence_id') . ' = ' . $db->quoteName('ea.absence_id') . ')' . ')');
                        ->join('LEFT', $db->quoteName('#__hr_employee_absence', 'ea') . ' ON (' . $db->quoteName('ea.id') . ' = ' . $db->quoteName('ac.id_of_the_absence') . ')');


                $querycompletedabsencecharge->where($db->quoteName('ac.absence_id') . ' =  ' . "'$employee_absence_absence_id'");
                $querycompletedabsencecharge->where($db->quoteName('ac.employee_id') . ' =  ' . "'$employee_absence_employee_id'");
                $querycompletedabsencecharge->where($db->quoteName('ea.final_approval_status') . ' = ' . "'Approved'");
                $querycompletedabsencecharge->where($db->quoteName('ea.charge_status') . ' =  ' . "'Completed'");



                $querycompletedabsencecharge->select('SUM(ac.charge) AS ac_sum_charge');

                $db->setQuery($querycompletedabsencecharge);
                $querycompletedabsencecharges = $db->loadObjectList();

                foreach ($querycompletedabsencecharges as $querycompletedabsencecharge) {


                    $sum_charge = $querycompletedabsencecharge->ac_sum_charge;
                }

                $employee_remaining_balance = $employee_entitlement_entitlement - $sum_charge;



                if ($current_employee_absence == $keep_employee_absence) {
                    if ($current_employee_absence_roster != $keep_employee_absence_roster) {


                        if ($employee_absence_measureunit == 'Days') {
                            $count_no_of_days = $count_no_of_days + 1;
                        }
                        if ($employee_absence_measureunit == 'Hours_minutes') {
                            //same roster every time within the absence        
                            $employee_absence_roster_period_start = new DateTime($start_absence_roster_datetime);
                            $employee_absence_roster_period_end = new DateTime($end_absence_roster_datetime);
                            $period_diff = (((int) (date_diff($employee_absence_roster_period_start, $employee_absence_roster_period_end, TRUE)->format("%H%"))) * 60) + (date_diff($employee_absence_roster_period_start, $employee_absence_roster_period_end, TRUE)->format("%I%"));


                            $sum_of_periods = $sum_of_periods + $period_diff;
                        }
                    }
                }



                if ($employee_absence_measureunit == 'Days') {

                    ////var_depr_dump($employee_entitlement_validity_datetime_from); 
                    ////var_depr_dump($startdatetime);             




                    $revised_employee_entitlement_validity_datetime_to = new DateTime($employee_entitlement_validity_datetime_to);
                    $revised_employee_entitlement_validity_datetime_to->modify('+1 day');
                    $employee_entitlement_validity_datetime_to = date_format($revised_employee_entitlement_validity_datetime_to, 'Y-m-d H:i:s');




                    if (($sumcharge <= ($employee_remaining_balance - 1)) &&
                            ($date_startdatetime > $keep_last_inserted_date) &&
                            ($employee_entitlement_validity_datetime_from <= $startdatetime) &&
                            ($employee_entitlement_validity_datetime_to >= $startdatetime)) {

                        //to update the charge status of the absence

                        $charge = 1;
                        $count_charged_days = $count_charged_days + 1;
                        $sumcharge = $sumcharge + $charge;
                        $keep_last_inserted_date = new DateTime($startdatetime);
                        $keep_last_inserted_date_formatted = date_format($keep_last_inserted_date, 'Y-m-d H:i:s');


                        $date_startdatetime_for_insert = $startdatetime;
                        $date_enddatetime_for_insert = $enddatetime;

                        $date_startdatetime_for_insert_unchanged = $startdatetime;
                        $date_enddatetime_for_insert_unchanged = $enddatetime;
                    } else {
                        $charge = 0;

                        $date_startdatetime_for_insert = $startdatetime;
                        $date_enddatetime_for_insert = $enddatetime;

                        $date_startdatetime_for_insert_unchanged = $startdatetime;
                        $date_enddatetime_for_insert_unchanged = $enddatetime;
                    }
                }





                if ($employee_absence_measureunit == 'Hours_minutes') { {

                        $date_startdatetime = new DateTime($startdatetime);
                        $date_enddatetime = new DateTime($enddatetime);

                        $date_startdatetime_for_insert_unchanged = $startdatetime;
                        $date_enddatetime_for_insert_unchanged = $enddatetime;

                        ////var_depr_dump($date_startdatetime);
                        ////var_depr_dump($date_enddatetime);
                        ////var_depr_dump($keep_last_inserted_date);

                        if (($keep_last_inserted_date > $date_startdatetime) &&
                                ($keep_last_inserted_date < $date_enddatetime)) {
                            //   jexit();
                            $startdatetime = $keep_last_inserted;
                            $date_startdatetime = new DateTime($startdatetime);
                            $charge = (((int) (date_diff($date_startdatetime, $date_enddatetime, TRUE)->format("%H%"))) * 60) + (date_diff($date_startdatetime, $date_enddatetime, TRUE)->format("%I%"));
                        }

                        if ($keep_last_inserted_date <= $date_startdatetime) {
                            //   jexit();
                            $charge = (((int) (date_diff($date_startdatetime, $date_enddatetime, TRUE)->format("%H%"))) * 60) + (date_diff($date_startdatetime, $date_enddatetime, TRUE)->format("%I%"));
                        }

                        if ($keep_last_inserted_date >= $date_enddatetime) {
                            //   jexit();
                            $charge = 0;
                            //     jexit();
                        }

                        //$sumcharge = $charge ; 
                        //}  



                        if ((($employee_remaining_balance - $sumcharge) <= $charge) && ($charge != 0)) {


                            $charge = $employee_remaining_balance - $sumcharge;
                            $sumcharge = $sumcharge + $charge;
                            //     $keep_enddatetime = $startdatetime;

                            if ($charge != 0) {


                                $keep_enddatetime_to_date = new DateTime($startdatetime);
                                $keep_new_enddatetime_to_date_modified = $keep_enddatetime_to_date->modify('+' . $charge . ' minutes');
                                //    $keep_enddatetime_to_date_modified_formatted=date_format($keep_enddatetime_to_date_modified, 'Y-m-d H:i:s');
                                //to have it as string
                                $keep_new_enddatetime = $keep_new_enddatetime_to_date_modified->format('Y-m-d H:i:s');

                                //     ////var_depr_dump('$keep_new_enddatetime '.$keep_new_enddatetime);     
                                // date_format($keep_enddatetime_to_date, 'Y-m-d H:i:s');          
                                //     $keep_enddatetime=$keep_enddatetime_to_date->format('Y-m-d H:i:s');


                                $date_enddatetime = new DateTime($keep_new_enddatetime);
                                // 21/09      $enddatetime = $keep_new_enddatetime;
                                //   $charge = (((int) (date_diff($date_startdatetime, $keep_enddatetime1, TRUE)->format("%H%"))) * 60) + (date_diff($date_startdatetime, $keep_enddatetime1, TRUE)->format("%I%"));  
                                $date_startdatetime_for_insert = $startdatetime;
                                $date_enddatetime_for_insert = $enddatetime;




                                $keep_last_inserted = $keep_new_enddatetime;
                                $keep_last_inserted_date = new DateTime($keep_last_inserted);
                                $keep_last_inserted_date_formatted = date_format($keep_last_inserted_date, 'Y-m-d H:i:s');
                            } else
                            if ($charge == 0) {
                                $date_startdatetime_for_insert = $startdatetime;
                                $date_enddatetime_for_insert = $enddatetime;
                            }
                            /*
                              ////var_depr_dump('$keep_last_inserted_date ');
                              ////var_depr_dump($keep_last_inserted_date);

                              ////var_depr_dump('$date_startdatetime_for_insert ');
                              ////var_depr_dump($date_startdatetime_for_insert);
                              ////var_depr_dump('$date_enddatetime_for_insert ');
                              ////var_depr_dump($date_enddatetime_for_insert);
                             */
                        } else

                        if ((($employee_remaining_balance - $sumcharge) > $charge) && ($charge != 0)) {


//$keep_enddatetime = '';
                            $keep_last_inserted = $enddatetime;
                            $keep_last_inserted_date = new DateTime($enddatetime);

                            $charge = (((int) (date_diff($date_startdatetime, $date_enddatetime, TRUE)->format("%H%"))) * 60) + (date_diff($date_startdatetime, $date_enddatetime, TRUE)->format("%I%"));
                            $date_startdatetime_for_insert = $startdatetime;
                            $date_enddatetime_for_insert = $enddatetime;




                            $sumcharge = $sumcharge + $charge;
                            //$keep_enddatetime_for_next_insert=date_format($keep_enddatetime, 'Y-m-d H:i:s');   
                            // $keep_enddatetime = $enddatetime;
                            //  $charge = $charge-$sumcharge;
                            //krata tin timi pou exei
                        } else

                        if ($charge == 0) {
                            $date_startdatetime_for_insert = $startdatetime;
                            $date_enddatetime_for_insert = $enddatetime;
                        }


                        $sum_of_charged_periods = $sum_of_charged_periods + $charge;



//for the ist timeof the loop
//////var_depr_dump('$k$keep_last_inserted ', $keep_last_inserted);
                        // $sumcharge = $sumcharge + $charge;
                        //  $keep_last_inserted_date = new DateTime($startdatetime);
                        //  $keep_last_inserted_date_to_date  = date_format($keep_last_inserted_date_to_date, 'Y-m-d H:i:s');
                    }
                }


                $sys_or_current_date = JHtml::date($input = 'now', 'Y-m-d H:i:s', false);



//$sys_or_current_date = '2015-02-01 00:00:00';            
//$sys_or_current_date = new DateTime($sys_or_current_date);
//$sys_or_current_date = date_format($sys_or_current_date, 'Y-m-d H:i:s');
//////var_depr_dump($sys_or_current_date);
//
//$current_date_string=date_format($currentdate, 'Y-m-d H:i:s');                    
//$current_date_string=$currentdate->format();
//////var_depr_dump($currentdate);
//jexit();
                //     $queryemployeeplancat->where("(DATE_FORMAT(e.date_from,'%Y') = '" . $data_ref_year . "' 
                //           OR '" . $data_dateofyear . "' BETWEEN e.date_from AND (DATE_ADD(e.date_to,INTERVAL 1 DAY))
                //           OR DATE_FORMAT(e.date_to,'%Y') = '" . $data_ref_year . "')");
//DATE_FORMAT($currentdate) =



                $queryupdatechargestatusofabsencetopending = $db->getQuery(true);
                // Fields to update.
                $fields_to_pending = array(
                    $db->quoteName('charge_status') . ' = ' . $db->quote('Pending')
                );
// Conditions for which records should be updated.
                $conditions_to_pending = array(
                    $db->quoteName('datetime_from') . ' > ' . "DATE_FORMAT('$sys_or_current_date', '%Y-%m-%d %H:%i:%s')",
                    $db->quoteName('datetime_to') . ' > ' . "DATE_FORMAT('$sys_or_current_date', '%Y-%m-%d %H:%i:%s')",
                    $db->quoteName('id') . ' = ' . "'$employee_absence_id'"
                );

                $queryupdatechargestatusofabsencetopending->update($db->quoteName('#__hr_employee_absence'))->set($fields_to_pending)->where($conditions_to_pending);
                $db->setQuery($queryupdatechargestatusofabsencetopending);
                $pending_result = $db->execute();


                $queryupdatechargestatusofabsencetocompleted = $db->getQuery(true);
                // Fields to update.
                $fields_to_completed = array(
                    $db->quoteName('charge_status') . ' = ' . $db->quote('Completed')
                );
// Conditions for which records should be updated.
                $conditions_to_completed = array(
                    $db->quoteName('datetime_from') . ' < ' . "DATE_FORMAT('$sys_or_current_date', '%Y-%m-%d %H:%i:%s')",
                    $db->quoteName('datetime_to') . ' <= ' . "DATE_FORMAT('$sys_or_current_date', '%Y-%m-%d %H:%i:%s')",
                    $db->quoteName('id') . ' = ' . "'$employee_absence_id'"
                );

                $queryupdatechargestatusofabsencetocompleted->update($db->quoteName('#__hr_employee_absence'))->set($fields_to_completed)->where($conditions_to_completed);
                $db->setQuery($queryupdatechargestatusofabsencetocompleted);
                $completed_result = $db->execute();


//for the 2 queries below
                //   $startdatetimedatetime = new DateTime($startdatetime);
                //     $currentdatetimedatetime = new DateTime($currentdate);

                $queryupdatechargestatusofabsencetoprocessing = $db->getQuery(true);
                // Fields to update.
                $fields_to_processing = array(
                    $db->quoteName('charge_status') . ' = ' . $db->quote('Processing')
                );
// Conditions for which records should be updated.
                $conditions_to_processing = array(
                    $db->quoteName('datetime_from') . ' <= ' . "DATE_FORMAT('$sys_or_current_date', '%Y-%m-%d %H:%i:%s')",
                    $db->quoteName('datetime_to') . ' > ' . "DATE_FORMAT('$sys_or_current_date', '%Y-%m-%d %H:%i:%s')",
                    $db->quoteName('id') . ' = ' . "'$employee_absence_id'"
                );

                $queryupdatechargestatusofabsencetoprocessing->update($db->quoteName('#__hr_employee_absence'))->set($fields_to_processing)->where($conditions_to_processing);
                $db->setQuery($queryupdatechargestatusofabsencetoprocessing);
                $processing_result = $db->execute();



                //if ($startdatetime != '1970-01-01 00:00:00')
                {

                    //use these 2 variuables for the insert
                    //        $keep_employee_absence_startdatetime_enddatetime = $current_employee_absence_startdatetime_enddatetime;
                    //     $keep_employee_absence_startdatetime = $current_employee_absence_startdatetime;
//
                    //       $keep_employee_absence_roster = $current_employee_absence_roster;



                    $showcharge = $charge;
                    ////var_depr_dump($showcharge);
                    //     if ($charge != 0) 
                    {
                        $values = array($employee_absence_id, $employee_absence_employee_id, $employee_absence_absence_id, $employee_entitlement_ref_year, "'$date_startdatetime_for_insert_unchanged'", "'$date_enddatetime_for_insert_unchanged'", $charge);
                        //    $values = array($employee_absence_id, $employee_absence_employee_id, $employee_absence_absence_id, $employee_entitlement_ref_year, "'$startdatetime'", "'$enddatetime'", $charge);



                        $queryinsertabsencecharge->insert($db->quoteName('#__hr_absence_charge'));
                        $queryinsertabsencecharge->values(implode(',', $values));
                        $charge = 0;
                    }




                    ////var_depr_dump('$user_name=' . $user_name);
                    ////var_depr_dump(' absence=' . $employee_absence_absence_id . '   ' . $employee_absence_datetime_from . '    ' . $employee_absence_datetime_to);
                    ////var_depr_dump(' roster=' . $employee_roster_datetime_from . '   ' . $employee_roster_datetime_to . '   ' . $employee_roster_work_status);
                    ////var_depr_dump('   $absence_roster_datetime' . $start_absence_roster_datetime . ' ' . $end_absence_roster_datetime);
                    ////var_depr_dump('   $startenddatetime' . $startdatetime . ' ' . $enddatetime);
                    ////var_depr_dump('insert ' . $date_startdatetime_for_insert . '   ' . $date_enddatetime_for_insert);
                    //     ////var_depr_dump($date_enddatetime_for_insert);  
                    //   ////var_depr_dump('   keep_last_inserted_date' . $keep_last_inserted_date_to_date);
                    ////var_depr_dump(' $sum_charge =' . $sum_charge);
                    ////var_depr_dump(' charge=' . $showcharge);
                    ////var_depr_dump(' count_no_of-days=' . $count_no_of_days);
                    ////var_depr_dump(' $count_charged_days =' . $count_charged_days);
                    ////var_depr_dump(' $sum_of_periods =' . $sum_of_periods);
                    ////var_depr_dump(' $sum_of_charged_periods =' . $sum_of_charged_periods);
                    //        ////var_depr_dump(' $period_diff ='. $period_diff. ' sumof periods =' . $sum_of_periods);     
                }
            }
//$queryinsertabsencecharge    
            //  ////var_depr_dump('$queryinsertabsencecharge');  
            //////var_depr_dump($queryinsertabsencecharge);        
            //   ->values($values);
            // Set the query using our newly populated query object and execute it.
            //$charge = 0;
            /*
              if ($sumcharge <= $employee_entitlement_entitlement){
              $employee_entitlement_taken = $employee_entitlement_taken + $charge;
              }



              if ($sumcharge > $employee_entitlement_entitlement){

              $employee_entitlement_taken = $employee_entitlement_taken + $charge;


              }

             */



            //      if ($startdatetime != '1970-01-01 00:00:00') 
//$querydeleteabsencecharge->delete($db->quoteName('#__hr_absence_charge'));
            //   $querydeleteabsencecharge->delete($db->quoteName('#__hr_absence_charge'));
            //     $db->setQuery($querydeleteabsencecharge);
            //   jexit();       

            $db->setQuery($queryinsertabsencecharge);
            $db->execute();
        }
        //                   ////var_depr_dump(' $data_roster_id=' . $data_roster_id);
        //                         ////var_depr_dump(' $data_employee_id=' . $data_employee_id);
        //   jexit();
        return true;
    }

    public static function getActions($categoryId = 0) {
        $user = JFactory::getUser();
        $result = new JObject;

        if (empty($categoryId)) {
            $assetName = 'com_hr';
            $level = 'component';
        } else {
            $assetName = 'com_hr.category.' . (int) $categoryId;
            $level = 'category';
        }

        $actions = JAccess::getActions('com_hr', $level);

        foreach ($actions as $action) {
            $result->set($action->name, $user->authorise($action->name, $assetName));
        }

        return $result;
    }

    public static function addSubmenu($vName = 'cpanels') {

        JHtmlSidebar::addEntry(
                JText::_('COM_HR_SUBMENU_CPANELS'), 'index.php?option=com_hr&view=cpanels', $vName == 'cpanels'
        );
        /*
          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_ADM_MGR_CATEGORIES'),
          'index.php?option=com_hr&view=admmgrcats',
          $vName == 'admmgrcats'
          );

          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_EMP_ENTL_BALS'),
          'index.php?option=com_hr&view=emp_entl_bals',
          $vName == 'emp_entl_bals'
          );

          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_EMPLOYEE_ATTENDANCES'),
          'index.php?option=com_hr&view=employee_attendances',
          $vName == 'employee_attendances'
          );


          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_POSITIONS'),
          'index.php?option=com_hr&view=positions',
          $vName == 'positions'
          );

          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_TITLES'),
          'index.php?option=com_hr&view=titles&layout=edit',
          $vName == 'titles'
          );


          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_CATEGORIES'),
          'index.php?option=com_categories&extension=com_hr',
          $vName == 'categories'
          );


          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_POSITION_CATEGORIES'),
          'index.php?option=com_hr&view=positioncats&layout=edit',
          $vName == 'positioncats'
          );



          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_EMPLOYEE_POSITIONS'),
          'index.php?option=com_hr&view=employee_positions',
          $vName == 'employee_positions'
          );

          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_EMPLOYEE_TITLES'),
          'index.php?option=com_hr&view=employee_titles&layout=edit',
          $vName == 'employee_titles'
          );


          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_ABSENCES'),
          'index.php?option=com_hr&view=absences',
          $vName == 'absences'
          );

          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_EMPLOYEE_ABSENCES'),
          'index.php?option=com_hr&view=employee_absences',
          $vName == 'employee_absences'
          );

          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_EMPLOYEE_APPROVERS'),
          'index.php?option=com_hr&view=employee_approvers',
          $vName == 'employee_approvers'
          );


          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_PLANS'),
          'index.php?option=com_hr&view=plans',
          $vName == 'plans'
          );

          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_ROSTERS'),
          'index.php?option=com_hr&view=rosters',
          $vName == 'rosters'
          );

          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_ROSTER_CATEGORIES'),
          'index.php?option=com_hr&view=rostercats',
          $vName == 'rostercats'
          );

          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_ROSTER_PATTERNS'),
          'index.php?option=com_hr&view=roster_patterns',
          $vName == 'roster_patterns'
          );


          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_EMPLOYEE_PATTERNLINES'),
          'index.php?option=com_hr&view=employee_patternlines',
          $vName == 'employee_patternlines'
          );


          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_EMPLOYEE_ROSTERS'),
          'index.php?option=com_hr&view=employee_rosters',
          $vName == 'employee_rosters'
          );

          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_EMPLOYEE_ROSTERS_BY_EMPLOYEE'),
          'index.php?option=com_hr&view=employee_roster_byemps',
          $vName == 'employee_roster_byemps'
          );

          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_EMPLOYEE_ROSTERS_BY_TRADE'),
          'index.php?option=com_hr&view=employee_roster_bytrades',
          $vName == 'employee_roster_bytrades'
          );


          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_ROSTER_MANAGEMENT'),
          'index.php?option=com_hr&view=roster_management&layout=edit',
          $vName == 'roster_management'
          );

          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_YEARLY_ENTITLEMENT'),
          'index.php?option=com_hr&view=yearly_entitlement&layout=edit',
          $vName == 'yearly_entitlement'
          );


          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_PLANCATS'),
          'index.php?option=com_hr&view=plancats',
          $vName == 'plancats'
          );


          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_PLAN_ENTITLEMENTS'),
          'index.php?option=com_hr&view=plan_entitlements',
          $vName == 'plan_entitlements'
          );

          JHtmlSidebar::addEntry(
          JText::_('COM_HR_SUBMENU_EMPLOYEE_ENTITLEMENTS'),
          'index.php?option=com_hr&view=employee_entitlements',
          $vName == 'employee_entitlements'
          );

         */


        JHtmlSidebar::addEntry(
                JText::_('COM_HR_SUBMENU_ABSENCE_CHARGES'), 'index.php?option=com_hr&view=absence_charge&layout=edit', $vName == 'absence_charge'
        );


        JHtmlSidebar::addEntry(
                JText::_('COM_HR_SUBMENU_CHANGEOFSTATE'), 'index.php?option=com_hr&view=changeofstates&layout=edit', $vName == 'changeofstates'
        );



        JHtmlSidebar::addEntry(
                JText::_('COM_HR_SUBMENU_PREVIEW'), 'index.php?option=com_hr&view=preview', $vName == 'preview'
        );
    }

}
