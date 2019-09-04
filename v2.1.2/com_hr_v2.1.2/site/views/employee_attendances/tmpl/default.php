
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
$hierarchyOptions = $hierarchies->getOptions(); 
$groupings = JFormHelper::loadFieldType('MyGrouping', false);
$groupingOptions = $groupings->getOptions(); 

//make sure user is logged in
if ($user->id == 0) {
    JFactory::getApplication()->enqueueMessage(JText::_('COM_HR_ERROR_MUST_LOGIN'), 'warning');
    $joomlaLoginUrl = 'index.php?option=com_users&view=login';

    echo "<br><a href='" . JRoute::_($joomlaLoginUrl) . "'>" . JText::_('COM_HR_LOG_IN') . "</a><br>";
} else {
    $listOrder = $this->escape($this->state->get('list.ordering'));
    $listDirn = $this->escape($this->state->get('list.direction'));
    $groupselected = $this->escape($this->state->get('filter.grouping'));
    ?>
    <form action="<?php echo JRoute::_('index.php?option=com_hr&view=employee_attendances'); ?>" method="post" name="adminForm" id="adminForm">
        <div class="btn-toolbar">
            <div class="btn-group">
                <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('employee_attendance.add')">
                    <i class="icon-new"></i> <?php echo JText::_('RECORD MY ATTENDANCE') ?>
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



                    <div class="filter-search btn-group pull-left">                         
       <select name="filter_hierarchy" class="inputbox" onchange="this.form.submit()">
    <?php echo JHtml::_('select.options', $hierarchyOptions, 'value', 'text', $this->state->get('filter.hierarchy')); ?>
                        </select>
                    </div>       

                    <div class="filter-search btn-group pull-left">                         
       <select name="filter_grouping" class="inputbox" onchange="this.form.submit()">
    <?php echo JHtml::_('select.options', $groupingOptions, 'value', 'text', $this->state->get('filter.grouping')); ?>
                        </select>
                    </div>                          
                    
                                   

                    <div class="btn-group pull-left">
                        <button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search fa fa-search"></i></button>
                        <button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('filter_search_date_from').value = '';
                            document.getElementById('filter_search_date_to').value = '';
                            document.getElementById('filter_search_employee').value = '';
this.form.submit();"><i class="icon-remove fa fa-remove"></i></button>
                    </div>




                    <div class="btn-group pull-left">
                        <label for="limit" class="element-invisible"><?php// echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
                        <?php echo $this->pagination->getLimitBox(); ?>
                    </div>   

                </div>
                <div class="clearfix"> </div>
                <table class="table table-striped" id="employee_attendanceList">
                    <thead>
                        <tr>


                            <th width="15%"class="nowrap has-context">
    <?php echo JHtml::_('grid.sort', 'COM_HR_FIELD_EMPLOYEE_LABEL', 'u.name', $listDirn, $listOrder); ?>
                            </th>	                      
                            <th width="30%"class="nowrap has-context">
                                <?php echo JHtml::_('grid.sort', 'COM_HR_FIELD_LOGIN_LABEL', 'a.log_in', $listDirn, $listOrder); ?>
                            </th>
                            <th width="30%"class="nowrap has-context">
                                <?php echo JHtml::_('grid.sort', 'COM_HR_FIELD_LOGOUT_LABEL', 'a.log_out', $listDirn, $listOrder); ?>
                            </th>

                            <th width="15%"class="nowrap has-context">
                                <?php echo JHtml::_('grid.sort', 'COM_HR_FIELD_DURATION_LABEL', 'totduration', $listDirn, $listOrder); ?>
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

                                <td class="has-context">
                                    <div class="pull-left break-word"> 
        <?php
        /*
          if ($item->log_in == '0000-00-00 00:00:00' ) {
          echo 'Not Applicable';
          }

          else
          {



          $local_log_in = hrHelper::getLocalTime($this->escape($item->log_in));
          echo $this->escape($local_log_in);

          }
         * 
         * */
        if (($this->escape($item->log_in) != '0000-00-00 00:00:00')) {

            $local_log_in = hrHelper::getLocalTime($this->escape($item->log_in));


            if (($groupselected == 'Detail') || (($groupselected != 'Detail') && ($groupselected != 'Summary'))) {
                echo $this->escape(substr($local_log_in, 0, strlen($local_log_in) - 3));
            }
        } else {
            if (($groupselected == 'Detail') || (($groupselected != 'Detail') && ($groupselected != 'Summary'))) {
                echo $this->escape($item->log_in);
            }
        }
        ?> 
                                    </div>
                                </td>
                                <td class="has-context">
                                    <div class="pull-left break-word"> 
                                        <?php
                                        /*


                                          if ($item->log_out == '0000-00-00 00:00:00' ) {
                                          echo 'Pending';
                                          }

                                          else
                                          {
                                          $local_log_out = hrHelper::getLocalTime($this->escape($item->log_out));
                                          echo $this->escape($local_log_out);
                                          }
                                         * 
                                         * */
                                        if (($this->escape($item->log_out) != '0000-00-00 00:00:00')) {

                                            $local_log_out = hrHelper::getLocalTime($this->escape($item->log_out));
                                            if (($groupselected == 'Detail') || (($groupselected != 'Detail') && ($groupselected != 'Summary'))) {
                                                echo $this->escape(substr($local_log_out, 0, strlen($local_log_out) - 3));
                                            }
                                        } else {
                                            if (($groupselected == 'Detail') || (($groupselected != 'Detail') && ($groupselected != 'Summary'))) {
                                                echo $this->escape($item->log_out);
                                            }
                                        }
                                        ?> 
                                    </div>
                                </td>

                                <td class="has-context">
                                    <div class="pull-left break-word"> 
                                        <?php
                                        $hours = intval($item->minduration / 60);
                                        $minutes = $item->minduration - ($hours * 60);

                                        if (strlen($hours) == '1') {
                                            $hours = '0' . $hours;
                                        }
                                        if (strlen($minutes) == '1') {
                                            $minutes = '0' . $minutes;
                                        }

                                        echo $hours . ':' . $minutes;
                                        ?>

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

    <?php
}