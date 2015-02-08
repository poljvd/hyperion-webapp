<?php

/**
 * Configuration
 *
 * @library Conf
 * @author  Brad Cornford <me@bradleycornford.co.uk>
 */

$config = array(
    /**
     * Remote server IP address.
     * Always required.
     * Default "127.0.0.1".
     */
    'remoteAddress' => '127.0.0.1',

    /**
     * Remote server authentication username.
     * Set to false when unneeded.
     * Default "false".
     */
    'remoteUsername' => false,

    /**
     * Remote server authentication password.
     * Set to false when unneeded. Default "false".
     */
    'remotePassword' => false,

    /**
     * Hyperion IP address.
     * Always required.
     * Default "127.0.0.1".
     */
    'hyperionAddress' => '127.0.0.1',

    /**
     * Hyperion port.
     * Always required.
     * Default "19444".
     */
    'hyperionPort' => '19444',

    /**
     * Hyperion server authentication username.
     * Set to false when unneeded.
     * Default "false".
     */
    'hyperionUsername' => false,

    /**
     * Hyperion server authentication password.
     * Set to false when unneeded. Default "false".
     */
    'hyperionPassword' => false,

    /**
     * System service controller.
     * Always required.
     * Default "/sbin/initctl", can be "sudo /etc/init.d".
     */
    'hyperionController' => '/sbin/initctl',

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

    /**
     * Debug mode.
     * Set to true to allow command debugging to the PHP error log.
     * Default "false".
     */
    'debug' => false,

    /**
     * Log mode.
     * Set to error_log or var_dump.
     * Default "error_log".
     */
    'log' => 'error_log',
);

