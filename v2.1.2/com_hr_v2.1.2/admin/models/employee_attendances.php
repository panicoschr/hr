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

/**
 * Methods supporting a list of employee_attendance records.
 *
 * @since  1.6
 */
class HrModelEmployee_attendances extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   1.6
	 * @see     JController
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'alias', 'a.alias',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'catid', 'a.catid', 'category_title',
				'state', 'a.state',
				'access', 'a.access', 'access_level',
				'created', 'a.created',
				'created_by', 'a.created_by',
				'created_by_alias', 'a.created_by_alias',
				'ordering', 'a.ordering',
				'featured', 'a.featured',
				'language', 'a.language',
				'hits', 'a.hits',
				'publish_up', 'a.publish_up',
				'publish_down', 'a.publish_down',
				'published', 'a.published',
				'author_id',
				'category_id',
				'level',
				'tag',
                                'employee_id', 'a.employee_id',                            
                 		'username',   
				'log_in', 'a.log_in',                            
				'log_out', 'a.log_out',                               
                            	'minduration'   
                            
                            
                        
			);

			if (JLanguageAssociations::isEnabled())
			{
				$config['filter_fields'][] = 'association';
			}
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = 'a.id', $direction = 'desc')
	{
		$app = JFactory::getApplication();

		$forcedLanguage = $app->input->get('forcedLanguage', '', 'cmd');

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}

		// Adjust the context to support forced languages.
		if ($forcedLanguage)
		{
			$this->context .= '.' . $forcedLanguage;
		}

		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access');
		$this->setState('filter.access', $access);

		$authorId = $app->getUserStateFromRequest($this->context . '.filter.author_id', 'filter_author_id');
		$this->setState('filter.author_id', $authorId);

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		$categoryId = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);

		$level = $this->getUserStateFromRequest($this->context . '.filter.level', 'filter_level');
		$this->setState('filter.level', $level);

		$language = $this->getUserStateFromRequest($this->context . '.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);
                
		$employee_id = $this->getUserStateFromRequest($this->context . '.filter.employee_id', 'filter_employee_id', '');
		$this->setState('filter.employee_id', $employee_id);

                $itemselected = $this->getUserStateFromRequest($this->context . '.filter.itemselected', 'filter_itemselected', '');
		$this->setState('filter.itemselected', $itemselected);

                $groupselected = $this->getUserStateFromRequest($this->context . '.filter.groupselected', 'filter_groupselected', '');
		$this->setState('filter.groupselected', $groupselected);                
                
                
	//	$tag = $this->getUserStateFromRequest($this->context . '.filter.tag', 'filter_tag', '');
	//	$this->setState('filter.tag', $tag);

		// List state information.
		parent::populateState($ordering, $direction);

		// Force a language
		if (!empty($forcedLanguage))
		{
			$this->setState('filter.language', $forcedLanguage);
			$this->setState('filter.forcedLanguage', $forcedLanguage);
		}
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.access');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.category_id');
		$id .= ':' . $this->getState('filter.author_id');
		$id .= ':' . $this->getState('filter.language');
                $id .= ':' . $this->getState('filter.employee_id');
                $id .= ':' . $this->getState('filter.itemselected');
                $id .= ':' . $this->getState('filter.groupselected');
                

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 *
	 * @since   1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

                  
   $userid = $user->id;

         
     $now = new DateTime();
     $nowstring=$now->format('Y-m-d');   
     
     
    $mynoofmethodandmycategory = HrHelper::GetMyCategory();  
    $no_of_case = substr($mynoofmethodandmycategory, 0, 1);
    $mycatid = substr($mynoofmethodandmycategory, 1);
     
    $catarray = array();

    $catarray[0]=$mycatid;
 
 
    $arrayofcatandsubcats=HrHelper::GetCategoriesWithChildren($catarray);
  
 
    $stringofarrayofcatandsubcats = implode("' , '", $arrayofcatandsubcats);     
     
          
     
     
     
		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.title, a.alias, a.checked_out, a.checked_out_time, a.catid' .
					', a.state, a.access, a.created, a.created_by, a.created_by_alias, a.ordering, a.featured, a.language, a.hits' .
					', a.publish_up, a.publish_down' .
                                 ', a.employee_id, ' .
                                     
                                '(SELECT usrs.name as usrname
                       FROM  #__users As usrs
             where usrs.id = a.employee_id), ' .                                   
                             
                                

      //      '(SELECT  CONCAT(a.log_in, a.log_out) as dd), '.          
           
                        
                       
                                
                                'a.log_in, ' .                
                                'a.log_out, '.
                           
  '(SELECT DISTINCT pc.refcatid as pccatid
                       FROM  #__hr_positioncat As pc INNER JOIN
                       #__hr_employee_position As ep ON pc.position_id = ep.position_id 
                         where ep.employee_id = '. "'$userid'". ' and '.
                         "'$nowstring'". ' between pc.datetime_from and pc.datetime_to and ' .
                         "'$nowstring'". ' between ep.datetime_from and ep.datetime_to)'                            
                                            
                                        
			)
		);
		$query->from('#__hr_employee_attendance AS a');

		// Join over the language
		$query->select('l.title AS language_title, l.image AS language_image')
			->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor')
			->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the asset groups.
		$query->select('ag.title AS access_level')
			->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		// Join over the categories.
		$query->select('c.title AS category_title')
			->join('LEFT', '#__categories AS c ON c.id = a.catid');

		// Join over the users for the author.
		$query->select('ua.name AS author_name')
			->join('LEFT', '#__users AS ua ON ua.id = a.created_by');
                
                
                $query->select('a.title AS employee_attendance_title');
           
                $query->select('(SELECT usrs.name as usrname
                       FROM  #__users As usrs
                          where usrs.id = a.employee_id)  AS username');       
                
        

 $groupselected = $this->getState('filter.groupselected');
        if ($groupselected == '') {
            $groupselected = 'Detail';
        }        



        if (($groupselected) == 'Detail') {
            // Join over the associations.
                  $query->select('TIMESTAMPDIFF(MINUTE,a.log_in,a.log_out) as minduration');
            if (JLanguageAssociations::isEnabled()) {
             
                $query->select('TIMESTAMPDIFF(MINUTE,a.log_in,a.log_out) as minduration');
                $query->select('COUNT(asso2.id)>1 as association')
                        ->join('LEFT', '#__associations AS asso ON asso.id = a.id AND asso.context=' . $db->quote('com_hr.item'))
                        ->join('LEFT', '#__associations AS asso2 ON asso2.key = asso.key')
                        ->group('a.id, l.title, l.image, uc.name, ag.title, c.title, ua.name');
            }
        }

        
        //replaced a.id with a.employee_id to group it. 
        if ($groupselected == 'Summary') {
            // Join over the associations.
                        $query->select('SUM(TIMESTAMPDIFF(MINUTE,a.log_in,a.log_out)) as minduration');
            if (JLanguageAssociations::isEnabled()) {
            
                $query->select('SUM(TIMESTAMPDIFF(MINUTE,a.log_in,a.log_out)) as minduration');
                $query->select('COUNT(asso2.id)>1 as association')
                        ->join('LEFT', '#__associations AS asso ON asso.id = a.id AND asso.context=' . $db->quote('com_hr.item'))
                        ->join('LEFT', '#__associations AS asso2 ON asso2.key = asso.key')
                        ->group('a.employee_id, l.title, l.image, uc.name, ag.title, c.title, ua.name');
            }
        }







        // Filter by access level.
		if ($access = $this->getState('filter.access'))
		{
			$query->where('a.access = ' . (int) $access);
		}

		// Implement View Level Access
		if (!$user->authorise('core.admin'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
		}

		// Filter by published state
		$published = $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.state = 0 OR a.state = 1)');
		}
    
                
                
        $employee_id = $this->getState('filter.employee_id');

        if (($employee_id != NULL) || ($employee_id != 0)){

            $query->where($db->quoteName('a.employee_id') . ' =  ' . (int) $employee_id);
        }
        
                        

		// Filter by a single or group of categories.
		$baselevel = 1;
		$categoryId = $this->getState('filter.category_id');

		if (is_numeric($categoryId))
		{
			$cat_tbl = JTable::getInstance('Category', 'JTable');
			$cat_tbl->load($categoryId);
			$rgt = $cat_tbl->rgt;
			$lft = $cat_tbl->lft;
			$baselevel = (int) $cat_tbl->level;
			$query->where('c.lft >= ' . (int) $lft)
				->where('c.rgt <= ' . (int) $rgt);
		}
		elseif (is_array($categoryId))
		{
			JArrayHelper::toInteger($categoryId);
			$categoryId = implode(',', $categoryId);
			$query->where('a.catid IN (' . $categoryId . ')');
		}

		// Filter on the level.
		if ($level = $this->getState('filter.level'))
		{
			$query->where('c.level <= ' . ((int) $level + (int) $baselevel - 1));
		}

		// Filter by author
		$authorId = $this->getState('filter.author_id');

		if (is_numeric($authorId))
		{
			$type = $this->getState('filter.author_id.include', true) ? '= ' : '<>';
			$query->where('a.created_by ' . $type . (int) $authorId);
		}

		// Filter by search in title.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			elseif (stripos($search, 'author:') === 0)
			{
				$search = $db->quote('%' . $db->escape(substr($search, 7), true) . '%');
				$query->where('(ua.name LIKE ' . $search . ' OR ua.username LIKE ' . $search . ')');
			}
			else
			{
				$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where('(a.title LIKE ' . $search . ' OR a.alias LIKE ' . $search . ')');
			}
		}

		// Filter on the language.
		if ($language = $this->getState('filter.language'))
		{
			$query->where('a.language = ' . $db->quote($language));
		}

		// Filter by a single tag.
                /*
		$tagId = $this->getState('filter.tag');

		if (is_numeric($tagId))
		{
			$query->where($db->quoteName('tagmap.tag_id') . ' = ' . (int) $tagId)
				->join(
					'LEFT', $db->quoteName('#__hr_employee_attendanceitem_tag_map', 'tagmap')
					. ' ON ' . $db->quoteName('tagmap.content_item_id') . ' = ' . $db->quoteName('a.id')
					. ' AND ' . $db->quoteName('tagmap.type_alias') . ' = ' . $db->quote('com_hr.employee_attendance')
				);
		}
*/
                
   $query->where("CONCAT(a.log_in, a.log_out)".' != '. "'0000-00-00 00:00:000000-00-00 00:00:00'");  
   
        //        $query->where('a.log_in != '. "'0000-00-00 00:00:00'");     
                
                
                

                
 $itemselected = $this->getState('filter.itemselected');
        if ($itemselected == '') {
            $itemselected = 'supervisor';
        }

        if (($itemselected) == 'employee') {

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

         
        








        // Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'a.id');
		$orderDirn = $this->state->get('list.direction', 'desc');

		if ($orderCol == 'a.ordering' || $orderCol == 'category_title')
		{
			$orderCol = 'c.title ' . $orderDirn . ', a.ordering';
		}

		// SQL server change
		if ($orderCol == 'language')
		{
			$orderCol = 'l.title';
		}

		if ($orderCol == 'access_level')
		{
			$orderCol = 'ag.title';
		}

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	/**
	 * Build a list of authors
	 *
	 * @return  JDatabaseQuery
	 *
	 * @since   1.6
	 */
	public function getAuthors()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Construct the query
		$query->select('u.id AS value, u.name AS text')
			->from('#__users AS u')
			->join('INNER', '#__hr_employee_attendance AS c ON c.created_by = u.id')
			->group('u.id, u.name')
			->order('u.name');

		// Setup the query
		$db->setQuery($query);

		// Return the result
		return $db->loadObjectList();
	}

	/**
	 * Method to get a list of employee_attendances.
	 * Overridden to add a check for access levels.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   1.6.1
	 */
	public function getItems()
	{
		$items = parent::getItems();

		if (JFactory::getApplication()->isSite())
		{
			$user = JFactory::getUser();
			$groups = $user->getAuthorisedViewLevels();

			for ($x = 0, $count = count($items); $x < $count; $x++)
			{
				// Check the access level. Remove employee_attendances the user shouldn't see
				if (!in_array($items[$x]->access, $groups))
				{
					unset($items[$x]);
				}
			}
		}

		return $items;
	}
}
