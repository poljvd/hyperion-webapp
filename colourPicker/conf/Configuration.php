<?php

/**
 * Configuration
 *
 * @library Conf
 * @author  Brad Cornford <me@bradleycornford.co.uk>
 */

$config = array(
    /**
     * Remote server IP address with port number.
     * Always required.
     * Default "127.0.0.1".
     */
    'serverAddress' => '127.0.0.1',

    /**
     * Remote server authentication username.
     * Set to false when unneeded.
     * Default "false".
     */
    'serverUsername' => false,

    /**
     * Remote server authentication password.
     * Set to false when unneeded. Default "false".
     */
    'serverPassword' => false,

    /**
     * Hyperion IP address with port number.
     * Always required.
     * Default "127.0.0.1:19444".
     */
    'hyperionAddress' => '127.0.0.1:19444',

    /**
     * Message display setting.
     * Set to false to stop status messages from being displayed.
     * Default "true".
     */
    'messageDisplay' => true,

    /**
     * Overwrite status setting.
     * Set to true to not show status actions such as turn on / off.
     * Default "false".
     */
    'overwriteStatus' => false,
);
