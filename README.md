# User Management System

## Description

Our project is a [brief description of the project, e.g., web application, API service, etc.]. It [briefly describe the purpose or main functionality of the project].

## Setup Instructions

### Environment Setup

<b>Note: </b>you must have php in version 8.2 or higher, and node js in version 18.13 or higher.

1. Clone the repository:

```
git clone https://github.com/Angel-Yahir-Castillo/thiios-test.git
```

2. Navigate to the project directory:

``` 
cd thiios-test 
```

3. Open a terminal and run npm install to install all node dependencies for the project:

```
npm install
```

4. Run composer install to install the dependencies of the laravel project:

``` 
composer install
```

5. Rename the file ".env.example" to ".env", located in the root directory of the project

6. For run the Development Servers, you must open two terminal: 

In the first, run: ```  php artisan serve ```  <br>
In the second, run: ```  npm run dev ``` 

### Database Configuration

The application uses a sqlite database, so you only need to run the migrations and the seeder to have the test user:

```bash
php artisan migrate
php artisan db:seed
```

<b>Note: </b> If a warning message appears, just click enter

### Running Tests

Must run the next command to generate a jwt secret key for your app

```
php artisan jwt:secret
```

After you have configured everything above, to run the tests you only need to run the following command, the tests should not cause any errors:

```
php artisan test
```

## Application Architecture

This application follows a monolithic architecture pattern. It consists of the following key components:

- **Frontend:** The frontend is built using Vue.js with Vite and Vuetify.
- **Backend:** The backend is built with Laravel 11, providing APIs for frontend interaction.
- **Database:** SQLite is utilized, which comes pre-configured with Laravel by default.
- **APIs:** Laravel provides RESTful APIs for frontend-backend communication, secured with JWT authentication.

## Key Decisions

During development, I made several key decisions to ensure the project's success:

- **Technology Stack:** Vue.js was chosen for the frontend and Laravel for the backend due to their robustness, scalability, and extensive community support.
- **Database Choice:** SQLite was chosen as the database technology for its ease of use and seamless integration with Laravel.
- **Authentication Mechanism:** JWT-based authentication was implemented to secure user authentication and authorization, ensuring data integrity and user privacy.

## Test-Driven Development (TDD)

Test-Driven Development (TDD) was an integral part of our development process. We followed the red-green-refactor cycle, writing tests before implementing new features or making changes to existing code. This approach helped us ensure code quality, maintainability, and reliability throughout the development lifecycle.

Feel free to tailor this content to fit your project's specifics, and let me know if you need further assistance!
