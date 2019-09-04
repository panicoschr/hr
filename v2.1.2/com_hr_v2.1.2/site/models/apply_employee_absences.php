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

class HrModelApply_employee_absences extends JModelList
{
    

    
    
	public function __construct($config = array())
	{


		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id', 'a.catid',
				'title', 'a.title', 'hierarchy',
				'state', 'a.state',
				'company', 
				'measureunit', 'a.measureunit',                            
				'employee_id', 'a.employee_id',                               
				'absence_id', 'a.absence_id',                               
				'datetime_from', 'a.datetime_from',                               
				'datetime_to', 'a.datetime_to',                               
				'introtext',  'a.introtext',  
                                'fulltext', 'a.fulltext',                               
				'abs_certification', 'a.abs_certification',   
                            
              
                            

                                'approver1_id', 'a.approver1_id',
                                'approver2_id', 'a.approver2_id',
                                'approver3_id', 'a.approver3_id',
                                'approver4_id', 'a.approver4_id',
                                'approver5_id', 'a.approver5_id',
                                'approval_status1', 'a.approval_status1',
                                'approval_status2', 'a.approval_status2',
                                'approval_status3', 'a.approval_status3',
                                'approval_status4', 'a.approval_status4',
                                'approval_status5', 'a.approval_status5',
                                        
                            
                            
				'final_approval_status', 'a.final_approval_status',    
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
            
           // $app = JFactory::getApplication('administrator');
            
             
        
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
				'a.id, a.catid, a.title,  ' .
				'a.state,' .

 '(SELECT DISTINCT pc.refcatid as pccatid
                       FROM  #__hr_positioncat As pc INNER JOIN
                       #__hr_employee_position As ep ON pc.position_id = ep.position_id 
                         where ep.employee_id = '. "'$userid'". ' and '.
                         "'$nowstring'". ' between pc.datetime_from and pc.datetime_to and ' .
                         "'$nowstring'". ' between ep.datetime_from and ep.datetime_to) , '.                                
                                
                                
                                'a.measureunit, ' .                
                                'a.employee_id, ' .   
                                'b.name, ' .                                
                                'd.abstype, ' .                                
                                'a.datetime_from, ' .                                
                                'a.datetime_to, ' .                                
                                'a.introtext, ' .          
                                'a.fulltext, ' .       
                                'a.abs_certification, ' .      
                                'a.approver1_id, ' .          
                                'a.approver2_id, ' .   
                                'a.approver3_id, ' .   
                                'a.approver4_id, ' .   
                                'a.approver5_id, ' .   
                                'a.approval_status1, ' .   
                                'a.approval_status2, ' . 
                                'a.approval_status3, ' . 
                                'a.approval_status4, ' . 
                                'a.approval_status5, ' . 
                                
                 'COUNT(DISTINCT(ac.datetime_from)), ' .    
                                
                    '(SELECT SUM(FOO.diff) as sumdiff FROM         
                        (SELECT DISTINCT acs.datetime_from as dacs, 
                          TIMESTAMPDIFF(MINUTE,acs.datetime_from,acs.datetime_to) as diff,
                           acs.id_of_the_absence as theid 
                       FROM  #__hr_absence_charge As acs INNER JOIN
                       #__hr_employee_absence As a ON acs.id_of_the_absence = a.id
                         ) As FOO where FOO.theid = a.id
                       GROUP BY theid
                     )  , '.             
                                
                                
                                'SUM(ac.charge), ' .      
                                                
                  
                                'a.final_approval_status, ' .     
				'a.publish_up, a.publish_down, a.ordering'
			)
		);
		$query->from($db->quoteName('#__hr_employee_absence').' AS a');

		$published = $this->getState('filter.state');
		if (is_numeric($published))
		{
			$query->where('a.state = '.(int) $published);
		} elseif ($published === '')
		{
			$query->where('(a.state IN (0, 1))');
		}


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
		//$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

$query->select('(SELECT DISTINCT pc.refcatid as pccatid
                       FROM  #__hr_positioncat As pc INNER JOIN
                       #__hr_employee_position As ep ON pc.position_id = ep.position_id 
                         where ep.employee_id = '. "'$userid'". ' and '.
                         "'$nowstring'". ' between pc.datetime_from and pc.datetime_to and ' .
                         "'$nowstring'". ' between ep.datetime_from and ep.datetime_to) as refcatid' );        



$query->join('INNER', $db->quoteName('#__users', 'b') . ' ON (' . $db->quoteName('a.employee_id') . ' = ' . $db->quoteName('b.id') . ')');


$query->join('INNER', $db->quoteName('#__hr_absence', 'd') . ' ON (' . $db->quoteName('a.absence_id') . ' = ' . $db->quoteName('d.id') . ')');
 
                
$query->join('LEFT OUTER', $db->quoteName('#__hr_absence_charge', 'ac') . ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('ac.id_of_the_absence') . ')');
                 
      

$query->group($db->quoteName('a.id')) ; 



$query->select('COUNT(DISTINCT(ac.datetime_from)) AS noofcalls');

$query->select('(SELECT SUM(FOO.diff) as sumdiff FROM         
                        (SELECT DISTINCT acs.datetime_from as dacs, 
                          TIMESTAMPDIFF(MINUTE,acs.datetime_from,acs.datetime_to) as diff,
                           acs.id_of_the_absence as theid 
                       FROM  #__hr_absence_charge As acs INNER JOIN
                       #__hr_employee_absence As a ON acs.id_of_the_absence = a.id
                         ) As FOO where FOO.theid = a.id
                       GROUP BY theid
                     )    AS sumofminutes');
        
//this must be sum of miniuutes


$query->select('SUM(ac.charge) AS accharge');


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
                $query->where('(b.name LIKE ' . $search_employee . ' OR b.name LIKE ' . $search_employee . ')');
            }
        }


// $query->where('a.employee_id' . ' = ' . $user->id);
                
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.datetime_from LIKE '.$search.' OR d.abstype LIKE '.$search.')');
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