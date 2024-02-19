<?php

/**
 * Class xpanSettingsFormGUI
 *
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */

use ILIAS\HTTP;
class xpanSettingsFormGUI extends ilPropertyFormGUI {

    const F_TITLE = 'title';
    const F_DESCRIPTION = 'description';
    const F_ONLINE = 'online';

    /**
     * @var ilCtrl
     */
    protected ilCtrl $ctrl;
    /**
     * @var ilLanguage
     */
    protected ilLanguage $lng;
    /**
     * @var ilPanoptoPlugin
     */
    protected $pl;
    /**
     * @var xpanSettingsGUI
     */
    protected $parent_gui;
    /**
     * @var xpanSettings
     */
    protected $xpan_settings;
    protected HTTP\Services $http;

    /**
     * xpanSettingsFormGUI constructor.
     * @param xpanSettingsGUI $parent_gui
     */
    public function __construct(xpanSettingsGUI $parent_gui) {
        global $DIC;
        $this->ctrl = $DIC['ilCtrl'];
        $this->lng = $DIC['lng'];
        $this->pl = ilPanoptoPlugin::getInstance();
        $this->parent_gui = $parent_gui;
        $this->xpan_settings = xpanSettings::find($this->parent_gui->getObjId());
        $this->setTitle($this->lng->txt('settings'));
        $this->setFormAction($this->ctrl->getFormAction($parent_gui));
        $this->initForm();
        // do it as early as possible
        if (isset($DIC["http"])) {
            $this->http = $DIC->http();
        }
    }

    /**
     *
     */
    protected function initForm() {
        // TITLE
        $input = new ilTextInputGUI($this->lng->txt(self::F_TITLE), self::F_TITLE);
        $input->setRequired(true);
        $this->addItem($input);

        // DESCRIPTION
        $input = new ilTextInputGUI($this->lng->txt(self::F_DESCRIPTION), self::F_DESCRIPTION);
        $this->addItem($input);

        // ONLINE
        $input = new ilCheckboxInputGUI($this->lng->txt(self::F_ONLINE), self::F_ONLINE);
        $this->addItem($input);

        $this->addCommandButton(xpanSettingsGUI::CMD_UPDATE, $this->lng->txt('save'));
    }

    /**
     *
     */
    public function fillForm() {
        $values = array(
            self::F_TITLE => $this->parent_gui->getObject()->getTitle(),
            self::F_DESCRIPTION => $this->parent_gui->getObject()->getDescription(),
            self::F_ONLINE => $this->xpan_settings->isOnline(),
        );
        $this->setValuesByArray($values);
    }


    /**
     * @return bool
     */
    public function saveForm() {
        if (!$this->checkInput()) {
            return false;
        }

        $this->parent_gui->getObject()->setTitle($this->getInput(self::F_TITLE));
        $this->parent_gui->getObject()->setDescription($this->getInput(self::F_DESCRIPTION));
        $this->parent_gui->getObject()->update();

        $this->xpan_settings->setIsOnline($this->getInput(self::F_ONLINE));
        $this->xpan_settings->update();

        return true;
    }
}
