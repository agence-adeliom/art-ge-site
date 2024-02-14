<?php

namespace Deployer;

require 'recipe/symfony.php';

set('repository_name', 'agence-adeliom/art-ge-site');
set('repository', 'git@github.com:agence-adeliom/art-ge-site.git');
set('http_user', 'uid251956');
set('http_group', 'gid251956');
set('bin/php', '/usr/bin/php-8.2');
set('bin/composer', '/usr/bin/composer2_php8.2');

add('shared_files', []);
add('shared_dirs', [
    'public/upload'
]);
add('writable_dirs', [
    'public/build',
    'public/medias',
    'var/upload',
    'var/pdf',
]);
set('writable_mode', "chmod");
set('writable_recursive', true);

import('.secrets/.inventory.yaml');

task('dotenv:set-env', function (): void {
    if (test('[ -f {{release_path}}/.env.local ]')) {
        run('rm {{release_path}}/.env.local');
    }

    if (get("database_url")) {
        run('echo "DATABASE_URL={{database_url}}" >> {{release_path}}/.env.local');
    }

    run('touch {{release_path}}/.env.local');
    run('echo "APP_ENV={{app_env}}" >> {{release_path}}/.env.local');
    run('echo "MAILER_DSN=smtp://"{{brevo_user}}":{{brevo_pass}}@smtp-relay.brevo.com:587" >> {{release_path}}/.env.local');
});

task('npm:build', static function (): void {
    runLocally('npm run build');
    upload('public/build/', '{{release_or_current_path}}/public/build/');
});

task('upload:csv', static function (): void {
    runLocally('npm run build');
    upload('var/datas/', '{{release_or_current_path}}/var/datas/');
});

task('set-permissions', function () {
    cd('{{release_path}}');
    run("chown -R {{http_user}}:{{http_group}} {{release_path}}/public/build/");
    run("chown -R {{http_user}}:{{http_group}} {{release_path}}/public/bundles/");
    run("chmod -R 775 {{release_path}}/public/build/");
    run("chmod -R 775 {{release_path}}/public/bundles/");
    run("chmod -R g+w {{release_path}}/var/cache");
    run("chmod -R g+w {{release_path}}/var/log");
    run("mkdir -p {{release_path}}/var/upload/files");
    run("chmod -R 775 {{release_path}}/var/upload/files");
});

before('deploy:vendors', 'dotenv:set-env');
before('deploy:cache:clear', 'database:migrate');
before('deploy:symlink', 'npm:build');
after('deploy:failed', 'deploy:unlock');
after('npm:build', 'set-permissions');
