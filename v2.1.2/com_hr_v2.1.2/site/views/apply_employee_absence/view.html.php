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

class HrViewApply_employee_absence extends JViewLegacy
{
	protected $item;

	protected $form;

	public function display($tpl = null)
	{
	require_once JPATH_COMPONENT.'/helpers/hr.php'; 
        
                JHtml::script(JURI::root() . 'media/com_hr/js/validate_hr.js', true);
             //   JHtml::script(JURI::root() . 'media/com_hr/js/jquery-1.11.3.min.js', true);
                JHtml::script(JURI::root() . 'media/com_hr/js/jquery-3.1.1.js', true);                
                JHtml::script(JURI::root() . 'media/com_hr/js/jquery.maskedinput.js', true);
          
          
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');

		if (count($errors = $this->get('Errors')))
		{
			JFactory::getApplication()->enqueueMessage(implode("\n", $errors), 'error');
			return false;
		}

		parent::display($tpl);
	}
}