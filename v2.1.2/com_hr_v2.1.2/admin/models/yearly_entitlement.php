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
 * Item Model for an Yearly_entitlement.
 *
 * @since  1.6
 */
class HrModelYearly_entitlement extends JModelAdmin
{
	/**
	 * @var        string    The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_HR';

	/**
	 * The type alias for this content type (for example, 'com_hr.yearly_entitlement').
	 *
	 * @var      string
	 * @since    3.2
	 */
	public $typeAlias = 'com_hr.yearly_entitlement';

	/**
	 * The context used for the associations table
	 *
	 * @var      string
	 * @since    3.4.4
	 */
	protected $associationsContext = 'com_hr.item';

        
        
        
        
        
        
        public function checkDates($data) {

        // set the variables from the passed data

        $date1string = $data['datetime_from'];
        $date1 = new DateTime($date1string);
        
        $date2string = $data['datetime_to'];
        $date2 = new DateTime($date2string);
        
        $date3string = $data['validity_datetime_from'];
        $date3 = new DateTime($date3string);
        
        $date4string = $data['validity_datetime_to'];
        $date4 = new DateTime($date4string);

        if (($date1 > $date2) || ($date3 > $date4)){
            return false;
        } else {
            return true;
        }
    }     

    public function allFiledsHaveAValue($data) {   
              
       
        $date1 = $data['datetime_from'];
        $date2 = $data['datetime_to'];
        $date3 = $data['validity_datetime_from'];
        $date4 = $data['validity_datetime_to'];
        $ref_year = $data['ref_year'];
        $plan_id = $data['plan_id'];
        $catid = $data['catid'];
        $refcatid = $data['refcatid'];        
        $employee_id = $data['employee_id'];
    //    $absence_id = $data['absence_id'];
     //   $entitlement = $data['entitlement'];
      //  ////var_depr_dump($insertmethod);
         ////var_depr_dump($date1);
        ////var_depr_dump( $date2);
        ////var_depr_dump( $date3);
        ////var_depr_dump( $date4);
        ////var_depr_dump( $ref_year);
        ////var_depr_dump( $plan_id);
        ////var_depr_dump( $catid);
        ////var_depr_dump( $employee_id);
      //  ////var_depr_dump( $absence_id);
     //   ////var_depr_dump( $entitlement);
     //   jexit();       
        

   
          if    (($date1 == '') ||  ($date2 == '') || ($date3 == '') || ($date4 == '') ||
              ($ref_year == '')) {
               return false;
           } else {
               return true;
           }
       }    
    
    
public function all_date_fields_have_same_year($data) { 
              
    $date1 = $data['datetime_from'];
        $date2 = $data['datetime_to'];           
            $ref_year = $data['ref_year'];
       
                ////var_depr_dump(substr($ref_year,0,4));
         //       jexit();
   //     substr($date1,0,4);    
            if (((substr($date1,0,4)) == (substr($date2,0,4)))  &&
                 ((substr($date1,0,4)) == $ref_year))
                    {
            
                return true;
            } else
            {
                return false;
            }
                    
                    
                  
              
              
          }  
    
    public function datesCompare($date1_string, $date2_string, $date3_string, $date4_string) {
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
            }
            
            else
             if (($date1 > $date4) && ($date2 > $date4)) {
                $date1 = date_create('1970-01-01');
                $date2 = date_create('1970-01-01');
            }   

            
       
     //to return the value  as one concatenated string  
        $date1_converted_to_string = $date1->format("Y-m-d");
        $date2_converted_to_string = $date2->format("Y-m-d");
 
       //    
             
           
        
            
          return ($date1_converted_to_string.' '.$date2_converted_to_string);
        
        
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
    
       public function deleteAndInsertYearlyEntitlements($data) {

        
        
           
  $insertentitlementdata = array();
  //$insertpatternlinedata = array();
        
        $count_no_of_cycles = 0;
    

 JTable::addIncludePath(JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_hr' . DIRECTORY_SEPARATOR . 'tables');
     
           
           
           
        
        $data_employee_id = $data['employee_id'];
        $data_ref_year = $data['ref_year'];
        $data_catid = $data['catid'];
        $refcatid = $data['refcatid'];         
        $data_plan_id = $data['plan_id'];
   //    $data_absence_id = $data['absence_id'];

        
        $data_dateofyear = $data_ref_year . '-05-05 00:00:00';

        
        $validity_datetime_from = $data['validity_datetime_from'];
      //  $date_from = $data['date_from'];
        
    //    $validity_datetime_from = date_create($validity_datetime_from_string);
        $validity_datetime_to = $data['validity_datetime_to'];
     //   $date_to = $data['date_to'];        
//        $validity_datetime_to = date_create($validity_datetime_to_string);
        
        
        
        $validity_datetime_to = new DateTime($validity_datetime_to);
        $validity_datetime_to->modify('+1 day');
        $validity_datetime_to=date_format($validity_datetime_to, 'Y-m-d H:i:s');        
        
 require_once JPATH_COMPONENT.'/helpers/hr.php';      
        
        //to delete old data
     
$db = JFactory::getDbo();
              

           

     
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










$querymyplanandchildren  = $db->getQuery(true);
$querymyplanandchildren
                ->select($db->quoteName(array('pl.id')))
                ->from($db->quoteName('#__hr_plan', 'pl'));
$querymyplanandchildren->where('(pl.state = 1)');

  if (($no_of_case == 1) || ($no_of_case == 2)) {
$querymyplanandchildren->join('INNER', $db->quoteName('#__hr_plancat', 'pc') . ' ON (' . $db->quoteName('pc.plan_id') . ' = ' . $db->quoteName('pl.id') . ')');
$querymyplanandchildren->where('('. "'$nowstring'" . ' BETWEEN ' . $db->quoteName('pc.datetime_from'). ' AND '. $db->quoteName('pc.datetime_to').')' );
$querymyplanandchildren->where($db->quoteName('pc.refcatid') . ' IN (' . '\''. "$stringofarrayofcatandsubcats". '\''. ')' );
$querymyplanandchildren->where('(pc.state = 1)');
  }
  
    if ($no_of_case == 4) {
//$querymyuserandchildren->join('LEFT', $db->quoteName('#__hr_position', 'p') . ' ON (' . $db->quoteName('pc.position_id') . ' = ' . $db->quoteName('p.position_id') . ')');
//$querymypositionandchildren->join('LEFT', $db->quoteName('#__hr_position', 'p') . ' ON (' . $db->quoteName('pc.position_id') . ' = ' . $db->quoteName('p.id') . ')');
//$querymyuserandchildren->where($db->quoteName('pc.refcatid') . ' != '  .  "'$mycatid'");  
//$querymypositionandchildren->where($db->quoteName('p.title') . ' != ' . "'hosting position for structure'");

  }  
$db->setQuery($querymyplanandchildren);
$arraymyplanandchildrens = array();

$is=0;

$querymyplanandchildrens = $db->loadObjectList();

        foreach ($querymyplanandchildrens as $querymyplanandchildren) {
        //    jexit();
           $arraymyplanandchildrens[$is] = $querymyplanandchildren->id;
            $is=$is+1;
        }
$stringofarraymyplanandchildrens = implode("' , '", $arraymyplanandchildrens);




    




















        
              
if ($refcatid == '') {
         //   $data_catid = $db->quoteName('catid');
$refcatid = $stringofarrayofcatandsubcats;    
    
}   
    

if ($data_plan_id == '') {
            $data_plan_id = $stringofarraymyplanandchildrens;
}


if ($data_employee_id == '') {
            $data_employee_id = $stringofarraymyuserandchildrens;
}

        


// delete .

 







        
            $trashstate = -2;
            $currentstate = 1;
                    
	$updatedatafromemployeeentitlement = $db->getQuery(true);
$conditionsupdatedatafromemployeeentitlement = array(
    $db->quoteName('ref_year') . ' = '. $data_ref_year, 
    $db->quoteName('employee_id') .  ' IN ( '. '\''. "$data_employee_id". '\''. ' )',
        $db->quoteName('refcatid') .  ' IN ( '. '\''. "$refcatid". '\''. ' )',
        $db->quoteName('plan_id') .  ' IN ( '. '\''. "$data_plan_id". '\''. ' )',
                $db->quoteName('state') . ' IN ( ' . '\'' . "$currentstate" . '\'' . ' )'
);
        $updatedatafromemployeeentitlement->update($db->quoteName('#__hr_employee_entitlement'));
        $updatedatafromemployeeentitlement->set('state = ' . $trashstate);

        $updatedatafromemployeeentitlement->where($conditionsupdatedatafromemployeeentitlement);
        $db->setQuery($updatedatafromemployeeentitlement);
        $update_employee_entitlement = $db->execute();












$queryupdateallabsencestopending = $db->getQuery(true);
 // Fields to update.
$fields_to_pending = array(
    $db->quoteName('charge_status') . ' = ' .  $db->quote('Pending') 
);
// Conditions for which records should be updated.
$conditions_to_pending = array(
  
     $db->quoteName('employee_id') .  ' IN ( '. '\''. "$data_employee_id". '\''. ' )'
//    $db->quoteName('employee_id') . ' = ' . $data_employee_id
);
 
$queryupdateallabsencestopending->update($db->quoteName('#__hr_employee_absence'))->set($fields_to_pending)->where($conditions_to_pending);
$db->setQuery($queryupdateallabsencestopending);
$update_to_pending = $db->execute();   














// Create a new query object.
        $queryemployeeplancat = $db->getQuery(true);

/*    $queryemployeeplancat
        ->select($db->quoteName(array('e.id', 'e.date_from', 'e.date_to', 'e.employee_id', 'e.catid', 'u.profile_value')))
        ->select($db->quoteName(array('pc.id', 'pc.datetime_from', 'pc.datetime_to', 'pc.plan_id', 'pc.refcatid')))
        ->from($db->quoteName('#__employee', 'e'))
        ->join('INNER', $db->quoteName('#__categories', 'c') . ' ON (' . $db->quoteName('e.catid') . ' = ' . $db->quoteName('c.id') . ')')
        ->join('INNER', $db->quoteName('#__hr_plancat', 'pc') . ' ON (' . $db->quoteName('pc.refcatid') . ' = ' . $db->quoteName('c.id') . ')')
        ->join('INNER', $db->quoteName('#__user_profiles', 'u') . ' ON (' . $db->quoteName('e.employee_id') . ' = ' . $db->quoteName('u.user_id') . ')');

  */  
    
   $queryemployeeplancat
->select($db->quoteName(array('u.id', 'u.name')))
->select($db->quoteName(array('ep.id', 'ep.datetime_from', 'ep.datetime_to', 'ep.employee_id', 'ep.position_id')))
->select($db->quoteName(array('pc.id', 'pc.datetime_from', 'pc.datetime_to', 'pc.refcatid', 'pc.position_id')))
->select($db->quoteName(array('plc.id', 'plc.datetime_from', 'plc.datetime_to', 'plc.plan_id', 'plc.refcatid')))
//->select($db->quoteName(array('epl.no_of_day', 'epl.ref_date')))

->from($db->quoteName('#__users', 'u'))
           
->join('LEFT', $db->quoteName('#__hr_employee_position', 'ep') . ' ON (' . $db->quoteName('ep.employee_id') . ' = ' . $db->quoteName('u.id') . ')')                
->join('LEFT', $db->quoteName('#__hr_position', 'p') . ' ON (' . $db->quoteName('ep.position_id') . ' = ' . $db->quoteName('p.id') . ')')                

 ->join('LEFT', $db->quoteName('#__hr_positioncat', 'pc') . ' ON (' . $db->quoteName('pc.position_id') . ' = ' . $db->quoteName('p.id') . ')') 
           
->join('LEFT', $db->quoteName('#__categories', 'c') . ' ON (' . $db->quoteName('pc.refcatid') . ' = ' . $db->quoteName('c.id') . ')')
           
->join('LEFT', $db->quoteName('#__hr_plancat', 'plc') . ' ON (' . $db->quoteName('plc.refcatid') . ' = ' . $db->quoteName('c.id') . ')')
           
->join('LEFT', $db->quoteName('#__hr_plan', 'pl') . ' ON (' . $db->quoteName('plc.plan_id') . ' = ' . $db->quoteName('pl.id') . ')')                        
           
//->join('INNER', $db->quoteName('#__hr_plan_entitlement', 'ple') . ' ON (' . $db->quoteName('ple.plan_id') . ' = ' . $db->quoteName('pl.id') . ')')                                   
           
               
//->join('INNER', $db->quoteName('#__hr_absence', 'ab') . ' ON (' . $db->quoteName('ple.absence_id') . ' = ' . $db->quoteName('ab.id') . ')')                                   
  
           
//->join('INNER', $db->quoteName('#__hr_employee_position', 'ep') . ' ON (' . $db->quoteName('ep.employee_id') . ' = ' . $db->quoteName('u.id') . ')')           
  
    
->join('LEFT', $db->quoteName('#__user_profiles', 'up') . ' ON (' . $db->quoteName('up.user_id') . ' = ' . $db->quoteName('u.id') . ')');
           
//   ->join('INNER', $db->quoteName('#__user_profiles', 'u') . ' ON (' . $db->quoteName('e.employee_id') . ' = ' . $db->quoteName('u.user_id') . ')')
//->join('INNER', $db->quoteName('#__hr_employee_patternline', 'epl') . ' ON (' . $db->quoteName('ep.employee_id') . ' = ' . $db->quoteName('epl.employee_id') . ')');

 
    
	$queryemployeeplancat->where('(ep.state = 1)');
	$queryemployeeplancat->where('(pc.state = 1)');  
        $queryemployeeplancat->where('(plc.state = 1)');
        $queryemployeeplancat->where('(pl.state = 1)');       
        $queryemployeeplancat->where('(p.state = 1)');

    
       
         $queryemployeeplancat->select('ep.datetime_from AS employee_position_datetime_from');
         $queryemployeeplancat->select('ep.datetime_to AS employee_position_datetime_to');
         $queryemployeeplancat->select('ep.employee_id AS employee_position_employee_id');
          $queryemployeeplancat->select('ep.position_id AS employee_position_position_id');        
          
          $queryemployeeplancat->select('pc.datetime_from AS position_category_datetime_from');
         $queryemployeeplancat->select('pc.datetime_to AS position_category_datetime_to');
         $queryemployeeplancat->select('pc.refcatid AS position_category_category_id');
          $queryemployeeplancat->select('pc.position_id AS position_category_position_id');           
    
           $queryemployeeplancat->select('plc.datetime_from AS plan_category_datetime_from');
         $queryemployeeplancat->select('plc.datetime_to AS plan_category_datetime_to');
         $queryemployeeplancat->select('plc.plan_id AS plan_category_plan_id');
         $queryemployeeplancat->select('plc.refcatid AS plan_category_category_id');
         

         $queryemployeeplancat->select('up.profile_value AS profile_value');
         
         
         ////               OR '" . $data_dateofyear . "' BETWEEN ep.datetime_from AND (DATE_ADD(ep.datetime_to,INTERVAL 0 DAY))
        $queryemployeeplancat->where("(DATE_FORMAT(ep.datetime_from,'%Y') = '" . $data_ref_year . "' 
     
        OR '" . $data_dateofyear . "' BETWEEN ep.datetime_from AND ep.datetime_to                
                    OR DATE_FORMAT(ep.datetime_to,'%Y') = '" . $data_ref_year . "')");
        
        
        
//     OR '" . $data_dateofyear . "' BETWEEN pc.datetime_from AND DATE_ADD(pc.datetime_to,INTERVAL 0 DAY)        
        $queryemployeeplancat->where("(DATE_FORMAT(pc.datetime_from,'%Y') = '" . $data_ref_year . "' 
         OR '" . $data_dateofyear . "' BETWEEN pc.datetime_from AND pc.datetime_to
         OR DATE_FORMAT(pc.datetime_to,'%Y') = '" . $data_ref_year . "')");
        
     
        
   //    OR '" . $data_dateofyear . "' BETWEEN plc.datetime_from AND DATE_ADD(plc.datetime_to,INTERVAL 0 DAY)
        
        $queryemployeeplancat->where("(DATE_FORMAT(plc.datetime_from,'%Y') = '" . $data_ref_year . "' 
         OR '" . $data_dateofyear . "' BETWEEN plc.datetime_from AND plc.datetime_to
         OR DATE_FORMAT(plc.datetime_to,'%Y') = '" . $data_ref_year . "')");        

        
        $queryemployeeplancat->where('up.profile_key = ' . '"profile.doj"'); 
       
            
  /*       
        
 if ($data_employee_id == 'employee_id') {
            $data_employee_id = $db->quoteName('u.id');
}       
  if (($data_employee_id != $db->quoteName('u.id')) && ($data_employee_id != $db->quoteName('employee_id'))){
    $queryemployeeplancat->where('u.id = ' . $data_employee_id);
  }
  
 
 if ($data_absence_id == 'absence_id') {
            $data_absence_id = $db->quoteName('ab.id');
}       
  if (($data_absence_id != $db->quoteName('ab.id')) && ($data_absence_id != $db->quoteName('absence_id'))){
    $queryemployeeplancat->where('ab.id = ' . $data_absence_id);
  }  
  */
          //  ////var_depr_dump($data_catid);
       //     jexit();

          
   $queryemployeeplancat->where ($db->quoteName('c.id') .  ' IN ( '. '\''. "$refcatid". '\''. ' )');  
   
   
      $queryemployeeplancat->where ($db->quoteName('u.id') .  ' IN ( '. '\''. "$data_employee_id". '\''. ' )'); 
      
      
      $queryemployeeplancat->where ($db->quoteName('pl.id') .  ' IN ( '. '\''. "$data_plan_id". '\''. ' )');       
  
            /*
         if ($data_catid == 'catid') {
            $data_catid = $db->quoteName('c.id');
         }         
        if (($data_catid != $db->quoteName('c.id')) && ($data_catid != $db->quoteName('catid'))){
            $queryemployeeplancat->where('c.id = ' . $data_catid);
        }
      
        
   if ($data_plan_id == 'plan_id') {
            $data_plan_id = $db->quoteName('pl.id');
}    
        
  if (($data_plan_id != $db->quoteName('pl.id')) && ($data_plan_id != $db->quoteName('plan_id'))) {
            $queryemployeeplancat->where('pl.id = ' . $data_plan_id);
        }
        
   */      
  
        $queryplanentitlement = $db->getQuery(true);
        $queryplanentitlement
                ->select($db->quoteName(array('pe.id', 'pe.absence_id', 'pe.plan_id', 'pe.char_from', 'pe.char_to', 'pe.measureunitofpattern', 'pe.measureunit', 'pe.entitlement')))
                ->from($db->quoteName('#__hr_plan_entitlement', 'pe'));
$queryplanentitlement->where ($db->quoteName('pe.plan_id') .  ' IN ( '. '\''. "$data_plan_id". '\''. ' )');       

$queryplanentitlement->where('(pe.state = 1)');
  
   //->where('pe.plan_id = ' . $plan_cat_plan_id);
        
        /*
             $queryplancat->where("(DATE_FORMAT(pc.datetime_from,'%Y') = '" . $data_ref_year . "' 
                 OR '" . $data_dateofyear . "' BETWEEN pc.datetime_from AND pc.datetime_to
                 OR DATE_FORMAT(pc.datetime_to,'%Y') = '" . $data_ref_year . "')");
        
  */      
        
        $db->setQuery($queryemployeeplancat);
        
    

// Load the results as a list of stdClass objects (see later for more options on retrieving data).
        $queryemployeeplancats = $db->loadObjectList();

   //     $db->setQuery($queryplancat);
     //   $resultplancats = $db->loadObjectList();


        $db->setQuery($queryplanentitlement);
        $resultplanentitlements = $db->loadObjectList();




        //      ////var_depr_dump($resultemployees);
        //    ////var_depr_dump($resultplancats);
        //   ////var_depr_dump($resultplanentitlements);



       

        foreach ($queryemployeeplancats as $queryemployeeplancat) {

   
         
                    

            $employee_position_datetime_from = $queryemployeeplancat->employee_position_datetime_from;
            $employee_position_datetime_to = $queryemployeeplancat->employee_position_datetime_to;
            $employee_position_employee_id = $queryemployeeplancat->employee_position_employee_id;
            $employee_position_position_id = $queryemployeeplancat->employee_position_position_id;
            
            
            $position_category_datetime_from = $queryemployeeplancat->position_category_datetime_from;
            $position_category_datetime_to = $queryemployeeplancat->position_category_datetime_to;
            $position_category_position_id = $queryemployeeplancat->position_category_position_id;
            $position_category_category_id = $queryemployeeplancat->position_category_category_id;
            
            
            $date_of_joinment = $queryemployeeplancat->profile_value;
            
            
            $plan_category_datetime_from = $queryemployeeplancat->plan_category_datetime_from;
            $plan_category_datetime_to = $queryemployeeplancat->plan_category_datetime_to;
            $plan_category_plan_id = $queryemployeeplancat->plan_category_plan_id;
            $plan_category_category_id = $queryemployeeplancat->plan_category_category_id;
            
            
   
$requesteddates=  HrModelYearly_entitlement::datetimesCompare($employee_position_datetime_from, $employee_position_datetime_to, $position_category_datetime_from, $position_category_datetime_to);
    
//fields to run in the next datesCompare function
$employee_position_position_category_start_datetime =  substr($requesteddates, 0, -19);
$employee_position_position_category_end_datetime = substr($requesteddates, 20);


  
////var_depr_dump('$employee_position_position_category_start_datetime='.$employee_position_position_category_start_datetime);
////var_depr_dump('$employee_position_position_category_end_datetime='.$employee_position_position_category_end_datetime); 

//jexit();         
            
                    
            //01/12 calendar 2015
            //
            //   A

$requesteddates=HrModelYearly_entitlement::datetimesCompare($employee_position_position_category_start_datetime, $employee_position_position_category_end_datetime, $plan_category_datetime_from, $plan_category_datetime_to);
    
//fields to run in the next datesCompare function
$employee_position_category_plan_category_start_datetime =  substr($requesteddates, 0, -19);
$employee_position_category_plan_category_end_datetime = substr($requesteddates, 20);
$yearstartdatetime = $data_ref_year . '-01-01 00:00:00';
$yearenddatetime = ($data_ref_year+1) . '-01-01 00:00:00';

  
////var_depr_dump('$employee_position_category_plan_category_start_datetime='.$employee_position_category_plan_category_start_datetime);
////var_depr_dump('$employee_position_category_plan_category_end_datetime='.$employee_position_category_plan_category_end_datetime); 
////var_depr_dump($yearstartdatetime);
////var_depr_dump($yearenddatetime);
//jexit();

           
//   A-1
                
$requesteddates=HrModelYearly_entitlement::datetimesCompare($employee_position_category_plan_category_start_datetime, $employee_position_category_plan_category_end_datetime, $yearstartdatetime, $yearenddatetime);
 /////////////////////
$employee_position_category_plan_category_in_year_start_datetime =  substr($requesteddates, 0, -19);
$employee_position_category_plan_category_in_year_end_datetime = substr($requesteddates, 20);
/////////////////////////

 ////var_depr_dump('employee_position_category_plan_category_in_year_start_datetime'.$employee_position_category_plan_category_in_year_start_datetime);
////var_depr_dump('employee_position_category_plan_category_in_year_end_datetime'.$employee_position_category_plan_category_in_year_end_datetime);        


//jexit();
foreach ($resultplanentitlements as $resultplanentitlement) {


                    $plan_entitlement_id = $resultplanentitlement->id;
                    $plan_entitlement_absence_id = $resultplanentitlement->absence_id;
                    $plan_entitlement_plan_id = $resultplanentitlement->plan_id;
                    $plan_entitlement_char_from = $resultplanentitlement->char_from;
                    $plan_entitlement_char_to = $resultplanentitlement->char_to;
                    $plan_entitlement_measureunitofpattern = $resultplanentitlement->measureunitofpattern;
                    $plan_entitlement_measureunit = $resultplanentitlement->measureunit;                    
                    $plan_entitlement_entitlement = $resultplanentitlement->entitlement;

                    if ($plan_category_plan_id === $plan_entitlement_plan_id) 
                        
                              {
                                
                    $plan_entitlement_char_from = $resultplanentitlement->char_from;
                    $plan_entitlement_char_to = $resultplanentitlement->char_to;
                    $plan_entitlement_measureunitofpattern = $resultplanentitlement->measureunitofpattern;
                    $plan_entitlement_entitlement = $resultplanentitlement->entitlement;                        
                    $date_of_joinment_year = substr($date_of_joinment, 1, -16);
                    $date_of_joinment_year_day_and_month = substr($date_of_joinment, 5, -10);
         

   if ($plan_entitlement_measureunitofpattern  == 'Years Of Service'){
 
       //calculate startdate and enddates based on the records of the plan entitplements 0000-0004 years etc
       $startdatetime   =  ((int) $date_of_joinment_year + (int) $plan_entitlement_char_from). $date_of_joinment_year_day_and_month. ' '. '00:00:00';
       $enddatetime   =  ((int) $date_of_joinment_year + (int) $plan_entitlement_char_to). $date_of_joinment_year_day_and_month. ' '. '00:00:00';
     
////var_depr_dump('startdatetime'.$startdatetime);

////var_depr_dump('enddatetime'.$enddatetime);
       
    //    ////var_depr_dump('startdateyear'.$startdate);
//////var_depr_dump('enddateyeartest'.$enddate);
//jexit();
       //increase the end date by one day

       $enddate_datetime = new DateTime($enddatetime);
        $enddate_datetime->modify('+1 day');
        $enddatetime=date_format($enddate_datetime, 'Y-m-d H:i:s');
        
////var_depr_dump('enddatetime increased by one'.$enddatetime);    

   //      $date1_converted_to_string = $date1->format("Y-m-d");       
        
  //      ////var_depr_dump('startdateyear'.$startdate);
//////var_depr_dump('enddateyeartest'.$enddate);
//////var_depr_dump('date_of_joinment_year_day_and_month'.$date_of_joinment_year_day_and_month);
//jexit();
       
     } else
         
         
      //calculate startdate and enddates based on the records of the plan entitlements 12-25  12-25
     if ($plan_entitlement_measureunitofpattern  == 'Dates Specific'){
         
       if   ($plan_entitlement_measureunit  == 'Days'){
       $startdatetime   =  $data_ref_year.'-'.$plan_entitlement_char_from. ' '. '00:00:00';
       $enddatetime   =   $data_ref_year.'-'.$plan_entitlement_char_to. ' '. '00:00:00';
        $enddate_datetime = new DateTime($enddatetime);
        $enddate_datetime->modify('+1 day');
        $enddatetime=date_format($enddate_datetime, 'Y-m-d H:i:s');       
   //            ////var_depr_dump('ds'.$startdate);
//////var_depr_dump('ds'.$enddate);
//jexit();
       } else 
           if ($plan_entitlement_measureunit  == 'Hours_minutes'){
       $startdatetime   =  $data_ref_year.'-'.$plan_entitlement_char_from.':00';
       $enddatetime   =   $data_ref_year.'-'.$plan_entitlement_char_to.':00';
               $enddate_datetime = new DateTime($enddatetime);
        $enddatetime=date_format($enddate_datetime, 'Y-m-d H:i:s');
     //          ////var_depr_dump('hm'.$startdate);
//////var_depr_dump('hm'.$enddate);
//jexit();
       } else 
           if ($plan_entitlement_measureunit  == ''){
      $startdatetime   =  $data_ref_year.'-'.$plan_entitlement_char_from. ' '. '00:00:00';
       $enddatetime   =   $data_ref_year.'-'.$plan_entitlement_char_to. ' '. '00:00:00';
        $enddate_datetime = new DateTime($enddatetime);
        $enddate_datetime->modify('+1 day');
        $enddatetime=date_format($enddate_datetime, 'Y-m-d H:i:s');       
      //         ////var_depr_dump('empty '.$startdate);
//////var_depr_dump('empty '.$enddate);
//jexit();
       }
               
    //   jexit();            
               
         ////var_depr_dump('startdatetimehhhhhh'.$startdatetime);
        ////var_depr_dump('enddatetimehhhhhh'.$enddatetime);
//jexit();

       //increase the end date by one day
       

      $keepstartdatetime = new DateTime($startdatetime);
            $keependdatetime = new DateTime($enddatetime);
       
        
        
        
        
     }     
       
//$employee_position_category_plan_category_in_year_start_date = $employee_position_category_plan_category_in_year_start_date . ' '. '00:00:00';
//$employee_position_category_plan_category_in_year_end_date= $employee_position_category_plan_category_in_year_end_date . ' '. '00:00:00';   
     
 ////var_depr_dump('$employee_position_category_plan_category_in_year_start_datetime'.$employee_position_category_plan_category_in_year_start_datetime);
  ////var_depr_dump('employee_position_category_plan_category_in_year_end_datetime'.$employee_position_category_plan_category_in_year_end_datetime);
//$requesteddates=HrModelYearly_entitlement::datesCompare($startdate, $enddate, $employee_position_category_plan_category_in_year_start_date, $employee_position_category_plan_category_in_year_end_date);
 
     
$requesteddates=HrModelYearly_entitlement::datetimesCompare($startdatetime, $enddatetime, $employee_position_category_plan_category_in_year_start_datetime, $employee_position_category_plan_category_in_year_end_datetime);     
     
     
$startdatetime =  substr($requesteddates, 0, -19);
$enddatetime = substr($requesteddates, 20);


//////var_depr_dump('req'.$requesteddates);

 ////var_depr_dump('startdate'.$startdatetime);
////var_depr_dump('enddate'.$enddatetime);
//jexit(); 
  
//////var_depr_dump('new start date'.$startdate);
//////var_depr_dump('new end date'.$enddate); 
//////var_depr_dump($final_entitlement); 

 
 $startdatetime = new DateTime($startdatetime);
 //$startdatetime=date_format($startdatetime, 'Y-m-d H:i');
        
 $enddatetime = new DateTime($enddatetime);
  // $enddatetime=date_format( $enddatetime, 'Y-m-d H:i'); 


//jexit();
 
 
if ($plan_entitlement_measureunitofpattern  == 'Years Of Service'){
    
//$startdatetime=date_create($startdate);
//$enddatetime=date_create($enddate);



//////var_depr_dump('ASAsAS'.$startdatetime);
//////var_depr_dump('asASas'.$enddatetime);
//jexit();

//to find the number of days in the year - in case its disektos

$first_day_of_year = $data_ref_year.'-'.'01-01 00:00:00';
$first_day_of_next_year= ((int) $data_ref_year +1).'-'.'01-01 00:00:00';
$first_day_of_year_datetime = new DateTime($first_day_of_year);
$first_day_of_next_year_datetime = new DateTime($first_day_of_next_year); 
$year_no_of_days = (int) date_diff($first_day_of_year_datetime, $first_day_of_next_year_datetime, TRUE)->format("%R%a");

 
//////var_depr_dump($final_entitlement); 
                  //      jexit();
                        
$final_entitlement = $plan_entitlement_entitlement * (int) date_diff($startdatetime, $enddatetime, TRUE)->format("%R%a") / $year_no_of_days;

         //       jexit();
  }

 if ($plan_entitlement_measureunitofpattern  == 'Dates Specific'){
//$startdatetime=date_create($startdate);
//$enddatetime=date_create($enddate);
//////var_depr_dump($startdate);
//////var_depr_dump($enddate);
     
     
////var_depr_dump(date_diff($startdatetime, $enddatetime, TRUE));     

       if   ($plan_entitlement_measureunit  == 'Days'){
           
           $plan_no_of_days = (int) date_diff($keepstartdatetime, $keependdatetime, TRUE)->format("%R%a");
           $actual_no_of_days = (int) date_diff($startdatetime, $enddatetime, TRUE)->format("%R%a");
           ////var_depr_dump('plann no of days'.$plan_no_of_days) ;
           
           ////var_depr_dump('actual no of dAYS'. $actual_no_of_days) ;
           
//$final_entitlement =  $plan_entitlement_entitlement * $actual_no_of_days / $plan_no_of_days ;

$final_entitlement =  (int) date_diff($startdatetime, $enddatetime, TRUE)->format("%R%a") / $plan_entitlement_entitlement;

       }
       
           if ($plan_entitlement_measureunit  == 'Hours_minutes'){

$plan_days = (int) date_diff($keepstartdatetime, $keependdatetime, TRUE)->format("%R%a");            
$plan_hours   = (int) date_diff($keepstartdatetime, $keependdatetime, TRUE)->format('%h'); 
$plan_minutes = (int) date_diff($keepstartdatetime, $keependdatetime, TRUE)->format('%i');

$actual_days = (int) date_diff($startdatetime, $enddatetime, TRUE)->format("%R%a");            
$actual_hours   = (int) date_diff($startdatetime, $enddatetime, TRUE)->format('%h'); 
$actual_minutes = (int) date_diff($startdatetime, $enddatetime, TRUE)->format('%i');


//////var_depr_dump('days'.$days);
//////var_depr_dump('hours'.$hours);
//////var_depr_dump('minutes'.$minutes);


$plan_no_of_minutes = ($plan_days * 60 *24) + ($plan_hours * 60) + $plan_minutes ;
$actual_no_of_minutes = ($actual_days * 60 *24) + ($actual_hours * 60) + $actual_minutes ;
               
$final_entitlement =  $plan_entitlement_entitlement * $actual_no_of_minutes / $plan_no_of_minutes;
               
               
           }       
       
     
 //$final_entitlement = $plan_entitlement_entitlement;    
 }  
  
 
  
         // Create a new query object.
 /*
                        $queryinsert = $db->getQuery(true);

                        // Insert columns.
                        $columns = array('employee_id', 'plan_id', 'absence_id', 
                            'catid', 'refcatid', 'measureunit', 'ref_year', 'entitlement', 'datetime_from', 'datetime_to', 'validity_datetime_from', 'validity_datetime_to');
*/
            
                     //    jexit();
                        // Insert values.
                        
  //03/12 2015 calendar
      //   $startdatetime=date_create($startdatetime);
//$enddatetime=date_create($enddatetime);  
                        
  ////var_depr_dump($startdatetime);
////var_depr_dump($enddatetime);
////var_depr_dump('----------------');

                        
  $startdatetimestring=date_format($startdatetime, 'Y-m-d H:i:s');                        
    $enddatetimestring=date_format($enddatetime, 'Y-m-d H:i:s'); 
    
if ($plan_entitlement_measureunit    == 'Days') {
    ////var_depr_dump('e'.$enddatetimestring);
    
//  $enddatetimestring = strtotime(date("Y-m-d H:i:s", strtotime($enddatetimestring)) . " +" . 1 . " day");
 //     ////var_depr_dump('e+'.$enddatetimestring);
  //    jexit();
}

    
   $startdatetimestring = hrHelper::getGmtTime($startdatetimestring);
    $enddatetimestring = hrHelper::getGmtTime($enddatetimestring);
    
       $validity_datetime_from_string = hrHelper::getGmtTime($validity_datetime_from);
    $validity_datetime_to_string = hrHelper::getGmtTime($validity_datetime_to);
                      
   

    ////var_depr_dump($employee_position_employee_id);
////var_depr_dump($startdatetimestring);
////var_depr_dump($enddatetimestring);
//jexit();
                    
    /*
                        $values = array($employee_position_employee_id, $plan_entitlement_plan_id, 
                            $plan_entitlement_absence_id,
                            $data_catid,  $plan_category_category_id, "'$plan_entitlement_measureunit'",
                            $data_ref_year, $final_entitlement,
                            "'$startdatetimestring'", "'$enddatetimestring'",
                            "'$validity_datetime_from_string'", "'$validity_datetime_to_string'");
*/
         
    
               $count_no_of_cycles++;
            
               $table = JTable::getInstance('Employee_entitlement', 'HrTable');




                        $insertentitlementdata['id'] = '';
                        $insertentitlementdata['employee_id'] = $employee_position_employee_id;
                       $insertentitlementdata['plan_id'] = $plan_entitlement_plan_id;
                      $insertentitlementdata['absence_id'] = $plan_entitlement_absence_id;
                       $insertentitlementdata['catid'] = $data_catid;
                        $insertentitlementdata['refcatid'] = $plan_category_category_id;
                       $insertentitlementdata['measureunit'] = $plan_entitlement_measureunit;
                        $insertentitlementdata['ref_year'] = $data_ref_year;
                        $insertentitlementdata['entitlement'] =  $final_entitlement;
                        $insertentitlementdata['datetime_from'] = $startdatetimestring;
                        $insertentitlementdata['datetime_to'] = $enddatetimestring;
                        $insertentitlementdata['validity_datetime_from'] = $validity_datetime_from_string;
                        $insertentitlementdata['validity_datetime_to'] = $validity_datetime_to_string;

                        $insertentitlementdata['state'] = 1;
                        $insertentitlementdata['access'] = 1;
                        $insertentitlementdata['params'] = '{"target":"","image":""}';
                        $insertentitlementdata['metadata'] = '{"page_title":"","author":"","robots":"","tags":""}';
                        $insertentitlementdata['language'] = '*';
                        $insertentitlementdata['featured'] = 0;

                        $date = JFactory::getDate();
                        $nowstring = $date->format('Y-m-d h:i:s:u A');
                        $insertentitlementdata['publish_up'] = $nowstring;

                        $insertentitlementdata['title'] = $nowstring . '-' . $count_no_of_cycles;
                        $insertentitlementdata['alias'] = $nowstring . '-' . $count_no_of_cycles;


             
    
                   
        
              //          ////var_depr_dump($startdatetime->date) ;
              //            ////var_depr_dump($enddatetime->date) ;
                        

                        // Prepare the insert query.
  //      $allowedfromdate = $startdatetime->date;                         
 //       $allowedtodate = $enddatetime->date;                         
  
                        
if ((substr($startdatetimestring, 0, 10) != '1970-01-01') && (substr($startdatetimestring, 0, 10) != '1969-12-31')
           && (substr($startdatetimestring, 0, 10) != '1970-01-02')   && (substr($enddatetimestring, 0, 10) != '1969-12-31')
        && (substr($enddatetimestring, 0, 10) != '1970-01-01')   && (substr($enddatetimestring, 0, 10) != '1970-01-02'))
      
    
   // if ((true))
    {
     //  ////var_depr_dump( $final_entitlement); 
       //       jexit();    
    
    /*
                        $queryinsert
                                ->insert($db->quoteName('#__hr_employee_entitlement'))
                                ->columns($db->quoteName($columns))
                                ->values(implode(',', $values));

                        // Set the query using our newly populated query object and execute it.
                        $db->setQuery($queryinsert);
                        $db->execute();
                        
                        
   */
                    
                        
                        if (!$table->save($insertentitlementdata)) {
            $table->setError($this->getError());
            return false;
        }                              
                        
                        
                        
                      
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
}
                    }
                }
            
        }
//  jexit();
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

			// Check if the yearly_entitlement was featured and update the #__hr_employee_entitlement_frontpage table
			if ($featured == 1)
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true)
					->insert($db->quoteName('#__hr_employee_entitlement_frontpage'))
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

			return $user->authorise('core.delete', 'com_hr.yearly_entitlement.' . (int) $record->id);
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

		// Check for existing yearly_entitlement.
		if (!empty($record->id))
		{
			return $user->authorise('core.edit.state', 'com_hr.yearly_entitlement.' . (int) $record->id);
		}
		// New yearly_entitlement, so check against the category.
		elseif (!empty($record->catid))
		{
			return $user->authorise('core.edit.state', 'com_hr.category.' . (int) $record->catid);
		}
		// Default to component settings if neither yearly_entitlement nor category known.
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

		// Reorder the yearly_entitlements within the category so the new yearly_entitlement is first
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
	public function getTable($type = 'Yearly_entitlement', $prefix = 'HrTable', $config = array())
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
                        
                 
			$item->yearly_entitlementtext = trim($item->fulltext) != '' ? $item->introtext . "<hr id=\"system-readmore\" />" . $item->fulltext : $item->introtext;

                      
			if (!empty($item->id))
			{
				$item->tags = new JHelperTags;
				$item->tags->getTagIds($item->id, 'com_hr.yearly_entitlement');
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
				$associations = JLanguageAssociations::getAssociations('com_hr', '#__hr_employee_entitlement', 'com_hr.item', $item->id);

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
		$form = $this->loadForm('com_hr.yearly_entitlement', 'yearly_entitlement', array('control' => 'jform', 'load_data' => $loadData));

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
		if ($this->getState('yearly_entitlement.id'))
		{
			$id = $this->getState('yearly_entitlement.id');

			// Existing record. Can only edit in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit');

			// Existing record. Can only edit own yearly_entitlements in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit.own');
		}
		else
		{
			// New record. Can only create in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

		$user = JFactory::getUser();

		// Check for existing yearly_entitlement.
		// Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('core.edit.state', 'com_hr.yearly_entitlement.' . (int) $id))
			|| ($id == 0 && !$user->authorise('core.edit.state', 'com_hr')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('featured', 'disabled', 'true');
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('publish_up', 'disabled', 'true');
			$form->setFieldAttribute('publish_down', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is an yearly_entitlement you can edit.
			$form->setFieldAttribute('featured', 'filter', 'unset');
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('publish_up', 'filter', 'unset');
			$form->setFieldAttribute('publish_down', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');
		}

		// Prevent messing with yearly_entitlement language and category when editing existing yearly_entitlement with associations
		$app = JFactory::getApplication();
		$assoc = JLanguageAssociations::isEnabled();

		// Check if yearly_entitlement is associated
		if ($this->getState('yearly_entitlement.id') && $app->isSite() && $assoc)
		{
			$associations = JLanguageAssociations::getAssociations('com_hr', '#__hr_employee_entitlement', 'com_hr.item', $id);

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
		$data = $app->getUserState('com_hr.edit.yearly_entitlement.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

			// Pre-select some filters (Status, Category, Language, Access) in edit form if those have been selected in Yearly_entitlement Manager: Yearly_entitlements
			if ($this->getState('yearly_entitlement.id') == 0)
			{
				$filters = (array) $app->getUserState('com_hr.yearly_entitlements.filter');
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
            $query->where($db->quoteName('title') . ' = ' . "'Entitlements of Employees'");
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
                
                
		$this->preprocessData('com_hr.yearly_entitlement', $data);

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

				$table = JTable::getInstance('Yearly_entitlement', 'HrTable');

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
	 * Method to toggle the featured setting of yearly_entitlements.
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

		$table = $this->getTable('Employee_entitlement', 'HrTable');

		try
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true)
				->update($db->quoteName('#__hr_employee_entitlement'))
				->set('featured = ' . (int) $value)
				->where('id IN (' . implode(',', $pks) . ')');
			$db->setQuery($query);
			$db->execute();

			if ((int) $value == 0)
			{
				// Adjust the mapping table.
				// Clear the existing features settings.
				$query = $db->getQuery(true)
					->delete($db->quoteName('#__hr_employee_entitlement_frontpage'))
					->where('ms_id IN (' . implode(',', $pks) . ')');
				$db->setQuery($query);
				$db->execute();
			}
			else
			{
				// First, we find out which of our new featured yearly_entitlements are already featured.
				$query = $db->getQuery(true)
					->select('f.ms_id')
					->from('#__hr_employee_entitlement_frontpage AS f')
					->where('ms_id IN (' . implode(',', $pks) . ')');
				$db->setQuery($query);

				$old_featured = $db->loadColumn();

				// We diff the arrays to get a list of the yearly_entitlements that are newly featured
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
						->insert($db->quoteName('#__hr_employee_entitlement_frontpage'))
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
					$field->addAttribute('type', 'modal_yearly_entitlement');
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
	//	parent::cleanCache('mod_yearly_entitlements_archive');
	//	parent::cleanCache('mod_yearly_entitlements_categories');
	//	parent::cleanCache('mod_yearly_entitlements_category');
	//	parent::cleanCache('mod_yearly_entitlements_latest');
	//	parent::cleanCache('mod_yearly_entitlements_news');
	//	parent::cleanCache('mod_yearly_entitlements_popular');
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
