role :app, "23.21.203.24:8024"
role :web, "23.21.203.24:8024"
role :db,  "23.21.203.24:8024"

set :user, 'test1'
set :password, 'RTjTHHvgkacPZeR'
set :deploy_to, "/home/test.fandrop"
set :branch, "master"
set :run_sync, false
