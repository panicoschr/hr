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
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select($db->quoteName(array('absence_id', 'employee_id', 'approver1_id', 'approver2_id', 'approver3_id', 'approver4_id', 'approver5_id')));
$query->from($db->quoteName('#__hr_employee_approver'));
//$abstype = $db->Quote($db->escape($this->item->abstype, true));
$query->where('(absence_id = '.$this->item->absence_id.' AND employee_id = '.$this->item->employee_id.')');
//$query->where('(id = '.$this->item->id.')');
$db->setQuery($query);
$results = $db->loadObjectList();


foreach ($results as $result) {
    if ($result->approver1_id == $user->id)
    {$position = 1;}
    elseif ($result->approver2_id == $user->id) {
        $position = 2;
    } elseif ($result->approver3_id == $user->id) {
        $position = 3;
    } elseif ($result->approver4_id == $user->id) {
        $position = 4;
    } elseif ($result->approver5_id == $user->id) {
        $position = 5;
    }
    else {
        $position = null;
    }
    

    
}
?>

<form action="<?php echo JRoute::_('index.php?option=com_hr&view=approve_employee_absence&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
		<div class="btn-toolbar">
			<div class="btn-group">
				<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('approve_employee_absence.save')">
					<i class="icon-new"></i> <?php echo JText::_('COM_HR_BUTTON_SAVE_AND_CLOSE') ?>
				</button>
			</div>
			<div class="btn-group">
				<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('approve_employee_absence.cancel')">
					<i class="icon-cancel"></i> <?php echo JText::_('JCANCEL') ?>
				</button>
			</div>
		</div>
	<div class="row-fluid">
		<div class="span10 form-horizontal">

	<fieldset>
		<?php echo JHtml::_('bootstrap.startPane', 'myTab', array('active' => 'details')); ?>

			<?php echo JHtml::_('bootstrap.addPanel', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_HR_NEW_FOLIO', true) : JText::sprintf('COM_HR_EDIT_FOLIO', $this->item->id, true)); ?>

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
            
                <div class="control-group">
                    <div class="control-label">
                        <?php
                        $name = "approval_status". $position;
                        $field = $this->form->getField($name);
                    //    echo $name;

                        echo $field->label;
                        ?>
                    </div>
                    <div class="controls">
                    <?php echo $field->input; ?>


                    </div>
                </div>            

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