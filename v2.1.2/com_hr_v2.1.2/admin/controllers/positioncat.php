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

class HrControllerPositioncat extends JControllerForm
{
	/**
	 * Class constructor.
	 *
	 * @param   array  $config  A named array of configuration variables.
	 *
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		// An positioncat edit form can come from the positioncats or featured view.
		// Adjust the redirect view on the value of 'return' in the request.
		if ($this->input->get('return') == 'featured')
		{
			$this->view_list = 'featured';
			$this->view_item = 'positioncat&return=featured';
		}
	}

        
        
        
    public function save($key = null, $urlVar = null) {
        // Check for request forgeries.
        //	JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $lang = JFactory::getLanguage();
        $model = $this->getModel('positioncat');
        $table = $model->getTable();
        $data = $this->input->post->get('jform', array(), 'array');
        $checkin = property_exists($table, 'checked_out');
        $context = "$this->option.edit.$this->context";
        $task = $this->getTask();



 

        $msg_date2_to_greater_than_date1 = 'From Date can not be greater than To Date';
        $msg_dates_overlapping = 'This entry overlapps with the following entry :  ';


        $dates_ok = $model->checkDates($data);
        $overlapping = $model->checkOverlappingAbsences($data);
          ////var_depr_dump($overlapping);
    //            ////var_depr_dump($count);
  //      jexit();
        
        // Saves the data in the session. 
        $app->setUserState($context . '.data', $data);
        $url = 'index.php?option=com_hr&view=positioncat&layout=edit';

        if (($dates_ok) && ($overlapping == 'nodates')) {
            parent::save($data, $urlVar);
        } else {
            if (!$dates_ok) {
                $this->setRedirect($url, $msg_date2_to_greater_than_date1);
                return false;
            }
            if ($overlapping != 'nodates') {
                $this->setRedirect($url, $msg_dates_overlapping . ' ' . $overlapping);
                return false;
            }
        }
    }            
        
        
        
	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowAdd($data = array())
	{
		$user = JFactory::getUser();
		$categoryId = JArrayHelper::getValue($data, 'catid', $this->input->getInt('filter_category_id'), 'int');
		$allow = null;

		if ($categoryId)
		{
			// If the category has been passed in the data or URL check it.
			$allow = $user->authorise('core.create', 'com_hr.category.' . $categoryId);
		}

		if ($allow === null)
		{
			// In the absense of better information, revert to the component permissions.
			return parent::allowAdd();
		}
		else
		{
			return $allow;
		}
	}

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		$user = JFactory::getUser();

		// Zero record (id:0), return component edit permission by calling parent controller method
		if (!$recordId)
		{
			return parent::allowEdit($data, $key);
		}

		// Check edit on the record asset (explicit or inherited)
		if ($user->authorise('core.edit', 'com_hr.positioncat.' . $recordId))
		{
			return true;
		}

		// Check edit own on the record asset (explicit or inherited)
		if ($user->authorise('core.edit.own', 'com_hr.positioncat.' . $recordId))
		{
			// Existing record already has an owner, get it
			$record = $this->getModel()->getItem($recordId);

			if (empty($record))
			{
				return false;
			}

			// Grant if current user is owner of the record
			return $user->get('id') == $record->created_by;
		}

		return false;
	}

	/**
	 * Method to run batch operations.
	 *
	 * @param   object  $model  The model.
	 *
	 * @return  boolean   True if successful, false otherwise and internal error is set.
	 *
	 * @since   1.6
	 */
	public function batch($model = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model = $this->getModel('Positioncat', '', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_hr&view=positioncats' . $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}

	/**
	 * Function that allows child controller access to model data after the data has been saved.
	 *
	 * @param   JModelLegacy  $model      The data model object.
	 * @param   array         $validData  The validated data.
	 *
	 * @return	void
	 *
	 * @since	3.1
	 */
	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{

		return;
	}
}
