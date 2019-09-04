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

class HrModelEmployee_rosters extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id', 'hierarchy',
                'title', 'a.title',
                'state', 'a.state',
                'company', 
                'abstype', 'ab.abstype',
                'final_approval_status', 'ea.final_approval_status',          
                'measureunit', 'ab.measureunit',                    
                'employee_id', 'a.employee_id',
                'a.datetime_from',
                'u.name',
                'a.datetime_to', 'a.work_status',
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
        
        $search_date_from = $this->getUserStateFromRequest($this->context . '.filter.search_date_from', 'filter_search_date_from');
        $this->setState('filter.search_date_from', $search_date_from);

        $search_date_to = $this->getUserStateFromRequest($this->context . '.filter.search_date_to', 'filter_search_date_to');
        $this->setState('filter.search_date_to', $search_date_to);


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
                        'a.state,' .
                        'a.employee_id, ' .
                        
                         'ea.datetime_from, ' .                 
                         'ea.datetime_to, ' .   
                         'ea.final_approval_status, ' .                         
                         'ab.abstype, ' .  
                        
                         'ab.measureunit, ' .                          
                        'a.datetime_from, ' .
                        'a.datetime_to, ' .
                        'u.name, ' .
                        'a.work_status, ' .
                        'a.comment, ' .
                        'a.no_of_day, ' .
                        '(SELECT DISTINCT pc.refcatid as pccatid
                       FROM  #__hr_positioncat As pc INNER JOIN
                       #__hr_employee_position As ep ON pc.position_id = ep.position_id 
                         where ep.employee_id = ' . "'$userid'" . ' and ' .
                        "'$nowstring'" . ' between pc.datetime_from and pc.datetime_to and ' .
                        "'$nowstring'" . ' between ep.datetime_from and ep.datetime_to) , ' .
                        'a.publish_up, a.publish_down, a.ordering'
                )
        );
        $query->from($db->quoteName('#__hr_employee_roster') . ' AS a');

        $published = $this->getState('filter.state');

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

        $query->join('INNER', $db->quoteName('#__users', 'u') . ' ON (' . $db->quoteName('a.employee_id') . ' = ' . $db->quoteName('u.id') . ')');
//$query->join('INNER', $db->quoteName('#__hr_employee_absence', 'ea') . ' ON (' . $db->quoteName('ea.employee_id') . ' = ' . $db->quoteName('u.id') . ')');
        


$query->join('LEFT', $db->quoteName('#__hr_employee_absence', 'ea') . " ON ((`ea`.`employee_id` = `a`.`employee_id`) AND
    (`a`.`work_status` = 'WORK') AND
    
            (((`ea`.`datetime_from` < `a`.`datetime_from`) AND (`ea`.`datetime_to` > `a`.`datetime_from`) AND (`ea`.`datetime_to` < `a`.`datetime_to`)) OR
             ((`ea`.`datetime_from` < `a`.`datetime_from`) AND (`ea`.`datetime_to` = `a`.`datetime_to`)) OR
             ((`ea`.`datetime_from` < `a`.`datetime_from`) AND (`ea`.`datetime_to` > `a`.`datetime_to`)) OR

             ((`ea`.`datetime_from` = `a`.`datetime_from`) AND (`ea`.`datetime_to` > `a`.`datetime_from`) AND (`ea`.`datetime_to` < `a`.`datetime_to`)) OR
             ((`ea`.`datetime_from` = `a`.`datetime_from`) AND (`ea`.`datetime_to` = `a`.`datetime_to`)) OR 
             ((`ea`.`datetime_from` = `a`.`datetime_from`) AND (`ea`.`datetime_to` > `a`.`datetime_to`)) OR
             ((`ea`.`datetime_from` > `a`.`datetime_from`) AND (`ea`.`datetime_from` < `a`.`datetime_to`) AND (`ea`.`datetime_to` > `a`.`datetime_from`) AND (`ea`.`datetime_to` < `a`.`datetime_to`))  OR 
             ((`ea`.`datetime_from` > `a`.`datetime_from`) AND (`ea`.`datetime_from` < `a`.`datetime_to`) AND (`ea`.`datetime_to` = `a`.`datetime_to`)) OR
             ((`ea`.`datetime_from` > `a`.`datetime_from`) AND (`ea`.`datetime_from` < `a`.`datetime_to`) AND (`ea`.`datetime_to` > `a`.`datetime_to`))))");

$query->join('LEFT', $db->quoteName('#__hr_absence', 'ab') . ' ON (' . $db->quoteName('ea.absence_id') . ' = ' . $db->quoteName('ab.id') . ')');



        //$query->where('a.employee_id' . ' = ' . $user->id); 
//$query->select('TIMESTAMPDIFF(HOUR,a.log_in,a.log_out) as hourduration');  
//$query->select('TIMESTAMPDIFF(MINUTE,a.log_in,a.log_out) - TIMESTAMPDIFF(HOUR,a.log_in,a.log_out)*60  as minduration');       
//$query->select('TIMESTAMPDIFF(MINUTE,a.log_in,a.log_out) as totduration');   
//$query->order($db->escape('a.log_in'));
        // Filter by search in title

$query->select('a.datetime_from AS roster_datetime_from');
$query->select('a.datetime_to AS roster_datetime_to');

$query->select('ea.datetime_from AS absence_datetime_from');
$query->select('ea.datetime_to AS absence_datetime_to');



        $search_date_from = $this->getState('filter.search_date_from');
        if (!empty($search_date_from)) {
            if (stripos($search_date_from, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search_date_from, 3));
            } else {
                //$search = $db->Quote('%'.$db->escape($search, true).'%');
                $query->where($db->quoteName('a.datetime_from') . ' >= ' . "DATE_FORMAT('$search_date_from', '%Y-%m-%d %H:%i:%s')");
            }
        }



        $search_date_to = $this->getState('filter.search_date_to');
        if (!empty($search_date_to)) {
            if (stripos($search_date_to, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search_date_to, 3));
            } else {
                //$search2 = $db->Quote('%'.$db->escape($search2, true).'%');
                $query->where($db->quoteName('a.datetime_to') . ' <= ' . "DATE_FORMAT('$search_date_to', '%Y-%m-%d %H:%i:%s')");
            }
        }


        $search_employee = $this->getState('filter.search_employee');
        if (!empty($search_employee)) {
            if (stripos($search_employee, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search_employee, 3));
            } else {
                //$search2 = $db->Quote('%'.$db->escape($search2, true).'%');

                $search_employee = $db->Quote('%' . $db->escape($search_employee, true) . '%');
                $query->where('(u.name LIKE ' . $search_employee . ' OR u.name LIKE ' . $search_employee . ')');
            }
        }


        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol == 'a.ordering') {
            $orderCol = 'a.datetime_from ' . $orderDirn . ', a.ordering';
        }
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

}
