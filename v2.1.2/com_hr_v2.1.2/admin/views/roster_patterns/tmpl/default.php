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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();
$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'a.ordering';
$columns = 10;
$view_name = 'roster_patterns';

if ($saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_hr&task=roster_patterns.saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'roster_patternList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$assoc = JLanguageAssociations::isEnabled(); $assoc = false;  $lang  = false;  //added by panikos to remove association and language 
?>

<form action="<?php echo JRoute::_('index.php?option=com_hr&view=roster_patterns'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty($this->sidebar)) : ?>
        <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
        </div>
        <div id="j-main-container" class="span10">
<?php else : ?>
            <div id="j-main-container">
        <?php endif; ?>
            <?php
            // Search tools bar
            echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
            ?>
            <?php if (empty($this->items)) : ?>
                <div class="alert alert-no-items">
                <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                </div>
            <?php else : ?>
                <table class="table table-striped" id="roster_patternList">
                    <thead>
                        <tr>
                            <th width="1%" class="nowrap center hidden-phone">
                <?php echo JHtml::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
                            </th>
                            <th width="1%" class="center">
    <?php echo JHtml::_('grid.checkall'); ?>
                            </th>
                            <th width="1%" style="min-width:55px" class="nowrap center">
    <?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
                            </th>
                            <th>
    <?php echo JHtml::_('searchtools.sort', 'COM_HR_FIELD_ROSTER_LABEL', 'r.title', $listDirn, $listOrder); ?>
                            </th>

                            <th width="10%" class="nowrap">
                                <?php echo JHtml::_('searchtools.sort', 'COM_HR_FIELD_NO_OF_DAY_LABEL', 'a.no_of_day', $listDirn, $listOrder); ?>
                            </th>                                                
                            <th width="10%" class="nowrap">
    <?php echo JHtml::_('searchtools.sort', 'COM_HR_FIELD_TIME_FROM_LABEL', 'time_from_to_sort', $listDirn, $listOrder); ?>
                            </th>  
                            <th width="10%" class="nowrap">
    <?php echo JHtml::_('searchtools.sort', 'COM_HR_FIELD_TIME_TO_LABEL', 'time_to_to_sort', $listDirn, $listOrder); ?>
                            </th>  
                            <th width="10%" class="nowrap">
    <?php echo JHtml::_('searchtools.sort', 'COM_HR_FIELD_PLUS_NO_OF_DAYS_LABEL', 'a.plus_no_of_days', $listDirn, $listOrder); ?>
                            </th>  
                            <th width="10%" class="nowrap">
    <?php echo JHtml::_('searchtools.sort', 'COM_HR_FIELD_WORK_STATUS_LABEL', 'a.work_status', $listDirn, $listOrder); ?>
                            </th>  



                            <th width="10%" class="nowrap hidden-phone">
    <?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
                            </th>
    <?php if ($assoc) : ?>
        <?php $columns++; ?>
                                <th width="5%" class="nowrap hidden-phone">
                                    <?php echo JHtml::_('searchtools.sort', 'COM_HR_HEADING_ASSOCIATION', 'association', $listDirn, $listOrder); ?>
                                </th>
                            <?php endif; ?>
                            <th width="10%" class="nowrap hidden-phone">
                                <?php echo JHtml::_('searchtools.sort', 'JAUTHOR', 'a.created_by', $listDirn, $listOrder); ?>
                            </th>
                                     <?php if ($lang) : ?>  
                            <th width="10%" class="nowrap hidden-phone">
                            <?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'language', $listDirn, $listOrder); ?>
                            </th>
                               <?php endif;?>    
                            <th width="10%" class="nowrap hidden-phone">
    <?php echo JHtml::_('searchtools.sort', 'JDATE', 'a.created', $listDirn, $listOrder); ?>
                            </th>
                            <th width="1%" class="nowrap hidden-phone">
    <?php //echo JHtml::_('searchtools.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder);  ?>
                            </th>
                            <th width="1%" class="nowrap hidden-phone">
    <?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                            </th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="<?php echo $columns; ?>">
                            </td>
                        </tr>
                    </tfoot>
                    <tbody>
    <?php
    foreach ($this->items as $i => $item) :
        $item->max_ordering = 0;
        $ordering = ($listOrder == 'a.ordering');
        $canCreate = $user->authorise('core.create', 'com_hr.category.' . $item->catid);
        $canEdit = $user->authorise('core.edit', 'com_hr.roster_pattern.' . $item->id);
        $canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
        $canEditOwn = $user->authorise('core.edit.own', 'com_hr.roster_pattern.' . $item->id) && $item->created_by == $userId;
        $canChange = $user->authorise('core.edit.state', 'com_hr.roster_pattern.' . $item->id) && $canCheckin;
        ?>
                            <tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->catid; ?>">
                                <td class="order nowrap center hidden-phone">
                            <?php
                            $iconClass = '';
                            if (!$canChange) {
                                $iconClass = ' inactive';
                            } elseif (!$saveOrder) {
                                $iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
                            }
                            ?>
                                    <span class="sortable-handler<?php echo $iconClass ?>">
                                        <span class="icon-menu"></span>
                                    </span>
                                    <?php if ($canChange && $saveOrder) : ?>
                                        <input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
                                    <?php endif; ?>
                                </td>
                                <td class="center">
        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                                </td>
                                <td class="center">
                                    <div class="btn-group">
                                    <?php echo JHtml::_('jgrid.published', $item->state, $i, 'roster_patterns.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
        <?php echo JHtml::_('hradministrator.featured', $item->featured, $i, $canChange, $view_name); ?>
                                    <?php
                                    // Create dropdown items and render the dropdown list.
                                    if ($canChange) {
                                        JHtml::_('actionsdropdown.' . ((int) $item->state === 2 ? 'un' : '') . 'archive', 'cb' . $i, 'roster_patterns');
                                        JHtml::_('actionsdropdown.' . ((int) $item->state === -2 ? 'un' : '') . 'trash', 'cb' . $i, 'roster_patterns');
                                        echo JHtml::_('actionsdropdown.render', $this->escape($item->title));
                                    }
                                    ?>
                                    </div>
                                </td>
                                <td class="has-context">
                                    <div class="pull-left">
                                        <?php if ($item->checked_out) : ?>
                                            <?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'roster_patterns.', $canCheckin); ?>
                                        <?php endif; ?>
        <?php if ($item->language == '*'): ?>
            <?php $language = JText::alt('JALL', 'language'); ?>
        <?php else: ?>
                                            <?php $language = $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
                                        <?php endif; ?>
                                        <?php if ($canEdit || $canEditOwn) : ?>
                                            <a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_hr&task=roster_pattern.edit&id=' . $item->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
                                            <?php echo $this->escape($item->roster_title); ?></a>
                                        <?php else : ?>
                                            <span title="<?php echo JText::sprintf('JFIELD_ALIAS_LABEL', $this->escape($item->alias)); ?>"><?php echo $this->escape($item->roster_title); ?></span>
                                        <?php endif; ?>
                                        <span class="small">
                                        <?php //echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>
                                        </span>
                                        <div class="small">
                                        <?php echo JText::_('JCATEGORY') . ": " . $this->escape($item->category_title); ?>
                                        </div>
                                    </div>
                                </td>

                                <td class="has-context">
                                    <div class="pull-left break-word">    

                                      <?php echo $this->escape($item->no_of_day); ?>                         

                                    </div>
                                </td>    
                                
                                <td class="has-context">
                                    <div class="pull-left break-word">    

                                      <?php 
                                      
                           echo substr($item->time_from, 11, 5);           
                                    //  echo $this->escape($item->no_of_day); ?>                         

                                    </div>
                                </td>                    
                                
                                <td class="has-context">
                                    <div class="pull-left break-word">    

                                      <?php 
                                      
                           echo substr($item->time_to, 11, 5);           
                                    //  echo $this->escape($item->no_of_day); ?>                         

                                    </div>
                                </td>              
                                
                                <td class="has-context">
                                    <div class="pull-left break-word">    

                                      <?php 
                                         if ($item->plus_no_of_days == 0){
                                                 $item->plus_no_of_days_desc = 'Same Day';
                                             }
                                              if ($item->plus_no_of_days == 1){
                                                 $item->plus_no_of_days_desc = 'Next Day';
                                             }                                                
                                             echo $this->escape($item->plus_no_of_days_desc);                         

                                              ?>                         

                                    </div>
                                </td>                                     
                                
                                  <td class="has-context">
                                    <div class="pull-left break-word">    

                                      <?php echo $this->escape($item->work_status); ?>                         

                                    </div>
                                </td>                         
                                
                                
                                <td class="small hidden-phone">
        <?php echo $this->escape($item->access_level); ?>
                                </td>
                                        <?php if ($assoc) : ?>
                                    <td class="hidden-phone">
            <?php if ($item->association) : ?>
                <?php echo JHtml::_('hradministrator.association', $item->id); ?>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                                <td class="small hidden-phone">
                                    <?php if ($item->created_by_alias) : ?>
                                        <a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->created_by); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
                                        <?php echo $this->escape($item->author_name); ?></a>
                                        <div class="small"><?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->created_by_alias)); ?></div>
                                <?php else : ?>
                                        <a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->created_by); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
                                        <?php echo $this->escape($item->author_name); ?></a>
                                    <?php endif; ?>
                                </td>
                                 <?php if ($lang) : ?>       
                                <td class="small hidden-phone">
                                    <?php if ($item->language == '*'): ?>
                                        <?php echo JText::alt('JALL', 'language'); ?>
                                        <?php else: ?>
                                            <?php echo $item->language_title ? JHtml::_('image', 'mod_languages/' . $item->language_image . '.gif', $item->language_title, array('title' => $item->language_title), true) . '&nbsp;' . $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
                                    <?php endif; ?>
                                </td>
                                <?php endif; ?>     
                                <td class="nowrap small hidden-phone">
                                    <?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?>
                                </td>
                                <td class="hidden-phone">
                                    <?php
// echo (int) $item->hits; 
                                    ?>
                                </td>
                                <td class="hidden-phone">
                                     <?php echo (int) $item->id; ?>
                                </td>
                            </tr>
                                <?php endforeach; ?>
                    </tbody>
                </table>
    <?php // Load the batch processing form.  ?>
                                <?php
                                if ($user->authorise('core.create', 'com_hr') && $user->authorise('core.edit', 'com_hr') && $user->authorise('core.edit.state', 'com_hr')) :
                                    ?>
                            <?php
                            echo JHtml::_(
                                    'bootstrap.renderModal', 'collapseModal', array(
                                'title' => JText::_('COM_HR_BATCH_OPTIONS'),
                                'footer' => $this->loadTemplate('batch_footer')
                                    ), $this->loadTemplate('batch_body')
                            );
                            ?>
                <?php endif; ?>
            <?php endif; ?>

            <?php echo $this->pagination->getListFooter(); ?>

            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
</form>
