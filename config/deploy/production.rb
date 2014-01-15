role :app, "107.20.199.204:8022"
role :web, "107.20.199.204:8022"
role :db,  "107.20.199.204:8022"
#role :app, "23.21.203.24"
#role :web, "23.21.203.24"
#role :db,  "23.21.203.24"

set :user, 'fandrop'
set :password, 'TZeZxP3M6Euvi9j'
set :deploy_to, "/home/fandrop"
set :branch, "production"
set :run_sync, true
set :run_tests, false
