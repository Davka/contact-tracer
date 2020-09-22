<?php
/**
 * ContactTracer.php
 *
 * Plugin for contact tracing across courses.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * @author      Thomas Hackl <thomas.hackl@uni-passau.de>
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GPL version 2
 * @category    Tracer
 */

class ContactTracer extends StudIPPlugin implements StandardPlugin, SystemPlugin {

    public function __construct() {
        parent::__construct();

        StudipAutoloader::addAutoloadPath(__DIR__ . '/models');

        // Localization
        bindtextdomain('tracer', realpath(__DIR__.'/locale'));
    }

    /**
     * Plugin name to show in navigation.
     */
    public function getDisplayName()
    {
        return dgettext('tracer', 'Kontaktverfolgung');
    }

    public function getVersion()
    {
        $metadata = $this->getMetadata();
        return $metadata['version'];
    }

    /**
     * @see StandardPlugin::getIconNavigation()
     */
    public function getIconNavigation($course_id, $last_visit, $user_id)
    {
        return null;
    }

    /**
     * @see StandardPlugin::getTabNavigation()
     */
    public function getTabNavigation($course_id)
    {
        if ($GLOBALS['user']->id == 'nobody') {
            return [];
        }

        $tracer = new Navigation($this->getDisplayName());
        $tracer->addSubNavigation('qr', new Navigation(dgettext('tracer', 'QR-Code'),
            PluginEngine::getURL($this, [], 'coursetracer')));
        $tracer->addSubNavigation('manual', new Navigation(dgettext('tracer', 'Anwesenheit manuell erfassen'),
            PluginEngine::getURL($this, [], 'coursetracer/manual')));

        return compact('tracer');
    }

    /**
     * @see StudipModule::getMetadata()
     */
    public function getMetadata()
    {
        $metadata = parent::getMetadata();

        $metadata['summary'] = dgettext('tracer', 'Kontaktverfolgung via QR-Codes');
        $metadata['description'] = dgettext('tracer', 'Erfassen Sie Kontaktlisten über QR-Codes pro Termin und Veranstaltung');
        $metadata['category'] = _('Lehr- und Lernorganisation');
        $metadata['icon'] = Icon::create('code-qr', 'info');

        return $metadata;
    }

    /**
     * @see StandardPlugin::getInfoTemplate()
     */
    public function getInfoTemplate($course_id)
    {
        return null;
    }

    public function perform($unconsumed_path) {
        $range_id = Request::option('cid', Context::get()->id);

        URLHelper::removeLinkParam('cid');
        $dispatcher = new Trails_Dispatcher(
            $this->getPluginPath(),
            rtrim(PluginEngine::getLink($this, [], null), '/'),
            'register'
        );
        URLHelper::addLinkParam('cid', $range_id);

        $dispatcher->current_plugin = $this;
        $dispatcher->range_id       = $range_id;
        $dispatcher->dispatch($unconsumed_path);
    }

}