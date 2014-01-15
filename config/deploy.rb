require 'capistrano/ext/multistage'
set :application, "fandrop"
set :repository,  "git@github.com:fandrop/fantoon-CI.git"

set :scm, :git
set :scm_username, 'radilr@yahoo.com'
set :scm_password, '851221'
set :use_sudo, false
set :git_enable_submodules, 1
after "deploy", "deploy:web:disable", "deploy:migrate", "deploy:js_pack", "deploy:web:enable", "deploy:cleanup", "deploy:sync", "deploy:tests"
after "deploy:upload", "deploy:js_pack", "deploy:sync"
after "deploy:rollback", "deploy:js_pack", "deploy:sync"

set :default_stage, 'test.fandrop'
set :deploy_via, :remote_cache
default_run_options[:pty] = true

depend :local, :command, "git"
depend :local, :command, "php"

#CHECK FOR PHP EXTENSIONS
depend :remote, :command, 'php'
depend :remote, :match, "php -m", "curl"
depend :remote, :match, "php -m", "xml"

set :remove_htaccess, false

namespace (:deploy) do

  #desc <<-DESC
  #  Overriding original task to exclude restart
  #DESC
  #task :default do
  #  update
  #end

  task :restart do
    #run "php #{latest_release}/web/index.php script/cc"
    #run "chmod g+w -R #{latest_release}/application/cache"
    #run "apache2ctl graceful"
  end

  desc <<-DESC
    Run the YUI compressor to pack javascript files
  DESC
  task :js_pack do
    run "cd ./current/ && php index.php js_packer"
  end
  
  desc <<-DESC
    Run the "php unittests" task, for testing the code
  DESC
  task :unittests do
      #run "cd #{current_release} && php unittests"
  end
  
  desc <<-DESC
    Test the new version
  DESC
  task :tests do
  	transaction do
  		on_rollback do
  			deploy.rollback.revision
  			deploy.js_pack
  			deploy.sync
  		end
	    run "chmod 775 #{current_release}/js/tests/linux/phantomjs"
	    run "cd #{current_release} && php qunit.php" if run_tests
  	end
  end
  
  desc <<-DESC
    Sync the servers
  DESC
  task :sync do
    #run "rsync --delete --copy-links --exclude \".git\" --exclude \"uploads/log-*\" --exclude \"uploads/*/*\" --exclude \"not_using/\" -ae \"ssh -i .ssh/deploy_key.rsa\" /home/fandrop/current root@174.129.20.240:/vz/private/141/home/fandrop" if run_sync
    #run "rsync --delete -ae \"ssh -i .ssh/deploy_key.rsa\" /home/fandrop/static root@174.129.20.240:/vz/private/141/home/fandrop" if run_sync
  end
  
  desc <<-DESC
    Run the "index.php migrate/version/<version>" task, for version
  DESC
  task :migrate do
    if defined?(version)
      run "cd #{latest_release} && php index.php migrate/version/#{version}"
    else
      run "cd #{latest_release} && php index.php migrate/latest"
    end
  end
    
  desc <<-DESC
    Finalize Things
  DESC
  task :finalize_update, :except => { :no_release => true } do
    run "chmod -R g+w #{latest_release}" if fetch(:group_writable, true)
    run "rm #{latest_release}/web/.htaccess" if remove_htaccess
    
    # mkdir -p is making sure that the directories are there for some SCM's that don't save empty folders
    
    run "mkdir -p -m0777 #{latest_release}/application/cache"
    run "chmod 777 -R #{latest_release}/application/cache"
    
    run "mkdir -p #{latest_release}/users/pages"
    run "chmod g+w -R #{latest_release}/users/pages"
    
    run "rm -rf #{latest_release}/application/modules/fantoon-extensions/libraries/pheanstalk"
    run "ln -nfs #{shared_path}/pheanstalk #{latest_release}/application/modules/fantoon-extensions/libraries/pheanstalk"
    
    run "rm -rf #{latest_release}/uploads"
    run "ln -nfs #{shared_path}/uploads #{latest_release}/uploads"
    [ 'uploads/screenshots', 'uploads/snapshots', 'uploads/links', 'uploads/users', 'uploads/pages', 'uploads/images', 'uploads/photos', 'uploads/images/users' ].each do |dir_path|
      run "mkdir -p -m0777 #{shared_path}/#{dir_path}"
    end
    
     run "cp #{previous_release}/sitemap*.xml #{latest_release}/"
  end
  
  namespace :web do
    desc <<-DESC
      Present a maintenance page to visitors. Disables your application's web \
      interface by writing a "maintenance.html" file to each web server. The \
      servers must be configured to detect the presence of this file, and if \
      it is present, always display it instead of performing the request.

      By default, the maintenance page will just say the site is down for \
      "maintenance", and will be back "shortly", but you can customize the \
      page by specifying the REASON and UNTIL environment variables:

        $ cap deploy:web:disable \\
              REASON="hardware upgrade" \\
              UNTIL="12pm Central Time"

      Further customization will require that you write your own task.
    DESC
    task :disable, :roles => :web, :except => { :no_release => true } do
      require 'erb'
      on_rollback { run "rm #{shared_path}/uploads/system/maintenance.html" }

      reason = ENV['REASON']
      deadline = ENV['UNTIL']

      template = File.read(File.join(File.dirname(__FILE__), "templates", "maintenance.rhtml"))
      result = ERB.new(template).result(binding)

      put result, "#{shared_path}/uploads/system/maintenance.html", :mode => 0644
    end
    task :enable do
	    #run "rm -rf #{shared_path}/cached-copy/" #fix 'cp -rPp' memory leak
	    run "rm #{shared_path}/uploads/system/maintenance.html"
    end
 
  end
end