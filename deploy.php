<?php
namespace Deployer;

require 'recipe/common.php';

// Project name
set('application', 'my_project');

// Project repository
set('repository', 'git@github.com:BennyJake/pineboxshop.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);

// Shared files/dirs between deploys 
set('shared_files', []);
set('shared_dirs', []);

// Writable dirs by web server 
set('writable_dirs', []);

// number of deployments saved on server
set('keep_releases', 5);

// Hosts

host('104.248.181.33')
    ->port(22)
    ->stage('production')
    ->forwardAgent(true)
    ->multiplexing(true)
    ->user('bennyjake')
    ->addSshOption('StrictHostKeyChecking', 'no')
    ->set('deploy_path', '/var/www/pinebox.shop');
    

// Tasks

task('pwd', function () {
    $result = run('pwd');
    writeln("Current dir: $result");
});

desc('Deploy your project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
