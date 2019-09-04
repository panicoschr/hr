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

  

$user = JFactory::getUser();


//$this->form->setValue('employee_id', null, $user->id);

//$status = 'Pending';

?>

<form action="<?php echo JRoute::_('index.php?option=com_hr&view=apply_employee_absence&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
		<div class="btn-toolbar">
<div class="btn-group">
				<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('apply_employee_absence.save')">
					<i class="icon-new"></i> <?php echo JText::_('COM_HR_BUTTON_SUBMIT_AND_CLOSE') ?>
				</button>
			</div>
			<div class="btn-group">
				<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('apply_employee_absence.cancel')">
					<i class="icon-cancel"></i> <?php echo JText::_('COM_HR_BUTTON_CANCEL') ?>
				</button>
			</div>
		</div>
	<div class="row-fluid">
		<div class="span10 form-horizontal">

	<fieldset>
		<?php echo JHtml::_('bootstrap.startPane', 'myTab', array('active' => 'details')); ?>

			<?php echo JHtml::_('bootstrap.addPanel', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_HR_NEW_EMPLOYEE_ABSENCE', true) : JText::sprintf('COM_HR_EDIT_EMPLOYEE_ABSENCE', $this->item->id, true)); ?>

				<?php foreach ($this->form->getFieldset('myfields') as $field) : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $field->label; ?>
						</div>
						<div class="controls">
							<?php echo $field->input; ?>
                                           
						</div>
					</div>
				<?php endforeach; ?>

			<?php echo JHtml::_('bootstrap.endPanel'); ?>

			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>

		<?php echo JHtml::_('bootstrap.endPane'); ?>
		</fieldset>
		</div>
	</div>
</form>

  <script type="text/javascript">
   document.addEventListener('DOMContentLoaded', function () {
        document.getElementById("jform_idmeasureunitandid").value = document.getElementById("jform_idmeasureunit").value.concat('the id is').concat(document.getElementById("jform_id_absence_id").value);
        var abstype = document.getElementById("jform_idmeasureunitandid").value;
        if ((abstype.indexOf("Days") > -1))
        {
            enableFields();
       //     enableCalendarFields();
            setDateFormat9999();
        }
       else if ((abstype.indexOf("Hours_minutes") > -1))
    {       
            enableFields();
       //     disableCalendarFields();
            setDateTimeFormat9999();
        } else
        {
            disableFields();
         //   disableCalendarFields();
        }
    }, false);

</script>





