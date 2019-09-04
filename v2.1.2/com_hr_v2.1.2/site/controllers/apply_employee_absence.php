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
class HrControllerApply_employee_absence extends JControllerForm
{
	protected function allowAdd($data = array())
	{
		$user = JFactory::getUser();
		$categoryId = JArrayHelper::getValue($data, 'catid', $this->input->getInt('filter_category_id'), 'int');
		$allow = null;

		if ($categoryId)
		{
			// If the category has been passed in the URL check it.
			$allow = $user->authorise('core.create', $this->option . '.category.' . $categoryId);
		}

		if ($allow === null)
		{
			// In the absense of better information, revert to the component permissions.
			return parent::allowAdd($data);
		}
		else
		{
			return $allow;
		}
	}

	protected function allowEdit($data = array(), $key = 'id')
	{
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
          //   jexit();        
              
		$categoryId = 0;

		if ($recordId)
		{
			$categoryId = (int) $this->getModel()->getItem($recordId)->catid;
		}

                
		if ($categoryId)
		{
			// The category has been set. Check the category permissions.
			return JFactory::getUser()->authorise('core.edit', $this->option . '.category.' . $categoryId);
//$f = JFactory::getUser()->authorise('core.edit', $this->option . '.category.' . $categoryId);    
                //    return false;
                     
                        
		}
		else
		{
			// Since there is no asset tracking, revert to the component permissions.
			return parent::allowEdit($data, $key);
		}
	}
        
        

        
        
        
       public function save($key = null, $urlVar = null) {
        // Check for request forgeries.
        //	JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $lang  = JFactory::getLanguage();
        $model = $this->getModel('apply_employee_absence');
        $table = $model->getTable();
        $data = $this->input->post->get('jform', array(), 'array');
       	$checkin = property_exists($table, 'checked_out');
        $context = "$this->option.edit.$this->context";
	$task = $this->getTask();


       
     //   ////var_depr_dump($data);
     //   
   //     jexit();
        $msg_date2_to_greater_than_date1 = 'From Date (&Time) can not be greater than To Date (&Time)';
        $msg_dates_overlapping = 'This absence overlapps with other absence(s) ';
        
        $dates_ok = $model->checkDates($data);
        $overlapping = $model->checkOverlappingAbsences($data);
         
        // Saves the data in the session. 
        $app->setUserState($context . '.data', $data);
        $url = 'index.php?option=com_hr&view=apply_employee_absence&layout=edit';

        if (($dates_ok) && ($overlapping==0)) {
            parent::save($data, $urlVar);
        } else {
        if (!$dates_ok){
            $this->setRedirect($url, $msg_date2_to_greater_than_date1);
        return false;
        
        }
        if ($overlapping!= 0) {
            $this->setRedirect($url, $msg_dates_overlapping. ' '.$overlapping );
            return false;
            
        }
        
        }

        
    }     
    
    
    
    
    
    
}