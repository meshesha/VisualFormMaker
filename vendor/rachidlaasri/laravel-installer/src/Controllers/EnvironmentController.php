<?php

namespace RachidLaasri\LaravelInstaller\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use PDO;
use Illuminate\Support\Facades\DB;
use RachidLaasri\LaravelInstaller\Events\EnvironmentSaved;
use RachidLaasri\LaravelInstaller\Helpers\EnvironmentManager;
use Validator;

class EnvironmentController extends Controller
{
    /**
     * @var EnvironmentManager
     */
    protected $EnvironmentManager;

    /**
     * @param EnvironmentManager $environmentManager
     */
    public function __construct(EnvironmentManager $environmentManager)
    {
        $this->EnvironmentManager = $environmentManager;
    }

    /**
     * Display the Environment menu page.
     *
     * @return \Illuminate\View\View
     */
    public function environmentMenu()
    {
        return view('vendor.installer.environment');
    }

    /**
     * Display the Environment page.
     *
     * @return \Illuminate\View\View
     */
    public function environmentWizard()
    {
        $envConfig = $this->EnvironmentManager->getEnvContent();

        return view('vendor.installer.environment-wizard', compact('envConfig'));
    }

    /**
     * Display the Environment page.
     *
     * @return \Illuminate\View\View
     */
    public function environmentClassic()
    {
        $envConfig = $this->EnvironmentManager->getEnvContent();

        return view('vendor.installer.environment-classic', compact('envConfig'));
    }

    /**
     * Processes the newly saved environment configuration (Classic).
     *
     * @param Request $input
     * @param Redirector $redirect
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveClassic(Request $input, Redirector $redirect)
    {
        $message = $this->EnvironmentManager->saveFileClassic($input);

        event(new EnvironmentSaved($input));

        return $redirect->route('LaravelInstaller::environmentClassic')
                        ->with(['message' => $message]);
    }

    /**
     * Processes the newly saved environment configuration (Form Wizard).
     *
     * @param Request $request
     * @param Redirector $redirect
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveWizard(Request $request, Redirector $redirect)
    {
        $rules = config('installer.environment.form.rules');
        $messages = [
            'environment_custom.required_if' => trans('installer_messages.environment.wizard.form.name_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $redirect->route('LaravelInstaller::environmentWizard')->withInput()->withErrors($validator->errors());
        }

        if (! $this->checkDatabaseConnection($request)) {
            return $redirect->route('LaravelInstaller::environmentWizard')->withInput()->withErrors([
                'database_connection' => trans('installer_messages.environment.wizard.form.db_connection_failed'),
            ]);
        }

        $results = $this->EnvironmentManager->saveFileWizard($request);

        event(new EnvironmentSaved($request));

        return $redirect->route('LaravelInstaller::database')
                        ->with(['results' => $results]);
    }

    /**
     * TODO: We can remove this code if PR will be merged: https://github.com/RachidLaasri/LaravelInstaller/pull/162
     * Validate database connection with user credentials (Form Wizard).
     *
     * @param Request $request
     * @return bool
     */
    private function checkDatabaseConnection(Request $request)
    {
        $connection = $request->input('database_connection');

        $settings = config("database.connections.$connection");

        $database_name = $request->input('database_name');
        $hostname = $request->input('database_hostname');
        $user = $request->input('database_username');
        $password = $request->input('database_password');
        $port = $request->input('database_port');
        if($connection == "sqlite"){
            $database_name = database_path("$database_name.sqlite");
        }
        $status = $this->createDatabase($connection, $hostname, $database_name, $user, $password);
        
        config([
            'database' => [
                'default' => $connection,
                'connections' => [
                    $connection => array_merge($settings, [
                        'driver' => $connection,
                        'host' => $hostname,
                        'port' => $port,
                        'database' => $database_name,
                        'username' => $user,
                        'password' => $password,
                    ]),
                ],
            ],
        ]);
        
        DB::purge();

        try {
            DB::connection()->getPdo();

            return true;
        } catch (Exception $e) {
            //dd($e->getMessage());
            return false;
        }
    }

    //create db - meshesha
    private function createDatabase($type, $host, $databaseName, $username, $password)
    {
        switch ($type){
            case 'sqlite':
                try{ 
                    if (!file_exists($databaseName)) {
                        file_put_contents($databaseName, '');
                    }
                    return true;
                } catch(Exception $exception){ 
                    //dd($exception->getMessage()); 
                    return false;
                }
                break;
            case 'mysql':
                $dsn = "mysql:host=$host";
                try{
                    $dbh = new PDO($dsn, $username, $password);
                    $dbh->exec("CREATE DATABASE IF NOT EXISTS `$databaseName`");
                    return true;
                }catch (PDOException $e){
                    //echo 'Connection failed: ' . $e->getMessage();
                    return false;
                }
                return false;
                break;
            default:
                return false;
                break;
        }
    }
}
