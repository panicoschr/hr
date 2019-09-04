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
class HrControllerEmployee_attendance extends JControllerForm
{
    
   // table->bind and table->save
 
    
    
   public function save($key = null, $urlVar = null) {
        // Check for request forgeries.
        //	JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $lang = JFactory::getLanguage();
        $model = $this->getModel('employee_attendance');
        $table = $model->getTable();
        $data = $this->input->post->get('jform', array(), 'array');
        $checkin = property_exists($table, 'checked_out');
        $context = "$this->option.edit.$this->context";
        $task = $this->getTask();

        $msg_authority_not_ok = 'You are not authorised to access Attendance on this machine';        
        


        $authority_ok = $model->authorityOk($data);
   
    
        // Saves the data in the session. 
        $app->setUserState($context . '.data', $data);
        $url = 'index.php?option=com_hr&view=employee_attendance&layout=edit';

      if ($authority_ok!=0)   {
   
          $dates_ok = $model->recordLogDateTime($data);
          $url = 'index.php?option=com_hr&view=employee_attendances';
          $this->setRedirect($url);
          

        
       }      
   //     }  
      if ($authority_ok==0){
   $this->setRedirect($url, $msg_authority_not_ok);
              return false;                
          }

            
    }
    
        
    
    
	protected function allowAdd($data = array())
	{
		$user = JFactory::getUser();
		$categoryId = JArrayHelper::getValue($data, 'catid', $this->input->getInt('filter_category_id'), 'int');
		$allow = null;
                $model = $this->getModel('employee_attendance');
                
   

		if ($categoryId)
		{
			// If the category has been passed in the URL check it.
			$allow = $user->authorise('core.create', $this->option . '.category.' . $categoryId);
		}

		if (($allow === null))
		{
			// In the absense of better information, revert to the component permissions.
			return parent::allowAdd($data);
		}
		else
		{
                //    $msg_authority_not_ok = 'You are not authorised to access Attendance on this machine';       
                //        $url = 'index.php?option=com_hr&view=employee_attendance&layout=edit';
             //       $this->setRedirect($url, $msg_authority_not_ok);
			return $allow;
		}
	}

	protected function allowEdit($data = array(), $key = 'id')
	{
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		$categoryId = 0;

		if ($recordId)
		{
			$categoryId = (int) $this->getModel()->getItem($recordId)->catid;
		}

		if ($categoryId)
		{
			// The category has been set. Check the category permissions.
			return JFactory::getUser()->authorise('core.edit', $this->option . '.category.' . $categoryId);
		}
		else
		{
			// Since there is no asset tracking, revert to the component permissions.
			return parent::allowEdit($data, $key);
		}
	}
}