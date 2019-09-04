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

$form = $displayData->getForm();

$abstype = $form->getField('abstype') ? 'abstype' : ($form->getField('name') ? 'name' : '');
$measureunit = $form->getField('measureunit') ? 'measureunit' : ($form->getField('name') ? 'name' : '');

?>
<div class="form-inline form-inline-header">
	<?php
	echo $abstype ? $form->renderField($abstype) : '';
        echo $measureunit ? $form->renderField($measureunit) : '';
	//echo $form->renderField('alias');
	?>
</div>
