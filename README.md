# Steak Overflow

Steak Overflow is a spoof of Stack Overflow.

Using Steak Overflow, you will be able to ask any question about your steak, see the answers of our loving community, and select the answer you judge is the most accurate.

## Requirements
* MySQL 5.7 or higher
* PHP 7.4 or higher

## Installation

* `cd` to the folder of your choice and do a `git clone`
* You may want to check the `.env` file and enter your MySQL credentials for `DATABASE_URL`
* Install project

```bash
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
php bin/console assets:install // Install assets as hard copies
yarn install
yarn dev
symfony server:start
```

* You can now browse [https://localhost:8000](https://localhost:8000)

## Usage

* The landing page lists all the questions, open questions first, then closed questions.
* To create a question you need to be logged in, register, then log-in
* You can also answer existing questions
* Only the author of the question can select an answer and mark it as valid, this makes the question closed, and no more answers can be posted
