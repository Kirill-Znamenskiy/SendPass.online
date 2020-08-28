<?php
require(__DIR__.'/vendor/autoload.php');

require(__DIR__.'/inself-local-commit-and-push.php');

$csh = \KZ\ConsoleTools\ConsoleScriptHelper::create()->start_script();


if (!isset($remote_sshost)) $remote_sshost = ['vv','rr.zkiy.ru',222];
if (!isset($remote_project_dir_path)) $remote_project_dir_path = '/home/vv/sendpass.online';
if (!isset($remote_cd_command)) $remote_cd_command = 'cd '.$remote_project_dir_path.';';
if (!isset($remote_branch_name)) $remote_branch_name = 'master';
if (!isset($is_with_composer_update)) $is_with_composer_update = false;


$csh->exec_commands([
    //'rsync -av -e "ssh -p 222" vv@rr.zkiy.ru:'.$remote_project_dir_path.'/.env /Users/hw/Projects/sendpass.online/.env.at.vv.rr.zkiy.ru',
    'rsync -av -e "ssh -p 222" /Users/hw/Projects/sendpass.online/.env.remote.'.$remote_branch_name.' vv@rr.zkiy.ru:'.$remote_project_dir_path.'/.env',
]);


//$csh->finish_script(true);


$csh->check_by_ssh_git_problems($remote_sshost,$remote_cd_command);
$csh->exec_commands_by_ssh($remote_sshost,[
    $remote_cd_command,

    $csh::commands_git_config_for_readonly(),
    //$csh::commands_git_pull($remote_branch_name),
    $csh::commands_git_pull('develop'),

    ($is_with_composer_update ? $csh::commands_composer_update((is_string($is_with_composer_update) ? $is_with_composer_update : null)) : null),

    'crontab ./crontab;',

    'chmod u+x ./artisan',

    $csh::commands_chmod_dirrs('./runtime','0775'),
    //$csh::commands_chmod_files('./runtime','0664'),

    $csh::commands_chmod_dirrs('./storage','0775'),
    //$csh::commands_chmod_files('./storage','0664'),

    $csh::commands_chmod_dirrs('./bootstrap/cache','0775'),
    //$csh::commands_chmod_files('./bootstrap/cache','0664'),

    './artisan config:clear',
    'rm ./bootstrap/cache/config.php', // force config:clear
    './artisan config:cache',

    './artisan route:clear',
    './artisan route:cache', // not able closures routes, only controller based routes!

]);



$csh->finish_script();

