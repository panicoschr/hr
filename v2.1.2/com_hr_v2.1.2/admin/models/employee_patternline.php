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
 * Item Model for an Employee_patternline.
 *
 * @since  1.6
 */
class HrModelEmployee_patternline extends JModelAdmin
{
	/**
	 * @var        string    The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_HR';

	/**
	 * The type alias for this content type (for example, 'com_hr.employee_patternline').
	 *
	 * @var      string
	 * @since    3.2
	 */
	public $typeAlias = 'com_hr.employee_patternline';

	/**
	 * The context used for the associations table
	 *
	 * @var      string
	 * @since    3.4.4
	 */
	protected $associationsContext = 'com_hr.item';
        
        
      public function positionEmployee($data) {

        // set the variables from the passed data
       // $id = $data['id'];
        $data_employee_id = $data['employee_id'];
        $data_ref_datetime = $data['ref_datetime'];
        
       //        ////var_depr_dump($data_ref_datetime);
         //      jexit();
        $data_no_of_day = $data['no_of_day'];
     
        

        
        $db = JFactory::getDbo();

// Create a new query object.
        $queryempdateday = $db->getQuery(true);
        

        $queryempdateday
                ->select($db->quoteName(array('rp.no_of_day', 'rc.roster_id', 'pc.refcatid', 'rc.datetime_from')))
                ->from($db->quoteName('#__hr_roster_pattern', 'rp'))
                
->join('INNER', $db->quoteName('#__hr_roster', 'r') . ' ON (' . $db->quoteName('r.id') . ' = ' . $db->quoteName('rp.roster_id') . ')')                     
 ->join('INNER', $db->quoteName('#__hr_rostercat', 'rc') . ' ON (' . $db->quoteName('rc.roster_id') . ' = ' . $db->quoteName('r.id') . ')')
 ->join('INNER', $db->quoteName('#__categories', 'c') . ' ON (' . $db->quoteName('c.id') . ' = ' . $db->quoteName('rc.refcatid') . ')')                                    
 ->join('INNER', $db->quoteName('#__hr_positioncat', 'pc') . ' ON (' . $db->quoteName('pc.refcatid') . ' = ' . $db->quoteName('rc.refcatid') . ')')               
->join('INNER', $db->quoteName('#__hr_employee_position', 'ep') . ' ON (' . $db->quoteName('ep.position_id') . ' = ' . $db->quoteName('pc.position_id') . ')');       

                             
                
$queryempdateday->where("('" . $data_ref_datetime . "' BETWEEN rc.datetime_from AND rc.datetime_to)");
$queryempdateday->where("('" . $data_ref_datetime . "' BETWEEN ep.datetime_from AND ep.datetime_to)");
$queryempdateday->where("('" . $data_ref_datetime . "' BETWEEN pc.datetime_from AND pc.datetime_to)");
                
               
     //$queryempdateday->where("('" . $data_ref_datetime . "' BETWEEN rc.datetime_from AND DATE_ADD(rc.ref_datetime,INTERVAL 1 DAY))");
    //$queryempdateday->where("('" . $data_ref_datetime . "' BETWEEN e.date_from AND DATE_ADD(e.date_to,INTERVAL 1 DAY))");
     
   
    
      $queryempdateday->where('ep.employee_id = ' . $data_employee_id);
     
             
           
              
        
        $db->setQuery($queryempdateday);
        
        $queryempdatedays = $db->loadObjectList();
       

                 ////var_depr_dump($queryempdatedays);
              //       echo 'false';
            //         echo $keepDatesTrack;
         //   jexit();
             
      $count = 0;  
      
        foreach ($queryempdatedays as $queryempdateday) {
         $value_of_a_no_of_day = $queryempdateday->no_of_day; 
          ////var_depr_dump($value_of_a_no_of_day);
         
         if ($value_of_a_no_of_day == $data_no_of_day){
         $count=1;     
         }
       
        }
 //       jexit();       
        if  ($count==1)
        {  return true;}
        else
          if  ($count==0)  {
              return false;
          }
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

			// Check if the employee_patternline was featured and update the #__hr_employee_patternline_frontpage table
			if ($featured == 1)
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true)
					->insert($db->quoteName('#__hr_employee_patternline_frontpage'))
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

			return $user->authorise('core.delete', 'com_hr.employee_patternline.' . (int) $record->id);
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

		// Check for existing employee_patternline.
		if (!empty($record->id))
		{
			return $user->authorise('core.edit.state', 'com_hr.employee_patternline.' . (int) $record->id);
		}
		// New employee_patternline, so check against the category.
		elseif (!empty($record->catid))
		{
			return $user->authorise('core.edit.state', 'com_hr.category.' . (int) $record->catid);
		}
		// Default to component settings if neither employee_patternline nor category known.
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
                

                
    
            $ref_datetime = $table->ref_datetime;
            $ref_datetime_new = strtotime(date("Y-m-d H:i", strtotime($ref_datetime)) . " +" . 0 . " day");
            $ref_datetime_new = date('Y-m-d H:i', $ref_datetime_new);
            $table->ref_datetime = $ref_datetime_new;
            
                

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

		// Reorder the employee_patternlines within the category so the new employee_patternline is first
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
	public function getTable($type = 'Employee_patternline', $prefix = 'HrTable', $config = array())
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
                        
                 
			$item->employee_patternlinetext = trim($item->fulltext) != '' ? $item->introtext . "<hr id=\"system-readmore\" />" . $item->fulltext : $item->introtext;

                      
			if (!empty($item->id))
			{
				$item->tags = new JHelperTags;
				$item->tags->getTagIds($item->id, 'com_hr.employee_patternline');
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
				$associations = JLanguageAssociations::getAssociations('com_hr', '#__hr_employee_patternline', 'com_hr.item', $item->id);

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
		$form = $this->loadForm('com_hr.employee_patternline', 'employee_patternline', array('control' => 'jform', 'load_data' => $loadData));

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
		if ($this->getState('employee_patternline.id'))
		{
			$id = $this->getState('employee_patternline.id');

			// Existing record. Can only edit in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit');

			// Existing record. Can only edit own employee_patternlines in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit.own');
		}
		else
		{
			// New record. Can only create in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

		$user = JFactory::getUser();

		// Check for existing employee_patternline.
		// Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('core.edit.state', 'com_hr.employee_patternline.' . (int) $id))
			|| ($id == 0 && !$user->authorise('core.edit.state', 'com_hr')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('featured', 'disabled', 'true');
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('publish_up', 'disabled', 'true');
			$form->setFieldAttribute('publish_down', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is an employee_patternline you can edit.
			$form->setFieldAttribute('featured', 'filter', 'unset');
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('publish_up', 'filter', 'unset');
			$form->setFieldAttribute('publish_down', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');
		}

		// Prevent messing with employee_patternline language and category when editing existing employee_patternline with associations
		$app = JFactory::getApplication();
		$assoc = JLanguageAssociations::isEnabled();

		// Check if employee_patternline is associated
		if ($this->getState('employee_patternline.id') && $app->isSite() && $assoc)
		{
			$associations = JLanguageAssociations::getAssociations('com_hr', '#__hr_employee_patternline', 'com_hr.item', $id);

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
		$data = $app->getUserState('com_hr.edit.employee_patternline.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
                        
                  if ((isset($data->id)) && ($data->id != '0')) {
                
          //      jexit();
            //     ////var_depr_dump($data->datetime_from);
           //            ////var_depr_dump($data->ref_datetime);
                
                $data->ref_datetime = hrHelper::getLocalTime($data->ref_datetime);
           
                
    

                    $ref_datetime = $data->ref_datetime;
                    $ref_datetime_new = strtotime(date("Y-m-d H:i", strtotime($ref_datetime)));
                    $ref_datetime_new = date('Y-m-d H:i', $ref_datetime_new);
                    $data->ref_datetime = $ref_datetime_new;
                    
                   
                 
            }    
                        
                        
                        
                        
                        
                        
                        
			// Pre-select some filters (Status, Category, Language, Access) in edit form if those have been selected in Employee_patternline Manager: Employee_patternlines
			if ($this->getState('employee_patternline.id') == 0)
			{
				$filters = (array) $app->getUserState('com_hr.employee_patternlines.filter');
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
            $query->where($db->quoteName('title') . ' = ' . "'Employee to Pattern Line'");
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
                
                
		$this->preprocessData('com_hr.employee_patternline', $data);

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

				$table = JTable::getInstance('Employee_patternline', 'HrTable');

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
	 * Method to toggle the featured setting of employee_patternlines.
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

		$table = $this->getTable('Employee_patternline', 'HrTable');

		try
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true)
				->update($db->quoteName('#__hr_employee_patternline'))
				->set('featured = ' . (int) $value)
				->where('id IN (' . implode(',', $pks) . ')');
			$db->setQuery($query);
			$db->execute();

			if ((int) $value == 0)
			{
				// Adjust the mapping table.
				// Clear the existing features settings.
				$query = $db->getQuery(true)
					->delete($db->quoteName('#__hr_employee_patternline_frontpage'))
					->where('ms_id IN (' . implode(',', $pks) . ')');
				$db->setQuery($query);
				$db->execute();
			}
			else
			{
				// First, we find out which of our new featured employee_patternlines are already featured.
				$query = $db->getQuery(true)
					->select('f.ms_id')
					->from('#__hr_employee_patternline_frontpage AS f')
					->where('ms_id IN (' . implode(',', $pks) . ')');
				$db->setQuery($query);

				$old_featured = $db->loadColumn();

				// We diff the arrays to get a list of the employee_patternlines that are newly featured
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
						->insert($db->quoteName('#__hr_employee_patternline_frontpage'))
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
					$field->addAttribute('type', 'modal_employee_patternline');
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
	//	parent::cleanCache('mod_employee_patternlines_archive');
	//	parent::cleanCache('mod_employee_patternlines_categories');
	//	parent::cleanCache('mod_employee_patternlines_category');
	//	parent::cleanCache('mod_employee_patternlines_latest');
	//	parent::cleanCache('mod_employee_patternlines_news');
	//	parent::cleanCache('mod_employee_patternlines_popular');
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
