# Avrillo-Kanye

## Excuses, excuses...
Apologies for the delay on this one, I've been quite ill.  I have focused more on the back end functionality and the front end has come in play last minute.  There are a number of things that I've done for speed and wouldn't do in a production situation.

1.  Docker Setup for Vue is not quite there.  You'll need to run this in dev mode to demo the app.  With more time I'd have committed to getting this across the line.

2.  CORS - I have turned this off more or less in the back end to ensure smooth sailing with the demo.  I don't know what IP's or Ports you'll have running, so it was easier to leave them open and ensure you can run the project.

3.  Doc Blocks - I've tried where possible to add these in, but due to time constraints, it may have slipped, particularly where the functionality seemed easy enough to follow.


## Running the app.

### Docker
The app runs in docker and makes use of docker compose for easy of deployment.  The back end is a standard Laravel API while the front end will be a dev version of a vue 3 + vite app (although the vite functionality is limited!)

1. Spinning it up:
1a. Back End

* Open windows power shell / terminal / your choice of console, and navigate to the root of the project.
* Run `docker-compose up` to spin up your containers.
* Once they're up and running (the build may take some time),  populate your .env file with the .env.example file provided in `api/env.example`.  This has already been populated so you shouldn't need to tweak it for dev purposes.
* Run `docker-compose exec avrillo_app_api composer install` to install composer packages.
* run `docker-compose exec avrillo_app_api php artisan key:generate` to generate your app key.
* run `docker-compose exec avrillo_app_api php artisan migrate` to migrate the database.  Note: any errors here may suggest you need to edit your .env file.  At this point you'll have all your required tables, etc.
* Visit `http://localhost:8000` to check if laravel is working.  You can also visit `http://localhost:8000/api/health-check` to see the 'access denied' message!

1b. Front End

* CD into the client directory.
* Type `npm run dev`
* This should start a vite/vue server to serve as your front end.
* You can visit `http://localhost:8001/` now but you may get 'access denied' as we haven't generated any keys for you yet.
* Generate your secret and app id by changing directory to root.
* type `docker-compose exec avrillo_app_api php artisan app:generate "test app"`.  The name isn't important really.  After a moment or two you'll be issued with a client ID and a secret.
* Navigate to the client folder, either in your cli or IDE, and open the `.env` file.
* Update the values with your newly generated keys.

That's it!  Now you can access Kanyes quotes.

To begin with it won't display any quotes.  You can choose `get quotes` to load quotes from cache.  If none exist in cache to begin with, then it will favour the restful API, so the first call may be slower than normal.  You may also find speed varies depending how much ram you've given Docker. 

You can click `refresh quotes` to generate and save another 5 quotes into cache.
