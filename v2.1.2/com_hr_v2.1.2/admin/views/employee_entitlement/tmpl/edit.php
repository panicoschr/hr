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

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select', null, array('disable_search_threshold' => 0 ));

$this->configFieldsets  = array('editorConfig');
$this->hiddenFieldsets  = array('basic-limited');
$this->ignore_fieldsets = array('jmetadata', 'item_associations');

// Create shortcut to parameters.
$params = $this->state->get('params');

$app = JFactory::getApplication();
$input = $app->input;

$assoc = JLanguageAssociations::isEnabled(); $assoc = false;  $lang  = false; $confeditscreen = false; //added by panikos to remove association and language 

// This checks if the config options have ever been saved. If they haven't they will fall back to the original settings.
$params = json_decode($params);
$editoroptions = isset($params->show_publishing_options);

if (!$editoroptions)
{
	$params->show_publishing_options = '1';
	$params->show_employee_entitlement_options = '1';
	$params->show_urls_images_backend = '0';
	$params->show_urls_images_frontend = '0';
}

// Check if the employee_entitlement uses configuration settings besides global. If so, use them.
if (isset($this->item->attribs['show_publishing_options']) && $this->item->attribs['show_publishing_options'] != '')
{
	$params->show_publishing_options = $this->item->attribs['show_publishing_options'];
}

if (isset($this->item->attribs['show_employee_entitlement_options']) && $this->item->attribs['show_employee_entitlement_options'] != '')
{
	$params->show_employee_entitlement_options = $this->item->attribs['show_employee_entitlement_options'];
}

if (isset($this->item->attribs['show_urls_images_frontend']) && $this->item->attribs['show_urls_images_frontend'] != '')
{
	$params->show_urls_images_frontend = $this->item->attribs['show_urls_images_frontend'];
}

if (isset($this->item->attribs['show_urls_images_backend']) && $this->item->attribs['show_urls_images_backend'] != '')
{
	$params->show_urls_images_backend = $this->item->attribs['show_urls_images_backend'];
}



JFactory::getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function(task)
	{
		if (task == "employee_entitlement.cancel" || document.formvalidator.isValid(document.getElementById("item-form")))
		{
			jQuery("#permissions-sliders select").attr("disabled", "disabled");
			' . $this->form->getField('employee_entitlementtext')->save() . '
	                          
			Joomla.submitform(task, document.getElementById("item-form"));

			if (task !== "employee_entitlement.apply")
			{
				window.parent.jQuery("#employee_entitlementEdit' . (int) $this->item->id . 'Modal").modal("hide");
			}
		}
	};
');

// In case of modal
$isModal = $input->get('layout') == 'modal' ? true : false;
$layout  = $isModal ? 'modal' : 'edit';
$tmpl    = $isModal || $input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';
?>



<form action="<?php echo JRoute::_('index.php?option=com_hr&layout=' . $layout . $tmpl . '&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">

	
<?php echo JLayoutHelper::render('components.com_hr.helpers.edit.employee_entitlement',  $this, JPATH_ADMINISTRATOR); ?>      
 
 
    
	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_HR_EMPLOYEE_ENTITLEMENT_CONTENT')); ?>
		<div class="row-fluid">
			<div class="span9">
				<fieldset class="adminform">
					<?php echo $this->form->getInput('employee_entitlementtext'); ?>
				</fieldset>
			</div>
			<div class="span3">
				<?php //echo JLayoutHelper::render('joomla.edit.global', $this); ?>
                                <?php echo JLayoutHelper::render('components.com_hr.helpers.edit.global',  $this, JPATH_ADMINISTRATOR); ?>  
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php // Do not show the images and links options if the edit form is configured not to. ?>
		<?php if ($params->show_urls_images_backend == 1) : ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'images', JText::_('COM_HR_FIELDSET_URLS_AND_IMAGES')); ?>
			<div class="row-fluid form-horizontal-desktop">
				<div class="span6">
					<?php echo $this->form->renderField('images'); ?>
					<?php foreach ($this->form->getGroup('images') as $field) : ?>
						<?php echo $field->renderField(); ?>
					<?php endforeach; ?>
				</div>
				<div class="span6">
					<?php foreach ($this->form->getGroup('urls') as $field) : ?>
						<?php echo $field->renderField(); ?>
					<?php endforeach; ?>
				</div>
			</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php endif; ?>

		<?php $this->show_options = $params->show_employee_entitlement_options; ?>
		<?php // echo JLayoutHelper::render('joomla.edit.params', $this);  added by panikos to remove options - params ?> 

		<?php // Do not show the publishing options if the edit form is configured not to. ?>
		<?php if ($params->show_publishing_options == 1) : ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('COM_HR_FIELDSET_PUBLISHING')); ?>
			<div class="row-fluid form-horizontal-desktop">
				<div class="span6">
					<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
				</div>
				<div class="span6">
					<?php // echo JLayoutHelper::render('joomla.edit.metadata', $this); added by panikos to remove metadata ?> 
				</div>
			</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php endif; ?>


		<?php if ( ! $isModal && $assoc) : ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'associations', JText::_('JGLOBAL_FIELDSET_ASSOCIATIONS')); ?>
			<?php echo $this->loadTemplate('associations'); ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php elseif ($isModal && $assoc) : ?>
			<div class="hidden"><?php echo $this->loadTemplate('associations'); ?></div>
		<?php endif; ?>

		<?php if (($this->canDo->get('core.admin')) && ($confeditscreen == true)) : ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'editor', JText::_('COM_HR_SLIDER_EDITOR_CONFIG')); ?>
			<?php echo $this->form->renderFieldset('editorConfig'); ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php endif; ?>

		<?php if ($this->canDo->get('core.admin')) : ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('COM_HR_FIELDSET_RULES')); ?>
				<?php echo $this->form->getInput('rules'); ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php endif; ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo $input->getCmd('return'); ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>



<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {

       
        
        document.getElementById("jform_idmeasureunitandid").value = document.getElementById("jform_idmeasureunit").value.concat('the id is').concat(document.getElementById("jform_id_absence_id").value);
        var abstype = document.getElementById("jform_idmeasureunitandid").value;
       
        if ((abstype.indexOf("Days") > -1))
        {
        //    disableCalendarFields();            
           enableFields();

            setDateFormat9999();            
       //     setDateFormat();
        }
       else if ((abstype.indexOf("Hours_minutes") > -1))
    {       
          //  disableCalendarFields();            
            enableFields();
            setDateTimeFormat9999();            
      //      setDateTimeFormat();
        } else
        {
         disableFields();
    //        disableCalendarFields();
        }
    }, false);

</script>