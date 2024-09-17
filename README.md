# Articles API

## Overview

The Articles API is a service designed to fetch, store, and manage articles from various sources. The API allows users to retrieve articles, search for articles by keywords, and store new articles in the database.

The project includes both a Laravel back end and a React front end to manage and display articles.

## Table of Contents

- **[Requirements](#requirements)**
- **[Installation](#installation)**
- **[Frontend Setup](#frontend-setup)**
- **[Running the Project](#running-the-project)**
- **[Testing](#testing)**
- **[Environment](#environment)**
- **[Command and Scheduler](#command-and-scheduler)**

## Requirements

To run this project, you will need the following:

- PHP >= 8.0
- Composer
- Laravel = 11.9
- Node.js =v22.8.0
- NPM or Yarn
- MySQL or any other database of your choice

## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/kristinaNik/Articles-api-task.git
    ```

2. Navigate to the project directory:

    ```bash
    cd project-name
    ```

3. Install the backend dependencies using Composer:

    ```bash
    composer install
    ```

4. Set up the `.env` file by copying the example:

    ```bash
    cp .env.example .env
    ```

5. Generate the application key:

    ```bash
    php artisan key:generate
    ```

6. Configure your database and other environment variables in the `.env` file.

7. Run database migrations:

    ```bash
    php artisan migrate
    ```

## Frontend Setup

The frontend is built using React. To set up the frontend:

1. Install the frontend dependencies using NPM or Yarn:

    ```bash
    npm install
    ```

   or

    ```bash
    yarn install
    ```

2. Build the frontend for development:

    ```bash
    npm run dev
    ```

   or for production:

    ```bash
    npm run build
    ```

## Running the Project

1. Start the Laravel server:

    ```bash
    php artisan serve
    ```

2. Open your browser and go to `http://localhost:8000`.

## Testing

To run the unit tests for the backend:

```bash
php artisan test
```

## Environment

### Configuration
To access third-party APIs such as The Guardian, New York Times, or News API, you need to set the appropriate environment variable. The application will automatically bind the correct service provider based on your configuration.

The service provider binding is determined by the ARTICLE_SERVICE environment variable. Depending on the value you set, the corresponding API service will be used to fetch articles.

Here’s how the binding is handled in the application:

```php
   $service = config('services.article_service');

   if ($service === 'guardian') {
       $this->app->bind(ArticleServiceInterface::class, GuardianArticleService::class);
   } elseif ($service === 'new_york_times') {
       $this->app->bind(ArticleServiceInterface::class, NewYorkTimesArticleService::class);
   } elseif ($service === 'news') {
       $this->app->bind(ArticleServiceInterface::class, NewsApiArticleService::class);
   } else {
       throw new \Exception("Unsupported article service: $service");
   }
```
### Setting the Service Provider

In your .env file, define the ARTICLE_SERVICE variable to specify which third-party API service you’d like to use. For example:
```bash 
# Use the New York Times API
ARTICLE_SERVICE=new_york_times

# Or, use The Guardian API
# ARTICLE_SERVICE=guardian

# Or, use the News API
# ARTICLE_SERVICE=news
```
## Command and Scheduler

To automate the process of fetching and storing articles from external sources, the application uses a custom Artisan command. This command can be scheduled to run automatically at specified intervals using Laravel’s task scheduler.

### Running the Command Manually

```bash
php artisan articles:fetch-and-store
```

This will trigger the process of fetching articles and saving them to the database.

### Scheduling and the command

The articles:fetch-and-store command can be scheduled to run automatically at specific intervals. In this case, the command is set to run daily using Laravel’s task scheduling feature, defined in console.php:

```php
use Illuminate\Support\Facades\Schedule;

// Schedule the article fetching command to run daily
Schedule::command('articles:fetch-and-store')->daily();
```







