<?php
require(__DIR__.'/vendor/autoload.php');

$csh = \kz\console_tools\ConsoleScriptHelper::create()->start_script();


$csh->exec_commands([
    //'rsync -av -e "ssh -p 222" vv@rr.zkiy.ru:/home/vv/sendpass.online/.env /Users/hw/Projects/sendpass.online/.env.at.vv.rr.zkiy.ru',
    'rsync -av -e "ssh -p 222" /Users/hw/Projects/sendpass.online/.env.at.prod vv@rr.zkiy.ru:/home/vv/sendpass.online/.env',
]);


//$csh->finish_script();


$csh->check_by_ssh_git_problems(['vv','rr.zkiy.ru',222],'cd /home/vv/sendpass.online;');
$csh->exec_commands_by_ssh(['vv','rr.zkiy.ru',222],[
    'cd /home/vv/sendpass.online;',

    $csh::commands_git_config_for_readonly(),
    $csh::commands_git_pull(),

    //$csh::commands_composer_update(),



    'chmod 0770 ./runtime',

    $csh::commands_chmod_dirrs('./storage','0775'),
    //$csh::commands_chmod_files('./storage','0664'),

    $csh::commands_chmod_dirrs('./bootstrap/cache','0775'),
    //$csh::commands_chmod_files('./bootstrap/cache','0664'),

    'php artisan config:clear',
    'php artisan config:cache',

    'php artisan route:clear',
    'php artisan route:cache', // not able closures routes, only controller based routes!

]);



$csh->finish_script();

