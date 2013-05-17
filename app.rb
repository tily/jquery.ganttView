require 'rubygems'
require 'sinatra'
require 'mongo_mapper'
require 'mongomapper_id2'

## Models
class Project
  include MongoMapper::Document
  has_many :task
  auto_increment! :override => true
end

class Task
  include MongoMapper::Document
  belongs_to :project
  auto_increment! :override => true
end

## Settings
set :public_folder, '.'
configure do
  MongoMapper.database = 'pm_development'
end

helpers do 
  def json_data
    if @json_data
      return @json_data
    end
    request.body.rewind
    @json_data = JSON.parse(request.body.read)
    return @json_data
  end
end

## Controllers
before do
  content_type 'application/json'
end

get '/projects' do
  projects = Project.all
  projects.to_json
end

post '/projects' do
  logger.info json_data
  Project.create!(json_data)
end

put '/projects/:id' do
  project = Project.find(params[:id])
  project.update_attributes!(json_data)
  nil
end

delete '/projects' do
  project = Project.find(params[:id])
  project.destroy
  nil
end

get '/projects/:id' do
  project = Project.find(params[:id])
  project.tasks.to_json
end

post '/projects/:id' do
  project = Project.find(params[:id])
  project.tasks.create(json_data)
  nil
end

put '/projects/:id/:task_id' do
  project = Project.find(params[:id])
  task = project.tasks.find(params[:task_id])
  task.update_attributes!(json_data)
  nil
end

delete '/projects/:id/:task_id' do
  project = Project.find(params[:id])
  task = project.tasks.find(params[:task_id])
  task.destroy
  nil
end
