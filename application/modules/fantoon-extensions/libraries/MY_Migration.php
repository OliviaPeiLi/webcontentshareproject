<?php
class MY_Migration extends CI_Migration {
	
	public function __construct($config = array()){
		parent::__construct();
		// Load migration language
		$this->lang->load('migration');
		$this->load->config('migrate/migration');	
		
		// They'll probably be using dbforge
		$this->load->dbforge();
	}
	
	public function latest()
	{
        $current = $this->db->get('migrations')->row()->version;
        $files = glob($this->config->item('migration_path') . '[0-9]*_*.php');
		//list($last,) = explode('_', end());
		echo "Database updated on: ".date('H:i:s d M Y', $current)." \r\n";
		$last = 0;
        foreach ($files as $file) {
        	list($version, $name) = explode('_', basename($file));
        	if ($version > $current) {
        		echo "Migrating to: `".$this->humanize($name)."` Created on: ".date('H:i:s d M Y', $version)."\r\n";
        		$last = $this->version((int) $version);
        		if (!$last) die("ERR: ".$this->_error_string);
        	}
        }
        die();
	}
	
	public function needs_update() {
		 list($last,) = explode('_', end(explode('/', end(glob($this->config->item('migration_path') . '[0-9]*_*.php')))));
        $current = $this->db->get('migrations')->row()->version;
        return $current < $last;
	}
	
	private function humanize($str) {
		$str = trim(strtolower($str));
		$str = preg_replace('/[^a-z0-9\s+]/', '', $str);
		$str = preg_replace('/\s+/', ' ', $str);
		
		return implode(' ', array_map('ucwords', explode(' ', $str)));
	}
	
	public function version($target_version)
	{
		$start = $current_version = $this->_get_version();
		$stop = $target_version;

		if ($target_version > $current_version)
		{
			// Moving Up
			++$start;
			++$stop;
			$step = 1;
		}

		else
		{
			// Moving Down
			$step = -1;
		}
		
		$method = $step === 1 ? 'up' : 'down';
		$migrations = array();

		// We now prepare to actually DO the migrations
		// But first let's make sure that everything is the way it should be
		
			$f = glob($this->config->item('migration_path') . $target_version.'_*.php');

			// Only one migration per step is permitted
			if (count($f) > 1)
			{
				$this->_error_string = sprintf($this->lang->line('migration_multiple_version'), $target_version);
				return FALSE;
			}

			// Migration step not found
			if (count($f) == 0)
			{
				// If trying to migrate up to a version greater than the last
				// existing one, migrate to the last one.
				if ($step == 1)
				{
					break;
				}

				// If trying to migrate down but we're missing a step,
				// something must definitely be wrong.
				$this->_error_string = sprintf($this->lang->line('migration_not_found'), $i);
				return FALSE;
			}
			
			$file = basename($f[0]);
			$name = basename($f[0], '.php');

			// Filename validations
			if (preg_match('/^\d{10}_(\w+)$/', $name, $match))
			{
				$match[1] = strtolower($match[1]);

				// Cannot repeat a migration at different steps
				if (in_array($match[1], $migrations))
				{
					$this->_error_string = sprintf($this->lang->line('migration_multiple_version'), $match[1]);
					return FALSE;
				}				

				include $f[0];
				$class = 'Migration_' . ucfirst($match[1]);
				
				if ( ! class_exists($class))
				{
					$this->_error_string = sprintf($this->lang->line('migration_class_doesnt_exist'), $class);
					return FALSE;
				}

				if ( ! is_callable(array($class, $method)))
				{
					$this->_error_string = sprintf($this->lang->line('migration_missing_'.$method.'_method'), $class);
					return FALSE;
				}
				
				$migrations[] = $match[1];
			}
			else
			{
				$this->_error_string = sprintf($this->lang->line('migration_invalid_filename'), $file);
				return FALSE;
			}

		log_message('debug', 'Current migration: ' . $current_version);

		//$version = $i + ($step == 1 ? -1 : 0);

		// If there is nothing to do so quit
		if ($migrations === array())
		{
			return TRUE;
		}

		log_message('debug', 'Migrating from ' . $method . ' to version ' . $current_version);

		// Loop through the migrations
		foreach ($migrations AS $migration)
		{
			// Run the migration class
			$class = 'Migration_' . ucfirst(strtolower($migration));
			call_user_func(array(new $class, $method));

			//$current_version += $step;
			$current_version = $target_version;
			$this->_update_version($current_version);
		}

		log_message('debug', 'Finished migrating to '.$current_version);

		return $current_version;
	}
}