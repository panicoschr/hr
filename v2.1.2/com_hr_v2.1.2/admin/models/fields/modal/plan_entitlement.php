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
 * Supports a modal plan_entitlement picker.
 *
 * @since  1.6
 */
class JFormFieldModal_Plan_entitlement extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var     string
	 * @since   1.6
	 */
	protected $type = 'Modal_Plan_entitlement';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.6
	 */
	protected function getInput()
	{
		$allowEdit  = ((string) $this->element['edit'] == 'true') ? true : false;
		$allowClear = ((string) $this->element['clear'] != 'false') ? true : false;

		// Load language
		JFactory::getLanguage()->load('com_hr', JPATH_ADMINISTRATOR);

		// The active plan_entitlement id field.
		$value = (int) $this->value > 0 ? (int) $this->value : '';

		// Build the script.
		$script = array();

		// Select button script
		$script[] = '	function jSelectPlan_entitlement_' . $this->id . '(id, title, catid, object) {';
		$script[] = '		document.getElementById("' . $this->id . '_id").value = id;';
		$script[] = '		document.getElementById("' . $this->id . '_name").value = title;';

		if ($allowEdit)
		{
			$script[] = '		if (id == "' . $value . '") {';
			$script[] = '			jQuery("#' . $this->id . '_edit").removeClass("hidden");';
			$script[] = '		} else {';
			$script[] = '			jQuery("#' . $this->id . '_edit").addClass("hidden");';
			$script[] = '		}';
		}

		if ($allowClear)
		{
			$script[] = '		jQuery("#' . $this->id . '_clear").removeClass("hidden");';
		}

		$script[] = '		jQuery("#plan_entitlementSelect' . $this->id . 'Modal").modal("hide");';

		if ($this->required)
		{
			$script[] = '		document.formvalidator.validate(document.getElementById("' . $this->id . '_id"));';
			$script[] = '		document.formvalidator.validate(document.getElementById("' . $this->id . '_name"));';
		}

		$script[] = '	}';

		// Edit button script
		$script[] = '	function jEditPlan_entitlement_' . $value . '(title) {';
		$script[] = '		document.getElementById("' . $this->id . '_name").value = title;';
		$script[] = '	}';

		// Clear button script
		static $scriptClear;

		if ($allowClear && !$scriptClear)
		{
			$scriptClear = true;

			$script[] = '	function jClearPlan_entitlement(id) {';
			$script[] = '		document.getElementById(id + "_id").value = "";';
			$script[] = '		document.getElementById(id + "_name").value = "' .
				htmlspecialchars(JText::_('COM_HR_SELECT_AN_PLAN_ENTITLEMENT', true), ENT_COMPAT, 'UTF-8') . '";';
			$script[] = '		jQuery("#"+id + "_clear").addClass("hidden");';
			$script[] = '		if (document.getElementById(id + "_edit")) {';
			$script[] = '			jQuery("#"+id + "_edit").addClass("hidden");';
			$script[] = '		}';
			$script[] = '		return false;';
			$script[] = '	}';
		}

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Setup variables for display.
		$html = array();

		$linkPlan_entitlements = 'index.php?option=com_hr&amp;view=plan_entitlements&amp;layout=modal&amp;tmpl=component'
			. '&amp;function=jSelectPlan_entitlement_' . $this->id;

		$linkPlan_entitlement  = 'index.php?option=com_hr&amp;view=plan_entitlement&amp;layout=modal&amp;tmpl=component'
			. '&amp;task=plan_entitlement.edit'
			. '&amp;function=jEditPlan_entitlement_' . $value;

		if (isset($this->element['language']))
		{
			$linkPlan_entitlements .= '&amp;forcedLanguage=' . $this->element['language'];
			$linkPlan_entitlement  .= '&amp;forcedLanguage=' . $this->element['language'];
			$modalTitle    = JText::_('COM_HR_CHANGE_PLAN_ENTITLEMENT') . ' &#8212; ' . $this->element['label'];
		}
		else
		{
			$modalTitle    = JText::_('COM_HR_CHANGE_PLAN_ENTITLEMENT');
		}

		$urlSelect = $linkPlan_entitlements . '&amp;' . JSession::getFormToken() . '=1';
		$urlEdit   = $linkPlan_entitlement . '&amp;id=' . $value . '&amp;' . JSession::getFormToken() . '=1';

		if ($value)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->quoteName('title'))
				->from($db->quoteName('#__hr_plan_entitlement'))
				->where($db->quoteName('id') . ' = ' . (int) $value);
			$db->setQuery($query);

			try
			{
				$title = $db->loadResult();
			}
			catch (RuntimeException $e)
			{
				JFactory::getApplication()->enqueueMessage(JText::_($e->getMessage()), 'warning');
			}
		}

		if (empty($title))
		{
			$title = JText::_('COM_HR_SELECT_AN_PLAN_ENTITLEMENT');
		}

		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		// The current plan_entitlement display field.
		$html[] = '<span class="input-append">';
		$html[] = '<input class="input-medium" id="' . $this->id . '_name" type="text" value="' . $title . '" disabled="disabled" size="35" />';

		// Select plan_entitlement button
		$html[] = '<a'
			. ' class="btn hasTooltip"'
			. ' data-toggle="modal"'
			. ' role="button"'
			. ' href="#plan_entitlementSelect' . $this->id . 'Modal"'
			. ' title="' . JHtml::tooltipText('COM_HR_CHANGE_PLAN_ENTITLEMENT') . '">'
			. '<span class="icon-file"></span> ' . JText::_('JSELECT')
			. '</a>';

		// Edit plan_entitlement button
		if ($allowEdit)
		{
			$html[] = '<a'
				. ' class="btn hasTooltip' . ($value ? '' : ' hidden') . '"'
				. ' id="' . $this->id . '_edit"'
				. ' data-toggle="modal"'
				. ' role="button"'
				. ' href="#plan_entitlementEdit' . $value . 'Modal"'
				. ' title="' . JHtml::tooltipText('COM_HR_EDIT_PLAN_ENTITLEMENT') . '">'
				. '<span class="icon-edit"></span> ' . JText::_('JACTION_EDIT')
				. '</a>';
		}

		// Clear plan_entitlement button
		if ($allowClear)
		{
			$html[] = '<button'
				. ' class="btn' . ($value ? '' : ' hidden') . '"'
				. ' id="' . $this->id . '_clear"'
				. ' onclick="return jClearPlan_entitlement(\'' . $this->id . '\')">'
				. '<span class="icon-remove"></span>' . JText::_('JCLEAR')
				. '</button>';
		}

		$html[] = '</span>';

		// Select plan_entitlement modal
		$html[] = JHtml::_(
			'bootstrap.renderModal',
			'plan_entitlementSelect' . $this->id . 'Modal',
			array(
				'title'       => $modalTitle,
				'url'         => $urlSelect,
				'height'      => '400px',
				'width'       => '800px',
				'bodyHeight'  => '70',
				'modalWidth'  => '80',
				'footer'      => '<a type="button" class="btn" data-dismiss="modal" aria-hidden="true">'
						. JText::_("JLIB_HTML_BEHAVIOR_CLOSE") . '</a>',
			)
		);

		// Edit plan_entitlement modal
		$html[] = JHtml::_(
			'bootstrap.renderModal',
			'plan_entitlementEdit' . $value . 'Modal',
			array(
				'title'       => JText::_('COM_HR_EDIT_PLAN_ENTITLEMENT'),
				'backdrop'    => 'static',
				'keyboard'    => false,
				'closeButton' => false,
				'url'         => $urlEdit,
				'height'      => '400px',
				'width'       => '800px',
				'bodyHeight'  => '70',
				'modalWidth'  => '80',
				'footer'      => '<a type="button" class="btn" data-dismiss="modal" aria-hidden="true"'
						. ' onclick="jQuery(\'#plan_entitlementEdit' . $value . 'Modal iframe\').contents().find(\'#closeBtn\').click();">'
						. JText::_("JLIB_HTML_BEHAVIOR_CLOSE") . '</a>'
						. '<button type="button" class="btn btn-primary" aria-hidden="true"'
						. ' onclick="jQuery(\'#plan_entitlementEdit' . $value . 'Modal iframe\').contents().find(\'#saveBtn\').click();">'
						. JText::_("JSAVE") . '</button>'
						. '<button type="button" class="btn btn-success" aria-hidden="true"'
						. ' onclick="jQuery(\'#plan_entitlementEdit' . $value . 'Modal iframe\').contents().find(\'#applyBtn\').click();">'
						. JText::_("JAPPLY") . '</button>',
			)
		);

		// Note: class='required' for client side validation.
		$class = $this->required ? ' class="required modal-value"' : '';

		$html[] = '<input type="hidden" id="' . $this->id . '_id"' . $class . ' name="' . $this->name . '" value="' . $value . '" />';

		return implode("\n", $html);
	}

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 *
	 * @since   3.4
	 */
	protected function getLabel()
	{
		return str_replace($this->id, $this->id . '_id', parent::getLabel());
	}
}
