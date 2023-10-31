<?php
/**
 * @package plugin.content menudatealiasghsvs for Joomla! ge 3.6.0
 * @version See menudatealiasghsvs.xml
 * @author G@HService Berlin Neukölln, Volkmar Volli Schlothauer
 * @copyright Copyright (C) 2016, G@HService Berlin Neukölln, Volkmar Volli Schlothauer. All rights reserved.
 * @license GNU General Public License version 3 or later; see LICENSE.txt
 * @authorUrl https://ghsvs.de
 * @link german description: https://ghsvs.de/programmierer-schnipsel/joomla/189-plugin-menuetyp-menuealias-alias-wieder-zeitstempel
 */
?>
<?php
namespace GHSVS\Plugin\Content\MenuDateAliasGhsvs\Extension;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;

\defined('_JEXEC') or die;

final class MenuDateAliasGhsvs extends CMSPlugin
{
	protected $supportedContext = [
		'com_menus.item',
	];

	/**
		* The save event.
		*
		* @param   string   $context  The context
		* @param   object   $table    The item
		* @param   boolean  $isNew    Is new item
		* @param   array    $data     The validated data
		*
		* @return  boolean
		*
		* @since   4.0.0
		*/
	public function onContentBeforeSave($context, $table, $isNew, $data)
	{
		$onlyNewMenuItems = $this->params->get('onlyNewMenuItems', 1);

		if (
			$this->getApplication()->isClient('administrator')
			&& version_compare(JVERSION, '3.6.0', 'ge')
			&& \in_array($context, $this->supportedContext)
			&& ($isNew || !$onlyNewMenuItems)
			&& !$table->alias
			&& $table->type === 'alias'

			// Some more against paranoia at the moment.
			&& !$table->component_id
			&& strpos($table->params, '"aliasoptions":"')
		){
			$table->alias = Factory::getDate()->format('Y-m-d-H-i-s');
		}
	}
}
