# This defines a deployment "recipe" that you can feed to switchtower
# (http://manuals.rubyonrails.com/read/book/17). It allows you to automate
# (among other things) the deployment of your application.

# =============================================================================
# REQUIRED VARIABLES
# =============================================================================
# You must always specify the application and repository for every recipe. The
# repository must be the URL of the repository you want this recipe to
# correspond to. The deploy_to path must be the path on each machine that will
# form the root of the application path.

set :application, "48bottles.com"
set :repository, "http://tgl.textdriven.com/svn/#{application}/trunk"

# =============================================================================
# RAILS VERSION
# =============================================================================
# Use this to freeze your deployment to a specific rails version.  Uses the rake
# init task run in after_symlink below.

# set :rails_version, 4928

# TODO: test this works and I can remove the restart task and use the cleanup task
set :use_sudo, false

# =============================================================================
# ROLES
# =============================================================================
# You can define any number of roles, each of which contains any number of
# machines. Roles might include such things as :web, or :app, or :db, defining
# what the purpose of each machine is. You can also specify options that can
# be used to single out a specific subset of boxes in a particular role, like
# :primary => true.

role :web, "tgl.textdriven.com"
role :app, "tgl.textdriven.com"
role :db,  "tgl.textdriven.com", :primary => true

# =============================================================================
# OPTIONAL VARIABLES
# =============================================================================
# set :deploy_to, "/path/to/app" # defaults to "/u/apps/#{application}"
# set :user, "flippy"            # defaults to the currently logged in user
# set :scm, :darcs               # defaults to :subversion
# set :svn, "/path/to/svn"       # defaults to searching the PATH
# set :darcs, "/path/to/darcs"   # defaults to searching the PATH
# set :cvs, "/path/to/cvs"       # defaults to searching the PATH
# set :gateway, "gate.host.com"  # default to no gateway

set :deploy_to, "/users/home/tgl/domains/#{application}/web"
set :user, "tgl"

# =============================================================================
# SSH OPTIONS
# =============================================================================
# ssh_options[:keys] = %w(/path/to/my/key /path/to/another/key)
# ssh_options[:port] = 25

# =============================================================================
# TASKS
# =============================================================================
# Define tasks that run on all (or only some) of the machines. You can specify
# a role (or set of roles) that each task should be executed on. You can also
# narrow the set of servers to a subset of a role by specifying options, which
# must match the options given for the servers to select (like :primary => true)

# no sudo access on txd :)

namespace :deploy do
  desc "The restart webserver"
  task :restart, :roles => :app do
    run "cd /users/home/tgl; ./etc/rc.d/48bottles.sh"
  end
end


desc "After updating code we need to populate a new database.yml"
task :after_update_code, :roles => :app do
  require "yaml"
  set :production_database_password, proc { Capistrano::CLI.password_prompt("Pro
duction database remote Password : ") }
  
  buffer = YAML::load_file('config/database.example.yml')
  # get ride of uneeded configurations
  buffer.delete('test')
  buffer.delete('development')
  
  # Populate production element
  buffer['production']['adapter'] = "mysql"
  buffer['production']['database'] = "48bottles"
  buffer['production']['username'] = "tgl"
  buffer['production']['password'] = production_database_password
  buffer['production']['host'] = "localhost"
              
  put YAML::dump(buffer), "#{release_path}/config/database.yml", :mode => 0664
end

