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

JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');
$hierarchies = JFormHelper::loadFieldType('MyHierarchy', false);

$hierarchyOptions = $hierarchies->getOptions(); // works only if you set your field getOptions on public!!
//make sure user is logged in
if ($user->id == 0) {
    JFactory::getApplication()->enqueueMessage(JText::_('COM_HR_ERROR_MUST_LOGIN'), 'warning');
    $joomlaLoginUrl = 'index.php?option=com_users&view=login';

    echo "<br><a href='" . JRoute::_($joomlaLoginUrl) . "'>" . JText::_('COM_HR_LOG_IN') . "</a><br>";
} else {
    $listOrder = $this->escape($this->state->get('list.ordering'));
    $listDirn = $this->escape($this->state->get('list.direction'));
    ?>
    <form action="<?php echo JRoute::_('index.php?option=com_hr&view=apply_employee_absences'); ?>" method="post" name="adminForm" id="adminForm">
        <div class="btn-toolbar">
            <div class="btn-group">
                <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('apply_employee_absence.add')">
                    <i class="icon-new"></i> <?php echo JText::_('COM_HR_BUTTON_APPLY_FOR_LEAVE') ?>
                </button>


            </div>
        </div>
    <?php if (!empty($this->sidebar)) : ?>
            <div id="j-sidebar-container" class="span2">
            <?php echo $this->sidebar; ?>
            </div>
            <div id="j-main-container" class="span10">
    <?php else : ?>
                <div id="j-main-container">
            <?php endif; ?>
                <div id="filter-bar" class="btn-toolbar">
                    <div class="filter-search btn-group pull-left">
                        <label for="filter_search_employee" class="element-invisible"><?php //echo JText::_('COM_HR_SEARCH_EMPLOYEE'); ?></label>
                        <input type="text" name="filter_search_employee" id="filter_search_employee" placeholder="<?php echo JText::_('COM_HR_SEARCH_EMPLOYEE'); ?>" value="<?php echo $this->escape($this->state->get('filter.search_employee')); ?>" title="<?php echo JText::_('COM_HR_SEARCH_EMPLOYEE'); ?>" />
                    </div>                                  
                    <div class="filter-search btn-group pull-left">
                        <label for="filter_search_date_from" class="element-invisible"><?php //echo JText::_('COM_HR_SEARCH_IN_ROSTER_DATETIME_FROM'); ?></label>
                        <input type="text" name="filter_search_date_from" id="filter_search_date_from" placeholder="<?php echo JText::_('COM_HR_SEARCH_IN_ROSTER_DATETIME_FROM'); ?>" value="<?php echo $this->escape($this->state->get('filter.search_date_from')); ?>" title="<?php echo JText::_('COM_HR_SEARCH_IN_ROSTER_DATETIME_FROM'); ?>" />
                    </div>

                    <div class="filter-search btn-group pull-left">
                        <label for="filter_search_date_to" class="element-invisible"><?php //echo JText::_('COM_HR_SEARCH_IN_ROSTER_DATETIME_TO'); ?></label>
                        <input type="text" name="filter_search_date_to" id="filter_search_date_to" placeholder="<?php echo JText::_('COM_HR_SEARCH_IN_ROSTER_DATETIME_TO'); ?>" value="<?php echo $this->escape($this->state->get('filter.search_date_to')); ?>" title="<?php echo JText::_('COM_HR_SEARCH_IN_ROSTER_DATETIME_TO'); ?>" />
                    </div>


                    <div class="btn-group pull-left">
                        <button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search fa fa-search"></i></button>
                        <button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('filter_search_date_from').value = '';
                                    document.getElementById('filter_search_date_to').value = '';
                                    document.getElementById('filter_search_employee').value = '';
                                    this.form.submit();"><i class="icon-remove fa fa-remove"></i></button>
                    </div>


                    <div class="filter-search btn-group pull-left">                         
       <select name="filter_hierarchy" class="inputbox" onchange="this.form.submit()">
    <?php echo JHtml::_('select.options', $hierarchyOptions, 'value', 'text', $this->state->get('filter.hierarchy')); ?>
                        </select>
                    </div>  

                    <div class="btn-group pull-left">
                        <label for="limit" class="element-invisible"><?php //echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
    <?php echo $this->pagination->getLimitBox(); ?>
                    </div>   


                </div>
                <div class="clearfix"> </div>
                <table class="table table-striped" id="employee_absenceList">
                    <thead>
                        <tr>

                         
                            
                            
                            <th width="15%"class="nowrap has-context">
    <?php echo JHtml::_('grid.sort', 'COM_HR_FIELD_EMPLOYEE_LABEL', 'b.name', $listDirn, $listOrder); ?>
                            </th>	
                              <th width="5%"class="nowrap hidden-phone">
    <?php echo JHtml::_('grid.sort', 'COM_HR_FIELD_NO_OF_WORKING_W_LABEL', 'noofcalls', $listDirn, $listOrder); ?>
                            </th> 
                            
                              <th width="5%"class="nowrap hidden-phone">
    <?php echo JHtml::_('grid.sort', 'COM_HR_FIELD_CHARGE_AMOUNT_C_LABEL', 'accharge', $listDirn, $listOrder); ?>
                            </th>      

                            <th width="10%"class="nowrap has-context">
    <?php echo JHtml::_('grid.sort', 'COM_HR_FIELD_CHAR_FROM_LABEL', 'a.datetime_from', $listDirn, $listOrder); ?>
                            </th>
                            <th width="10%"class="nowrap has-context">
    <?php echo JHtml::_('grid.sort', 'COM_HR_FIELD_CHAR_TO_LABEL', 'a.datetime_to', $listDirn, $listOrder); ?>
                            </th>
                            <th width="10%"class="nowrap has-context">
    <?php echo JHtml::_('grid.sort', 'COM_HR_FIELD_ABSTYPE_LABEL', 'd.abstype', $listDirn, $listOrder); ?>
                            </th>                            

                            <th width="10%"class="nowrap has-context">
    <?php echo JHtml::_('grid.sort', 'COM_HR_FIELD_FINAL_APPROVAL_STATUS_LABEL', 'a.final_approval_status', $listDirn, $listOrder); ?>
                            </th>                                                


                            <th width="1%" class="nowrap center hidden-phone">
    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                            </th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="10">
    <?php echo $this->pagination->getListFooter(); ?>
                            </td>
                        </tr>
                    </tfoot>
                    <tbody>
    <?php
    foreach ($this->items as $i => $item) :
        $canCheckin = $user->authorise('core.manage', 'com_checkin');
        $canChange = $user->authorise('core.edit.state', 'com_hr') && $canCheckin;
        $canEdit = $user->authorise('core.edit', 'com_hr.category.' . $item->catid);
        ?>
                            <tr class="row<?php echo $i % 2; ?>" sortable-group-id="1">




                                <td class="has-context">
                                    <div class="pull-left break-word"> 
        <?php echo $item->name; ?>
                                    </div>
                                </td>          

                                
           
 
                                <td class="hidden-phone">
                                    <div class="pull-left break-word">

                                        <?php
                                        if ($item->measureunit == 'Days') {

if ((int) $this->escape($item->noofcalls) == 0)
{echo '';}
else
{echo (int) $this->escape($item->noofcalls);}   
                                        
                                            //            echo substr($item->datetime_from, 0, 16);
                                        } else {
                                            echo (int) $this->escape($item->sumofminutes);
                                        }
                                        ?>
                                        
                                    </div>
                                </td>                                                     

                                <td class="hidden-phone">
                                    <div class="pull-left break-word">
                                        <?php echo $this->escape($item->accharge); ?>


                                    </div>
                                </td>                                             
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                


                                <td class="has-context">
                                    <div class="pull-left break-word"> 

        <?php
        $local_datetime_from = hrHelper::getLocalTime($this->escape($item->datetime_from));

        if ($item->measureunit == 'Days') {
            echo substr($local_datetime_from, 0, 10);
        } else {

            echo substr($local_datetime_from, 0, 16);
            //    echo substr($item->datetime_from, 0, 16);
        }
        ?>                           
                                    </div>
                                </td>
                                <td class="has-context">
                                    <div class="pull-left break-word"> 
        <?php
        $local_datetime_to = hrHelper::getLocalTime($this->escape($item->datetime_to));
//////var_depr_dump($item->datetime_to);
//////var_depr_dump($local_datetime_from);

        if ($item->measureunit == 'Days') {

            $dateminusoneday = new DateTime($local_datetime_to);
            $dateminusoneday->modify('-1 day');
            $strdateminusoneday = date_format($dateminusoneday, 'Y-m-d');
            echo substr($strdateminusoneday, 0, 10);
            //      echo substr($item->datetime_to, 0, 16);
        } else {
            echo substr($local_datetime_to, 0, 16);
            // echo substr($item->datetime_to, 0, 16);
        }
        ?>
                                    </div>
                                </td>                                    


                                <td class="nowrap has-context">
                                    <div class="pull-left break-word"> 

        <?php
        if ((($item->approval_status1 != 'Pending') && ($item->approval_status1 != null)) ||
                ($item->employee_id != $user->id)) {

            $canEditAbsence = 0;
        } else {
            $canEditAbsence = 1;
        }
        ?>                      

                                        <?php if (($canEdit) && ($canEditAbsence)) : ?>
                                            <a href="<?php echo JRoute::_('index.php?option=com_hr&task=apply_employee_absence.edit&id=' . (int) $item->id); ?>">
                                            <?php echo $this->escape($item->abstype); ?>
                                            </a>
                                        <?php else : ?>
                                                <?php echo $this->escape($item->abstype); ?>
                                            <?php endif; ?>
                                    </div>
                                </td>

                                <td class="has-context">
                                    <div class="pull-left break-word"> 
        <?php echo $this->escape($item->final_approval_status); ?>

                                    </div>
                                </td>                                                      


                                <td class="center hidden-phone">
        <?php echo (int) $item->id; ?>
                                </td>
                            </tr>
                                <?php endforeach; ?>
                    </tbody>
                </table>

                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
                <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
                <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
    <?php echo JHtml::_('form.token'); ?>
            </div>
    </form>



    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById("jform_idmeasureunitandid").value = document.getElementById("jform_idmeasureunit").value.concat('the id is').concat(document.getElementById("jform_id_absence_id").value);
            var abstype = document.getElementById("jform_idmeasureunitandid").value;
            if ((abstype.indexOf("Days") > -1))
            {
                enableFields();
                enableCalendarFields();
                setDateFormat();
            } else if ((abstype.indexOf("Hours_minutes") > -1))
            {
                enableFields();
                disableCalendarFields();
                setDateTimeFormat();
            } else
            {
                disableFields();
                disableCalendarFields();
            }
        }, false);

    </script>

    <?php
}