<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! isset($_SERVER['argv'])) exit('This script can be run with the console only');

class Migrate extends MX_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->config('migration');
        //check if migration is enable in configuration
        if(! $this->config->item('migration_enabled'))
        {
            show_error('Migration is disabled in configuration');
        }
        else
        {
            //load migration class
            $this->load->library('migration');
        }
    }

    public function index()
    {

        list($last,) = explode('_', end(glob($this->config->item('migration_path') . '[0-9]*_*.php')));

        $current = $this->db->get('migrations')->row()->version;

        echo "Current database version: \033[01;32m{$data['current_version']}\033[0m\r\n";
        if($current < $last)
        {
            echo "A new version is avaliable type \033[01;31m'migrate/latest'\033[0m to install it.\r\n";
        }
        /*foreach($data['files'] as $index => $file)
        {
            echo str_replace($this->config->item('migration_path'), '', $file)."\r\n";
        }*/
        echo "To generate a new migration type: \r\n\r\n";
        echo "  php index.php migrate/generate/<migraion_name> \r\n";
    }

    public function generate($name='')
    {
        $name = strtolower($name);
        if (glob($this->config->item('migration_path').'*_'.$name.'.php'))
        {
            die("This migration name already exists. Choose a different one \r\n");
        }
        $contents = file_get_contents($this->config->item('migration_path').'templates/migration.php');
        $filename = time().'_'.$name.'.php';
        $contents = str_replace("Migration_name", "Migration_".ucfirst($name), $contents);
        file_put_contents($this->config->item('migration_path').$filename, $contents);
        echo "Migration `".$filename."` successfully generated \r\n";
    }


    /*
    *	By default Migration class will use the newest migration files found in the filesystem.
    */
    public function latest()
    {
        //migrate to last version
        $version = $this->migration->latest();
        $this->_show_results($version);

    }


    /*
    *	The current migration is whatever is set for $config['migration_version']
    *	in application/config/migration.php.
    */
    public function current()
    {

        $version = $this->migration->current();

        $this->_show_results($version);
    }

    /*
    * Migrate changes to a specified version
    */
    public function version($version="")
    {
        if(!$version)
        {
            $this->index();
        }
        $version =$this->migration->version($version);
        $this->_show_results($version);
    }


    /*
    *	Display results related to migration
    */
    private function _show_results($version)
    {


        if( ! $version)
        {
            show_error($this->migration->error_string());
        }
        else
        {
            list($last,) = explode('_', end(glob($this->config->item('migration_path') . '[0-9]*_*.php')));
            $current = $this->db->get('migrations')->row()->version;

            echo "Current database version: \033[01;32m{$current}\033[0m\r\n";
            if($current < $last)
            {
                echo "A new version is avaliable type \033[01;31m'migrate/latest'\033[0m to install it.\r\n";
            }

        }
    }

}

/* End of file migrate.php */
/* Location: ./application/controllers/migrate.php */