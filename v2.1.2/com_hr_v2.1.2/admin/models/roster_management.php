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

use Joomla\Registry\Registry;

JLoader::register('HrHelper', JPATH_ADMINISTRATOR . '/components/com_hr/helpers/hr.php');

/**
 * Item Model for an Roster_management.
 *
 * @since  1.6
 */
class HrModelRoster_management extends JModelAdmin
{
	/**
	 * @var        string    The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_HR';

	/**
	 * The type alias for this content type (for example, 'com_hr.roster_management').
	 *
	 * @var      string
	 * @since    3.2
	 */
	public $typeAlias = 'com_hr.roster_management';

	/**
	 * The context used for the associations table
	 *
	 * @var      string
	 * @since    3.4.4
	 */
	protected $associationsContext = 'com_hr.item';
        
  public function checkDates($data) {

        // set the variables from the passed data

        $date1string = $data['roster_datetime_from'];
        $date1 = new DateTime($date1string);
        $date2string = $data['roster_datetime_to'];
        $date2 = new DateTime($date2string);
      
        

        if ($date1 > $date2) {
            return false;
        } else {
            return true;
        }
    }

public function datetimesCompare($date1_string, $date2_string, $date3_string, $date4_string) {
      //checking  $date1, $date2 against $date3, $date4 
   
       
        $date1 = date_create($date1_string);
        $date2 = date_create($date2_string);
        $date3 = date_create($date3_string);
        $date4 = date_create($date4_string);
//////var_depr_dump($date1_string);
//////var_depr_dump($date1);
//jexit();





         if (($date1 < $date3) &&
                    ($date2 < $date3)) {
                $date1 = date_create('1970-01-01 00:00:00');
                $date2 = date_create('1970-01-01 00:00:00');
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
            }
            
            else
             if (($date1 > $date4) && ($date2 > $date4)) {
                $date1 = date_create('1970-01-01 00:00:00');
                $date2 = date_create('1970-01-01 00:00:00');
            }   

            
       
     //to return the value  as one concatenated string  
        $date1_converted_to_string = $date1->format("Y-m-d H:i:s");
        $date2_converted_to_string = $date2->format("Y-m-d H:i:s");
 
       //    
             
           
        
            
          return ($date1_converted_to_string.' '.$date2_converted_to_string);
        
        
    }        

    public function deleteAndInsertEmployeeRosters($data) {

                    $insertrosterdata = array();
                    $insertpatternlinedata = array();
        
        $count_no_of_cycles = 0;
    

 JTable::addIncludePath(JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_hr' . DIRECTORY_SEPARATOR . 'tables');
     

        

        $data_employee_id = $data['employee_id'];
        $data_roster_datetime_from = $data['roster_datetime_from'];
        $data_roster_datetime_to = $data['roster_datetime_to'];
        
        
        $data_roster_datetime_from =  $data_roster_datetime_from . ' 00:00:00';
         $data_roster_datetime_to =  $data_roster_datetime_to . ' 00:00:00';
                
      
        
        $data_catid = $data['catid'];
        
        
        $data_refcatid = $data['refcatid'];
        $data_roster_id = $data['roster_id'];

       

        
        

  
                        
  $data_roster_gmt_datetime_from = hrHelper::getGmtTime($data_roster_datetime_from);
  $data_roster_gmt_datetime_to = hrHelper::getGmtTime($data_roster_datetime_to);        
    

    
  //  jexit();
    
        /*
        if ($data_catid == '0') {
            $data_catid = 'catid';
        }

        if ($data_roster_id == '') {
            $data_roster_id = 'roster_id';
        }


        if ($data_employee_id == '') {
            $data_employee_id = 'employee_id';
        } 
        
*/
        
        $db = JFactory::getDbo();
        
        
        
        
        
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




$querymyuserandchildren  = $db->getQuery(true);
$querymyuserandchildren
                ->select($db->quoteName(array('u.id')))
                ->from($db->quoteName('#__users', 'u'));

  if (($no_of_case == 1) || ($no_of_case == 2)) {
$querymyuserandchildren->join('LEFT', $db->quoteName('#__hr_employee_position', 'ep') . ' ON (' . $db->quoteName('ep.employee_id') . ' = ' . $db->quoteName('u.id') . ')');
$querymyuserandchildren->join('LEFT', $db->quoteName('#__hr_positioncat', 'pc') . ' ON (' . $db->quoteName('ep.position_id') . ' = ' . $db->quoteName('pc.position_id') . ')');
$querymyuserandchildren->where('('. "'$nowstring'" . ' BETWEEN ' . $db->quoteName('pc.datetime_from'). ' AND '. $db->quoteName('pc.datetime_to').')' );
$querymyuserandchildren->where('('. "'$nowstring'" . ' BETWEEN ' . $db->quoteName('ep.datetime_from'). ' AND '. $db->quoteName('ep.datetime_to').')' );
$querymyuserandchildren->where($db->quoteName('pc.refcatid') . ' IN (' . '\''. "$stringofarrayofcatandsubcats". '\''. ')' );
  }
  
  
  if ($no_of_case == 4) {
$querymyuserandchildren->where($db->quoteName('u.name') . ' != ' . "'Super User'");
$querymyuserandchildren->where($db->quoteName('u.name') . ' != ' . "'admin'");

  }    
  
  
$db->setQuery($querymyuserandchildren);
$arraymyuserandchildrens = array();

$is=0;

$querymyuserandchildrens = $db->loadObjectList();

        foreach ($querymyuserandchildrens as $querymyuserandchildren) {
        //    jexit();
            $arraymyuserandchildrens[$is] = $querymyuserandchildren->id;
            $is=$is+1;
        }
$stringofarraymyuserandchildrens = implode("' , '", $arraymyuserandchildrens);







$querymyrosterandchildren  = $db->getQuery(true);
$querymyrosterandchildren
                ->select($db->quoteName(array('r.id')))
                ->from($db->quoteName('#__hr_roster', 'r'));

  if (($no_of_case == 1) || ($no_of_case == 2)) {
      $querymyrosterandchildren->join('INNER', $db->quoteName('#__hr_rostercat', 'rc') . ' ON (' . $db->quoteName('rc.roster_id') . ' = ' . $db->quoteName('r.id') . ')');

$querymyrosterandchildren->where('('. "'$nowstring'" . ' BETWEEN ' . $db->quoteName('rc.datetime_from'). ' AND '. $db->quoteName('rc.datetime_to').')' );
$querymyrosterandchildren->where($db->quoteName('rc.refcatid') . ' IN (' . '\''. "$stringofarrayofcatandsubcats". '\''. ')' );
$querymyrosterandchildren->where('(rc.state = 1)');

  }
  
$querymyrosterandchildren->where('(r.state = 1)');
  
$db->setQuery($querymyrosterandchildren);
$arraymyrosterandchildrens = array();

$is=0;

$querymyrosterandchildrens = $db->loadObjectList();

        foreach ($querymyrosterandchildrens as $querymyrosterandchildren) {
        //    jexit();
           $arraymyrosterandchildrens[$is] = $querymyrosterandchildren->id;
            $is=$is+1;
        }
$stringofarraymyrosterandchildrens = implode("' , '", $arraymyrosterandchildrens);



        
        
        //to delete old data
        //adjust the dates for the delete query

  //      ////var_depr_dump($data_roster_datetime_from);
   //     jexit();
        //$data_roster_datetime_from_for_delete = $data_roster_datetime_from . ' ' . '00:00';
        $data_roster_datetime_from_for_delete =$data_roster_gmt_datetime_from;
        
        $data_roster_datetime_to_for_delete = new DateTime($data_roster_gmt_datetime_to);
        $data_roster_datetime_to_for_delete->modify('+1 day');
        $data_roster_datetime_to_for_delete=date_format($data_roster_datetime_to_for_delete, 'Y-m-d H:i:s');  
        
        
 //       $data_roster_datetime_to_for_delete = strtotime(date("Y-m-d H:i:s", strtotime($data_roster_gmt_datetime_to)) . " +" . 1 . " day");
    //    $data_roster_datetime_to_for_delete = date('Y-m-d H:i', $data_roster_date_to_for_delete);
     //   $data_roster_datetime_to_for_delete = $data_roster_date_to_for_delete . ' ' . '00:00';
        
     ////var_depr_dump($data_roster_datetime_from_for_delete);
     ////var_depr_dump($data_roster_datetime_to_for_delete);
     //jexit();
        
//$queryinsertroster = $db->getQuery(true);
//$insertrostercolumns = array('catid', 'refcatid', 'roster_id', 'employee_id', 'datetime_from', 'datetime_to', 'work_status', 'no_of_day');                                      
//$queryinsertroster->columns($db->quoteName($insertrostercolumns));  
        
      



if ($data_refcatid == '') {
         //   $data_catid = $db->quoteName('catid');
        
$data_refcatid = $stringofarrayofcatandsubcats;    
////var_depr_dump('++++++++++++++++++++++');
////var_depr_dump($data_catid);
    
}   
    

if ($data_roster_id == '') {
            $data_roster_id = $stringofarraymyrosterandchildrens;
     ////var_depr_dump('-------------------');
////var_depr_dump($data_roster_id);       
}


if ($data_employee_id == '') {
            $data_employee_id = $stringofarraymyuserandchildrens;
            
     ////var_depr_dump('=======================');
////var_depr_dump($data_employee_id);               
}


/*
        
        if ((($data_catid == 'catid') &&
                ($data_roster_id == 'roster_id') &&
                ($data_employee_id == 'employee_id')) ||
             
            (($data_catid == 'catid') &&
                ($data_roster_id == 'roster_id') &&
                ($data_employee_id != 'employee_id'))) {   
            
            
            
                             ////var_depr_dump(' $data_catid=' . $data_catid);
                       ////var_depr_dump(' $data_roster_id=' . $data_roster_id);
                           ////var_depr_dump(' $data_employee_id=' . $data_employee_id);
      //                      jexit();   
            

                }
        
        
        
       
        
        
        
        
        if ((($data_catid == 'catid') &&
                ($data_roster_id == 'roster_id') &&
                ($data_employee_id == 'employee_id')) ||
             
            (($data_catid == 'catid') &&
                ($data_roster_id == 'roster_id') &&
                ($data_employee_id != 'employee_id'))) {  
            //   jexit();
            
          
  */          
      
/*
            $querydeleteolddatafromemployeeroster = $db->getQuery(true);
            // delete .
            $conditions = array(
                $db->quoteName('datetime_from') . ' BETWEEN ' . "'$data_roster_datetime_from_for_delete'" . ' AND ' . "'$data_roster_datetime_to_for_delete'",
                $db->quoteName('datetime_to') . ' BETWEEN ' . "'$data_roster_datetime_from_for_delete'" . ' AND ' . "'$data_roster_datetime_to_for_delete'",
    $db->quoteName('employee_id') .  ' IN ( '. '\''. "$data_employee_id". '\''. ' )',
                    $db->quoteName('refcatid') .  ' IN ( '. '\''. "$data_refcatid". '\''. ' )',
                    $db->quoteName('roster_id') .  ' IN ( '. '\''. "$data_roster_id". '\''. ' )',
   //                $db->quoteName('catid') . ' = ' . $data_catid,
    //                $db->quoteName('roster_id') . ' = ' . $data_roster_id,
                $db->quoteName('locked') . ' = ' . '0'
            );
            $querydeleteolddatafromemployeeroster->delete($db->quoteName('#__hr_employee_roster'));
            $querydeleteolddatafromemployeeroster->where($conditions);
            $db->setQuery($querydeleteolddatafromemployeeroster);
            $result2 = $db->execute();
    */     
           
            
            $trashstate = -2;
            $currentstate = 1;
                    
	$queryupdatedatafromemployeeroster = $db->getQuery(true);
        $queryupdatedatafromemployeerosterconditions = array(
            $db->quoteName('datetime_from') . ' BETWEEN ' . "'$data_roster_datetime_from_for_delete'" . ' AND ' . "'$data_roster_datetime_to_for_delete'",
            $db->quoteName('datetime_to') . ' BETWEEN ' . "'$data_roster_datetime_from_for_delete'" . ' AND ' . "'$data_roster_datetime_to_for_delete'",
            $db->quoteName('employee_id') . ' IN ( ' . '\'' . "$data_employee_id" . '\'' . ' )',
            $db->quoteName('refcatid') . ' IN ( ' . '\'' . "$data_refcatid" . '\'' . ' )',
            $db->quoteName('roster_id') . ' IN ( ' . '\'' . "$data_roster_id" . '\'' . ' )',
            $db->quoteName('state') . ' IN ( ' . '\'' . "$currentstate" . '\'' . ' )'
                //                $db->quoteName('catid') . ' = ' . $data_catid,
                //                $db->quoteName('roster_id') . ' = ' . $data_roster_id,
        );
        $queryupdatedatafromemployeeroster->update($db->quoteName('#__hr_employee_roster'));
        $queryupdatedatafromemployeeroster->set('state = ' . $trashstate);

        $queryupdatedatafromemployeeroster->where($queryupdatedatafromemployeerosterconditions);
        $db->setQuery($queryupdatedatafromemployeeroster);
        $db->execute();









        /*
        if ($data_catid == '0') {
            $data_catid = $db->quoteName('catid');
        }

        if ($data_roster_id == '') {
            $data_roster_id = $db->quoteName('roster_id');
        }


        if ($data_employee_id == '') {
            $data_employee_id = $db->quoteName('employee_id');
        }

*/

// Create a new query object.
        $queryemployeerostercat = $db->getQuery(true);

$queryemployeerostercat
//->select($db->quoteName(array('u.id', 'u.name')))
->select($db->quoteName(array('ep.id', 'ep.datetime_from', 'ep.datetime_to', 'ep.employee_id', 'ep.position_id')))
->select($db->quoteName(array('pc.id', 'pc.datetime_from', 'pc.datetime_to', 'pc.refcatid', 'pc.position_id')))
->select($db->quoteName(array('rc.id', 'rc.datetime_from', 'rc.datetime_to', 'rc.roster_id', 'rc.refcatid')))
->select($db->quoteName(array('epl.no_of_day', 'epl.ref_datetime')))
->from($db->quoteName('#__hr_employee_position', 'ep'))
//->join('INNER', $db->quoteName('#__hr_employee_position', 'ep') . ' ON (' . $db->quoteName('ep.employee_id') . ' = ' . $db->quoteName('u.id') . ')')                
->join('INNER', $db->quoteName('#__hr_position', 'p') . ' ON (' . $db->quoteName('ep.position_id') . ' = ' . $db->quoteName('p.id') . ')')                
->join('INNER', $db->quoteName('#__hr_positioncat', 'pc') . ' ON (' . $db->quoteName('pc.position_id') . ' = ' . $db->quoteName('p.id') . ')')        
->join('INNER', $db->quoteName('#__categories', 'c') . ' ON (' . $db->quoteName('pc.refcatid') . ' = ' . $db->quoteName('c.id') . ')')
->join('INNER', $db->quoteName('#__hr_rostercat', 'rc') . ' ON (' . $db->quoteName('rc.refcatid') . ' = ' . $db->quoteName('c.id') . ')')
->join('INNER', $db->quoteName('#__hr_roster', 'r') . ' ON (' . $db->quoteName('rc.roster_id') . ' = ' . $db->quoteName('r.id') . ')')                        
//   ->join('INNER', $db->quoteName('#__user_profiles', 'u') . ' ON (' . $db->quoteName('e.employee_id') . ' = ' . $db->quoteName('u.user_id') . ')')
->join('INNER', $db->quoteName('#__hr_employee_patternline', 'epl') . ' ON (' . $db->quoteName('ep.employee_id') . ' = ' . $db->quoteName('epl.employee_id') . ')');


        $queryemployeerostercat->select('ep.employee_id AS employee_id');
        
        $queryemployeerostercat->select('ep.id AS employee_position_id');
        $queryemployeerostercat->select('ep.datetime_from AS employee_position_datetime_from');
        $queryemployeerostercat->select('ep.datetime_to AS employee_position_datetime_to');
        $queryemployeerostercat->select('ep.employee_id AS employee_position_employee_id');
        $queryemployeerostercat->select('ep.position_id AS employee_position_category_id');
        
        $queryemployeerostercat->select('pc.datetime_from AS position_category_datetime_from');
        $queryemployeerostercat->select('pc.datetime_to AS position_category_datetime_to');
        $queryemployeerostercat->select('pc.refcatid AS position_category_id');
        $queryemployeerostercat->select('pc.position_id AS position_category_category_id');
        
        
        //  $queryemployeerostercat->select('u.profile_value AS profile_value');
        $queryemployeerostercat->select('rc.datetime_from AS roster_category_datetime_from');
        $queryemployeerostercat->select('rc.datetime_to AS roster_category_datetime_to');
        $queryemployeerostercat->select('rc.roster_id AS roster_category_roster_id');
        $queryemployeerostercat->select('rc.refcatid AS roster_category_category_id');
        
        $queryemployeerostercat->select('epl.no_of_day AS epl_no_of_day');
        $queryemployeerostercat->select('epl.ref_datetime AS epl_ref_datetime');

	$queryemployeerostercat->where('(ep.state = 1)');
	$queryemployeerostercat->where('(pc.state = 1)');        
        $queryemployeerostercat->where('(rc.state = 1)');
        $queryemployeerostercat->where('(epl.state = 1)');
      
        $queryemployeerostercat->where('epl.ref_datetime = ' . "'$data_roster_datetime_from_for_delete'");        
        
      
        //$date1string
        
        
           $queryemployeerostercat->where
                ("(((`pc`.`datetime_from` < '$data_roster_gmt_datetime_from') AND (`pc`.`datetime_to` > '$data_roster_gmt_datetime_from') AND (`pc`.`datetime_to` < '$data_roster_gmt_datetime_to')) OR
             ((`pc`.`datetime_from` < '$data_roster_gmt_datetime_from') AND (`pc`.`datetime_to` = '$data_roster_gmt_datetime_to')) OR
             ((`pc`.`datetime_from` < '$data_roster_gmt_datetime_from') AND (`pc`.`datetime_to` > '$data_roster_gmt_datetime_to')) OR

             ((`pc`.`datetime_from` = '$data_roster_gmt_datetime_from') AND (`pc`.`datetime_to` > '$data_roster_gmt_datetime_from') AND (`pc`.`datetime_to` < '$data_roster_gmt_datetime_to')) OR
             ((`pc`.`datetime_from` = '$data_roster_gmt_datetime_from') AND (`pc`.`datetime_to` = '$data_roster_gmt_datetime_to')) OR 
             ((`pc`.`datetime_from` = '$data_roster_gmt_datetime_from') AND (`pc`.`datetime_to` > '$data_roster_gmt_datetime_to')) OR
             ((`pc`.`datetime_from` > '$data_roster_gmt_datetime_from') AND (`pc`.`datetime_from` < '$data_roster_gmt_datetime_to') AND (`pc`.`datetime_to` > '$data_roster_gmt_datetime_from') AND (`pc`.`datetime_to` < '$data_roster_gmt_datetime_to'))  OR 
             ((`pc`.`datetime_from` > '$data_roster_gmt_datetime_from') AND (`pc`.`datetime_from` < '$data_roster_gmt_datetime_to') AND (`pc`.`datetime_to` = '$data_roster_gmt_datetime_to')) OR
             ((`pc`.`datetime_from` > '$data_roster_gmt_datetime_from') AND (`pc`.`datetime_from` < '$data_roster_gmt_datetime_to') AND (`pc`.`datetime_to` > '$data_roster_gmt_datetime_to')))");


  $queryemployeerostercat->where
                ("(((`ep`.`datetime_from` < '$data_roster_gmt_datetime_from') AND (`ep`.`datetime_to` > '$data_roster_gmt_datetime_from') AND (`ep`.`datetime_to` < '$data_roster_gmt_datetime_to')) OR
             ((`ep`.`datetime_from` < '$data_roster_gmt_datetime_from') AND (`ep`.`datetime_to` = '$data_roster_gmt_datetime_to')) OR
             ((`ep`.`datetime_from` < '$data_roster_gmt_datetime_from') AND (`ep`.`datetime_to` > '$data_roster_gmt_datetime_to')) OR

             ((`ep`.`datetime_from` = '$data_roster_gmt_datetime_from') AND (`ep`.`datetime_to` > '$data_roster_gmt_datetime_from') AND (`ep`.`datetime_to` < '$data_roster_gmt_datetime_to')) OR
             ((`ep`.`datetime_from` = '$data_roster_gmt_datetime_from') AND (`ep`.`datetime_to` = '$data_roster_gmt_datetime_to')) OR 
             ((`ep`.`datetime_from` = '$data_roster_gmt_datetime_from') AND (`ep`.`datetime_to` > '$data_roster_gmt_datetime_to')) OR
             ((`ep`.`datetime_from` > '$data_roster_gmt_datetime_from') AND (`ep`.`datetime_from` < '$data_roster_gmt_datetime_to') AND (`ep`.`datetime_to` > '$data_roster_gmt_datetime_from') AND (`ep`.`datetime_to` < '$data_roster_gmt_datetime_to'))  OR 
             ((`ep`.`datetime_from` > '$data_roster_gmt_datetime_from') AND (`ep`.`datetime_from` < '$data_roster_gmt_datetime_to') AND (`ep`.`datetime_to` = '$data_roster_gmt_datetime_to')) OR
             ((`ep`.`datetime_from` > '$data_roster_gmt_datetime_from') AND (`ep`.`datetime_from` < '$data_roster_gmt_datetime_to') AND (`ep`.`datetime_to` > '$data_roster_gmt_datetime_to')))");

        
        
     $queryemployeerostercat->where
                ("(((`rc`.`datetime_from` < '$data_roster_gmt_datetime_from') AND (`rc`.`datetime_to` > '$data_roster_gmt_datetime_from') AND (`rc`.`datetime_to` < '$data_roster_gmt_datetime_to')) OR
             ((`rc`.`datetime_from` < '$data_roster_gmt_datetime_from') AND (`rc`.`datetime_to` = '$data_roster_gmt_datetime_to')) OR
             ((`rc`.`datetime_from` < '$data_roster_gmt_datetime_from') AND (`rc`.`datetime_to` > '$data_roster_gmt_datetime_to')) OR

             ((`rc`.`datetime_from` = '$data_roster_gmt_datetime_from') AND (`rc`.`datetime_to` > '$data_roster_gmt_datetime_from') AND (`rc`.`datetime_to` < '$data_roster_gmt_datetime_to')) OR
             ((`rc`.`datetime_from` = '$data_roster_gmt_datetime_from') AND (`rc`.`datetime_to` = '$data_roster_gmt_datetime_to')) OR 
             ((`rc`.`datetime_from` = '$data_roster_gmt_datetime_from') AND (`rc`.`datetime_to` > '$data_roster_gmt_datetime_to')) OR
             ((`rc`.`datetime_from` > '$data_roster_gmt_datetime_from') AND (`rc`.`datetime_from` < '$data_roster_gmt_datetime_to') AND (`rc`.`datetime_to` > '$data_roster_gmt_datetime_from') AND (`rc`.`datetime_to` < '$data_roster_gmt_datetime_to'))  OR 
             ((`rc`.`datetime_from` > '$data_roster_gmt_datetime_from') AND (`rc`.`datetime_from` < '$data_roster_gmt_datetime_to') AND (`rc`.`datetime_to` = '$data_roster_gmt_datetime_to')) OR
             ((`rc`.`datetime_from` > '$data_roster_gmt_datetime_from') AND (`rc`.`datetime_from` < '$data_roster_gmt_datetime_to') AND (`rc`.`datetime_to` > '$data_roster_gmt_datetime_to')))");

        
             
        
        
       /* 
        
        if ($data_employee_id == 'employee_id') {
            $data_employee_id = $db->quoteName('ep.employee_id');
        }
        if (($data_employee_id != $db->quoteName('ep.employee_id')) && ($data_employee_id != $db->quoteName('employee_id'))) {
            $queryemployeerostercat->where('ep.employee_id = ' . $data_employee_id);
        }


        if ($data_catid == 'catid') {
            $data_catid = $db->quoteName('pc.refcatid');
        }
        if (($data_catid != $db->quoteName('pc.refcatid')) && ($data_catid != $db->quoteName('catid'))) {
            $queryemployeerostercat->where('pc.refcatid = ' . $data_catid);
        }


        if ($data_roster_id == 'roster_id') {
            $data_roster_id = $db->quoteName('rc.roster_id');
        }

        if (($data_roster_id != $db->quoteName('rc.roster_id')) && ($data_roster_id != $db->quoteName('roster_id'))) {
            $queryemployeerostercat->where('rc.roster_id = ' . $data_roster_id);
        }



*/
   $queryemployeerostercat->where ($db->quoteName('c.id') .  ' IN ( '. '\''. "$data_refcatid". '\''. ' )');  
   
   
 $queryemployeerostercat->where ($db->quoteName('ep.employee_id') .  ' IN ( '. '\''. "$data_employee_id". '\''. ' )'); 
      
      
 $queryemployeerostercat->where ($db->quoteName('rc.roster_id') .  ' IN ( '. '\''. "$data_roster_id". '\''. ' )'); 
      
      
      
      

      

        $queryrosterpattern = $db->getQuery(true);
        $queryrosterpattern
                ->select($db->quoteName(array('rp.id', 'rp.no_of_day', 'rp.time_from', 'rp.plus_no_of_days', 'rp.time_to', 'rp.work_status', 'rp.roster_id')))
                ->from($db->quoteName('#__hr_roster_pattern', 'rp'))
                ->order('rp.roster_id, rp.no_of_day');

	$queryrosterpattern->where('(rp.state = 1)');


        $db->setQuery($queryemployeerostercat);
        
  /*     
        $queryemployeerostercats = $db->loadObjectList();
      $i = 0;
      
            foreach ($queryemployeerostercats as $queryemployeerostercat) {
                $i++;
            }
        
echo $i;
jexit();
   * 
   * */
  
        $replyAG = $db->query();
        $countrows = $db->getNumRows();
        

 
if ($countrows > 0)   
        
    {
   // jexit();    
        

// Load the results as a list of stdClass objects (see later for more options on retrieving data).
        $queryemployeerostercats = $db->loadObjectList();

        $db->setQuery($queryrosterpattern);
        $queryrosterpatterns = $db->loadObjectList();


        foreach ($queryemployeerostercats as $queryemployeerostercat) {

            $count_no_of_days = 0;
            $first_time_loop = 0;

            $employee_id = $queryemployeerostercat->employee_id;
            
           
       
            $employee_position_datetime_from = hrHelper::getLocalTime($queryemployeerostercat->employee_position_datetime_from);
            $employee_position_datetime_to = hrHelper::getLocalTime($queryemployeerostercat->employee_position_datetime_to);
            $employee_position_employee_id = $queryemployeerostercat->employee_position_employee_id;
            $employee_position_id = $queryemployeerostercat->employee_position_id;
            
            $position_category_datetime_from = hrHelper::getLocalTime($queryemployeerostercat->position_category_datetime_from);
            $position_category_datetime_to = hrHelper::getLocalTime($queryemployeerostercat->position_category_datetime_to);
            $position_category_id = $queryemployeerostercat->position_category_id;
            $position_category_category_id = $queryemployeerostercat->position_category_category_id;
  
            
            //        $date_of_joinment = $queryemployeerostercat->profile_value;
            $roster_category_datetime_from = hrHelper::getLocalTime($queryemployeerostercat->roster_category_datetime_from);
            $roster_category_datetime_to = hrHelper::getLocalTime($queryemployeerostercat->roster_category_datetime_to);
            $roster_category_roster_id = $queryemployeerostercat->roster_category_roster_id;
            $roster_category_category_id = $queryemployeerostercat->roster_category_category_id;
           
            
            $epl_no_of_day = $queryemployeerostercat->epl_no_of_day;
            $ref_datetime = hrHelper::getLocalTime($queryemployeerostercat->epl_ref_datetime);


            $requesteddates = HrModelRoster_management::datetimesCompare($employee_position_datetime_from, $employee_position_datetime_to, $position_category_datetime_from, $position_category_datetime_to);

                   ////var_depr_dump('requested dates1' . $requesteddates);
            
      //      $requesteddates = HrModelRoster_management::datesCompare($employee_category_date_from, $employee_category_date_to, $roster_category_date_from, $roster_category_date_to);

//fields to run in the next datesCompare function
            //$employee_category_roster_category_end_date = substr($requesteddates, 11);
            $employee_position_category_start_datetime = substr($requesteddates, 0, -19);
            $employee_position_category_end_datetime = substr($requesteddates, 20);

            ////var_depr_dump('employee_position_category_start_datetime=' . $employee_position_category_start_datetime);
            ////var_depr_dump('employee_position_category_end_datetime=' . $employee_position_category_end_datetime);

  $requesteddates = HrModelRoster_management::datetimesCompare($employee_position_category_start_datetime, $employee_position_category_end_datetime, $roster_category_datetime_from, $roster_category_datetime_to);

                ////var_depr_dump('requested dates2' . $requesteddates);
   //         $requesteddates = HrModelRoster_management::datesCompare($employee_category_roster_category_start_date, $employee_category_roster_category_end_date, $data_roster_date_from, $data_roster_date_to);

            $employee_position_category_roster_category_start_datetime = substr($requesteddates, 0, -19);
            $employee_position_category_roster_category_end_datetime = substr($requesteddates, 20);
            
     ////var_depr_dump('$employee_position_category_roster_category_start_datetime' . $employee_position_category_roster_category_start_datetime);           

        ////var_depr_dump('$employee_position_category_roster_category_end_datetime' . $employee_position_category_roster_category_end_datetime);           

        ////var_depr_dump($data_roster_datetime_from);               
               ////var_depr_dump($data_roster_datetime_to);               


  $requesteddates = HrModelRoster_management::datetimesCompare($employee_position_category_roster_category_start_datetime, $employee_position_category_roster_category_end_datetime, $data_roster_datetime_from, $data_roster_datetime_to);

                ////var_depr_dump('requested dates3' . $requesteddates);
   //         $requesteddates = HrModelRoster_management::datesCompare($employee_category_roster_category_start_date, $employee_category_roster_category_end_date, $data_roster_date_from, $data_roster_date_to);

            $employee_position_category_roster_category_in_roster_start_datetime = substr($requesteddates, 0, -19);
            $employee_position_category_roster_category_in_roster_end_datetime = substr($requesteddates, 20);            

           ////var_depr_dump('employee_position_category_roster_category_in_roster_start_datetime' . $employee_position_category_roster_category_in_roster_start_datetime);
         ////var_depr_dump('employee_position_category_roster_category_in_roster_end_datetime' . $employee_position_category_roster_category_in_roster_end_datetime);


            $ref_datetime = strtotime(date("Y-m-d H:i", strtotime($employee_position_category_roster_category_in_roster_start_datetime)) . " -" . 1 . " day");
            $ref_datetime = date('Y-m-d H:i', $ref_datetime);

      ////var_depr_dump(' == ref_datetime ' . $ref_datetime);
            
      //jexit();
            //$first_time_loop = 0;
            $rp_no_of_day = -1;
            $keep_rp_no_of_day = -1;
            //$first_time_loop = $first_time_loop + 1;

            $start_datetime = $employee_position_category_roster_category_in_roster_start_datetime;
            $end_datetime = $employee_position_category_roster_category_in_roster_end_datetime;

      //      $data_roster_datetime_from = new DateTime($start_datetime);
        //    $data_roster_datetime_to = new DateTime($end_datetime);
            
            $data_roster_datetime_from_to_calculate = new DateTime($start_datetime);
            $data_roster_datetime_to_to_calculate = new DateTime($end_datetime);
            
         //   $total_no_of_days = (int) date_diff($data_roster_datetime_from, $data_roster_datetime_to, TRUE)->format("%R%a") + 1;

            
                   $total_no_of_days = (int) date_diff($data_roster_datetime_from_to_calculate, $data_roster_datetime_to_to_calculate, TRUE)->format("%R%a") + 1;

            
          ////var_depr_dump(' == $start_datetime  ' . $start_datetime);        
                    ////var_depr_dump(' == $end_datetime  ' . $end_datetime);   
               //     jexit();
          
      ////var_depr_dump(' == total_no_of_days  ' . $total_no_of_days);
   ////var_depr_dump(' $count_no_of_days ' . $count_no_of_days);      
  //   jexit();
      
            //$count_no_of_days = 0;

            while ($count_no_of_days <= $total_no_of_days) {
      ////var_depr_dump(' == total_no_of_days  ' . $total_no_of_days);
   ////var_depr_dump(' $count_no_of_days ' . $count_no_of_days); 
//jexit();
                $first_time_loop = $first_time_loop + 1;
                foreach ($queryrosterpatterns as $queryrosterpattern) {

                    $roster_pattern_id = $queryrosterpattern->id;
                    $roster_pattern_roster_id = $queryrosterpattern->roster_id;
                    $roster_pattern_time_from = $queryrosterpattern->time_from;
                    $roster_pattern_time_to = $queryrosterpattern->time_to;
                    $rp_no_of_day = $queryrosterpattern->no_of_day;
                    $plus_no_of_days = $queryrosterpattern->plus_no_of_days;
                    $roster_pattern_work_status = $queryrosterpattern->work_status;

                    if ($roster_category_roster_id === $roster_pattern_roster_id) {

                        if (((($rp_no_of_day >= $epl_no_of_day) && ($first_time_loop == 1)) || ($first_time_loop > 1)) && ($count_no_of_days <= $total_no_of_days)) {

                            if ($keep_rp_no_of_day == $rp_no_of_day) {

                                $current_datetime = strtotime(date("Y-m-d", strtotime($ref_datetime)) . " +" . $count_no_of_days . " day");
                                $current_datetime = date('Y-m-d', $current_datetime);

                                ////var_depr_dump(' == $roster_category_roster_id ' . $roster_category_roster_id);
                                ////var_depr_dump(' == $current_datetime ' . $current_datetime);
                            }

                            if ($keep_rp_no_of_day != $rp_no_of_day) {
                                $count_no_of_days = $count_no_of_days + 1;
                                $keep_rp_no_of_day = $rp_no_of_day;
                                $current_datetime = strtotime(date("Y-m-d", strtotime($ref_datetime)) . " +" . $count_no_of_days . " day");
                                $current_datetime = date('Y-m-d', $current_datetime);



                                ////var_depr_dump(' != $current_datetime ' . $current_datetime);
                                 ////var_depr_dump(' $count_no_of_days ' . $count_no_of_days);
                                    
                                
                            }

                            $time_from_only = substr($roster_pattern_time_from, 11, 5);
                            $time_to_only = substr($roster_pattern_time_to, 11, 5);

                                 $datetime_from = $current_datetime . ' ' . $time_from_only;
///?????
    /*                        if ($time_to_only == '00:00') {
                                //to handle same 00:00 and 00:00 from the pattern
                                $current_date = strtotime(date("Y-m-d", strtotime($current_date)) . " + 1 day");
                                $current_date = date('Y-m-j', $current_date);
                            }
                            
      */                      
     $current_datetime = strtotime(date("Y-m-d", strtotime($ref_datetime)) . " +" . ($count_no_of_days + $plus_no_of_days) . " day");
                               $current_datetime = date('Y-m-d', $current_datetime);
                          
                            
                            $datetime_to = $current_datetime . ' ' . $time_to_only;
        
        
                          
                            ////var_depr_dump(' == BEFORE IF $datetime_from ' . $datetime_from);    
                           ////var_depr_dump(' == BEFORE IF $end_date ' . $end_datetime);   
                    //       jexit(); 
                            
                            
 //$datetime_to = date('Y-m-j', $datetime_to);
                            //if (strtotime($datetime_from) < strtotime($end_datetime . ' ' . '00:00') + 24 * 60 * 60) {
       if (strtotime($datetime_from) < strtotime($end_datetime ) + 24 * 60 * 60) {
          
             
          //        if (strtotime($datetime_from) < strtotime($end_datetime ) ) {

                    ////var_depr_dump(' smaller' );                 
                                
                                ////var_depr_dump(' datetimefrom ' . $datetime_from);
                                ////var_depr_dump(' datetimeto ' . $datetime_to);

                                ////var_depr_dump('rp_no_of_day ' . $rp_no_of_day);
                                ////var_depr_dump('epl_no_of_day ' . $epl_no_of_day);
                                ////var_depr_dump('work status ' . $roster_pattern_work_status);

                                ////var_depr_dump(' time loop ' . $first_time_loop);


                                
                                
                                
               //DELETE PATTERN LINE                             
       //     $conditionsdeletepatternline = array(
       //         '(' . $db->quoteName('ref_date') . ' = ' . "'$data_roster_date_to_for_delete'" . ' AND ' . $db->quoteName('employee_id') . ' = ' . $employee_id . ')');
       //     $querydeleteolddatafromemployeepatternline->where($conditionsdeletepatternline, 'OR');

   

            //DELETE OLD ROSTER
       //     $deleterosterconditions = array(
      //          '(' . $db->quoteName('datetime_from') . ' BETWEEN ' . "'$data_roster_datetime_from_for_delete'" . ' AND ' . "'$data_roster_datetime_to_for_delete'" . ' AND ' . $db->quoteName('datetime_to') . ' BETWEEN ' . "'$data_roster_datetime_from_for_delete'" . ' AND ' . "'$data_roster_datetime_to_for_delete'" . ' AND ' . $db->quoteName('employee_id') . ' = ' . $employee_id . ' AND ' . $db->quoteName('locked') . ' = ' . '0' . ')');
     //       $querydeleteolddatafromemployeeroster->where($deleterosterconditions, 'OR');


     
                                
                                //get the gmt values
 //$datetime_from_string = new DateTime($datetime_from);                               
//$datetime_from_string=date_format($datetime_from_string, 'Y-m-d H:i:s');      
 //$datetime_to_string = new DateTime($datetime_to);
 //   $datetime_to_string=date_format($datetime_to_string, 'Y-m-d H:i:s');  
    
   $datetime_from_string = hrHelper::getGmtTime($datetime_from);
    $datetime_to_string = hrHelper::getGmtTime($datetime_to);                                
                                
                                
            //INSERT NEW ROSTER       
    
    
//if ((substr($datetime_from_string, 0, 10) != '1970-01-01') && (substr($datetime_from_string, 0, 10) != '1969-12-31')
  //         && (substr($datetime_from_string, 0, 10) != '1970-01-02')   && (substr($datetime_to_string, 0, 10) != '1969-12-31')
     //   && (substr($datetime_to_string, 0, 10) != '1970-01-01')   && (substr($datetime_to_string, 0, 10) != '1970-01-02'))
      
      
{
    
    
    /*
            $insertrostervalues = array($data_catid, $roster_category_category_id, $roster_category_roster_id, $employee_id,
                "'$datetime_from_string'", "'$datetime_to_string'", "'$roster_pattern_work_status'", 
                $rp_no_of_day);

            $queryinsertroster->insert($db->quoteName('#__hr_employee_roster'));
            $queryinsertroster->values(implode(',', $insertrostervalues));
    */        

   
            
               $count_no_of_cycles++;
            
        	$table = JTable::getInstance('Employee_roster', 'HrTable');      
                
        
                
                    $insertrosterdata['id'] = '';
                    $insertrosterdata['catid'] = $data_catid;
                    $insertrosterdata['refcatid'] = $roster_category_category_id;
                    $insertrosterdata['roster_id'] = $roster_category_roster_id;
                    $insertrosterdata['employee_id'] = $employee_id;
                    $insertrosterdata['datetime_from'] = $datetime_from_string;
                    $insertrosterdata['datetime_to'] = $datetime_to_string;
                    $insertrosterdata['work_status'] = $roster_pattern_work_status;
                    $insertrosterdata['no_of_day'] = $rp_no_of_day;

                    $insertrosterdata['state'] = 0;
                    $insertrosterdata['access'] = 1;
                    $insertrosterdata['params'] = '{"target":"","image":""}';
                    $insertrosterdata['metadata'] = '{"page_title":"","author":"","robots":"","tags":""}';
                    $insertrosterdata['language'] = '*';
                    $insertrosterdata['featured'] = 0;
                    
                   $date =JFactory::getDate();
                   $nowstring=$date->format('Y-m-d h:i:s:u A');
                  // $insertrosterdata['publish_up'] = $nowstring;  
   
                  $insertrosterdata['title'] = $nowstring.'-'.$count_no_of_cycles;
                $insertrosterdata['alias'] = $nowstring.'-'.$count_no_of_cycles;


                          if (!$table->save($insertrosterdata)) {
            $table->setError($this->getError());
            return false;
        }      
    
            
            
}           
           
            
        }



   //if (strtotime($datetime_from) >= strtotime($end_datetime . ' ' . '00:00') + 24 * 60 * 60) {
        if (strtotime($datetime_from) >= strtotime($end_datetime ) + 24 * 60 * 60) {
        //     if (strtotime($datetime_from) >= strtotime($end_datetime ) ) {

       

                    ////var_depr_dump(' greater' );   
                    
                                if ($time_to_only == '00:00') 
                                    {
                               $current_datetime = strtotime(date("Y-m-d", strtotime($current_datetime)) . " - 1 day");
                               $current_datetime = date('Y-m-d', $current_date);
                                }

       //INSERT PATTERN LINE  
            
                        
                                
        // delete  the entry from the employee pattern line    
        
                                
                    
   
                                
            /*
                                $querydeleteolddatafromemployeepatternlinenew = $db->getQuery(true);

                               $conditionsplnew = array(
                                   $db->quoteName('ref_datetime') . ' = ' . "'$data_roster_datetime_to_for_delete'",

                                   $db->quoteName('employee_id') . ' = ' . $employee_id
                               );

                               $querydeleteolddatafromemployeepatternlinenew->delete($db->quoteName('#__hr_employee_patternline'));
                               $querydeleteolddatafromemployeepatternlinenew->where($conditionsplnew);
                               $db->setQuery($querydeleteolddatafromemployeepatternlinenew);
                               $result1 = $db->execute();                                
               */                
                               
                               
                               
                                
         //Insert new Employee Pattern Line                 
                                
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('id')));
        $query->from($db->quoteName('#__categories'));
        $query->where($db->quoteName('title') . ' =  ' . "'Employee to Pattern Line'");        
        $query->where($db->quoteName('extension') . ' =  ' . "'com_hr'");    
        $db->setQuery($query);
        $results = $db->loadObjectList();
        foreach ($results as $result) {
            $catid = $result->id;
        }  
        
             
           	$table = JTable::getInstance('Employee_patternline', 'HrTable');       
           
                       
    //  $data_roster_datetime_to_for_delete_string = $data_roster_datetime_to_for_delete->format('Y-m-d H:i:s');
                
                   
                    $insertpatternlinedata['id'] = '';
                    $insertpatternlinedata['catid'] = (int) $catid; 
                    $insertpatternlinedata['employee_id'] = $employee_id;
                    $insertpatternlinedata['ref_datetime'] = $data_roster_datetime_to_for_delete;
                    $insertpatternlinedata['no_of_day'] = (int) $rp_no_of_day;
                    $insertpatternlinedata['state'] = 1;
                    $insertpatternlinedata['access'] = 1;
                    $insertpatternlinedata['params'] = '{"target":"","image":""}';
                    $insertpatternlinedata['metadata'] = '{"page_title":"","author":"","robots":"","tags":""}';
                    $insertpatternlinedata['language'] = '*';
                    $insertpatternlinedata['featured'] = 0;
                    
                   $date =JFactory::getDate();
                   $nowstring=$date->format('Y-m-d h:i:s:u A');
                   $insertpatternlinedata['publish_up'] = $nowstring;  
   
                  $insertpatternlinedata['title'] = $nowstring.'-'.$count_no_of_cycles;
                $insertpatternlinedata['alias'] = $nowstring.'-'.$count_no_of_cycles;
       

                          if (!$table->save($insertpatternlinedata)) {
            $table->setError($this->getError());
            return false;
        }                                
                                
                                
                        
                                
                                
                            }
                        }
                    }
                }
            }
        }
   
        /*
        $db->setQuery($querydeleteolddatafromemployeepatternline);
       $result1 = $db->execute();                                      

    $db->setQuery($queryinsertemployeepatternline);
    $result2 = $db->execute();  
                             
       */

                 //              ////var_depr_dump( $querydeleteolddatafromemployeeroster);   
                //             jexit();
    //  $db->setQuery($querydeleteolddatafromemployeeroster);
    //  $result3 = $db->execute();

 //     $db->setQuery($queryinsertroster);   removed today  5/2/17
 //     $result4 = $db->execute();


    }




    // jexit();
        return true;
    }  
        
        

	/**
	 * Batch copy items to a new category or current.
	 *
	 * @param   integer  $value     The new category.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  mixed  An array of new IDs on success, boolean false on failure.
	 *
	 * @since   11.1
	 */
	protected function batchCopy($value, $pks, $contexts)
	{
		$categoryId = (int) $value;

		$newIds = array();

		if (!parent::checkCategoryId($categoryId))
		{
			return false;
		}

		// Parent exists so we let's proceed
		while (!empty($pks))
		{
			// Pop the first ID off the stack
			$pk = array_shift($pks);

			$this->table->reset();

			// Check that the row actually exists
			if (!$this->table->load($pk))
			{
				if ($error = $this->table->getError())
				{
					// Fatal error
					$this->setError($error);

					return false;
				}
				else
				{
					// Not fatal error
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			// Alter the title & alias
			$data = $this->generateNewTitle($categoryId, $this->table->alias, $this->table->title);
			$this->table->title = $data['0'];
			$this->table->alias = $data['1'];


                        
                        
			// Reset the ID because we are making a copy
			$this->table->id = 0;

			// Reset hits because we are making a copy
			$this->table->hits = 0;

			// Unpublish because we are making a copy
			$this->table->state = 0;

			// New category ID
			$this->table->catid = $categoryId;

			// TODO: Deal with ordering?
			// $table->ordering	= 1;

			// Get the featured state
			$featured = $this->table->featured;

			// Check the row.
			if (!$this->table->check())
			{
				$this->setError($this->table->getError());

				return false;
			}

			parent::createTagsHelper($this->tagsObserver, $this->type, $pk, $this->typeAlias, $this->table);

			// Store the row.
			if (!$this->table->store())
			{
				$this->setError($this->table->getError());

				return false;
			}

			// Get the new item ID
			$newId = $this->table->get('id');

			// Add the new ID to the array
			$newIds[$pk] = $newId;

			// Check if the roster_management was featured and update the #__hr_roster_management_frontpage table
			if ($featured == 1)
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true)
					->insert($db->quoteName('#__hr_roster_management_frontpage'))
					->values($newId . ', 0');
				$db->setQuery($query);
				$db->execute();
			}
		}

		// Clean the cache
		$this->cleanCache();

		return $newIds;
	}

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 *
	 * @since   1.6
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id))
		{
			if ($record->state != -2)
			{
				return false;
			}

			$user = JFactory::getUser();

			return $user->authorise('core.delete', 'com_hr.roster_management.' . (int) $record->id);
		}

		return false;
	}

	/**
	 * Method to test whether a record can have its state edited.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 *
	 * @since   1.6
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		// Check for existing roster_management.
		if (!empty($record->id))
		{
			return $user->authorise('core.edit.state', 'com_hr.roster_management.' . (int) $record->id);
		}
		// New roster_management, so check against the category.
		elseif (!empty($record->catid))
		{
			return $user->authorise('core.edit.state', 'com_hr.category.' . (int) $record->catid);
		}
		// Default to component settings if neither roster_management nor category known.
		else
		{
			return parent::canEditState('com_hr');
		}
	}

	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param   JTable  $table  A JTable object.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function prepareTable($table)
	{
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
		$table->version++;

		// Reorder the roster_managements within the category so the new roster_management is first
		if (empty($table->id))
		{
			$table->reorder('catid = ' . (int) $table->catid . ' AND state >= 0');
		}
	}

	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable    A database object
	 */
	public function getTable($type = 'Roster_management', $prefix = 'HrTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			// Convert the params field to an array.
			$registry = new Registry;
			$registry->loadString($item->attribs);
			$item->attribs = $registry->toArray();

			// Convert the metadata field to an array.
			$registry = new Registry;
			$registry->loadString($item->metadata);
			$item->metadata = $registry->toArray();

			// Convert the images field to an array.
			$registry = new Registry;
			$registry->loadString($item->images);
			$item->images = $registry->toArray();

			// Convert the urls field to an array.
			$registry = new Registry;
			$registry->loadString($item->urls);
			$item->urls = $registry->toArray();
                        
                 
			$item->roster_managementtext = trim($item->fulltext) != '' ? $item->introtext . "<hr id=\"system-readmore\" />" . $item->fulltext : $item->introtext;

                      
			if (!empty($item->id))
			{
				$item->tags = new JHelperTags;
				$item->tags->getTagIds($item->id, 'com_hr.roster_management');
			}
		}

		// Load associated content items
		$app = JFactory::getApplication();
		$assoc = JLanguageAssociations::isEnabled();

		if ($assoc)
		{
			$item->associations = array();

			if ($item->id != null)
			{
				$associations = JLanguageAssociations::getAssociations('com_hr', '#__hr_roster_management', 'com_hr.item', $item->id);

				foreach ($associations as $tag => $association)
				{
					$item->associations[$tag] = $association->id;
				}
			}
		}

		return $item;
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_hr.roster_management', 'roster_management', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		$jinput = JFactory::getApplication()->input;

		// The front end calls this model and uses a_id to avoid id clashes so we need to check for that first.
		if ($jinput->get('a_id'))
		{
			$id = $jinput->get('a_id', 0);
		}
		// The back end uses id so we use that the rest of the time and set it to 0 by default.
		else
		{
			$id = $jinput->get('id', 0);
		}

		// Determine correct permissions to check.
		if ($this->getState('roster_management.id'))
		{
			$id = $this->getState('roster_management.id');

			// Existing record. Can only edit in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit');

			// Existing record. Can only edit own roster_managements in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit.own');
		}
		else
		{
			// New record. Can only create in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

		$user = JFactory::getUser();

		// Check for existing roster_management.
		// Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('core.edit.state', 'com_hr.roster_management.' . (int) $id))
			|| ($id == 0 && !$user->authorise('core.edit.state', 'com_hr')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('featured', 'disabled', 'true');
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('publish_up', 'disabled', 'true');
			$form->setFieldAttribute('publish_down', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is an roster_management you can edit.
			$form->setFieldAttribute('featured', 'filter', 'unset');
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('publish_up', 'filter', 'unset');
			$form->setFieldAttribute('publish_down', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');
		}

		// Prevent messing with roster_management language and category when editing existing roster_management with associations
		$app = JFactory::getApplication();
		$assoc = JLanguageAssociations::isEnabled();

		// Check if roster_management is associated
		if ($this->getState('roster_management.id') && $app->isSite() && $assoc)
		{
			$associations = JLanguageAssociations::getAssociations('com_hr', '#__hr_roster_management', 'com_hr.item', $id);

			// Make fields read only
			if (!empty($associations))
			{
				$form->setFieldAttribute('language', 'readonly', 'true');
				$form->setFieldAttribute('catid', 'readonly', 'true');
				$form->setFieldAttribute('language', 'filter', 'unset');
				$form->setFieldAttribute('catid', 'filter', 'unset');
			}
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_hr.edit.roster_management.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

			// Pre-select some filters (Status, Category, Language, Access) in edit form if those have been selected in Roster_management Manager: Roster_managements
			if ($this->getState('roster_management.id') == 0)
			{
				$filters = (array) $app->getUserState('com_hr.roster_managements.filter');
				$data->set(
					'state',
					$app->input->getInt(
						'state',
						((isset($filters['published']) && $filters['published'] !== '') ? $filters['published'] : null)
					)
				);
				$data->set('catid', $app->input->getInt('catid', (!empty($filters['category_id']) ? $filters['category_id'] : null)));
				$data->set('language', $app->input->getString('language', (!empty($filters['language']) ? $filters['language'] : null)));
				$data->set('access',
					$app->input->getInt('access', (!empty($filters['access']) ? $filters['access'] : JFactory::getConfig()->get('access')))
				);
			}
		}

		// If there are params fieldsets in the form it will fail with a registry object
		if (isset($data->params) && $data->params instanceof Registry)
		{
			$data->params = $data->params->toArray();
		}

    if (is_object($data)) {               
    if ($data->id == NULL) {
            $found = false;
            $db = $this->getDbo();
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('id')));
            $query->from($db->quoteName('#__categories'));
            $query->where($db->quoteName('extension') . ' = ' . "'com_hr'");
            $query->where($db->quoteName('title') . ' = ' . "'Rosters of Employees'");
            $db->setQuery($query);
            $results = $db->loadObjectList();
            foreach ($results as $result) {
                $catidfound = $result->id;
                $found = true;
            }
            if ($found == true) {
                $data->catid = $catidfound;
            }
        }
    }
		$this->preprocessData('com_hr.roster_management', $data);

		return $data;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.6
	 */
	public function save($data)
	{
		$input  = JFactory::getApplication()->input;
		$filter = JFilterInput::getInstance();

		if (isset($data['metadata']) && isset($data['metadata']['author']))
		{
			$data['metadata']['author'] = $filter->clean($data['metadata']['author'], 'TRIM');
		}

		if (isset($data['created_by_alias']))
		{
			$data['created_by_alias'] = $filter->clean($data['created_by_alias'], 'TRIM');
		}

		if (isset($data['images']) && is_array($data['images']))
		{
			$registry = new Registry;
			$registry->loadArray($data['images']);

			$data['images'] = (string) $registry;
		}

		JLoader::register('CategoriesHelper', JPATH_ADMINISTRATOR . '/components/com_categories/helpers/categories.php');

		// Cast catid to integer for comparison
		$catid = (int) $data['catid'];

		// Check if New Category exists
		if ($catid > 0)
		{
			$catid = CategoriesHelper::validateCategoryId($data['catid'], 'com_hr');
		}

		// Save New Categoryg
		if ($catid == 0 && $this->canCreateCategory())
		{
			$table = array();
			$table['title'] = $data['catid'];
			$table['parent_id'] = 1;
			$table['extension'] = 'com_hr';
			$table['language'] = $data['language'];
			$table['published'] = 1;

			// Create new category and get catid back
			$data['catid'] = CategoriesHelper::createCategory($table);
		}

		if (isset($data['urls']) && is_array($data['urls']))
		{
			$check = $input->post->get('jform', array(), 'array');

			foreach ($data['urls'] as $i => $url)
			{
				if ($url != false && ($i == 'urla' || $i == 'urlb' || $i == 'urlc'))
				{
					if (preg_match('~^#[a-zA-Z]{1}[a-zA-Z0-9-_:.]*$~', $check['urls'][$i]) == 1)
					{
						$data['urls'][$i] = $check['urls'][$i];
					}
					else
					{
						$data['urls'][$i] = JStringPunycode::urlToPunycode($url);
					}
				}
			}

			unset($check);

			$registry = new Registry;
			$registry->loadArray($data['urls']);

			$data['urls'] = (string) $registry;
		}

		// Alter the title for save as copy
		if ($input->get('task') == 'save2copy')
		{
			$origTable = clone $this->getTable();
			$origTable->load($input->getInt('id'));

			if ($data['title'] == $origTable->title)
			{
				list($title, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['title']);
				$data['title'] = $title;
				$data['alias'] = $alias;
			}
			else
			{
				if ($data['alias'] == $origTable->alias)
				{
					$data['alias'] = '';
				}
			}

			$data['state'] = 0;
		}

		// Automatic handling of alias for empty fields
		if (in_array($input->get('task'), array('apply', 'save', 'save2new')) && (!isset($data['id']) || (int) $data['id'] == 0))
		{
			if ($data['alias'] == null)
			{
				if (JFactory::getConfig()->get('unicodeslugs') == 1)
				{
					$data['alias'] = JFilterOutput::stringURLUnicodeSlug($data['title']);
				}
				else
				{
					$data['alias'] = JFilterOutput::stringURLSafe($data['title']);
				}

				
                                if ($table == '')                                 {
                                $table = JTable::getInstance('Employee_roster', 'HrTable');
                                }

				if ($table->load(array('alias' => $data['alias'], 'catid' => $data['catid'])))
				{
					$msg = JText::_('COM_HR_SAVE_WARNING');
				}

				list($title, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['title']);
				$data['alias'] = $alias;

				if (isset($msg))
				{
					JFactory::getApplication()->enqueueMessage($msg, 'warning');
				}
			}
		}

		if (parent::save($data))
		{
			if (isset($data['featured']))
			{
				$this->featured($this->getState($this->getName() . '.id'), $data['featured']);
			}

			return true;
		}

		return false;
	}

	/**
	 * Method to toggle the featured setting of roster_managements.
	 *
	 * @param   array    $pks    The ids of the items to toggle.
	 * @param   integer  $value  The value to toggle to.
	 *
	 * @return  boolean  True on success.
	 */
	public function featured($pks, $value = 0)
	{
		// Sanitize the ids.
		$pks = (array) $pks;
		JArrayHelper::toInteger($pks);

		if (empty($pks))
		{
			$this->setError(JText::_('COM_HR_NO_ITEM_SELECTED'));

			return false;
		}

		$table = $this->getTable('Employee_roster', 'HrTable');

		try
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true)
				->update($db->quoteName('#__hr_employee_roster'))
				->set('featured = ' . (int) $value)
				->where('id IN (' . implode(',', $pks) . ')');
			$db->setQuery($query);
			$db->execute();

			if ((int) $value == 0)
			{
				// Adjust the mapping table.
				// Clear the existing features settings.
				$query = $db->getQuery(true)
					->delete($db->quoteName('#__hr_employee_roster_frontpage'))
					->where('ms_id IN (' . implode(',', $pks) . ')');
				$db->setQuery($query);
				$db->execute();
			}
			else
			{
				// First, we find out which of our new featured roster_managements are already featured.
				$query = $db->getQuery(true)
					->select('f.ms_id')
					->from('#__hr_employee_roster_frontpage AS f')
					->where('ms_id IN (' . implode(',', $pks) . ')');
				$db->setQuery($query);

				$old_featured = $db->loadColumn();

				// We diff the arrays to get a list of the roster_managements that are newly featured
				$new_featured = array_diff($pks, $old_featured);

				// Featuring.
				$tuples = array();

				foreach ($new_featured as $pk)
				{
					$tuples[] = $pk . ', 0';
				}

				if (count($tuples))
				{
					$db = $this->getDbo();
					$columns = array('ms_id', 'ordering');
					$query = $db->getQuery(true)
						->insert($db->quoteName('#__hr_employee_roster_frontpage'))
						->columns($db->quoteName($columns))
						->values($tuples);
					$db->setQuery($query);
					$db->execute();
				}
			}
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		$table->reorder();

		$this->cleanCache();

		return true;
	}

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param   object  $table  A record object.
	 *
	 * @return  array  An array of conditions to add to add to ordering queries.
	 *
	 * @since   1.6
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'catid = ' . (int) $table->catid;

		return $condition;
	}

	/**
	 * Allows preprocessing of the JForm object.
	 *
	 * @param   JForm   $form   The form object
	 * @param   array   $data   The data to be merged into the form object
	 * @param   string  $group  The plugin group to be executed
	 *
	 * @return  void
	 *
	 * @since    3.0
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{
		if ($this->canCreateCategory())
		{
			$form->setFieldAttribute('catid', 'allowAdd', 'true');
		}

		// Association content items
		$assoc = JLanguageAssociations::isEnabled();

		if ($assoc)
		{
			$languages = JLanguageHelper::getLanguages('lang_code');
			$addform = new SimpleXMLElement('<form />');
			$fields = $addform->addChild('fields');
			$fields->addAttribute('name', 'associations');
			$fieldset = $fields->addChild('fieldset');
			$fieldset->addAttribute('name', 'item_associations');
			$fieldset->addAttribute('description', 'COM_HR_ITEM_ASSOCIATIONS_FIELDSET_DESC');
			$add = false;

			foreach ($languages as $tag => $language)
			{
				if (empty($data->language) || $tag != $data->language)
				{
					$add = true;
					$field = $fieldset->addChild('field');
					$field->addAttribute('name', $tag);
					$field->addAttribute('type', 'modal_roster_management');
					$field->addAttribute('language', $tag);
					$field->addAttribute('label', $language->title);
					$field->addAttribute('translate_label', 'false');
					$field->addAttribute('edit', 'true');
					$field->addAttribute('clear', 'true');
				}
			}

			if ($add)
			{
				$form->load($addform, false);
			}
		}

		parent::preprocessForm($form, $data, $group);
	}

	/**
	 * Custom clean the cache of com_hr and content modules
	 *
	 * @param   string   $group      The cache group
	 * @param   integer  $client_id  The ID of the client
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function cleanCache($group = null, $client_id = 0)
	{
		parent::cleanCache('com_hr');
	//	parent::cleanCache('mod_roster_managements_archive');
	//	parent::cleanCache('mod_roster_managements_categories');
	//	parent::cleanCache('mod_roster_managements_category');
	//	parent::cleanCache('mod_roster_managements_latest');
	//	parent::cleanCache('mod_roster_managements_news');
	//	parent::cleanCache('mod_roster_managements_popular');
	}

	/**
	 * Void hit function for pagebreak when editing content from frontend
	 *
	 * @return  void
	 *
	 * @since   3.6.0
	 */
	public function hit()
	{
		return;
	}

	/**
	 * Is the user allowed to create an on the fly category?
	 *
	 * @return  boolean
	 *
	 * @since   3.6.1
	 */
	private function canCreateCategory()
	{
		return JFactory::getUser()->authorise('core.create', 'com_hr');
	}
}
