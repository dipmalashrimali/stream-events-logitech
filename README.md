# Stream Events
## _Laravel 10 + Vue3 - FaceBook Login_

A Laravel 10 + Socialite + Vite + Vue3 + Bootstrap. Laravel with user authentication using FaceBook and list out events/records vue3.

## Application Features

- **Login with FaceBook**
- **List out Followers, Donations, Subscribers, Merchant Sales data as event(Merge all records)**
- **Load data on scroll**
- **Dummy data seeders**

## URL
[http://localhost:8000/](http://localhost:8000/)

**Note: Please access the application using the URL `http://localhost:8000/` for proper functionality. The Facebook callback function may not work as expected when accessed via `127.0.0.1`.**

## Installation Instructions

Install the dependencies and devDependencies and start the server. Run following commands in your console.

```sh
git clone https://github.com/dipmalashrimali/stream-events-logitech
```
- Create a MySQL database for the project
- Configure your ```.env``` file (VERY IMPORTANT)
    - Add ``FACEBOOK`` configurations in ```.env``` file
    - To create an application please follow this steps - https://theonetechnologies.com/blog/post/how-to-get-facebook-application-id-and-secret-key, https://developers.facebook.com/docs/development/create-an-app/

**Configuration for Facebook Authentication and Sanctum**

```sh
# Replace 'Your facebook application client id' with your actual Facebook application client id
FACEBOOK_CLIENT_ID={Your facebook application client id}

# Replace 'Your facebook application client secret' with your actual Facebook application client secret
FACEBOOK_CLIENT_SECRET={Your facebook application client secret}

# This is the URL where Facebook will redirect after authentication
FACEBOOK_REDIRECT_URI=http://localhost:8000/facebook/callback 

# Define the allowed domains for Sanctum (for API authentication)
SANCTUM_STATEFUL_DOMAINS=localhost:8000
```
**Run following commands**
```sh
composer install
php artisan key:generate
npm install
npm run build
php artisan migrate
php artisan serve
```

**Note:- Please log in using Facebook first and then run the ``db:seed`` command. This way, we can ensure that we have the necessary dummy data for display. If you run the ``db:seed`` command before logging in with Facebook, I will update all records with the current login data to ensure that we can view the dummy data on the events page.**

- Run ``php artisan db:seed``


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

