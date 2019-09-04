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
 * View class for a list of employee_absences.
 *
 * @since  1.6
 */
class HrViewEmployee_absences extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    /**
     * Display the view
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     */
    public function display($tpl = null) {
        if ($this->getLayout() !== 'modal') {
            HrHelper::addSubmenu('employee_absences');
        }

        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->authors = $this->get('Authors');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JFactory::getApplication()->enqueueMessage(implode("\n", $errors), 'error');

            return false;
        }

        // Levels filter.
        $options = array();
        $options[] = JHtml::_('select.option', '1', JText::_('J1'));
        $options[] = JHtml::_('select.option', '2', JText::_('J2'));
        $options[] = JHtml::_('select.option', '3', JText::_('J3'));
        $options[] = JHtml::_('select.option', '4', JText::_('J4'));
        $options[] = JHtml::_('select.option', '5', JText::_('J5'));
        $options[] = JHtml::_('select.option', '6', JText::_('J6'));
        $options[] = JHtml::_('select.option', '7', JText::_('J7'));
        $options[] = JHtml::_('select.option', '8', JText::_('J8'));
        $options[] = JHtml::_('select.option', '9', JText::_('J9'));
        $options[] = JHtml::_('select.option', '10', JText::_('J10'));

        $this->f_levels = $options;

        // We don't need toolbar in the modal window.
        if ($this->getLayout() !== 'modal') {
            $this->addToolbar();
            $this->sidebar = JHtmlSidebar::render();
        } else {
            // In employee_absence associations modal we need to remove language filter if forcing a language.
            // We also need to change the category filter to show show categories with All or the forced language.
            if ($forcedLanguage = JFactory::getApplication()->input->get('forcedLanguage', '', 'CMD')) {
                // If the language is forced we can't allow to select the language, so transform the language selector filter into an hidden field.
                $languageXml = new SimpleXMLElement('<field name="language" type="hidden" default="' . $forcedLanguage . '" />');
                $this->filterForm->setField($languageXml, 'filter', true);

                // Also, unset the active language filter so the search tools is not open by default with this filter.
                unset($this->activeFilters['language']);

                // One last changes needed is to change the category filter to just show categories with All language or with the forced language.
                $this->filterForm->setFieldAttribute('category_id', 'language', '*,' . $forcedLanguage, 'filter');
            }
        }

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @since   1.6
     */
    protected function addToolbar() {
        $canDo = HrHelper::getActions('com_hr', 'category', $this->state->get('filter.category_id'));
        $user = JFactory::getUser();

        // Get the toolbar object instance
        $bar = JToolbar::getInstance('toolbar');

        JToolbarHelper::title(JText::_('COM_HR_EMPLOYEE_ABSENCES_TITLE'), 'stack employee_absence');

        if ($canDo->get('core.create') || (count($user->getAuthorisedCategories('com_hr', 'core.create'))) > 0) {
            JToolbarHelper::addNew('employee_absence.add');
        }

        if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own'))) {
            JToolbarHelper::editList('employee_absence.edit');
        }

        if ($canDo->get('core.edit.state')) {
            JToolbarHelper::publish('employee_absences.publish', 'JTOOLBAR_PUBLISH', true);
            JToolbarHelper::unpublish('employee_absences.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            JToolbarHelper::custom('employee_absences.featured', 'featured.png', 'featured_f2.png', 'JFEATURE', true);
            JToolbarHelper::custom('employee_absences.unfeatured', 'unfeatured.png', 'featured_f2.png', 'JUNFEATURE', true);
            JToolbarHelper::archiveList('employee_absences.archive');
            JToolbarHelper::checkin('employee_absences.checkin');
        }

        // Add a batch button
        if ($user->authorise('core.create', 'com_hr') && $user->authorise('core.edit', 'com_hr') && $user->authorise('core.edit.state', 'com_hr')) {
            $title = JText::_('JTOOLBAR_BATCH');

            // Instantiate a new JLayoutFile instance and render the batch button
            $layout = new JLayoutFile('joomla.toolbar.batch');

            $dhtml = $layout->render(array('title' => $title));
            $bar->appendButton('Custom', $dhtml, 'batch');
        }

        if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete')) {
            JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'employee_absences.delete', 'JTOOLBAR_EMPTY_TRASH');
        } elseif ($canDo->get('core.edit.state')) {
            JToolbarHelper::trash('employee_absences.trash');
        }

        if ($user->authorise('core.admin', 'com_hr') || $user->authorise('core.options', 'com_hr')) {
            JToolbarHelper::preferences('com_hr');
        }

        JToolbarHelper::help('JHELP_CONTENT_EMPLOYEE_ABSENCE_MANAGER');
    }

    /**
     * Returns an array of fields the table can be sorted by
     *
     * @return  array  Array containing the field name to sort by as the key and display text as value
     *
     * @since   3.0
     */
    protected function getSortFields() {
        return array(
            'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
            'a.state' => JText::_('JSTATUS'),
            'a.approver1_id' => JText::_('COM_HR_FIELD_APPROVER1_ID_DESC'),
            'a.approver2_id' => JText::_('COM_HR_FIELD_APPROVER2_ID_DESC'),
            'a.approver3_id' => JText::_('COM_HR_FIELD_APPROVER3_ID_DESC'),
            'a.approver4_id' => JText::_('COM_HR_FIELD_APPROVER4_ID_DESC'),
            'a.approver5_id' => JText::_('COM_HR_FIELD_APPROVER5_ID_DESC'),
            'a.approval_status1' => JText::_('COM_HR_FIELD_APPROVAL_STATUS1_LABEL'),
            'a.approval_status2' => JText::_('COM_HR_FIELD_APPROVAL_STATUS2_LABEL'),
            'a.approval_status3' => JText::_('COM_HR_FIELD_APPROVAL_STATUS3_LABEL'),
            'a.approval_status4' => JText::_('COM_HR_FIELD_APPROVAL_STATUS4_LABEL'),
            'a.approval_status5' => JText::_('COM_HR_FIELD_APPROVAL_STATUS5_LABEL'),
            'a.final_approval_status' => JText::_('COM_HR_FIELD_FINAL_APPROVAL_STATUS_LABEL'),
            'noofcalls'     => JText::_('COM_HR_FIELD_NO_OF_WORKING_W_LABEL'), 
            'accharge'     => JText::_('COM_HR_FIELD_CHARGE_AMOUNT_C_LABEL'),            
            'username'     => JText::_('COM_HR_FIELD_EMPLOYEE_LABEL'),
            'a.datetime_from' => JText::_('COM_HR_FIELD_CHAR_FROM_LABEL'),
            'a.datetime_to' => JText::_('COM_HR_FIELD_CHAR_TO_LABEL'),
            'd.abstype' => JText::_('COM_HR_FIELD_ABSENCE_LABEL'),
            'a.charge_status' => JText::_('COM_HR_FIELD_CHARGE_STATUS_LABEL'),
            'a.abs_certification' => JText::_('COM_HR_FIELD_CERTIFICATION_LABEL'),
            'category_title' => JText::_('JCATEGORY'),
            'access_level' => JText::_('JGRID_HEADING_ACCESS'),
            'a.created_by' => JText::_('JAUTHOR'),
            'language' => JText::_('JGRID_HEADING_LANGUAGE'),
            'a.created' => JText::_('JDATE'),
            'a.id' => JText::_('JGRID_HEADING_ID'),
            'a.featured' => JText::_('JFEATURED')
        );
    }

}
