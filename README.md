SYNC: a sample app to practice web development workflow
=============================================

To get started as a developer using docker:

1. fork this project : git clone https://github.com/jimwitte/sync.git
2. use composer update to install dependencies (smarty template engine)
3. create folders "app/views/cache" and 'app/views/templates_c' and "app/views/configs". These are used by the smarty template engine.
4. chmod a+w for 'cache' and 'templates_c' folders
5. get the docker image: docker pull jimwitte/sync:latest
6. run the container: docker run -d -p 80:80 --name sync -v "path-to-app-folder"/app:/var/www/html jimwitte/sync:latest
7. changes you make to the code will be reflected in running container
8. commit and push changes to git repo to auto-build new docker image
