<?php
/**
 * @package	HR
 * @subpackage	Plugin
 * @copyright	WWW.MEPRO.CO - All rights reserved.
 * @author	MEPRO SOFTWARE SOLUTIONS
 * @link	http://www.mepro.co
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/

// No direct access
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('calendar');

/**
 * Provides input for "Date of Birth" field
 *
 * @package     Joomla.Plugin
 * @subpackage  User.profile
 * @since       3.3.7
 */
class JFormFieldDob extends JFormFieldCalendar
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  3.3.7
	 */
	protected $type = 'Dob';

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 *
	 * @since   3.3.7
	 */
	protected function getLabel()
	{
		$label = parent::getLabel();

		// Get the info text from the XML element, defaulting to empty.
		$text = $this->element['info'] ? (string) $this->element['info'] : '';
		$text = $this->translateLabel ? JText::_($text) : $text;

		if ($text)
		{
			$layout = new JLayoutFile('plugins.user.profile.fields.dob');
			$info   = $layout->render(array('text' => $text));
			$label  = $info . $label;
		}

		return $label;
	}
}
