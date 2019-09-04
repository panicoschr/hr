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

class HrViewEmp_entl_bals extends JViewLegacy
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

                        'entitlement'    => JText::_('COM_HR_ENTITLEMENT_ID_LABEL'),
                        'charge'     => JText::_('COM_HR_FIELD_ACCHARGE_LABEL'),
                        'balance'     => JText::_('COM_HR_FIELD_BALANCE_AMOUNT_LABEL'),          
                        'abs.measureunit'     => JText::_('COM_HR_FIELD_MEASUREUNIT_LABEL')             

		);
	}
}