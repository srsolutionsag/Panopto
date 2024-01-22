<?php
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Class ilPanoptoPlugin
 *
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
class ilPanoptoPlugin extends ilRepositoryObjectPlugin {

    const PLUGIN_NAME = 'Panopto';
    const XPAN = 'xpan';

    /**
     * @var ilPanoptoPlugin
     */
    protected static ilPanoptoPlugin $instance;


    /**
     * @return ilPlugin
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            global $DIC;

            $component_repository = $DIC["component.repository"];

            $info = null;
            $plugin_name = self::PLUGIN_NAME;
            $info = $component_repository->getPluginByName($plugin_name);

            $component_factory = $DIC["component.factory"];

            $plugin_obj = $component_factory->getPlugin($info->getId());

            self::$instance = $plugin_obj;
        }

        return self::$instance;
    }

    /**
     * @return string
     */
    function getPluginName(): string
    {
        return self::PLUGIN_NAME;
    }

    /**
     *
     */
    protected function uninstallCustom(): void
    {
        global $DIC;
        $DIC->database()->dropTable(xpanConfig::DB_TABLE_NAME);
        $DIC->database()->dropTable(xpanSettings::DB_TABLE_NAME);
        $DIC->database()->dropTable(SorterEntry::TABLE_NAME);
    }

    public function allowCopy(): bool
    {
        return true;
    }

}
