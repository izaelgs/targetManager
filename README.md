# Target Manager

## Screenshots

![login](https://user-images.githubusercontent.com/72363296/209216060-810cf770-fc96-433d-98b6-716b5f99dfaf.PNG)

![register](https://user-images.githubusercontent.com/72363296/209216073-5ea10e90-b1b6-4151-b6d4-cab436353125.PNG)

![home](https://user-images.githubusercontent.com/72363296/209216093-1a28a1dc-c3d9-4699-aae9-03e510f4b6bb.PNG)

![sidebar](https://user-images.githubusercontent.com/72363296/209216105-29de62a3-0177-4ab2-a7d4-c2c1219605a9.PNG)

![categories](https://user-images.githubusercontent.com/72363296/209216120-2c9fd5e0-074e-479e-ab06-1ddc62d436b9.PNG)

![addTarget](https://user-images.githubusercontent.com/72363296/209216161-7c281247-f567-4e6e-bd09-0f82edc979b0.PNG)

![target](https://user-images.githubusercontent.com/72363296/209216203-99fa4bd9-946c-4883-b56d-5715440abc8e.PNG)



## Technologies used

### Frontend
- VueJs
- VueRouter
- JsCookie
- Jquery
- Bootstrap
- Sass

### Backend
- PHp
- Laravel
- Jwt-Auth
- MySql
___

## Prerequisites
- Php 7.4
- Composer
- MySql
- NodeJs
___

## How to use?

## Step 1
The first step is to download the project, for this you can copy the code below and run it in your GIT terminal

```
git clone git@github.com:izaelgs/targetManager.git
```
____

## Step 2
### BACKEND
#### Step 1
With PHP and Composer installed, lets install laravel dependencies with the commands
```
composer install
cp .env.example .env
php artisan key:generate
```
#### Step 2
Then is needed to configure some database things on .env file
```
sudo nano .env
```
Then is possible to build the database structure running
```
php artisan migrate --seed
```
#### Step 3
Aubsequently let's generate a key for jwt
```
php artisan jwt:secret
```

### FRONTEND
Afterwords with NodeJs installed, just run the commands and then laravelmix will display some instructions
```
npm install
npm run dev
```

## Step 2
Now, just use the comand to run the server on localhost:8000
```
php artisan serve
```
