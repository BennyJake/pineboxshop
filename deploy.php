<?php
namespace Deployer;

require 'vendor/autoload.php';
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
    ->multiplexing(false)
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
    'phing-copy',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

task('phing-copy', function(){

    var_dump($_SERVER);

    $accessToken = $_SERVER['VAULT_WEB_TOKEN'];
    $baseUrl = $_SERVER['VAULT_WEB_ROOT'];

    $client = new \Psecio\Vaultlib\Client($accessToken, $baseUrl);

    // If the vault is sealed, unseal it
    if ($client->isSealed() == true) {
        $client->unseal($_SERVER['VAULT_KEY_1']);
        $client->unseal($_SERVER['VAULT_KEY_2']);
        $client->unseal($_SERVER['VAULT_KEY_3']);
    }

    $result = $client->getSecret('pineboxshop');

    if(isset($result) && !empty($result)){
        writeln('Pulled secrets!');
    }

    $variables = $result['data']['data'];

    writeln(run('[ -d "{{release_path}}" ] && echo "Directory {{release_path}} exists."'));
    writeln(run('[ -d "{{release_path}}/vendor" ] && echo "Directory {{release_path}}/vendor exists."'));

    run('cd {{release_path}} && phing \
   build \
   -f build.xml \
    -D PINEBOXSHOP_PORT="' . $variables['smtp_port'] . '" \
    -D PINEBOXSHOP_HOST="' . $variables['smtp_host'] . '" \
    -D PINEBOXSHOP_USER="' . $variables['smtp_user'] . '" \
    -D PINEBOXSHOP_PASS="' . $variables['smtp_pass'] . '" \
    -D PINEBOXSHOP_FROM="' . $variables['smtp_from'] . '" \
    -D PINEBOXSHOP_TOEM="' . $variables['smtp_toem'] . '" \
    -D PINEBOXSHOP_TONM="' . $variables['smtp_tonm'] . '";');

});

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
