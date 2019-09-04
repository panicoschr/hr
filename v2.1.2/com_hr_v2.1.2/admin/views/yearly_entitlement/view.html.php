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

/**
 * View to edit an yearly_entitlement.
 *
 * @since  1.6
 */
class HrViewYearly_entitlement extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $state;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @since   1.6
	 */
	public function display($tpl = null)
	{
            
            
     JHtml::script(JURI::root() . 'media/com_hr/js/validate_hr.js', true);
             //   JHtml::script(JURI::root() . 'media/com_hr/js/jquery-1.11.3.min.js', true);
                JHtml::script(JURI::root() . 'media/com_hr/js/jquery-3.1.1.js', true);                
                JHtml::script(JURI::root() . 'media/com_hr/js/jquery.maskedinput.js', true);
                            
            
		if ($this->getLayout() == 'pagebreak')
		{
			// TODO: This is really dogy - should change this one day.
			$input = JFactory::getApplication()->input;
			$eName = $input->getCmd('e_name');
			$eName    = preg_replace('#[^A-Z0-9\-\_\[\]]#i', '', $eName);
			$document = JFactory::getDocument();
			$document->setTitle(JText::_('COM_HR_PAGEBREAK_DOC_TITLE'));
			$this->eName = &$eName;
			parent::display($tpl);

			return;
		}

		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');
		$this->canDo = HrHelper::getActions('com_hr', 'yearly_entitlement', $this->item->id);

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JFactory::getApplication()->enqueueMessage(implode("\n", $errors), 'error');

			return false;
		}

		if ($this->getLayout() == 'modal')
		{
			$this->form->setFieldAttribute('language', 'readonly', 'true');
			$this->form->setFieldAttribute('catid', 'readonly', 'true');
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$user       = JFactory::getUser();
		$userId     = $user->get('id');
		$isNew      = ($this->item->id == 0);
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);

		// Built the actions for new and existing records.
		$canDo = $this->canDo;

		JToolbarHelper::title(
			JText::_('COM_HR_PAGE_' . ($checkedOut ? 'VIEW_YEARLY_ENTITLEMENT' : ($isNew ? 'ADD_YEARLY_ENTITLEMENT' : 'EDIT_YEARLY_ENTITLEMENT'))),
			'pencil-2 yearly_entitlement-add'
		);

		// For new records, check the create permission.
		if ($isNew && (count($user->getAuthorisedCategories('com_hr', 'core.create')) > 0))
		{
			JToolbarHelper::apply('yearly_entitlement.apply');
			JToolbarHelper::save('yearly_entitlement.save');
			JToolbarHelper::save2new('yearly_entitlement.save2new');
			JToolbarHelper::cancel('yearly_entitlement.cancel');
		}
		else
		{
			// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
			$itemEditable = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId);

			// Can't save the record if it's checked out and editable
			if (!$checkedOut && $itemEditable)
			{
				JToolbarHelper::apply('yearly_entitlement.apply');
				JToolbarHelper::save('yearly_entitlement.save');

				// We can save this record, but check the create permission to see if we can return to make a new one.
				if ($canDo->get('core.create'))
				{
					JToolbarHelper::save2new('yearly_entitlement.save2new');
				}
			}

			// If checked out, we can still save
			if ($canDo->get('core.create'))
			{
				JToolbarHelper::save2copy('yearly_entitlement.save2copy');
			}

			if ($this->state->params->get('save_history', 0) && $itemEditable)
			{
				JToolbarHelper::versions('com_hr.yearly_entitlement', $this->item->id);
			}

			JToolbarHelper::cancel('yearly_entitlement.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_CONTENT_YEARLY_ENTITLEMENT_MANAGER_EDIT');
	}
}
