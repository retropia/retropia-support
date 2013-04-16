# -*- mode: ruby; tab-width: 2; indent-tabs-mode: nil -*-

load "deploy" if respond_to?(:namespace)

require "rubygems"
require "bundler/setup"

require "capistrano/php"

set :application, "retropia-support"
set :environments, [:production]

###########################################

set :user, "deploy"

environments.each do |environment|
  task environment do
    role :web, "#{application}.#{environment}.protomou.se"
    set :deploy_to, "/var/#{user}/#{environment}/#{application}"
  end
end

set :scm, "git"
set :repository, "git@github.com:retropia/#{application}.git"
set :branch, "master"
set :git_enable_submodules, 1
default_run_options[:pty] = true
ssh_options[:forward_agent] = true
set :use_sudo, false
set :deploy_via, :remote_cache

namespace :composer do

  desc "run composer install and ensure all dependencies are installed"
  task :install do
    run "cd #{release_path} && composer install"
  end

end

after "deploy:setup" do
  run "mkdir -p #{shared_path}/conf"
end

after "deploy:create_symlink" do 
  run "ln -sf #{shared_path}/conf/ost-config.php #{current_path}/htdocs/include/ost-config.php"
end

after "deploy:finalize_update", "composer:install"