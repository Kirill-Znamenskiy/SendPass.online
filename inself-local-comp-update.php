<?php
require(__DIR__.'/vendor/autoload.php');

$csh = \kz\console_tools\ConsoleScriptHelper::create()->start_script();

$csh->check_git_problems();
$csh->exec_commands([
    $csh->commands_chdir(),

    $csh::commands_git_config_for_commits(),
    $csh::commands_git_commit_and_push(),

    $csh::commands_composer_update(),
]);


$csh->finish_script();

