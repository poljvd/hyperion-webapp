<?php

/*
 * ColourPicker webapp for Hyperion.
 *
 * Refactored by: Brad Cornford <me@bradleycornford.co.uk> - February 2014
 * Twitter: @BradCornford
 *
 * Based on ColourPicker v1.0 by nadnerb - April 2013
 * More info: http://blog.nadnerb.co.uk
 * Twitter: @_nadnerb
 *
 * Farbtastic: http://acko.net/blog/farbtastic-jquery-color-picker-plug-in/
 */

include('colourPicker/lib/RemoteCommand.class.php');

session_start();

$messageDisplay = true;

if (!isset($_SESSION['address'])) {
    $_SESSION['address'] = '127.0.0.1:19444';
}

if (!isset($_SESSION['priority'])) {
    $_SESSION['priority'] = array();
}

if (!isset($_SESSION['duration'])) {
    $_SESSION['duration'] = false;
}

if (!isset($_SESSION['colour'])) {
    $_SESSION['colour'] = '00FFFF';
}

$messages = array();

$com = new RemoteCommand();

if (isset($_POST['submit'])) {
    switch ($_POST['submit']) {
        case 'Turn On':
            $return = $com->withSleep(2)
                ->callOn();
            if ($return) {
                $messages[] = array(
                    'type' => 'success',
                    'content' => 'Turned on Hyperion successfully.'
                );
            }
            break;
        case 'Turn Off':
            $return = $com->withSleep(1)
                ->callOff();
            if ($return) {
                $messages[] = array(
                    'type' => 'success',
                    'content' => 'Turned off Hyperion successfully.'
                );
            }
            break;
        case 'Clear':
            $return = $com->withAddress($_SESSION['address'])
                ->withPriority($_POST['priority'])
                ->callClear();
            unset($_SESSION['priority'][$_POST['priority']]);
            if ($return) {
                $messages[] = array(
                    'type' => 'success',
                    'content' => 'Cleared the priority channel ' . $_POST['priority'] . '.'
                );
            }
            break;
        case 'Clear All':
            $return = $com->withAddress($_SESSION['address'])
                ->callClearAll();
            $_SESSION['priority'] = array();
            if ($return) {
                $messages[] = array(
                    'type' => 'success',
                    'content' => 'Cleared the all priority channels.'
                );
            }
            break;
        case 'Change Colour':
            $return = $com->withAddress($_SESSION['address'])
                ->withDuration($_POST['duration'] ? $_POST['duration'] : false)
                ->withPriority($_POST['priority'])
                ->withColour($_POST['colour'])
                ->callColour();
            $_SESSION['colour'] = $_POST['colour'];
            $_SESSION['priority'][$_POST['priority']] = true;
            $messages[] = array(
                'type' => 'success',
                'content' => 'Loaded colour "' . $_POST['colour'] . '" with the priority ' . $_POST['priority'] . '' . ($_POST['duration'] ? ' and the duration ' . $_POST['duration'] : '') . '.'
            );
            break;
        case 'Loading Effect...':
            $return = $com->withAddress($_SESSION['address'])
                ->withDuration($_POST['duration'] ? $_POST['duration'] : false)
                ->withPriority($_POST['priority'])
                ->withEffect($_POST['effect'])
                ->callEffect();
            $_SESSION['priority'][$_POST['priority']] = true;
            if ($return) {
                $messages[] = array(
                    'type' => 'success',
                    'content' => 'Loaded effect "' . $_POST['effect'] . '" with the priority ' . $_POST['priority'] . '' . ($_POST['duration'] ? ' and the duration ' . $_POST['duration'] : '') . '.'
                );
            }
            break;
        default:
            $return = $com->callDefault();
            break;
    }

    if (!$return) {
        $message[] = array(
            'type' => 'error',
            'content' => 'An error occurred running the command.'
        );
    }
}

$currentStatus = $com->getStatus();
$currentCommands = $com->withAddress($_SESSION['address'])->getCommands();
$currentEffects = $com->withAddress($_SESSION['address'])->getEffects();

if ($currentCommands) {
    $_SESSION['priority'] = array();
    foreach ($currentCommands as $key => $command) {
        if ($command == 1000 || (isset($_POST['submit']) && $_POST['submit'] == 'Turn On')) {
            unset($currentCommands[$key]);
        } else {
            $_SESSION['priority'][$command] = true;
        }
    }
}

?>

<html>

    <head>

        <title>Hyperion</title>

        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="HandheldFriendly" content="true">
        <meta name="MobileOptimized" content="320">

        <link rel="shortcut icon" href="colourPicker/img/favicon.ico" type="image/x-icon" />
        <link rel="apple-touch-icon" href="colourPicker/img/apple-touch-icon.png" />
        <link rel="apple-touch-icon" sizes="57x57" href="colourPicker/img/apple-touch-icon-57x57.png" />
        <link rel="apple-touch-icon" sizes="72x72" href="colourPicker/img/apple-touch-icon-72x72.png" />
        <link rel="apple-touch-icon" sizes="76x76" href="colourPicker/img/apple-touch-icon-76x76.png" />
        <link rel="apple-touch-icon" sizes="114x114" href="colourPicker/img/apple-touch-icon-114x114.png" />
        <link rel="apple-touch-icon" sizes="120x120" href="colourPicker/img/apple-touch-icon-120x120.png" />
        <link rel="apple-touch-icon" sizes="144x144" href="colourPicker/img/apple-touch-icon-144x144.png" />
        <link rel="apple-touch-icon" sizes="152x152" href="colourPicker/img/apple-touch-icon-152x152.png" />

        <link rel="stylesheet" href="colourPicker/css/jquery-ui.css" type="text/css" />
        <link rel="stylesheet" href="colourPicker/css/farbtastic.css" type="text/css" />
        <link rel="stylesheet" href="colourPicker/css/style.css" type="text/css" />

    </head>

    <body ontouchstart="">

        <div align="center" id="header">

             <h1><img src="colourPicker/img/logo.png" width="44"/>Hyperion</h1>

        </div>

        <?php

            if ($messages && $messageDisplay) {
                foreach ($messages as $message) {
                    echo '<div class="alert alert-' . $message['type'] . '">' . $message['content'] . '</div>';
                }

                $messages = array();
            }

        ?>

        <div id="wrapper">

            <div align="center" id="content">

                <form id="form" name="form" action="" method="post">

                    <div class="border thin">
                    <?php

                        if ($currentStatus) {
                            echo '<input name="submit" type="submit" class="large button red" value="Turn Off" />';
                        } else {
                            echo '<input name="submit" type="submit" class="large button green" value="Turn On" />';
                        }

                    ?>
                    </div>

                    <div <?php echo !$currentStatus ? 'class="hidden"' : ''; ?>>
                        <h2 class="border">Options</h2>
                    </div>

                    <div id="color-picker" class="<?php echo !$currentStatus ? 'hidden' : ''; ?>"></div>

                    <div class="border thin <?php echo !$currentStatus ? 'hidden' : ''; ?>">
                        <input name="colour" id="color" size="7" type="text" class="colourCode" value=""/>
                    </div>

                    <div id="priority-holder" class="border thin<?php echo !$currentStatus ? ' hidden' : ''; ?>">
                        <input type="hidden" id="priority" name="priority" value="<?php echo !empty($_SESSION['priority']) ? key($_SESSION['priority']) : 500; ?>" />
                        <div id="priority-slider-display"><strong>Priority:</strong> <span><?php echo !empty($_SESSION['priority']) ? key($_SESSION['priority']) : 500; ?></span></div>
                        <div id="priority-slider"></div>
                    </div>

                    <div id="duration-display-holder" class="border thin<?php echo !$currentStatus ? ' hidden' : ''; ?>">
                        <input type="hidden" id="duration" name="duration" value="" />
                        <input type="checkbox" id="duration-display" name="duration-display" value="duration" /><label for="duration-display">Enable Duration?</label>
                    </div>

                    <div id="duration-holder" class="border thin hidden">
                        <div id="duration-slider-display"><strong>Duration:</strong> <span>1 second</span></div>
                        <div id="duration-slider"></div>
                    </div>

                    <div <?php echo !$currentStatus ? 'class="hidden"' : ''; ?>>
                        <h2 class="border">Actions</h2>
                    </div>

                    <div <?php echo !$currentStatus ? ' class="hidden"' : ''; ?>>
                        <input name="submit" type="submit" class="large button green" value="Change Colour"/>
                    </div>

                    <div <?php echo !$currentStatus ? 'class="hidden"' : ''; ?>>
                        <?php

                            if ($currentStatus && count($currentCommands) > 0) {
                                echo '<input name="submit" type="submit" class="large button orange" value="Clear"/>';
                            }

                        ?>
                    </div>

                    <div <?php echo !$currentStatus ? 'class="hidden"' : ''; ?>>
                        <?php

                        if ($currentStatus && count($currentCommands) > 1) {
                            echo '<input name="submit" type="submit" class="large button orange" value="Clear All"/>';
                        }

                        ?>
                    </div>

                    <div <?php echo !$currentStatus ? ' class="hidden"' : ''; ?>>
                        <button id="effect-switch" class="large button blue"><?php echo !$_POST['effect'] ? ' Effects' : 'Close Effects'; ?></button>
                    </div>

                    <div id="effect-display" class="<?php echo !$_POST['effect'] ? ' hidden' : ''; ?>">
                        <input type="hidden" name="effect" id="effect" value="" />
                        <?php

                            if ($currentEffects) {
                                foreach ($currentEffects as $key => $value) {
                                    echo '<div>
                                        <input name="submit" type="submit" class="large button effect" value="' . $value . '"/>
                                    </div>';
                                }
                            } else {
                                echo '<p>No effects to display.</p>';
                            }

                        ?>
                    </div>

                </form>

            </div>

        </div>

        <div align="center" id="footer">
            <a href="https://github.com/tvdzwan/hyperion/wiki">Hyperion Team</a><br/>
            (original version by <a href="http://twitter.com/_nadnerb">@_nadnerb</a>)<br/>
            (refactored by <a href="http://twitter.com/BradCornford">@BradCornford</a>)
        </div>

    </body>

    <script type="text/javascript" src="colourPicker/js/jquery.js"></script>
    <script type="text/javascript" src="colourPicker/js/jquery-ui.js"></script>
    <script type="text/javascript" src="colourPicker/js/jquery-ui-touch-punch.js"></script>
    <script type="text/javascript" src="colourPicker/js/farbtastic.js"></script>
    <script type="text/javascript" src="colourPicker/js/general.js"></script>
    <script language="JavaScript" type="text/javascript">

        var currentColour="<?php echo $_SESSION['colour']; ?>";
        var currentPriority="<?php echo !empty($_SESSION['priority']) ? key($_SESSION['priority']) : 500; ?>";

    </script>

</html>
