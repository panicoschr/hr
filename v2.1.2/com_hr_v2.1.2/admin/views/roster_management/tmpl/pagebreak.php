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

$script  = 'function insertPagebreak() {' . "\n\t";

// Get the pagebreak title
$script .= 'var title = document.getElementById("title").value;' . "\n\t";
$script .= 'if (title != \'\') {' . "\n\t\t";
$script .= 'title = "title=\""+title+"\" ";' . "\n\t";
$script .= '}' . "\n\t";

// Get the pagebreak toc alias -- not inserting for now
// don't know which attribute to use...
$script .= 'var alt = document.getElementById("alt").value;' . "\n\t";
$script .= 'if (alt != \'\') {' . "\n\t\t";
$script .= 'alt = "alt=\""+alt+"\" ";' . "\n\t";
$script .= '}' . "\n\t";
$script .= 'var tag = "<hr class=\"system-pagebreak\" "+title+" "+alt+"/>";' . "\n\t";
$script .= 'window.parent.jInsertEditorText(tag, ' . json_encode($this->eName) . ');' . "\n\t";
$script .= 'window.parent.jModalClose();' . "\n\t";
$script .= 'return false;' . "\n";
$script .= '}' . "\n";

JFactory::getDocument()->addScriptDeclaration($script);
?>
<div class="container-popup">
	<form class="form-horizontal">

		<div class="control-group">
			<label for="title" class="control-label"><?php echo JText::_('COM_HR_PAGEBREAK_TITLE'); ?></label>
			<div class="controls"><input type="text" id="title" name="title" /></div>
		</div>

		<div class="control-group">
			<label for="alias" class="control-label"><?php echo JText::_('COM_HR_PAGEBREAK_TOC'); ?></label>
			<div class="controls"><input type="text" id="alt" name="alt" /></div>
		</div>

		<button onclick="insertPagebreak();" class="btn btn-primary"><?php echo JText::_('COM_HR_PAGEBREAK_INSERT_BUTTON'); ?></button>

	</form>
</div>
