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

class HrControllerPlan_entitlement extends JControllerForm
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

		// An plan_entitlement edit form can come from the plan_entitlements or featured view.
		// Adjust the redirect view on the value of 'return' in the request.
		if ($this->input->get('return') == 'featured')
		{
			$this->view_list = 'featured';
			$this->view_item = 'plan_entitlement&return=featured';
		}
	}
        
    public function save($key = null, $urlVar = null) {
        // Check for request forgeries.
        //	JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $lang = JFactory::getLanguage();
        $model = $this->getModel('plan_entitlement');
        $table = $model->getTable();
        $data = $this->input->post->get('jform', array(), 'array');
        $checkin = property_exists($table, 'checked_out');
        $context = "$this->option.edit.$this->context";
        $task = $this->getTask();



      
        $msg_not_numeric_fields = 'From Field and To Field must be integers. No empty values allowed';       
        $msg_integer_to_greater_than_integer_from = 'From Field must not be greater than To Field';
                
        
        $msg_date_from_and_date_to_valid_dates = 'From Field and To Fields must be Valid Dates of the format (mm-dd) i.e 03-05';
           $msg_date_to_greater_than_date_from = 'From Date Field must not be greater than To Date Field';           
        
   $msg_datetime_from_and_datetime_to_valid_datetimes = 'From Field and To Fields must be Valid DateTimes of the format (mm-dd hh:mm) i.e 03-05 12:35';
           $msg_datetime_to_greater_than_datetime_from = 'From DateTime Field must not be greater than To DateTime Field';           
                  
  
                
        
  $msg_entitlement_amount_not_integer = 'Entitlement Amount must be integer. No empty or zero value allowed';  
  
 
  
  
    //    $msg_dates_overlapping = 'This absence overlapps with other absence(s) ';

  
          $entitlement_ok = $model->checkEntitlementAmount($data);
          $valid_input_ok = $model->checkIfValidInput($data);
          
          
          
     //     ////var_depr_dump($entitlement_ok);
    //      jexit();

        
     
        // Saves the data in the session. 
        $app->setUserState($context . '.data', $data);
        $url = 'index.php?option=com_hr&view=plan_entitlement&layout=edit';

        if (($entitlement_ok) && ($valid_input_ok=='ok')) {
            parent::save($data, $urlVar);
        } else {
            if (!$entitlement_ok) {
                $this->setRedirect($url, $msg_entitlement_amount_not_integer);
                return false;
            }
         if ($valid_input_ok=='not_numeric_fields') {
                $this->setRedirect($url, $msg_not_numeric_fields);
                return false;
            }               
         if ($valid_input_ok=='integer_to_greater_than_integer_from') {
                $this->setRedirect($url,  $msg_integer_to_greater_than_integer_from);
                return false;
            }                 
         if ($valid_input_ok=='date_to_greater_than_date_from') {
                $this->setRedirect($url, $msg_date_to_greater_than_date_from);
                return false;
            }       
        if ($valid_input_ok=='date_from_and_date_to_valid_dates') {
                $this->setRedirect($url, $msg_date_from_and_date_to_valid_dates);
                return false;
            }              
         if ($valid_input_ok=='datetime_to_greater_than_datetime_from') {
                $this->setRedirect($url, $msg_datetime_to_greater_than_datetime_from);
                return false;
            }       
        if ($valid_input_ok=='datetime_from_and_datetime_to_valid_datetimes') {
                $this->setRedirect($url, $msg_datetime_from_and_datetime_to_valid_datetimes);
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
		if ($user->authorise('core.edit', 'com_hr.plan_entitlement.' . $recordId))
		{
			return true;
		}

		// Check edit own on the record asset (explicit or inherited)
		if ($user->authorise('core.edit.own', 'com_hr.plan_entitlement.' . $recordId))
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
		$model = $this->getModel('Plan_entitlement', '', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_hr&view=plan_entitlements' . $this->getRedirectToListAppend(), false));

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
