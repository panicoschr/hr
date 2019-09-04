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

class HrControllerYearly_entitlement extends JControllerForm
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

		// An yearly_entitlement edit form can come from the yearly_entitlements or featured view.
		// Adjust the redirect view on the value of 'return' in the request.
		if ($this->input->get('return') == 'featured')
		{
			$this->view_list = 'featured';
			$this->view_item = 'yearly_entitlement&return=featured';
		}
	}
        
        
    
      public function save($key = null, $urlVar = null) {
        // Check for request forgeries.
        //	JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
                     
    
         require_once JPATH_COMPONENT.'/helpers/hr.php';           
   //change this to use the helper        
           
        $app = JFactory::getApplication();
        	$lang  = JFactory::getLanguage();
        $model = $this->getModel('yearly_entitlement');
        	$table = $model->getTable();
        $data = $this->input->post->get('jform', array(), 'array');
        	$checkin = property_exists($table, 'checked_out');
        $context = "$this->option.edit.$this->context";
		$task = $this->getTask();

        $msg_date2_to_greater_than_date1 = 'From Dates can not be greater than To Dates';
        $msg_error = 'There was an Error in the Insert Process';
        $msg_general_error = 'Please make sure that all '
                . 'required fields have a value';
      
        $msg_different_year_error= 'From (Date), To (Date) and Reference Year must '
                . 'belong in the same Calendar year';
        
        
        $allFiledsHaveAValue = $model->allFiledsHaveAValue($data);
        $dates_ok = $model->checkDates($data);
        $all_date_fields_have_same_year = $model->all_date_fields_have_same_year($data);
        
        $app->setUserState($context . '.data', $data);
   //     $url = 'index.php?option=com_hr&view=yearly_entitlement&layout=edit';
        $url = 'index.php?option=com_hr&view=employee_entitlements&layout=default';
        
 //       ////var_depr_dump($data['insertmethod']);
  //      jexit();
if (($allFiledsHaveAValue) && ($dates_ok) && ($all_date_fields_have_same_year)){
   
  
    
        $dataDeletedAndInserted_ok = $model->deleteAndInsertYearlyEntitlements($data);
    
   //      parent::save($data, $urlVar);
           $this->setRedirect($url);
            HrHelper::absenceCharge();  
       
      
        
}  else {
            if ((!$allFiledsHaveAValue) || (!$dates_ok) || (!$dataDeletedAndInserted_ok) || (!$all_date_fields_have_same_year)) {
                if (!$dates_ok) {
                    $this->setRedirect($url, $msg_date2_to_greater_than_date1);
                    return false;
                } else
                if (!$dataDeletedAndInserted_ok) {
                    $this->setRedirect($url, $msg_error);
                    return false;
                } else
                if (!$all_date_fields_have_same_year) {
                    $this->setRedirect($url, $msg_different_year_error);
                    return false;
                } else
                if (!$allFiledsHaveAValue) {
                    $this->setRedirect($url, $msg_general_error);
                    return false;
                }                
            }
        }
    } 
    

     
   	public function cancel($key = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app = JFactory::getApplication();
		$model = $this->getModel();
		$table = $model->getTable();
		$checkin = property_exists($table, 'checked_out');
		$context = "$this->option.edit.$this->context";

		if (empty($key))
		{
			$key = $table->getKeyName();
		}

		$recordId = $app->input->getInt($key);

		// Attempt to check-in the current record.
		if ($recordId)
		{
			if ($checkin)
			{
				if ($model->checkin($recordId) === false)
				{
					// Check-in failed, go back to the record and display a notice.
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
					$this->setMessage($this->getError(), 'error');
parent::cancel($key);
					$this->setRedirect(
						JRoute::_(
							 $url = 'index.php?option=com_hr&view=cpanels&layout=default', false
						)
					);

					return false;
				}
			}
		}

		// Clean the session data and redirect.
		$this->releaseEditId($context, $recordId);
		$app->setUserState($context . '.data', null);

		$this->setRedirect(
			JRoute::_(
				'index.php?option=com_hr&view=cpanels&layout=default', false
			)
		);

		return true;
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
		if ($user->authorise('core.edit', 'com_hr.yearly_entitlement.' . $recordId))
		{
			return true;
		}

		// Check edit own on the record asset (explicit or inherited)
		if ($user->authorise('core.edit.own', 'com_hr.yearly_entitlement.' . $recordId))
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
		$model = $this->getModel('Yearly_entitlement', '', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_hr&view=yearly_entitlements' . $this->getRedirectToListAppend(), false));

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
