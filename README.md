# User Management System

A simple yet robust web application that allows for managing user accounts. This includes functionalities for user registration, login, viewing, editing, and deleting user profiles.

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

Test-Driven Development (TDD) was an integral part of the development process for this project. The implementation of TDD principles followed a structured approach, adhering to the red-green-refactor cycle.

### Implementation Summary:

1. **Red Phase:** In this phase, failing test cases were written based on the expected behavior of the feature or functionality being developed. These test cases were designed to initially fail, indicating the absence of the desired feature.

2. **Green Phase:** During this phase, the minimum code necessary to pass the failing test cases was implemented. The focus was solely on writing code to satisfy the requirements outlined in the test cases.

3. **Refactor Phase:** Once the test cases passed successfully, the code was refactored to improve readability, maintainability, and efficiency. Refactoring ensured that the code adhered to best practices and design principles without altering its functionality.

### Integration of TDD Principles:

The TDD principles were well integrated into the development process, influencing the workflow and decision-making at every stage. Here's how TDD principles were incorporated:

1. **Continuous Testing:** Test cases were written and executed continuously throughout the development process. This ensured that new features or changes didn't introduce regressions or unintended side effects.

2. **Incremental Development:** TDD encouraged incremental development, with each iteration focusing on delivering small, testable units of functionality. This approach facilitated early feedback and reduced the risk of developing complex, error-prone code.

3. **Improved Code Quality:** By writing test cases first, the developer was forced to think critically about the requirements and design of their code. This led to the creation of well-tested, robust code that met the specified criteria.

4. **Regression Prevention:** The comprehensive test suite created through TDD served as a safety net, guarding against regressions when making modifications or refactoring existing code. This proactive approach minimized the likelihood of introducing bugs into the codebase.

Overall, TDD played a crucial role in ensuring the reliability, maintainability, and quality of the software developed, contributing to a more efficient and structured development process.

