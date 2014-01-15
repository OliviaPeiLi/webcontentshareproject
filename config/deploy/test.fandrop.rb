role :app, "54.243.131.84:22"
role :web, "54.243.131.84:22"
role :db,  "54.243.131.84:22", :primary => true

set :user, 'test'
set :password, 'RTjTHHvgkacPZeR'
set :deploy_to, "/home/test.fandrop"
set :branch, "master"
set :run_sync, false
set :run_tests, true
