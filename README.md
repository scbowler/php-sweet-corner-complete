<img align="right" width="150" src="https://github.com/Learning-Fuze/react_starter/blob/php/public/dist/php-react.png">

# PHP React Starter

> This repo contains boilerplate code to aid in the creation of a new React app with Redux and PHP back end. Follow the below setup instructions to get started.

### Setup Instructions

> 1. Fork this repo
> 1. Clone your forked copy of this repo
>    - `git clone https://github.com/[Your Username]/react_starter.git`
> 1. Change directory into the newly cloned repo
>    - `cd react_starter`
> 1. Switch to the php branch
>    - `git checkout php`
> 1. Install dependencies 
>    - `npm install`
> 1. Use MAMP or similar program to start Apache and MySQL servers
>    - Set root directory of server to the `public` folder of this project
>    - Set Apache port to `8000`
>    - Use phpMyAdmin (or similar) to create a database
> 1. Start dev server
>    - `npm start`
> 1. Open a browser and navigate to `localhost:3000` You should see "Welcome to React".

### Build For Deployment

> 1. Run webpack to build project
>    - `npm run build`
> 
> **NOTE:** *After bundling and placing on a web server. The `public` folder should be the web root*
