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

class HrModelEmp_entl_bals extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id', 'hierarchy',
                'title', 'a.title',
                'state', 'a.state',
                'company',
                'uname', 'username', 'usrname', 'usr.name',
                'abstitle', 'abs.abstype',
                'ref_year', 'a.ref_year',
                'entitlement',
                'charge',
                'balance',
                'publish_up', 'a.publish_up',
                'publish_down', 'a.publish_down',
                'filter_listitem',
                'ordering', 'a.ordering',
                'catid', 'a.catid', 'category_title'
            );
        }

        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null) {

        $search_employee = $this->getUserStateFromRequest($this->context . '.filter.search_employee', 'filter_search_employee');
        $this->setState('filter.search_employee', $search_employee);

        $search_absence = $this->getUserStateFromRequest($this->context . '.filter.search_absence', 'filter_search_absence');
        $this->setState('filter.search_absence', $search_absence);

        $search_ref_year = $this->getUserStateFromRequest($this->context . '.filter.search_ref_year', 'filter_search_ref_year');
        $this->setState('filter.search_ref_year', $search_ref_year);

        /*
          $search_entitlement = $this->getUserStateFromRequest($this->context . '.filter.search_entitlement', 'filter_search_entitlement');
          $this->setState('filter.search_entitlement', $search_entitlement);


          $search_charge = $this->getUserStateFromRequest($this->context . '.filter.search_charge', 'filter_search_charge');
          $this->setState('filter.search_charge', $search_charge);

          $search_balance = $this->getUserStateFromRequest($this->context . '.filter.search_balance', 'filter_search_balance');
          $this->setState('filter.search_balance', $search_balance);

          $search_measureunit = $this->getUserStateFromRequest($this->context . '.filter.search_measureunit', 'filter_search_measureunit');
          $this->setState('filter.search_measureunit', $search_measureunit);

         */
        $itemselected = $this->getUserStateFromRequest($this->context . '.filter.hierarchy', 'filter_hierarchy');
        $this->setState('filter.hierarchy', $itemselected);

        $published = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $this->setState('filter.state', $published);

        parent::populateState('a.ordering', 'asc');
    }

    protected function getListQuery() {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $user = JFactory::getUser();
        $userid = $user->id;


        $now = new DateTime();
        $nowstring = $now->format('Y-m-d');




        $mynoofmethodandmycategory = HrHelper::GetMyCategory();
        $no_of_case = substr($mynoofmethodandmycategory, 0, 1);
        $mycatid = substr($mynoofmethodandmycategory, 1);


        $catarray = array();

        $catarray[0] = $mycatid;


        $arrayofcatandsubcats = HrHelper::GetCategoriesWithChildren($catarray);


        $stringofarrayofcatandsubcats = implode("' , '", $arrayofcatandsubcats);


        $query->select(
                $this->getState(
                        'list.select', 'a.id, a.title, a.catid,' .
                        'a.state' .
                        ', a.employee_id' .
                        ', a.absence_id' .
                        ', abs.abstype' .
                        ', abs.measureunit' .
                        ', a.ref_year, ' .
                        '(SELECT usrs.name as usrname
                                 FROM  #__users As usrs
                                 where usrs.id = a.employee_id), ' .
                        '(Case WHEN (SELECT SUM(FOO.diff) as sumdiff FROM                                 
(SELECT CONCAT(acs.employee_id, acs.absence_id, acs.ref_year)  as oneid,
                          acs.entitlement as diff
                       FROM  #__hr_employee_entitlement As acs) AS FOO
                       
where FOO.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)                       
                       GROUP BY FOO.oneid               
                             ) IS NULL then 0 
        else (SELECT SUM(FOO.diff) as sumdiff FROM                                 
(SELECT CONCAT(acs.employee_id, acs.absence_id, acs.ref_year)  as oneid,
                          acs.entitlement as diff
                       FROM  #__hr_employee_entitlement As acs) AS FOO
                       
where FOO.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)                       
                       GROUP BY FOO.oneid               
                             ) end)  , ' .
                        '(Case WHEN (SELECT SUM(FOO2.diff) as sumdiff FROM                                 
(SELECT CONCAT(ac.employee_id, ac.absence_id, ac.ref_year) as oneid,
                          ac.charge as diff
                       FROM  #__hr_absence_charge As ac) AS FOO2
                       
where FOO2.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)                       
                       GROUP BY FOO2.oneid               
                             ) IS NULL then 0 
        else (SELECT SUM(FOO2.diff) as sumdiff FROM                                 
(SELECT CONCAT(ac.employee_id, ac.absence_id, ac.ref_year) as oneid,
                          ac.charge as diff
                       FROM  #__hr_absence_charge As ac) AS FOO2
                       
where FOO2.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)                       
                       GROUP BY FOO2.oneid               
                             ) end)       , ' .
                        '((Case WHEN (SELECT SUM(FOO.diff) as sumdiff FROM                                 
(SELECT CONCAT(acs.employee_id, acs.absence_id, acs.ref_year)  as oneid,
                          acs.entitlement as diff
                       FROM  #__hr_employee_entitlement As acs) AS FOO
                       
where FOO.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)                       
                       GROUP BY FOO.oneid               
                             ) IS NULL then 0 
        else (SELECT SUM(FOO.diff) as sumdiff FROM                                 
(SELECT CONCAT(acs.employee_id, acs.absence_id, acs.ref_year)  as oneid,
                          acs.entitlement as diff
                       FROM  #__hr_employee_entitlement As acs) AS FOO
                       
where FOO.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)                       
                       GROUP BY FOO.oneid               
                             ) end)- (Case WHEN (SELECT SUM(FOO2.diff) as sumdiff FROM                                 
(SELECT CONCAT(ac.employee_id, ac.absence_id, ac.ref_year) as oneid,
                          ac.charge as diff
                       FROM  #__hr_absence_charge As ac) AS FOO2
                       
where FOO2.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)                       
                       GROUP BY FOO2.oneid               
                             ) IS NULL then 0 
        else (SELECT SUM(FOO2.diff) as sumdiff FROM                                 
(SELECT CONCAT(ac.employee_id, ac.absence_id, ac.ref_year) as oneid,
                          ac.charge as diff
                       FROM  #__hr_absence_charge As ac) AS FOO2
                       
where FOO2.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)                       
                       GROUP BY FOO2.oneid               
                             ) end))'
                )
        );
        $query->from('#__hr_employee_entitlement AS a');

        $published = $this->getState('filter.state');


        // Join over the language
        //	$query->select('l.title AS language_title, l.image AS language_image')
        //	->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');
        // Join over the users for the checked out user.
        //	$query->select('uc.name AS editor')
        //		->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
        // Join over the asset groups.
//		$query->select('ag.title AS access_level')
        //->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
        // Join over the categories.
        $query->select('c.title AS category_title')
                ->join('LEFT', '#__categories AS c ON c.id = a.catid');

        // Join over the users for the author.
        //	$query->select('ua.name AS author_name')
        //		->join('LEFT', '#__users AS ua ON ua.id = a.created_by');

        $query->join('LEFT', $db->quoteName('#__hr_absence', 'abs') . ' ON (' . $db->quoteName('a.absence_id') . ' = ' . $db->quoteName('abs.id') . ')');


        $query->join('LEFT', $db->quoteName('#__hr_absence_charge', 'ac') .
                ' ON (' . '(' . $db->quoteName('ac.absence_id') . ' = ' . $db->quoteName('a.absence_id') . ')' . ' AND ' .
                ' (' . $db->quoteName('ac.employee_id') . ' = ' . $db->quoteName('a.employee_id') . ')' . ' AND ' .
                ' (' . $db->quoteName('ac.ref_year') . ' = ' . $db->quoteName('a.ref_year') . ')' .
                ')');



        $query->where('a.employee_id != ' . '0');


        $query->select('a.title AS emp_entl_bal_title');



        $query->select('(SELECT usrs.name as usrname
                       FROM  #__users As usrs
             where usrs.id = a.employee_id)  AS username');





        /*
          // Join over the associations.
          if (JLanguageAssociations::isEnabled())
          {
          $query->select('COUNT(asso2.id)>1 as association')
          ->join('LEFT', '#__associations AS asso ON asso.id = a.id AND asso.context=' . $db->quote('com_hr.item'))
          ->join('LEFT', '#__associations AS asso2 ON asso2.key = asso.key')
          ->group('a.id, l.title, l.image, uc.name, ag.title, c.title, ua.name');
          }
         */













        if (is_numeric($published)) {
            $query->where('a.state = ' . (int) $published);
        } elseif ($published === '') {
            $query->where('(a.state IN (0, 1))');
        }
        /*
          $myemployee = $this->getState('filter.myemployee');

          if (is_numeric($myemployee)) {
          $query->where('u.id = ' . (int) $myemployee);
          } elseif ($myemployee === '') {
          //$query->where('(c.title IN (0, 1))');
          }
         */


        $itemselected = $this->getState('filter.hierarchy');


        if (($itemselected == '') || ($itemselected == NULL)) {
            $itemselected = 'employee';
        }


        if (($itemselected) == 'employee') {
            //$query->where('a.employee_id = '.(int) $checkbox);

            $query->where('a.employee_id' . ' = ' . $user->id);
        } elseif ($itemselected == 'supervisor') {

            if (($no_of_case == 1) || ($no_of_case == 2)) {
                $query->where($db->quoteName('a.employee_id') . ' IN ' .
                        '(SELECT DISTINCT ep.employee_id as empid
                       FROM  #__hr_employee_position As ep INNER JOIN
                       #__hr_positioncat As pc ON pc.position_id = ep.position_id 
                         where pc.refcatid IN (' . '\'' . "$stringofarrayofcatandsubcats" . '\'' . ') and ' .
                        "'$nowstring'" . ' between pc.datetime_from and pc.datetime_to and ' .
                        "'$nowstring'" . ' between ep.datetime_from and ep.datetime_to)'
                );
            }
        } elseif ($itemselected == 'approver') {

            $query->where($db->quoteName('a.employee_id') . ' IN ' .
                    '(SELECT DISTINCT ep.employee_id as empid
                       FROM  #__hr_employee_approver As ep 
                         where ' .
                    "'$userid'" . ' = approver1_id OR ' .
                    "'$userid'" . ' = approver2_id OR ' .
                    "'$userid'" . ' = approver3_id OR ' .
                    "'$userid'" . ' = approver4_id OR ' .
                    "'$userid'" . ' = approver5_id)'
            );
        } elseif ($itemselected == 'supervisorandapprover') {
//THIS IS NOT WORKING AT THE MOMENT
            if (($no_of_case == 1) || ($no_of_case == 2)) {
                $query->where($db->quoteName('a.employee_id') . ' IN ' .
                        '(SELECT DISTINCT ep.employee_id as empid
                       FROM  #__hr_employee_position As ep INNER JOIN
                       #__hr_positioncat As pc ON pc.position_id = ep.position_id 
                         where pc.refcatid IN (' . '\'' . "$stringofarrayofcatandsubcats" . '\'' . ') and ' .
                        "'$nowstring'" . ' between pc.datetime_from and pc.datetime_to and ' .
                        "'$nowstring'" . ' between ep.datetime_from and ep.datetime_to) OR ' .
                        $db->quoteName('a.employee_id') . ' IN ' .
                        '(SELECT DISTINCT ep.employee_id as empid
                       FROM  #__hr_employee_approver As ep 
                         where ' .
                        "'$userid'" . ' = approver1_id OR ' .
                        "'$userid'" . ' = approver2_id OR ' .
                        "'$userid'" . ' = approver3_id OR ' .
                        "'$userid'" . ' = approver4_id OR ' .
                        "'$userid'" . ' = approver5_id)'
                );
            } else {

                $query->where($db->quoteName('a.employee_id') . ' IN ' .
                        '(SELECT DISTINCT ep.employee_id as empid
                       FROM  #__hr_employee_approver As ep 
                         where ' .
                        "'$userid'" . ' = approver1_id OR ' .
                        "'$userid'" . ' = approver2_id OR ' .
                        "'$userid'" . ' = approver3_id OR ' .
                        "'$userid'" . ' = approver4_id OR ' .
                        "'$userid'" . ' = approver5_id)');
            }

            //$query->where('(a.employee_id IN (0, 1))');
            //     $query->where('a.employee_id = 270');
        }







        // Join over the categories.
        //	$query->select('c.title AS category_title');

        $query->select('(SELECT DISTINCT pc.refcatid as pccatid
                       FROM  #__hr_positioncat As pc INNER JOIN
                       #__hr_employee_position As ep ON pc.position_id = ep.position_id 
                         where ep.employee_id = ' . "'$userid'" . ' and ' .
                "'$nowstring'" . ' between pc.datetime_from and pc.datetime_to and ' .
                "'$nowstring'" . ' between ep.datetime_from and ep.datetime_to) as refcatid');


        //	$query->join('LEFT', '#__categories AS c ON c.id = a.catid');
        //$query->where('a.employee_id' . ' = ' . $user->id); 
//$query->select('TIMESTAMPDIFF(HOUR,a.log_in,a.log_out) as hourduration');  
//$query->select('TIMESTAMPDIFF(MINUTE,a.log_in,a.log_out) - TIMESTAMPDIFF(HOUR,a.log_in,a.log_out)*60  as minduration');       
//$query->select('TIMESTAMPDIFF(MINUTE,a.log_in,a.log_out) as totduration');   
//$query->order($db->escape('a.log_in'));
        // Filter by search in title

        $query->group($db->quoteName('a.employee_id'));
        $query->group($db->quoteName('a.absence_id'));
        $query->group($db->quoteName('a.ref_year'));




        $query->select('a.ref_year AS ref_year');
        $query->select('abs.measureunit AS measureunit');
        $query->select('abs.abstype AS abstype');








        $search_employee = $this->getState('filter.search_employee');
        if (!empty($search_employee)) {
            if (stripos($search_employee, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search_employee, 3));
            } else {
                //$search2 = $db->Quote('%'.$db->escape($search2, true).'%');

                $search_employee = $db->Quote('%' . $db->escape($search_employee, true) . '%');
                $query->where('((SELECT usrs.name as usrname
                                 FROM  #__users As usrs
                                 where usrs.id = a.employee_id) LIKE ' . $search_employee . ' OR (SELECT usrs.name as usrname
                                 FROM  #__users As usrs
                                 where usrs.id = a.employee_id) LIKE ' . $search_employee . ')');
            }
        }





        $search_absence = $this->getState('filter.search_absence');
        if (!empty($search_absence)) {
            if (stripos($search_absence, 'id:') === 0) {
                $query->where('abs.id = ' . (int) substr($search_absence, 3));
            } else {
                //$search2 = $db->Quote('%'.$db->escape($search2, true).'%');

                $search_absence = $db->Quote('%' . $db->escape($search_absence, true) . '%');
                $query->where('(abs.abstype LIKE ' . $search_absence . ' OR abs.abstype LIKE ' . $search_absence . ')');
            }
        }





        $search_ref_year = $this->getState('filter.search_ref_year');
        if (!empty($search_ref_year)) {

            //$search2 = $db->Quote('%'.$db->escape($search2, true).'%');

            $search_ref_year = $db->Quote('' . $db->escape($search_ref_year, true) . '');
            $query->where('(a.ref_year = ' . $search_ref_year . ' OR a.ref_year = ' . $search_ref_year . ')');
        }

        /*



          $search_entitlement = $this->getState('filter.search_entitlement');
          if (!empty($search_entitlement)) {
          if (stripos($search_entitlement, 'id:') === 0) {
          $query->where('abs.id = ' . (int) substr($search_entitlement, 3));
          } else {
          //$search2 = $db->Quote('%'.$db->escape($search2, true).'%');

          $search_entitlement = $db->Quote('%' . $db->escape($search_entitlement, true) . '%');
          $query->where('((Case WHEN (SELECT SUM(FOO.diff) as sumdiff FROM
          (SELECT CONCAT(acs.employee_id, acs.absence_id, acs.ref_year)  as oneid,
          acs.entitlement as diff
          FROM  #__hr_employee_entitlement As acs) AS FOO

          where FOO.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)
          GROUP BY FOO.oneid
          ) IS NULL then 0
          else (SELECT SUM(FOO.diff) as sumdiff FROM
          (SELECT CONCAT(acs.employee_id, acs.absence_id, acs.ref_year)  as oneid,
          acs.entitlement as diff
          FROM  #__hr_employee_entitlement As acs) AS FOO

          where FOO.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)
          GROUP BY FOO.oneid
          ) end) LIKE ' . $search_entitlement . ' OR (Case WHEN (SELECT SUM(FOO.diff) as sumdiff FROM
          (SELECT CONCAT(acs.employee_id, acs.absence_id, acs.ref_year)  as oneid,
          acs.entitlement as diff
          FROM  #__hr_employee_entitlement As acs) AS FOO

          where FOO.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)
          GROUP BY FOO.oneid
          ) IS NULL then 0
          else (SELECT SUM(FOO.diff) as sumdiff FROM
          (SELECT CONCAT(acs.employee_id, acs.absence_id, acs.ref_year)  as oneid,
          acs.entitlement as diff
          FROM  #__hr_employee_entitlement As acs) AS FOO

          where FOO.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)
          GROUP BY FOO.oneid
          ) end) LIKE ' . $search_entitlement . ')');
          }
          }






          $search_charge = $this->getState('filter.search_charge');
          if (!empty($search_charge)) {
          if (stripos($search_charge, 'id:') === 0) {
          $query->where('abs.id = ' . (int) substr($search_charge, 3));
          } else {
          //$search2 = $db->Quote('%'.$db->escape($search2, true).'%');

          $search_charge = $db->Quote('%' . $db->escape($search_charge, true) . '%');
          $query->where('((Case WHEN (SELECT SUM(FOO2.diff) as sumdiff FROM
          (SELECT CONCAT(ac.employee_id, ac.absence_id, ac.ref_year) as oneid,
          ac.charge as diff
          FROM  #__hr_absence_charge As ac) AS FOO2

          where FOO2.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)
          GROUP BY FOO2.oneid
          ) IS NULL then 0
          else (SELECT SUM(FOO2.diff) as sumdiff FROM
          (SELECT CONCAT(ac.employee_id, ac.absence_id, ac.ref_year) as oneid,
          ac.charge as diff
          FROM  #__hr_absence_charge As ac) AS FOO2

          where FOO2.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)
          GROUP BY FOO2.oneid
          ) end) LIKE ' . $search_charge . ' OR (Case WHEN (SELECT SUM(FOO2.diff) as sumdiff FROM
          (SELECT CONCAT(ac.employee_id, ac.absence_id, ac.ref_year) as oneid,
          ac.charge as diff
          FROM  #__hr_absence_charge As ac) AS FOO2

          where FOO2.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)
          GROUP BY FOO2.oneid
          ) IS NULL then 0
          else (SELECT SUM(FOO2.diff) as sumdiff FROM
          (SELECT CONCAT(ac.employee_id, ac.absence_id, ac.ref_year) as oneid,
          ac.charge as diff
          FROM  #__hr_absence_charge As ac) AS FOO2

          where FOO2.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)
          GROUP BY FOO2.oneid
          ) end) LIKE ' . $search_charge . ')');
          }
          }





          $search_balance = $this->getState('filter.search_balance');
          if (!empty($search_balance)) {
          if (stripos($search_balance, 'id:') === 0) {
          $query->where('abs.id = ' . (int) substr($search_balance, 3));
          } else {
          //$search2 = $db->Quote('%'.$db->escape($search2, true).'%');

          $search_balance = $db->Quote('%' . $db->escape($search_balance, true) . '%');
          $query->where('(((Case WHEN (SELECT SUM(FOO.diff) as sumdiff FROM
          (SELECT CONCAT(acs.employee_id, acs.absence_id, acs.ref_year)  as oneid,
          acs.entitlement as diff
          FROM  #__hr_employee_entitlement As acs) AS FOO

          where FOO.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)
          GROUP BY FOO.oneid
          ) IS NULL then 0
          else (SELECT SUM(FOO.diff) as sumdiff FROM
          (SELECT CONCAT(acs.employee_id, acs.absence_id, acs.ref_year)  as oneid,
          acs.entitlement as diff
          FROM  #__hr_employee_entitlement As acs) AS FOO

          where FOO.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)
          GROUP BY FOO.oneid
          ) end)- (Case WHEN (SELECT SUM(FOO2.diff) as sumdiff FROM
          (SELECT CONCAT(ac.employee_id, ac.absence_id, ac.ref_year) as oneid,
          ac.charge as diff
          FROM  #__hr_absence_charge As ac) AS FOO2

          where FOO2.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)
          GROUP BY FOO2.oneid
          ) IS NULL then 0
          else (SELECT SUM(FOO2.diff) as sumdiff FROM
          (SELECT CONCAT(ac.employee_id, ac.absence_id, ac.ref_year) as oneid,
          ac.charge as diff
          FROM  #__hr_absence_charge As ac) AS FOO2

          where FOO2.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)
          GROUP BY FOO2.oneid
          ) end)) LIKE ' . $search_balance . ' OR ((Case WHEN (SELECT SUM(FOO.diff) as sumdiff FROM
          (SELECT CONCAT(acs.employee_id, acs.absence_id, acs.ref_year)  as oneid,
          acs.entitlement as diff
          FROM  #__hr_employee_entitlement As acs) AS FOO

          where FOO.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)
          GROUP BY FOO.oneid
          ) IS NULL then 0
          else (SELECT SUM(FOO.diff) as sumdiff FROM
          (SELECT CONCAT(acs.employee_id, acs.absence_id, acs.ref_year)  as oneid,
          acs.entitlement as diff
          FROM  #__hr_employee_entitlement As acs) AS FOO

          where FOO.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)
          GROUP BY FOO.oneid
          ) end)- (Case WHEN (SELECT SUM(FOO2.diff) as sumdiff FROM
          (SELECT CONCAT(ac.employee_id, ac.absence_id, ac.ref_year) as oneid,
          ac.charge as diff
          FROM  #__hr_absence_charge As ac) AS FOO2

          where FOO2.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)
          GROUP BY FOO2.oneid
          ) IS NULL then 0
          else (SELECT SUM(FOO2.diff) as sumdiff FROM
          (SELECT CONCAT(ac.employee_id, ac.absence_id, ac.ref_year) as oneid,
          ac.charge as diff
          FROM  #__hr_absence_charge As ac) AS FOO2

          where FOO2.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)
          GROUP BY FOO2.oneid
          ) end)) LIKE ' . $search_balance . ')');
          }
          }






          $search_measureunit = $this->getState('filter.search_measureunit');

          if (($search_measureunit == '') || ($search_measureunit == NULL)) {
          $search_measureunit = 'Days';
          }


          if (!empty($search_measureunit)) {

          //$search2 = $db->Quote('%'.$db->escape($search2, true).'%');

          $search_measureunit = $db->Quote('%' . $db->escape($search_measureunit, true) . '%');
          $query->where('(abs.measureunit LIKE ' . $search_measureunit . ' OR abs.measureunit LIKE ' . $search_measureunit . ')');

          }


         */











        $query->select('(Case WHEN (SELECT SUM(FOO.diff) as sumdiff FROM                                 
(SELECT CONCAT(acs.employee_id, acs.absence_id, acs.ref_year)  as oneid,
                          acs.entitlement as diff
                       FROM  #__hr_employee_entitlement As acs) AS FOO
                       
where FOO.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)                       
                       GROUP BY FOO.oneid               
                             ) IS NULL then 0 
        else (SELECT SUM(FOO.diff) as sumdiff FROM                                 
(SELECT CONCAT(acs.employee_id, acs.absence_id, acs.ref_year)  as oneid,
                          acs.entitlement as diff
                       FROM  #__hr_employee_entitlement As acs) AS FOO
                       
where FOO.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)                       
                       GROUP BY FOO.oneid               
                             ) end) AS entitlement');


//to get charge

        $query->select('(Case WHEN (SELECT SUM(FOO2.diff) as sumdiff FROM                                 
(SELECT CONCAT(ac.employee_id, ac.absence_id, ac.ref_year) as oneid,
                          ac.charge as diff
                       FROM  #__hr_absence_charge As ac) AS FOO2
                       
where FOO2.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)                       
                       GROUP BY FOO2.oneid               
                             ) IS NULL then 0 
        else (SELECT SUM(FOO2.diff) as sumdiff FROM                                 
(SELECT CONCAT(ac.employee_id, ac.absence_id, ac.ref_year) as oneid,
                          ac.charge as diff
                       FROM  #__hr_absence_charge As ac) AS FOO2
                       
where FOO2.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)                       
                       GROUP BY FOO2.oneid               
                             ) end) AS charge');

//to get the balance

        $query->select('((Case WHEN (SELECT SUM(FOO.diff) as sumdiff FROM                                 
(SELECT CONCAT(acs.employee_id, acs.absence_id, acs.ref_year)  as oneid,
                          acs.entitlement as diff
                       FROM  #__hr_employee_entitlement As acs) AS FOO
                       
where FOO.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)                       
                       GROUP BY FOO.oneid               
                             ) IS NULL then 0 
        else (SELECT SUM(FOO.diff) as sumdiff FROM                                 
(SELECT CONCAT(acs.employee_id, acs.absence_id, acs.ref_year)  as oneid,
                          acs.entitlement as diff
                       FROM  #__hr_employee_entitlement As acs) AS FOO
                       
where FOO.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)                       
                       GROUP BY FOO.oneid               
                             ) end)- (Case WHEN (SELECT SUM(FOO2.diff) as sumdiff FROM                                 
(SELECT CONCAT(ac.employee_id, ac.absence_id, ac.ref_year) as oneid,
                          ac.charge as diff
                       FROM  #__hr_absence_charge As ac) AS FOO2
                       
where FOO2.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)                       
                       GROUP BY FOO2.oneid               
                             ) IS NULL then 0 
        else (SELECT SUM(FOO2.diff) as sumdiff FROM                                 
(SELECT CONCAT(ac.employee_id, ac.absence_id, ac.ref_year) as oneid,
                          ac.charge as diff
                       FROM  #__hr_absence_charge As ac) AS FOO2
                       
where FOO2.oneid = CONCAT(a.employee_id, a.absence_id, a.ref_year)                       
                       GROUP BY FOO2.oneid               
                             ) end)) AS balance');






        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol == 'a.ordering') {
            $orderCol = 'a.employee_id, a.absence_id, a.ref_year ' . $orderDirn . ', a.ordering';
        }
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

}
