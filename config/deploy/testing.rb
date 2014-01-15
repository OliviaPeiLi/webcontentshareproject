role :app, "testing.fantoon.com"
role :web, "testing.fantoon.com"
role :db,  "testing.fantoon.com", :primary => true

set :user, 'testing'
set :password, 'G_<3g,x+Sygrk<p'
set :deploy_to, "/home/testing"
set :branch, "master"
