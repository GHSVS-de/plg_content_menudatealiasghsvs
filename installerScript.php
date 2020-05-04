<?php
/**
 * @package plugin.content menudatealiasghsvs for Joomla! ge 3.6.0
 * @version See menudatealiasghsvs.xml
 * @author G@HService Berlin Neukölln, Volkmar Volli Schlothauer
 * @copyright Copyright (C) 2016-2019, G@HService Berlin Neukölln, Volkmar Volli Schlothauer. All rights reserved.
 * @license GNU General Public License version 3 or later; see LICENSE.txt
 * @authorUrl https://www.ghsvs.de
 * @link german description: https://www.ghsvs.de/programmierer-schnipsel/joomla/189-plugin-menuetyp-menuealias-alias-wieder-zeitstempel
 */
/**
 * Use in your extension manifest file (any tag is optional!!!!!):
 * <minimumPhp>7.0.0</minimumPhp>
 * <minimumJoomla>3.9.0</minimumJoomla>
 * Yes, use 999999 to match '3.9'. Otherwise comparison will fail.
 * <maximumJoomla>3.9.999999</maximumJoomla>
 * <maximumPhp>7.3.999999</maximumPhp>
 * <allowDowngrades>1</allowDowngrades>
 */
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerScript;
use Joomla\CMS\Log\Log;

class plgContentMenudatealiasGhsvsInstallerScript extends InstallerScript
{
/*
	public function __construct()
	{
		$this->deleteFiles = array(
			str_replace(
				JPATH_ROOT, '',
				Factory::getApplication()->get('log_path') . '/plg_system_hyphenateghsvs-log.txt'
			),
		);
	}
*/

	public function preflight($type, $parent)
	{
		$manifest = @$parent->getManifest();

		if ($manifest instanceof SimpleXMLElement)
		{
			$minimumPhp = trim((string) $manifest->minimumPhp);
			$minimumJoomla = trim((string) $manifest->minimumJoomla);

			// Custom
			$maximumPhp = trim((string) $manifest->maximumPhp);
			$maximumJoomla = trim((string) $manifest->maximumJoomla);

			$this->minimumPhp = $minimumPhp ? $minimumPhp : $this->minimumPhp;
			$this->minimumJoomla = $minimumJoomla ? $minimumJoomla : $this->minimumJoomla;

			if ($maximumJoomla && version_compare(JVERSION, $maximumJoomla, '>'))
			{
				$msg = 'Your Joomla version (' . JVERSION . ') is too high for this extension. Maximum Joomla version is: ' . $maximumJoomla . '.';
				Log::add($msg, Log::WARNING, 'jerror');
			}

			// Check for the maximum PHP version before continuing
			if ($maximumPhp && version_compare(PHP_VERSION, $maximumPhp, '>'))
			{
				$msg = 'Your PHP version (' . PHP_VERSION . ') is too high for this extension. Maximum PHP version is: ' . $maximumPhp . '.';

				Log::add($msg, Log::WARNING, 'jerror');
			}

			if (isset($msg))
			{
				return false;
			}

			if (trim((string) $manifest->allowDowngrades))
			{
				$this->allowDowngrades = true;
			}
		}

		if (!parent::preflight($type, $parent))
		{
			return false;
		}

		if ($type === 'update')
		{
			$this->removeOldUpdateservers();
		}

		return true;
	}

	/**
	 * Remove the outdated updateservers.
	 *
	 * @return  void
	 *
	 * @since   version after 2019.05.29
	 */
	 protected function removeOldUpdateservers()
 	{
 		$db = Factory::getDbo();
 		try
 		{
 			$query = $db->getQuery(true);

 			$query->select('update_site_id')
 				->from($db->qn('#__update_sites'))
 				->where($db->qn('location') . ' = '
 					. $db->q('https://updates.ghsvs.de/joomla/plg_content_menudatealiasghsvs.xml'));

 			$id = (int) $db->setQuery($query)->loadResult();

 			if (!$id)
 			{
 				return;
 			}

 			// Delete from update sites
 			$db->setQuery(
 				$db->getQuery(true)
 					->delete($db->qn('#__update_sites'))
 					->where($db->qn('update_site_id') . ' = ' . $id)
 			)->execute();

 			// Delete from update sites extensions
 			$db->setQuery(
 				$db->getQuery(true)
 					->delete($db->qn('#__update_sites_extensions'))
 					->where($db->qn('update_site_id') . ' = ' . $id)
 			)->execute();
 		}
 		catch (Exception $e)
 		{
 			return;
 		}
 	}
}
