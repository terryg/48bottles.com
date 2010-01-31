set :application, "48bottles.com"
set :repository,  "http://tgl.textdriven.com/svn/#{application}/trunk"

set :scm, :subversion

role :app, "#{application}"
role :db,  "#{application}", :primary => true

set :deploy_to, "/home/tgl/domains/48bottles.com/web"

set :checkout, "export"
set :use_sudo, false
set :user, "tgl"

after 'deploy:setup', 'forT8bottles:setup'

before 'deploy:restart', 'forT8bottles:permissions:fix', 'forT8bottles:symlink:application'

namespace :forT8bottles do
  task :setup, :exception => { :no_release => true } do
    run "chown -R #{user}:#{user} #{deploy_to}"
  end
end

namespace :deploy do
  task :finalize_update, :except => { :no_release => true } do
    run "chmod -R g+w #{latest_release}" if fetch(:group_writable, true)
  end

  task :restart do
    # do nothing, we're on mod-php
  end
end

namespace :forT8bottles do
  namespace :symlink do
    task :application, :except => { :no_release => true } do

    end
  end

  namespace :permissions do
    task :fix, :except => { :no_release => true } do

    end
  end
end


