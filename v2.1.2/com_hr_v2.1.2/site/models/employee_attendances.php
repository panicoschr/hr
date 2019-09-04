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

class HrModelEmployee_attendances extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title', 'hierarchy', 'grouping',
				'state', 'a.state',
				'company', 
                                'employee_id', 'a.employee_id',                            
                                'u.name',                            
				'log_in', 'a.log_in',                            
				'log_out', 'a.log_out',      
                            
				'totduration',
				'publish_up', 'a.publish_up',
				'publish_down', 'a.publish_down',
				'ordering', 'a.ordering',
				'catid', 'a.catid', 'category_title'
			);
		}

		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null)
	{
        $search_employee = $this->getUserStateFromRequest($this->context . '.filter.search_employee', 'filter_search_employee');
        $this->setState('filter.search_employee', $search_employee);
        
        $search_date_from = $this->getUserStateFromRequest($this->context . '.filter.search_date_from', 'filter_search_date_from');
        $this->setState('filter.search_date_from', $search_date_from);

        $search_date_to = $this->getUserStateFromRequest($this->context . '.filter.search_date_to', 'filter_search_date_to');
        $this->setState('filter.search_date_to', $search_date_to);


        $itemselected = $this->getUserStateFromRequest($this->context . '.filter.hierarchy', 'filter_hierarchy');
        $this->setState('filter.hierarchy', $itemselected);
        
        $groupselected = $this->getUserStateFromRequest($this->context . '.filter.grouping', 'filter_grouping');
        $this->setState('filter.grouping', $groupselected);        
        
     

		$published = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string');
		$this->setState('filter.state', $published);

		parent::populateState('a.ordering', 'desc');
	}

	protected function getListQuery()
	{
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
                
             $user = JFactory::getUser();                
 $userid = $user->id;

         
     $now = new DateTime();
     $nowstring=$now->format('Y-m-d'); 
     
     
   $mynoofmethodandmycategory = HrHelper::GetMyCategory();
        $no_of_case = substr($mynoofmethodandmycategory, 0, 1);
        $mycatid = substr($mynoofmethodandmycategory, 1);


        $catarray = array();

        $catarray[0] = $mycatid;


        $arrayofcatandsubcats = HrHelper::GetCategoriesWithChildren($catarray);


        $stringofarrayofcatandsubcats = implode("' , '", $arrayofcatandsubcats);     
     
     
		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.title, a.catid,' .
				'a.state,' .
                                'a.employee_id, ' .
                                'u.name, ' .    
                                'a.log_in, ' .                
                                'a.log_out, ' .                                
             '(SELECT DISTINCT pc.refcatid as pccatid
                       FROM  #__hr_positioncat As pc INNER JOIN
                       #__hr_employee_position As ep ON pc.position_id = ep.position_id 
                         where ep.employee_id = '. "'$userid'". ' and '.
                         "'$nowstring'". ' between pc.datetime_from and pc.datetime_to and ' .
                         "'$nowstring'". ' between ep.datetime_from and ep.datetime_to) , '.                       
				'a.publish_up, a.publish_down, a.ordering'
			)
		);
		$query->from($db->quoteName('#__hr_employee_attendance').' AS a');

		$published = $this->getState('filter.state');
		if (is_numeric($published))
		{
			$query->where('a.state = '.(int) $published);
		} elseif ($published === '')
		{
			$query->where('(a.state IN (0, 1))');
		}

               
		// Join over the categories.
	//	$query->select('c.title AS category_title');
            
                
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
                
                
       
$query->select('(SELECT DISTINCT pc.refcatid as pccatid
                       FROM  #__hr_positioncat As pc INNER JOIN
                       #__hr_employee_position As ep ON pc.position_id = ep.position_id 
                         where ep.employee_id = '. "'$userid'". ' and '.
                         "'$nowstring'". ' between pc.datetime_from and pc.datetime_to and ' .
                         "'$nowstring'". ' between ep.datetime_from and ep.datetime_to) as refcatid' );  

                
	//	$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

$query->join('INNER', $db->quoteName('#__users', 'u') . ' ON (' . $db->quoteName('a.employee_id') . ' = ' . $db->quoteName('u.id') . ')');

 //$query->where('a.employee_id' . ' = ' . $user->id); 
 
 
 $groupselected = $this->getState('filter.grouping');   


if (($groupselected == '') || ($groupselected == NULL)) {           
 $groupselected = 'Detail';                 
 }   


  


        if (($groupselected) == 'Detail') {

  $query->select('TIMESTAMPDIFF(MINUTE,a.log_in,a.log_out) as minduration');     
   
     
            
            
            
        } elseif ($groupselected == 'Summary') {
   $query->select('SUM(TIMESTAMPDIFF(MINUTE,a.log_in,a.log_out)) as minduration');                    
           // $query->select('SUM(totduration) as sumtotduration');  
            $query->group ('a.employee_id');
     


            //$query->where('(a.employee_id IN (0, 1))');
            //     $query->where('a.employee_id = 270');
        }        
        
elseif (($groupselected != 'Summary') && ($groupselected != 'Detail')) {

  $query->select('TIMESTAMPDIFF(MINUTE,a.log_in,a.log_out) as minduration');    
   
 
            //$query->where('(a.employee_id IN (0, 1))');
            //     $query->where('a.employee_id = 270');
        }         
        
//$query->order($db->escape('a.log_in'));
 
 
		// Filter by search in title
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


		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		if ($orderCol == 'a.ordering')
		{
			$orderCol = 'a.id '.$orderDirn.', a.ordering';
		}
		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
	}
}