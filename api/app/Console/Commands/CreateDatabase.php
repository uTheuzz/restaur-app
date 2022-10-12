<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CreateDatabase:createdb {data?} {driver?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = $this->argument('data');
        $driver = $this->argument('driver');
        $schemaName = $data['database'];
        $user = $data['database_user'];
        $password = $data['database_password'];
        $charset = 'utf8mb4';
        $collation = 'utf8mb4_unicode_ci';
        $queryCreateDB = null;
        $queryCreateUser = null;
        $queryResetPrivileges = null;

        if($driver == 'mysql') {
            $queryCreateDB = "CREATE DATABASE IF NOT EXISTS $schemaName CHARACTER SET $charset COLLATE $collation";
            $queryCreateUser = "CREATE USER '$user'@'localhost' IDENTIFIED BY '$password'";
            $queryResetPrivileges = "GRANT SELECT ON * . * TO '$user'@'localhost'";
            $querySetNewPrivileges = "GRANT ALL PRIVILEGES ON `$schemaName` . * TO '$user'@'localhost'";
        } else if ($driver == 'pgsql') {
            $query = "CREATE DATABASE $schemaName";
        }

        DB::statement($queryCreateDB);
        DB::statement($queryCreateUser);
        DB::statement($queryResetPrivileges);
        DB::statement($querySetNewPrivileges);
    }
}
