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
    private static ?self $instance = null;
    
    public static function getInstance(): self
    {
        if (!self::$instance instanceof self) {
            global $DIC;
            // ILIAS 8
            if (isset($DIC['component.factory']) && $DIC['component.factory'] instanceof ilComponentFactory) {
                $component_factory = $DIC['component.factory'];
                self::$instance = $component_factory->getPlugin(self::XPAN);
            } // ILIAS 7
            else {
                self::$instance = new self();
            }
        }
        
        return self::$instance;
    }

    /**
     * @return string
     */
    public function getPluginName(): string {
        return self::PLUGIN_NAME;
    }

    /**
     *
     */
    protected function uninstallCustom(): void {
        global $DIC;
        $DIC->database()->dropTable(xpanConfig::DB_TABLE_NAME);
        $DIC->database()->dropTable(xpanSettings::DB_TABLE_NAME);
        $DIC->database()->dropTable(SorterEntry::TABLE_NAME);
    }

    public function allowCopy() : bool
    {
        return true;
    }

}
