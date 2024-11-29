# Vivedia Memorialisation Development Guide

## Technologies

- PHP
- Vue
- Laravel
- MySql
- AWS (S3 buckets and SES email)

## Getting started

### Getting the code

Github ``https://github.com/the-curve-consulting/vivedia-memorialisation``

### Setting up your developer environment

Create ``.env`` file from the example file:

    cp .env.example .env

Start the compose stack:

    ./scripts/run-dev-env.sh

Apply migrations and seed the database once the stack has started:

    ./scripts/artisan migrate:fresh
    ./scripts/artisan db:seed

You should now be able to login at: http://admin.localhost:8080/ using `admin@admin.com` / `password`.

## Running Tests

While the compose stack is running you can run tests as follows:

    ./scripts/run-tests.sh

## Running the Linter

While the compose stack is running you can run the linter as follows:

    ./scripts/composer sniff
    ./scripts/composer lint

## Simulating AWS Buckets locally

A Localstack container is included in the ``docker-compose.dev.yml`` file.

This S3 bucket system is transparent to the running application. However any files created with in the developers local environment will be lost when the container is shut down.

### How to preserve local S3 bucket contents

To back up the files from a running environment ``scripts/localstack-save.sh``

To restore the files to a running environment ``scripts/localstack-restore.sh``

Both of these scripts expect you to have set up the following variables in your ``.env``

e.g.

    LOCALSTACK_CONTAINER_NAME="localstack_main"
    LOCALSTACK_BUCKET_NAME="vivedia-bucket"
    LOCALSTACK_TEMP_STOREAGE_PATH="temp/s3-localstack"

## Artisan, Composer, npm etc

You can run artisan, composer, npm etc by using the scripts within the `./scripts` folder.

These will run their associated executables from within the container

Here are a few examples:

    ./scripts/artisan db:seed
    ./scripts/npm install
    ./scripts/composer install

## Reseting the development data

To quickly clear out all customisations including database data and S3 local files you can run
``scripts/dev-env-reset.sh``

After prompting for confirmation it will drop and reseed all the database tables and remove any local S3 files within a running bucket.

## Email

Mail settings are set in the `.env` file (see `.env.example`)

The development environment includes SES through localstack, but setting up a free mailtrap account for SMTP is recommended to be able to see emails rendered.

### Mailtrap

https://mailtrap.io/ is free service for evaluation. Once you have created and verified your account set up the authentication details.

e.g.  
    MAIL_MAILER=smtp

    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME= 5c47a1bcda0eff
    MAIL_PASSWORD=df9f99cb8b14df

Where the _USERNAME_ and _PASSWORD_ are unique to your account.

If you are already running the development stack run

`docker exec -it memorialisation-web php artisan config:clear`

To reload the changes you have made to the `.env` file.

Now when the application sends an email it will appear in your Mailtrap inbox, regardless of who the **TO:** and **FROM:** are set to.

#### Mailtrap BCC

Please note that Mailtrap will not show you BCC information on the free evaluation and this feature is only available with a premium account. 

For testing BCC values from the application use SES

### SES

SES is the Amazon Simple Email Service and is handled locally during development by localstack.

#### Configure 

In the `.env` file. Ensure the `MAIL_MAILER` is set to `ses`  as all the **AWS** settings will alredy be set. They are required for S3 storage.

    MAIL_MAILER=ses

    AWS_ENDPOINT=http://s3.localhost.localstack.cloud:4566
    AWS_DEFAULT_REGION=us-west-2
    AWS_ACCESS_KEY_ID=NA
    AWS_SECRET_ACCESS_KEY=NA

If you are already running the development stack run

`docker exec -it memorialisation-web php artisan config:clear`

To reload the changes you have made to the `.env` file.

All emails sent by the application are stored as .json files within the running container and are lost when the container is stopped.

#### Listing sent emails

`docker exec -it localstack_main bash -c 'ls -l /var/lib/localstack/tmp/state/ses/*.json'`

Will show a list of the .json files containing email messages.

#### Clearing out sent emails

`docker exec -it localstack_main bash -c 'rm /var/lib/localstack/tmp/state/ses/*.json'`

Will delete the list of the .json files containing email messages from your running container.

#### Checking the To|From|Bcc|Cc|Reply-To fields

`docker exec -it localstack_main bash -c 'cat /var/lib/localstack/tmp/state/ses/*.json' | grep -Eio "(To|From|Bcc|Cc|Reply-To):[^\\]+"`

Will list all the headers from the email messages for `To|From|Bcc|Cc|Reply-To` values. This is recommend where the message may contain a large base64 encoded image.

#### Showing the entire message

`docker exec -it localstack_main bash -c 'cat /var/lib/localstack/tmp/state/ses/__FILENAME__.json'`

Where `__FILENAME__.json` is the file name for the specific message you wish to show. 

