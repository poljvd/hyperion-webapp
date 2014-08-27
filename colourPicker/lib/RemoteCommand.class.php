<?php

/**
 * Remote Command Class
 *
 * @library Lib
 * @author  Brad Cornford <me@bradleycornford.co.uk>
 */

class RemoteCommand
{
    /**
     * Command
     *
     * @var string
     */
    const COMMAND = '/usr/bin/hyperion-remote %s';

    /**
     * Command application
     *
     * @var string
     */
    const APPLICATION = 'hyperion';

    /**
     * Command on argument
     *
     * @var string
     */
    const ARGUMENT_ON = '%s start %s';

    /**
     * Command off argument
     *
     * @var string
     */
    const ARGUMENT_OFF = '%s stop %s';

    /**
     * Command address argument
     *
     * @var string
     */
    const ARGUMENT_ADDRESS = ' --address %s ';

    /**
     * Command priority argument
     *
     * @var string
     */
    CONST ARGUMENT_PRIORITY = ' --priority %s ';

    /**
     * Command duration argument
     *
     * @var string
     */
    CONST ARGUMENT_DURATION = ' --duration %s ';

    /**
     * Command colour argument
     *
     * @var string
     */
    CONST ARGUMENT_COLOUR = ' --color %s ';

    /**
     * Command effect argument
     *
     * @var string
     */
    CONST ARGUMENT_EFFECT = ' --effect \'%s\' ';

    /**
     * Command server
     *
     * @var string
     */
    protected $server = false;

    /**
     * Command server username
     *
     * @var string
     */
    protected $username = false;

    /**
     * Command server password
     *
     * @var string
     */
    protected $password = false;

    /**
     * Command address
     *
     * @var string
     */
    protected $address = false;

    /**
     * Command controller
     *
     * @var string
     */
    protected $controller = false;

    /**
     * Command priority as an integer
     *
     * @var integer
     */
    protected $priority = false;

    /**
     * Command duration set in milliseconds
     *
     * @var integer
     */
    protected $duration = false;

    /**
     * Command colour property value in HEX
     *
     * @var string
     */
    protected $colour = false;

    /**
     * Command effect property value as a string
     *
     * @var string
     */
    protected $effect = false;

    /**
     * Command post sleep period property value in seconds
     *
     * @var string
     */
    protected $sleep = false;

    /**
     * Command output
     *
     * @var string
     */
    protected $output;

    /**
     * Command debug
     *
     * @var boolean
     */
    protected $debug = false;

    /**
     * Create a controller type command from an argument
     *
     * @param string $argument The argument command
     *
     * @return string
     */
    protected function controllerType($argument)
    {
        switch ($this->controller) {
            case '/etc/init.d':
            case 'sudo /etc/init.d':
                return sprintf($argument, $this->controller . '/' . self::APPLICATION, '');
                break;
            case '/sbin/initctl':
            default:
                return sprintf($argument, $this->controller, self::APPLICATION);
        }
    }

    /**
     * Execute the ON command
     *
     * @return boolean
     */
    public function callOn()
    {
        $result = $this->executeCommand($this->controllerType(self::ARGUMENT_ON), true);

        return $result;
    }

    /**
     * Execute the OFF command
     *
     * @return boolean
     */
    public function callOff()
    {
        $result = $this->executeCommand($this->controllerType(self::ARGUMENT_OFF), true);

        return $result;
    }

    /**
     * Execute the CLEAR command
     *
     * @return boolean
     */
    public function callClear()
    {
        return $this->executeCommand('--clear');
    }

    /**
     * Execute the CLEAR ALL command
     *
     * @return boolean
     */
    public function callClearAll()
    {
        return $this->executeCommand('--clearall');
    }

    /**
     * Execute the COLOUR command
     *
     * @return boolean
     */
    public function callColour()
    {
        if ($this->colour) {
            return $this->executeCommand(sprintf(self::ARGUMENT_COLOUR, $this->colour));
        }

        return false;
    }

    /**
     * Execute the EFFECT command
     *
     * @return boolean
     */
    public function callEffect()
    {
        if ($this->effect) {
            return $this->executeCommand(sprintf(self::ARGUMENT_EFFECT, $this->effect));
        }

        return false;
    }

    /**
     * Execute NO command
     *
     * @return boolean
     */
    public function callDefault()
    {
        return false;
    }

    /**
     * Return the current list of remote effects
     *
     * @return array
     */
    public function getEffects()
    {
        $this->executeCommand('--list | grep \'"name" : \' | cut -d \'"\' -f4 | tr \'\\n\' \',\'');

        return $this->extractData();
    }

    /**
     * Return the current list of instances
     *
     * @return boolean
     */
    public function getStatus()
    {
        $this->executeCommand('ps aux | grep [h]yperion | awk \'{print $11}\'', true);

        if ($this->output === null) {
            return false;
        }

        return true;
    }

    /**
     * Return the current list of active remote commands
     *
     * @return array
     */
    public function getCommands()
    {
        $this->executeCommand('--list | grep \'"priority" : \' | cut -d \':\' -f2 | tr \'\\n\' \',\'');

        return $this->extractData();
    }

    /**
     * Return the current array of data
     *
     * @return array
     */
    protected function extractData()
    {
        $array = explode(',', $this->output);

        if (count($array) > 0) {
            array_splice($array, -1);
        }

        return $array;
    }

    /**
     * Reset the command argument values
     *
     * @return void
     */
    protected function resetArguments()
    {
        $this->server = false;
        $this->username = false;
        $this->password = false;
        $this->address = false;
        $this->controller = false;
        $this->priority = false;
        $this->duration = false;
        $this->colour = false;
        $this->effect = false;
        $this->sleep = false;
        $this->debug = false;
    }

    /**
     * Set the command server IP Address, authentication username and authentication password
     *
     * @param string  $address  The new command server IP Address value
     * @param string  $username The new command server authentication username value
     * @param string  $password The new command server authentication password value
     * @param boolean $debug    Debug commands to the error log
     *
     * @return self
     */
    public function withServer($address, $username, $password, $debug)
    {
        $this->server = (string) $address;
        $this->username = (string) $username;
        $this->password = (string) $password;
        $this->debug = (boolean) $debug;

        return $this;
    }

    /**
     * Set the command address as IP Address with Port
     *
     * @param string $value The new command address value
     *
     * @return self
     */
    public function withAddress($value)
    {
        $this->address = (string) $value;

        return $this;
    }

    /**
     * Set the controller command
     *
     * @param integer $controller The new controller command value
     *
     * @return self
     */
    public function withController($controller)
    {
        $this->controller = (string) $controller;

        return $this;
    }

    /**
     * Set the command priority as integer
     *
     * @param integer $value The new command priority value
     *
     * @return self
     */
    public function withPriority($value)
    {
        $this->priority = (int) $value;

        return $this;
    }

    /**
     * Set the command duration in seconds
     *
     * @param integer $value The new command duration value
     *
     * @return self
     */
    public function withDuration($value)
    {
        $this->duration = (int) ($value * 1000);

        return $this;
    }

    /**
     * Set the command colour property in HEX
     *
     * @param string $value The new command colour property value
     *
     * @return self
     */
    public function withColour($value)
    {
        $this->colour = (string) $value;

        return $this;
    }

    /**
     * Set the command effect property name
     *
     * @param string $value The new command effect property value
     *
     * @return self
     */
    public function withEffect($value)
    {
        $this->effect = (string) $value;

        return $this;
    }

    /**
     * Set the command post sleep period in seconds
     *
     * @param string $value The new command post sleep period property value
     *
     * @return self
     */
    public function withSleep($value)
    {
        $this->sleep = (int) $value;

        return $this;
    }

    /**
     * Set the debug state
     *
     * @param boolean $value The debug state
     *
     * @return self
     */
    public function withDebug($value)
    {
        $this->debug = $value;

        return $this;
    }

    /**
     * Execute command
     *
     * @param string  $command          The extending command to execute
     * @param boolean $overwriteCommand Execute only the extending command?
     *
     * @return boolean
     */
    private function executeCommand($command, $overwriteCommand = false)
    {
        if (!$this->server) {
            return false;
        }

        if (!$overwriteCommand) {
            if ($this->address) {
                $command = sprintf(self::ARGUMENT_ADDRESS, $this->address) . $command;
            }

            if ($this->priority) {
                $command .= sprintf(self::ARGUMENT_PRIORITY, $this->priority);
            }

            if ($this->duration) {
                $command .= sprintf(self::ARGUMENT_DURATION, $this->duration);
            }

            $command = sprintf(self::COMMAND, $command);
        }

        if ($this->debug) {
            error_log("Executing '" . self::APPLICATION . "' command to '{$this->server}': {$command}");
        }

        if ($this->server == '127.0.0.1' || $this->server == getHostByName(getHostName())) {
            $this->output = shell_exec($command);
        } else {
            $connection = ssh2_connect($this->server, 22);

            if (!$this->username) {
                return false;
            }

            if ($this->password) {
                ssh2_auth_password($connection, $this->username, $this->password);
            } else {
                ssh2_auth_none($connection, $this->username);
            }

            $this->output = ssh2_exec($connection, $command);
        }

        if ($this->sleep) {
            sleep($this->sleep);
        }

        $this->resetArguments();

        if ($this->output === null) {
            return false;
        }

        return true;
    }
}
