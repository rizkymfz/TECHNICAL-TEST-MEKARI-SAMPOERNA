# TECHNICAL-TEST-MEKARI-SAMPOERNA

## API Documentation
postman collection .json can be viewed at folder TECHNICAL-TEST-MEKARI-SAMPOERNA/API Docs
documentation URL
    https://documenter.getpostman.com/view/6424687/UzQuQRbg

## Installation (Frontend/API)

Database : 
    please import file technical_test_mekari_sampoerna.sql to database in folder **database/technical_test_mekari_sampoerna.sql**

Clone the repository

    git clone https://github.com/rizkymfz/TECHNICAL-TEST-MEKARI-SAMPOERNA.git

Switch to the repo folder

    cd TECHNICAL-TEST-MEKARI-SAMPOERNA

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file (if .env not exist)

    cp .env.example .env

Generate a new application key (if .env not exist)

    php artisan key:generate

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

**TL;DR command list**

    git clone https://github.com/rizkymfz/TECHNICAL-TEST-MEKARI-SAMPOERNA.git
    cd laravel-realworld-example-app
    composer install
    cp .env.example .env
    php artisan key:generate
    
    # For Testing API

Run the laravel development server

    php artisan serve

The api can now be accessed at

    http://localhost:8000/api
