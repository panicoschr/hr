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
 * Item Model for an Rostercat.
 *
 * @since  1.6
 */
class HrModelRostercat extends JModelAdmin
{
	/**
	 * @var        string    The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_HR';

	/**
	 * The type alias for this content type (for example, 'com_hr.rostercat').
	 *
	 * @var      string
	 * @since    3.2
	 */
	public $typeAlias = 'com_hr.rostercat';

	/**
	 * The context used for the associations table
	 *
	 * @var      string
	 * @since    3.4.4
	 */
	protected $associationsContext = 'com_hr.item';

        
        
        
   public function checkDates($data) {

        // set the variables from the passed data
        $id = $data['id'];
        $date1string = $data['datetime_from'];
        $date1 = new DateTime($date1string);
        $date2string = $data['datetime_to'];
        $date2 = new DateTime($date2string);
               

   
   if ($date1 > $date2) {
              return false;
        } else {
            return true;
        }  
    
    
    

    } 
    
    
      public function checkOverlappingAbsences($data) {

        // set the variables from the passed data
          
		require_once JPATH_COMPONENT.'/helpers/hr.php';          
        $id = $data['id'];
        $date1string = $data['datetime_from'];
        $date1 = new DateTime($date1string);
        $date2string = $data['datetime_to'];
        $date2 = new DateTime($date2string);
        
        $roster_id = $data['roster_id'];
        $refcatid = $data['refcatid'];
    
        
        $count = 0;
        $keepDatesTrack = '';

 
                    
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('a.roster_id', 'r.title', 'a.datetime_from', 'a.datetime_to', 'c.title', 'a.refcatid')));
        $query->from($db->quoteName('#__hr_rostercat') . ' AS a');
      //  $query->where('(a.roster_id = ' . $roster_id . ') ');
        $query->where('(a.refcatid = ' . $refcatid . ') ');
        $query->where('(a.id != ' . $id . ') ');
        
      //  $query->where('(final_approval_status NOT LIKE ' . '"Rejected"' . ') ');
     $query->join('INNER', $db->quoteName('#__hr_roster', 'r') . ' ON (' . $db->quoteName('a.roster_id') . ' = ' . $db->quoteName('r.id') . ')');
    $query->join('INNER', $db->quoteName('#__categories', 'c') . ' ON (' . $db->quoteName('a.refcatid') . ' = ' . $db->quoteName('c.id') . ')');
      $query->select('r.title AS roster_title');
       $query->select('c.title AS category_title');     
        $db->setQuery($query);
        $results = $db->loadObjectList();
       


      
        foreach ($results as $result) {
            $datetime_from_string = $result->datetime_from;
            
            $datetime_to_string = $result->datetime_to;
        
      //      $position_id = $result->position_id;
            $roster_title = $result->roster_title;
            $category_title = $result->category_title;
   //        $catid = $result->catid;
            
 //                     ////var_depr_dump($position_title);
   //     jexit();      

         $local_datetime_from = hrHelper::getLocalTime($datetime_from_string);
            $local_datetime_to = hrHelper::getLocalTime($datetime_to_string);


        $datetime_from = strtotime(date("Y-m-d H:i", strtotime($local_datetime_from)));
                    $datetime_from_string = date('Y-m-d H:i', $datetime_from);

                    $datetime_to = strtotime(date("Y-m-d H:i", strtotime($local_datetime_to)));
                    $datetime_to_string = date('Y-m-d H:i', $datetime_to);

    //only for printing the message we deduct one day                
     $datetime_to_minus_one_day = strtotime(date("Y-m-d H:i", strtotime($local_datetime_to)) . " -" . 1 . " day");
     $datetime_to_string_minus_one_day = date('Y-m-d H:i', $datetime_to_minus_one_day);   
     
                    $formatedfromdate = substr($datetime_from_string, 0, 10);
                    $formatedtodate = substr($datetime_to_string_minus_one_day, 0, 10);
                    
                    
 $datetime_from  = new DateTime($datetime_from_string);
          $datetime_to  = new DateTime($datetime_to_string);  
          
         
         
           if ($datetime_from != $datetime_to) {

                //0_string
                      if ((($date1 < $datetime_from) &&
                          ($date1 < $datetime_to) &&
                         ($date2 == $datetime_from)) || 

                   //1
                        (($date1 < $datetime_from) &&
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
                  //     (($date1 == $datetime_to) &&
                  //      ($date2 == $datetime_to)) || 
                         //11a 
                   //     (($date1 == $datetime_to) &&
                     //   ($date2 > $datetime_to))) {

            $count = $count + 1;
                    $keepDatesTrack = $keepDatesTrack . " " . $roster_title. " belongs to " . $category_title. " from " . $formatedfromdate . " to " . $formatedtodate .  ", " ;
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
                 //       (($date1 == $datetime_from) &&
                   //     ($date2 == $datetime_to)) ||
                        //15
                  //      (($date1 == $datetime_from) &&
                   //     ($date2 > $datetime_to))) {
                 $count = $count + 1;
                   $keepDatesTrack = $keepDatesTrack . " " . $roster_title. " belongs to " . $category_title. " from " . $formatedfromdate . " to " . $formatedtodate .  ", " ;
                }
            }
        }
        ////var_depr_dump($keepDatesTrack);
               ////var_depr_dump($count);
    //    jexit();
        
        if ($count > 0) {

            //        ////var_depr_dump($data);
              //       echo 'false';
            //         echo $keepDatesTrack;
          //    jexit();
            return ($keepDatesTrack);
        } else {
             //          echo 'true';
             //                   ////var_depr_dump($data);
           //          echo 'true';
           //          echo $keepDatesTrack;

            //         jexit();
            return 'nodates';
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

			// Check if the rostercat was featured and update the #__hr_rostercat_frontpage table
			if ($featured == 1)
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true)
					->insert($db->quoteName('#__hr_rostercat_frontpage'))
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

			return $user->authorise('core.delete', 'com_hr.rostercat.' . (int) $record->id);
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

		// Check for existing rostercat.
		if (!empty($record->id))
		{
			return $user->authorise('core.edit.state', 'com_hr.rostercat.' . (int) $record->id);
		}
		// New rostercat, so check against the category.
		elseif (!empty($record->catid))
		{
			return $user->authorise('core.edit.state', 'com_hr.category.' . (int) $record->catid);
		}
		// Default to component settings if neither rostercat nor category known.
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
       
         
            $datetime_to = $table->datetime_to;
            $datetime_to_new = strtotime(date("Y-m-d H:i", strtotime($datetime_to)) . " +" . 1 . " day");
            $datetime_to_new = date('Y-m-d H:i', $datetime_to_new);
            $table->datetime_to = $datetime_to_new;
            
            
             
       

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

		// Reorder the rostercats within the category so the new rostercat is first
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
	public function getTable($type = 'Rostercat', $prefix = 'HrTable', $config = array())
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
                        
                 
			$item->rostercattext = trim($item->fulltext) != '' ? $item->introtext . "<hr id=\"system-readmore\" />" . $item->fulltext : $item->introtext;

                      
			if (!empty($item->id))
			{
				$item->tags = new JHelperTags;
				$item->tags->getTagIds($item->id, 'com_hr.rostercat');
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
				$associations = JLanguageAssociations::getAssociations('com_hr', '#__hr_rostercat', 'com_hr.item', $item->id);

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
		$form = $this->loadForm('com_hr.rostercat', 'rostercat', array('control' => 'jform', 'load_data' => $loadData));

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
		if ($this->getState('rostercat.id'))
		{
			$id = $this->getState('rostercat.id');

			// Existing record. Can only edit in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit');

			// Existing record. Can only edit own rostercats in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit.own');
		}
		else
		{
			// New record. Can only create in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

		$user = JFactory::getUser();

		// Check for existing rostercat.
		// Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('core.edit.state', 'com_hr.rostercat.' . (int) $id))
			|| ($id == 0 && !$user->authorise('core.edit.state', 'com_hr')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('featured', 'disabled', 'true');
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('publish_up', 'disabled', 'true');
			$form->setFieldAttribute('publish_down', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is an rostercat you can edit.
			$form->setFieldAttribute('featured', 'filter', 'unset');
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('publish_up', 'filter', 'unset');
			$form->setFieldAttribute('publish_down', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');
		}

		// Prevent messing with rostercat language and category when editing existing rostercat with associations
		$app = JFactory::getApplication();
		$assoc = JLanguageAssociations::isEnabled();

		// Check if rostercat is associated
		if ($this->getState('rostercat.id') && $app->isSite() && $assoc)
		{
			$associations = JLanguageAssociations::getAssociations('com_hr', '#__hr_rostercat', 'com_hr.item', $id);

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
		$data = $app->getUserState('com_hr.edit.rostercat.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
                        
                        
     
                  if ((isset($data->id)) && ($data->id != '0')) {
                
          //      jexit();
            //     ////var_depr_dump($data->datetime_from);
           //            ////var_depr_dump($data->datetime_to);
                
                $data->datetime_from = hrHelper::getLocalTime($data->datetime_from);
                $data->datetime_to = hrHelper::getLocalTime($data->datetime_to);
                
    

                    $datetime_from = $data->datetime_from;
                    $datetime_from_new = strtotime(date("Y-m-d H:i", strtotime($datetime_from)));
                    $datetime_from_new = date('Y-m-d H:i', $datetime_from_new);
                    $data->datetime_from = $datetime_from_new;
                    
                    $datetime_to = $data->datetime_to;
                    $datetime_to_new = strtotime(date("Y-m-d H:i", strtotime($datetime_to)) . " -" . 1 . " day");
                    $datetime_to_new = date('Y-m-d H:i', $datetime_to_new);
                    $data->datetime_to = $datetime_to_new;
                 
            }                
       
       if (!isset($data->id)) {
                        //    $datetime_to_new = '2038-01-16 00:00:00';
                        //    $data->datetime_to =$datetime_to_new;
                            
                        }                           
                                                

			// Pre-select some filters (Status, Category, Language, Access) in edit form if those have been selected in Rostercat Manager: Rostercats
			if ($this->getState('rostercat.id') == 0)
			{
				$filters = (array) $app->getUserState('com_hr.rostercats.filter');
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
            $query->where($db->quoteName('title') . ' = ' . "'Rosters to Departments/Sections'");
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
                
		$this->preprocessData('com_hr.rostercat', $data);

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

				$table = JTable::getInstance('Rostercat', 'HrTable');

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
	 * Method to toggle the featured setting of rostercats.
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

		$table = $this->getTable('Rostercat', 'HrTable');

		try
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true)
				->update($db->quoteName('#__hr_rostercat'))
				->set('featured = ' . (int) $value)
				->where('id IN (' . implode(',', $pks) . ')');
			$db->setQuery($query);
			$db->execute();

			if ((int) $value == 0)
			{
				// Adjust the mapping table.
				// Clear the existing features settings.
				$query = $db->getQuery(true)
					->delete($db->quoteName('#__hr_rostercat_frontpage'))
					->where('ms_id IN (' . implode(',', $pks) . ')');
				$db->setQuery($query);
				$db->execute();
			}
			else
			{
				// First, we find out which of our new featured rostercats are already featured.
				$query = $db->getQuery(true)
					->select('f.ms_id')
					->from('#__hr_rostercat_frontpage AS f')
					->where('ms_id IN (' . implode(',', $pks) . ')');
				$db->setQuery($query);

				$old_featured = $db->loadColumn();

				// We diff the arrays to get a list of the rostercats that are newly featured
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
						->insert($db->quoteName('#__hr_rostercat_frontpage'))
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
					$field->addAttribute('type', 'modal_rostercat');
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
	//	parent::cleanCache('mod_rostercats_archive');
	//	parent::cleanCache('mod_rostercats_categories');
	//	parent::cleanCache('mod_rostercats_category');
	//	parent::cleanCache('mod_rostercats_latest');
	//	parent::cleanCache('mod_rostercats_news');
	//	parent::cleanCache('mod_rostercats_popular');
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
