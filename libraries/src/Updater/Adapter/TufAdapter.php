<?php

/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2022 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\CMS\Updater\Adapter;

\defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Table\Tuf as MetadataTable;
use Joomla\CMS\TUF\TufFetcher;
use Joomla\CMS\Updater\UpdateAdapter;
use Joomla\CMS\Updater\ConstraintChecker;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tuf\Exception\MetadataException;

/**
 * TUF Update Adapter Class
 *
 * @since   __DEPLOY_VERSION__
 */
class TufAdapter extends UpdateAdapter
{
    /**
     * Finds an update.
     *
     * @param array $options Update options.
     *
     * @return  array|boolean  Array containing the array of update sites and array of updates. False on failure
     *
     * @since   __DEPLOY_VERSION__
     */
    public function findUpdate($options)
    {
        $updates = [];
        $targets = $this->getUpdateTargets($options);

        if ($targets) {
            foreach ($targets as $target) {
                $updateTable = Table::getInstance('update');
                $updateTable->set('update_site_id', $options['update_site_id']);

                $updateTable->bind($target);

                $updates[] = $updateTable;
            }
        }

        return array('update_sites' => array(), 'updates' => $updates);
    }

    /**
     * Finds targets.
     *
     * @param array $options Update options.
     *
     * @return  array|boolean  Array containing the array of update sites and array of updates. False on failure
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getUpdateTargets($options)
    {
        $versions = array();
        $resolver = new OptionsResolver();

        try {
            $this->configureUpdateOptions($resolver);
            $keys = $resolver->getDefinedOptions();
        } catch (\Exception $e) {
        }

        /** @var MetadataTable $metadataTable */
        $metadataTable = new MetadataTable(Factory::getDbo());
        $metadataTable->load(['update_site_id' => $options['update_site_id']]);

        $tufFetcher = new TufFetcher($metadataTable, $options['location']);
        $metaData = $tufFetcher->getValidUpdate();

        $metaData = json_decode($metaData, true);

        if (!isset($metaData["signed"]["targets"])) {
            return false;
        }

        foreach ($metaData["signed"]["targets"] as $filename => $target) {
            $values = [];

            if (!isset($target["hashes"])) {
                throw new MetadataException("No trusted hashes are available for '$filename'");
            }

            foreach ($keys as $key) {
                if (isset($target["custom"][$key])) {
                    $values[$key] = $target["custom"][$key];
                }
            }

            if (isset($values['client']) && is_string($values['client'])) {
                $client = ApplicationHelper::getClientInfo($values['client'], true);

                if (is_object($client)) {
                    $values['client'] = $client->id;
                }
            }

            if (isset($values['infourl']) && isset($values['infourl']['url'])) {
                $values['infourl'] = $values['infourl']['url'];
            }

            try {
                $values = $resolver->resolve($values);
            } catch (\Exception $e) {
                continue;
            }

            $values['detailsurl'] = $options['location'];

            $versions[$values['version']] = $values;
        }

        // We only want the latest version we support
        usort($versions, function ($a, $b) {
            return version_compare($b['version'], $a['version']);
        });

        $checker = new ConstraintChecker();

        foreach ($versions as $version) {
            if ($checker->check($version)) {
                return [$version];
            }
        }

        return false;
    }

    /**
     * Configures default values or pass arguments to params
     *
     * @param OptionsResolver $resolver The OptionsResolver for the params
     *
     * @return void
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function configureUpdateOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'name' => null,
                'description' => '',
                'element' => '',
                'type' => null,
                'client' => 0,
                'version' => "1",
                'data' => '',
                'detailsurl' => '',
                'infourl' => '',
                'downloads' => [],
                'targetplatform' => new \StdClass(),
                'php_minimum' => null,
                'channel' => null,
                'supported_databases' => new \StdClass(),
                'stability' => ''
            ]
        )
            ->setAllowedTypes('version', 'string')
            ->setAllowedTypes('name', 'string')
            ->setAllowedTypes('element', 'string')
            ->setAllowedTypes('data', 'string')
            ->setAllowedTypes('description', 'string')
            ->setAllowedTypes('type', 'string')
            ->setAllowedTypes('detailsurl', 'string')
            ->setAllowedTypes('infourl', 'string')
            ->setAllowedTypes('client', 'int')
            ->setAllowedTypes('downloads', 'array')
            ->setAllowedTypes('targetplatform', 'array')
            ->setAllowedTypes('php_minimum', 'string')
            ->setAllowedTypes('channel', 'string')
            ->setAllowedTypes('supported_databases', 'array')
            ->setAllowedTypes('stability', 'string')
            ->setRequired(['version']);
    }
}
