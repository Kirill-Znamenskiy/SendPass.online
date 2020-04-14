<?php
require(__DIR__.'/vendor/autoload.php');

$csh = \kz\console_tools\ConsoleScriptHelper::create()->start_script();

$remote_sshost = ['vv','rr.zkiy.ru',222];
$remote_cd_command = 'cd /home/vv/sendpass.online;';
$csh->check_by_ssh_git_problems($remote_sshost,$remote_cd_command);
$csh->exec_commands_by_ssh($remote_sshost, [
    $remote_cd_command,

    $csh::commands_git_config_for_readonly(),
    $csh::commands_git_pull('develop'),

    $csh::commands_composer_update(),
]);


$csh->finish_script();
