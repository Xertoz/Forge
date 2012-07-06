<?php
    function stack($array,$title) {
?>
        <h1><?php echo htmlentities($title); ?></h1>
        <table>
            <thead>
                <tr>
                    <th>Parameter</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($array)): foreach ($array as $key => $value): ?>
                    <tr>
                        <td><?php echo htmlentities($key); ?></td>
                        <td><?php echo htmlentities($value); ?></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td><i>Empty</i></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
<?php
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>Forge <?php echo FORGE_VERSION; ?> - Debug Stack <?php echo date('Y-m-d H:i:s'); ?></title>
        <style type="text/css">
            tr:hover {
                color:#3366cc;
            }

            th, td {
                text-align:left;
                padding-right:1em;
            }
        </style>
    </head>
    <body>
        <h1>Exception</h1>
        <p><b><?php echo get_class($e); ?></b> was thrown in <b><?php echo htmlentities($e->getFile()); ?></b> at line <b><?php echo htmlentities($e->getLine()); ?></b> with the message <b><?php echo htmlentities($e->getMessage()); ?></b> and error code <b><?php echo htmlentities($e->getCode()); ?></b></p>
        <h2>Stack trace</h2>
        <table>
            <thead>
                <tr>
                    <th>Function</th>
                    <th>Arguments</th>
                    <th>File</th>
                    <th>Line</th>
                </tr>
            </thead>
            <?php foreach ($e->getTrace() as $trace): ?>
                <tr>
                    <td><?php echo isset($trace['function']) ? htmlentities($trace['function']) : null; ?></td>
                    <td>
                        <?php if (isset($trace['args'])): ?>
                            <table>
                                <?php foreach ($trace['args'] as $arg): ?>
                                    <tr>
                                        <td><?php echo htmlentities(var_export($arg)); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                            <?php endif; ?>
                    </td>
                    <td><?php echo isset($trace['file']) ? htmlentities($trace['file']) : null; ?></td>
                    <td><?php echo isset($trace['line']) ? htmlentities($trace['line']) : null; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php stack($_SERVER,'$_SERVER'); ?>
        <?php stack($_SESSION,'$_SESSION'); ?>
        <?php stack($_COOKIE,'$_COOKIE'); ?>
        <?php stack($_POST,'$_POST'); ?>
        <?php stack($_GET,'$_GET'); ?>
        <?php stack($_FILES,'$_FILES'); ?>
    </body>
</html>