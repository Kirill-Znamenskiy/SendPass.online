<?php
require(__DIR__.'/vendor/autoload.php');

$csh = \kz\console_tools\ConsoleScriptHelper::create()->start_script();

$csh->make_symlinks([
    './public/favicon.ico' => './favicons/favicon.ico',
    './public/apple-touch-icon.png' => './favicons/apple-touch-icon.png',
    './public/apple-touch-icon-precomposed.png' => './favicons/apple-touch-icon-precomposed.png',

    './public/icon.svg' => './logos/logo-64x64.svg',
    './public/icon.png' => './logos/logo-64x64.png',
    './public/icon-inverted.svg' => './logos/logo-inverted-64x64.svg',
    './public/icon-inverted.png' => './logos/logo-inverted-64x64.png',

    './public/logo.svg' => './logos/logo-320x320.svg',
    './public/logo.png' => './logos/logo-320x320.png',
    './public/logo-64.svg' => './logos/logo-64x64.svg',
    './public/logo-64.png' => './logos/logo-64x64.png',
    './public/logo-320.svg' => './logos/logo-320x320.svg',
    './public/logo-320.png' => './logos/logo-320x320.png',
    './public/logo-inverted.svg' => './logos/logo-inverted-320x320.svg',
    './public/logo-inverted.png' => './logos/logo-inverted-320x320.png',
    './public/logo-inverted-64.svg' => './logos/logo-inverted-64x64.svg',
    './public/logo-inverted-64.png' => './logos/logo-inverted-64x64.png',
    './public/logo-inverted-320.svg' => './logos/logo-inverted-320x320.svg',
    './public/logo-inverted-320.png' => './logos/logo-inverted-320x320.png',


]);

$csh->exec_commands([
    $csh->commands_chdir(),

    'npm run production',

    'php artisan ide-helper:generate --ansi',
    'php artisan ide-helper:meta --ansi',
    //'php artisan ide-helper:models --ansi -N --no-interaction',
]);

$csh->check_git_problems();
$csh->exec_commands([
    $csh->commands_chdir(),

    $csh::commands_git_config_for_commits(),
    $csh::commands_git_commit_and_push(),

    //$csh::commands_composer_update(),
]);


$csh->finish_script();

