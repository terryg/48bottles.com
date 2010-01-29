set :application, "48bottles.com"
set :repository,  "http://tgl.textdriven.com/svn/#{application}/trunk"

set :scm, :subversion

role :web, "48bottles.com"
role :app, "48bottles.com"
role :db,  "48bottles.com", :primary => true

set :use_sudo, false

set :deploy_to, "/users/home/tgl/domains/#{application}/web"
set :user, "tgl"

namespace :deploy do
  desc "The restart webserver"
  task :restart, :roles => :app do
    run "cd /users/home/tgl; ./etc/rc.d/48bottles-fcgi.sh"
  end
end

after :deploy, :make_database_yml

desc "After updating code we need to populate a new database.yml"
task :make_database_yml, :roles => :app do
  require "yaml"
  set :production_database_password, proc { Capistrano::CLI.password_prompt("Production database remote Password : ") }
  
  buffer = YAML::load_file('config/database.yml.example')
  # get ride of uneeded configurations
  buffer.delete('test')
  buffer.delete('development')
  
  # Populate production element
  buffer['login']['adapter'] = "mysql"
  buffer['login']['username'] = "tgl"
  buffer['login']['password'] = production_database_password
  buffer['login']['host'] = "localhost"
  buffer['production']['database'] = "bottles_production"
              
  put YAML::dump(buffer), "#{release_path}/config/database.yml", :mode => 0664
end

desc "After symlink, switch to shared themes directory"
task :symlink_files, :roles => :app do
  run "mkdir -p #{shared_path}/public_files" unless File.exists?("#{shared_path}/public_files")

  run "mv #{release_path}/public/files #{release_path}/public/files.local"
  run "ln -s #{shared_path}/public_files #{release_path}/public/files"
end
