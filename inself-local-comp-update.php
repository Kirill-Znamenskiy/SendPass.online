<?php
require(__DIR__.'/vendor/autoload.php');

$csh = \KZ\ConsoleTools\ConsoleScriptHelper::create()->start_script();

$csh->exec_commands([
    $csh->commands_chdir(),

    $csh::commands_composer_update(),
]);


$csh->finish_script();

