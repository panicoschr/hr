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

JHtml::_('bootstrap.tooltip', '.hasTooltip', array('placement' => 'bottom'));

$function  = JFactory::getApplication()->input->getCmd('function', 'jEditMachinecat_' . (int) $this->item->id);

// Function to update input title when changed
JFactory::getDocument()->addScriptDeclaration('
	function jEditMachinecatModal() {
		if (window.parent && document.formvalidator.isValid(document.getElementById("item-form"))) {
			return window.parent.' . $this->escape($function) . '(document.getElementById("jform_title").value);
		}
	}
');
?>
<button id="applyBtn" type="button" class="hidden" onclick="Joomla.submitbutton('machinecat.apply'); jEditMachinecatModal();"></button>
<button id="saveBtn" type="button" class="hidden" onclick="Joomla.submitbutton('machinecat.save'); jEditMachinecatModal();"></button>
<button id="closeBtn" type="button" class="hidden" onclick="Joomla.submitbutton('machinecat.cancel');"></button>

<div class="container-popup">
	<?php $this->setLayout('edit'); ?>
	<?php echo $this->loadTemplate(); ?>
</div>