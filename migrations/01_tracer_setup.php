<?php

/**
 * Initializes necessary data structures for contact tracing.
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

class TracerSetup extends Migration {

    public function description()
    {
        return 'Initializes necessary data structures for contact tracing.';
    }

    /**
     * Migration UP: We have just installed the plugin
     * and need to prepare all necessary data.
     */
    public function up()
    {
        // Table for storing who was present at which course date.
        DBManager::get()->execute("ALTER TABLE `contact_tracing` ADD `contact` TEXT NOT NULL AFTER `resource_id`;");
    }

    /**
     * Migration DOWN: cleanup all created data.
     */
    public function down()
    {
        // Remove database table.
        DBManager::get()->execute("DROP TABLE IF EXISTS `contact_tracing`");
    }

}
