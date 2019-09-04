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

class HrViewApprove_employee_absences extends JViewLegacy
{
	protected $items;

	protected $state;

	protected $pagination;
        
        protected $listitem;    
        
        protected $hierarchy;        

	public function display($tpl = null)
	{
            		require_once JPATH_COMPONENT.'/helpers/hr.php';              
		$this->items		= $this->get('Items');
		$this->state		= $this->get('State');
		$this->pagination	= $this->get('Pagination');
                $this->listitem		= $this->get('Listitem');                
                $this->hierarchy	= $this->get('Hierarchy');                  

		if (count($errors = $this->get('Errors')))
		{
			JFactory::getApplication()->enqueueMessage(implode("\n", $errors), 'error');
			return false;
		}

		parent::display($tpl);
	}

	protected function getSortFields()
	{
		return array(
			'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'a.state' => JText::_('JSTATUS'),
			'a.title' => JText::_('JGLOBAL_TITLE'),
                    
                        'a.employee_id' => JText::_('JGLOBAL_TITLE'),
                    	'a.datetime_from' => JText::_('JGLOBAL_TITLE'),
                    	'a.datetime_to' => JText::_('JGLOBAL_TITLE'),
                    	'a.absence_id' => JText::_('JGLOBAL_TITLE'),
  			'a.measureunit' => JText::_('JGLOBAL_TITLE'),
                    	'a.approver1_id' => JText::_('JGLOBAL_TITLE'),
                    	'a.approver2_id' => JText::_('JGLOBAL_TITLE'),
                    	'a.approver3_id' => JText::_('JGLOBAL_TITLE'),                  
			'a.approver4_id' => JText::_('JGLOBAL_TITLE'),
                    	'a.approver5_id' => JText::_('JGLOBAL_TITLE'),
                    	'a.approval_status1' => JText::_('JGLOBAL_TITLE'),
                    
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}