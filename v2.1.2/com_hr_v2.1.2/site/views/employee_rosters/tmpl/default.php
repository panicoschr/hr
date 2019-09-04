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

$user		= JFactory::getUser();
JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');
$hierarchies = JFormHelper::loadFieldType('MyHierarchy', false);

$hierarchyOptions = $hierarchies->getOptions(); // works only if you set your field getOptions on public!!

//make sure user is logged in
if($user->id == 0)
{
	JFactory::getApplication()->enqueueMessage(JText::_('COM_HR_ERROR_MUST_LOGIN'), 'warning');
	$joomlaLoginUrl = 'index.php?option=com_users&view=login';

	echo "<br><a href='".JRoute::_($joomlaLoginUrl)."'>".JText::_( 'COM_HR_LOG_IN')."</a><br>";
}
else
{
	$listOrder	= $this->escape($this->state->get('list.ordering'));
	$listDirn	= $this->escape($this->state->get('list.direction'));
?>
	<form action="<?php echo JRoute::_('index.php?option=com_hr&view=employee_rosters'); ?>" method="post" name="adminForm" id="adminForm">

		<?php if (!empty( $this->sidebar)) : ?>
			<div id="j-sidebar-container" class="span2">
				<?php echo $this->sidebar; ?>
			</div>
			<div id="j-main-container" class="span10">
		<?php else : ?>
			<div id="j-main-container">
		<?php endif;?>
			<div id="filter-bar" class="btn-toolbar">
                            
				<div class="filter-search btn-group pull-left">
					<label for="filter_search_employee" class="element-invisible"><?php //echo JText::_('COM_HR_SEARCH_EMPLOYEE');?></label>
					<input type="text" name="filter_search_employee" id="filter_search_employee" placeholder="<?php echo JText::_('COM_HR_SEARCH_EMPLOYEE'); ?>" value="<?php echo $this->escape($this->state->get('filter.search_employee')); ?>" title="<?php echo JText::_('COM_HR_SEARCH_EMPLOYEE'); ?>" />
				</div>                                  
				<div class="filter-search btn-group pull-left">
					<label for="filter_search_date_from" class="element-invisible"><?php //echo JText::_('COM_HR_SEARCH_IN_ROSTER_DATETIME_FROM');?></label>
					<input type="text" name="filter_search_date_from" id="filter_search_date_from" placeholder="<?php echo JText::_('COM_HR_SEARCH_IN_ROSTER_DATETIME_FROM'); ?>" value="<?php echo $this->escape($this->state->get('filter.search_date_from')); ?>" title="<?php echo JText::_('COM_HR_SEARCH_IN_ROSTER_DATETIME_FROM'); ?>" />
				</div>
                            
				<div class="filter-search btn-group pull-left">
					<label for="filter_search_date_to" class="element-invisible"><?php //echo JText::_('COM_HR_SEARCH_IN_ROSTER_DATETIME_TO');?></label>
					<input type="text" name="filter_search_date_to" id="filter_search_date_to" placeholder="<?php echo JText::_('COM_HR_SEARCH_IN_ROSTER_DATETIME_TO'); ?>" value="<?php echo $this->escape($this->state->get('filter.search_date_to')); ?>" title="<?php echo JText::_('COM_HR_SEARCH_IN_ROSTER_DATETIME_TO'); ?>" />
				</div>
                            
                      
                            


                    <div class="filter-search btn-group pull-left">                         
       <select name="filter_hierarchy" class="inputbox" onchange="this.form.submit()">
    <?php echo JHtml::_('select.options', $hierarchyOptions, 'value', 'text', $this->state->get('filter.hierarchy')); ?>
                        </select>
                    </div>                           
                
				<div class="btn-group pull-left">
					<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search fa fa-search"></i></button>
					<button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('filter_search_date_from').value = '';document.getElementById('filter_search_date_to').value = '';document.getElementById('filter_search_employee').value = '';this.form.submit();"><i class="icon-remove fa fa-remove"></i></button>
				</div>
                            
                                  
                   

                    <div class="btn-group pull-left">
                        <label for="limit" class="element-invisible"><?php //echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
                        <?php echo $this->pagination->getLimitBox(); ?>
                    </div>   


         







                            
                            
                            
                            
                            
                            
                            
			</div>
			<div class="clearfix"> </div>
			<table class="table table-striped" id="employee_rosterList">
				<thead>
					<tr>

                                                
                                                        <th width="15%"class="nowrap has-context">
                                                        <?php echo JHtml::_('grid.sort', 'COM_HR_FIELD_EMPLOYEE_LABEL', 'u.name', $listDirn, $listOrder); ?>
                                                </th>					
                      
                                         <th width="15%"class="nowrap has-context">
                                                        <?php echo JHtml::_('grid.sort', 'COM_HR_FIELD_CHAR_FROM_LABEL', 'a.datetime_from', $listDirn, $listOrder); ?>
                                                </th>
                                               <th width="15%"class="nowrap has-context">
                                                        <?php echo JHtml::_('grid.sort', 'COM_HR_FIELD_CHAR_TO_LABEL', 'a.datetime_to', $listDirn, $listOrder); ?>
                                                </th>
                                               <th width="10%"class="nowrap has-context">
                                                        <?php echo JHtml::_('grid.sort', 'COM_HR_FIELD_WORK_STATUS_LABEL', 'a.work_status', $listDirn, $listOrder); ?>
                                                </th>                                                    
                                         <th width="10%"class="nowrap has-context">
                                                        <?php echo JHtml::_('grid.sort', 'COM_HR_FIELD_ABSTYPE_LABEL', 'ab.abstype', $listDirn, $listOrder); ?>
                                                </th>
                                               <th width="15%"class="nowrap has-context">
                                                        <?php echo JHtml::_('grid.sort', 'COM_HR_FIELD_FINAL_APPROVAL_STATUS_LABEL', 'ea.final_approval_status', $listDirn, $listOrder); ?>
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
				<?php foreach ($this->items as $i => $item) :
					$canCheckin = $user->authorise('core.manage',     'com_checkin');
					$canChange  = $user->authorise('core.edit.state', 'com_hr') && $canCheckin;
					$canEdit    = $user->authorise('core.edit',       'com_hr.category.' . $item->catid);
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
                      //          $local_datetime_from = hrHelper::getLocalTime($this->escape($item->datetime_from));
                   //      echo substr($local_datetime_from, 0, 16);
                              
                                ?>      
							 <?php 
                                                  $local_datetime_from = hrHelper::getLocalTime($this->escape($item->datetime_from));
                                                  echo substr($local_datetime_from, 0, 16);
                                                 
                                                 ?>       
                                          </div>
						</td> 
                  
						   <td class="has-context">
                                    <div class="pull-left break-word"> 
				    <?php 
                                                        $local_datetime_to = hrHelper::getLocalTime($this->escape($item->datetime_to));
                                                        echo substr($local_datetime_to, 0, 16);
                                                  ?>       
                                          </div>
						</td> 
                  
                                                
						
                                                
                                                <td class="has-context">
                                                            <div class="pull-left break-word"  > 
                                                        <?php
                                                        if ($item->abstype == '') {
                                                              echo $item->work_status;
                                                        } else
                                                            
                                                        {
                                                                        if ($item->measureunit == 'Days') {
                                                                            
                                                                            if ($item->final_approval_status == 'Pending') {
                                                                                echo $item->work_status;
                                                                            }


                                                                            if ($item->final_approval_status == 'Rejected') {
                                                                                echo $item->work_status;
                                                                            }
                                                                        }



                                                                        if ($item->measureunit == 'Hours_minutes') {
                                                                            if ($item->final_approval_status == 'Pending') {
                                                                                echo $item->work_status;
                                                                            }


                                                                            if ($item->final_approval_status == 'Rejected') {
                                                                                echo $item->work_status;
                                                                            }

                                                                            if ($item->final_approval_status == 'Approved') {

                                                                                if ((($item->absence_datetime_from) <= ($item->roster_datetime_from)) &&
                                                                                        (($item->absence_datetime_to) >= ($item->roster_datetime_to))) {
                                                                                    echo 'sdsd';
                                                                                    
                                                                                    
                                                                                    
                                                                                    
                                                                                } else {

                                                                                    echo $item->work_status;
                                                                                }
                                                                            }
                                                                        }
                                                                        
                                                                       
                                                        }                     
                                                                        ?>    
                                                                     </div>
                                                        </td> 
                                                
   <td class="has-context">
                                    <div class="pull-left break-word"> 
                                        	<?php
                      //          $local_datetime_from = hrHelper::getLocalTime($this->escape($item->datetime_from));
                   //      echo substr($local_datetime_from, 0, 16);
                              
                                ?>      
							<?php 
                                                           echo $item->abstype;
                                                        
                                                          
                                                        ?>
                                          </div>
						</td>                          
                                                
                                                
						   <td class="has-context">
                                    <div class="pull-left break-word"> 
                                        	<?php

                              
                                ?>      
							<?php 
                                                           echo $item->final_approval_status;
                                                        
                                                          
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