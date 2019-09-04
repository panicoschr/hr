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
$hierarchies = JFormHelper::loadFieldType('MyApprovalHierarchy', false);
$hierarchyOptions = $hierarchies->getOptions(); // works only if you set your field getOptions on public!!


$measures = JFormHelper::loadFieldType('MyMeasure', false);
$measureOptions = $measures->getOptions(); // works only if you set your field getOptions on public!!


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
	<form action="<?php echo JRoute::_('index.php?option=com_hr&view=emp_entl_bals'); ?>" method="post" name="adminForm" id="adminForm">

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
					<label for="filter_search_absence" class="element-invisible"><?php //echo JText::_('COM_HR_SEARCH_ABSENCE');?></label>
					<input type="text" name="filter_search_absence" id="filter_search_absence" placeholder="<?php echo JText::_('COM_HR_SEARCH_ABSENCE'); ?>" value="<?php echo $this->escape($this->state->get('filter.search_absence')); ?>" title="<?php echo JText::_('COM_HR_SEARCH_ABSENCE'); ?>" />
				</div>
                            
				<div class="filter-search btn-group pull-left">
					<label for="filter_search_ref_year" class="element-invisible"><?php //echo JText::_('COM_HR_SEARCH_REF_YEAR');?></label>
					<input type="text" name="filter_search_ref_year" id="filter_search_ref_year" placeholder="<?php echo JText::_('COM_HR_SEARCH_REF_YEAR'); ?>" value="<?php echo $this->escape($this->state->get('filter.search_ref_year')); ?>" title="<?php echo JText::_('COM_HR_SEARCH_REF_YEAR'); ?>" />
				</div>
<!--div
   
				<div class="filter-search btn-group pull-left">
					<label for="filter_search_entitlement" class="element-invisible"><?php //echo JText::_('COM_HR_SEARCH_ENTITLEMENT');?></label>
					<input type="text" name="filter_search_entitlement" id="filter_search_entitlement" placeholder="<?php echo JText::_('COM_HR_SEARCH_ENTITLEMENT'); ?>" value="<?php echo $this->escape($this->state->get('filter.search_entitlement')); ?>" title="<?php echo JText::_('COM_HR_SEARCH_ENTITLEMENT'); ?>" />
				</div> 
                            
				<div class="filter-search btn-group pull-left">
					<label for="filter_search_charge" class="element-invisible"><?php //echo JText::_('COM_HR_SEARCH_CHARGE');?></label>
					<input type="text" name="filter_search_charge" id="filter_search_charge" placeholder="<?php echo JText::_('COM_HR_SEARCH_CHARGE'); ?>" value="<?php echo $this->escape($this->state->get('filter.search_charge')); ?>" title="<?php echo JText::_('COM_HR_SEARCH_CHARGE'); ?>" />
				</div>                            
   
				<div class="filter-search btn-group pull-left">
					<label for="filter_search_balance" class="element-invisible"><?php //echo JText::_('COM_HR_SEARCH_BALANCE');?></label>
					<input type="text" name="filter_search_balance" id="filter_search_balance" placeholder="<?php echo JText::_('COM_HR_SEARCH_BALANCE'); ?>" value="<?php echo $this->escape($this->state->get('filter.search_balance')); ?>" title="<?php echo JText::_('COM_HR_SEARCH_BALANCE'); ?>" />
				</div>                              
             </div-->              

                         
                           
                            
                    <div class="filter-search btn-group pull-left">                         
       <select name="filter_hierarchy" class="inputbox" onchange="this.form.submit()">
    <?php echo JHtml::_('select.options', $hierarchyOptions, 'value', 'text', $this->state->get('filter.hierarchy')); ?>
                        </select>
                    </div>            
  
<!--div
                    <div class="filter-search btn-group pull-left">                         
       <select name="filter_search_measureunit" class="inputbox" onchange="this.form.submit()">
    <?php //echo JHtml::_('select.options', $measureOptions, 'value', 'text', $this->state->get('filter.search_measureunit')); ?>
                        </select>
                    </div>                               
    </div-->                
				<div class="btn-group pull-left">
					<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search fa fa-search"></i></button>
					<button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('filter_search_employee').value = '';document.getElementById('filter_search_absence').value = '';document.getElementById('filter_search_ref_year').value = '';this.form.submit();"><i class="icon-remove fa fa-remove"></i></button>
				</div>
                            
                                  
                   

                    <div class="btn-group pull-left">
                        <label for="limit" class="element-invisible"><?php //echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
                        <?php echo $this->pagination->getLimitBox(); ?>
                    </div>   


         







                            
                            
                            
                            
                            
                            
                            
			</div>
			<div class="clearfix"> </div>
			<table class="table table-striped" id="emp_entl_balList">
				<thead>
					<tr>

                                                
                                                        <th width="15%"class="nowrap has-context">
                                                        <?php echo JHtml::_('grid.sort', 'COM_HR_FIELD_EMPLOYEE_LABEL', 'username', $listDirn, $listOrder); ?>
                                                </th>	
                                                
                                         <th width="10%"class="nowrap has-context">
                                                        <?php echo JHtml::_('grid.sort', 'COM_HR_FIELD_ABSTYPE_LABEL', 'abs.abstype', $listDirn, $listOrder); ?>
                                                </th>
                                                
                      
                                         <th width="15%"class="nowrap has-context">
                                                        <?php echo JHtml::_('grid.sort', 'COM_HR_FIELD_REF_YEAR_LABEL', 'a.ref_year', $listDirn, $listOrder); ?>
                                                </th>
                                               <th width="15%"class="nowrap has-context">
                                                        <?php echo JHtml::_('grid.sort', 'COM_HR_ENTITLEMENT_ID_LABEL', 'entitlement', $listDirn, $listOrder); ?>
                                                </th>
                                               <th width="10%"class="nowrap has-context">
                                                        <?php echo JHtml::_('grid.sort', 'COM_HR_FIELD_CHARGE_AMOUNT_C_DESC', 'charge', $listDirn, $listOrder); ?>
                                                </th>                                                    
                                               <th width="10%"class="nowrap has-context">
                                                        <?php echo JHtml::_('grid.sort', 'COM_HR_FIELD_BALANCE_AMOUNT_LABEL', 'balance', $listDirn, $listOrder); ?>
                                                </th> 
                                                <th width="10%"class="nowrap has-context">
                                                        <?php echo JHtml::_('grid.sort', 'COM_HR_FIELD_MEASUREUNIT_LABEL', 'abs.measureunit', $listDirn, $listOrder); ?>
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
							<?php echo $item->username; ?>
                                          </div>
						</td>                                           
                                                
   						   <td class="has-context">
                                    <div class="pull-left break-word"> 
							<?php echo $item->abstype; ?>
                                          </div>
						</td>  
                                                
 						   <td class="has-context">
                                    <div class="pull-left break-word"> 
							<?php echo $item->ref_year; ?>
                                          </div>
						</td>                                                  
 						   <td class="has-context">
                                    <div class="pull-left break-word"> 
							<?php echo $item->entitlement; ?>
                                          </div>
						</td>      
                                                
                                     <td class="has-context">
                                    <div class="pull-left break-word"> 
							<?php echo $item->charge; ?>
                                          </div>
						</td>      
						
                                                
   <td class="has-context">
                                    <div class="pull-left break-word"> 
                                        	<?php
                      //          $local_datetime_from = hrHelper::getLocalTime($this->escape($item->datetime_from));
                   //      echo substr($local_datetime_from, 0, 16);
                              
                                ?>      
							<?php 
                                                           echo $item->balance;
                                                        
                                                          
                                                        ?>
                                          </div>
						</td>                            
                                                
                                     <td class="has-context">
                                    <div class="pull-left break-word"> 
							<?php echo $item->measureunit; ?>
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