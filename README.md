<h1 align="center"> 
	   VACATION-PLANS-API 
</h1>

---

<!-- ---------------------------------------------------------------------- -->

## About the Project

`vacation-plans-api` is a project created for the Buzzvel recruitment process.

The challenge for this project was to create a RESTful API to manage vacation plans for the year 2024. In this project, we had to implement CRUD operations (Create, Read, Update, Delete) for vacation plans, ensure secure user authentication, validate inputs to ensure data integrity, and enable the generation of PDF documents with plan details.

The API was developed using [Laravel](https://laravel.com/), [Docker](https://www.docker.com/), and [MySQL](https://www.mysql.com/), and includes endpoints to create, retrieve, update, and delete vacation plans, as well as to generate detailed PDFs of the plans. The goal is to demonstrate skills in API development, data handling, and best programming practices, in addition to providing a robust and well-documented solution for vacation plan management.

---

<!-- ---------------------------------------------------------------------- -->

## Prerequisites

Before you begin, you will need to have the following tools installed on your machine:<br>
• [Git](https://git-scm.com/downloads)<br>
• [Docker](https://www.docker.com/products/docker-desktop) and [Docker Compose](https://docs.docker.com/compose/install/)<br>

Additionally, it is recommended to have an editor for working with the code, such as [Visual Studio Code](https://code.visualstudio.com/).

Ensure that Docker and Docker Compose are working correctly to set up and run the project environment. There is no need to install PHP or MySQL separately, as they are configured automatically within the Docker containers.

---

<!-- ---------------------------------------------------------------------- -->

## Setting Up the Environment

1. **Download the Project**

   Clone the project repository to your local machine:
   
   ```
   git clone https://github.com/GabrielVelosoo/vacation-plans-api.git
   ```
   
   Navigate to the project directory:
   
   ```
   cd vacation-plans-api
   ```
   
2. **Configure and Run Docker**

   Ensure that Docker and Docker Compose are installed and working correctly.
   
   • Start the Containers<br><br>

   Run the following command to build the images and start the containers:
   
   ```
   docker-compose up -d
   ```

   • Stop the Containers<br><br>

   When not in use, stop the containers with:

   ```
   docker-compose down
   ```

   • Install PHP Dependencies<br><br>

   Use the command `docker ps` to list the containers, you should see something like this:
   
   ```
   CONTAINER ID     IMAGE                   COMMAND                  CREATED          STATUS          PORTS                   NAMES
   <container-id>   vacation-plans-api-app  "docker-php-entrypoi…"   30 minutes ago   Up 30 minutes   0.0.0.0:8000->80/tcp    <container-name>
   <container-id>   mysql:8.0.39            "docker-entrypoint.s…"   30 minutes ago   Up 30 minutes   0.0.0.0:3306->3306/tcp  <container-name>
   ```

   Enter the application container and install the Composer dependencies:
   
   ```
   docker exec -it <container-name-or-id> bash
   composer install
   ```

3. **Configure the Application**

   Inside the application container, copy the `.env.example` file to `.env`:
   
   ```
   cp .env.example .env
   ```

   Configure the database variables in the `.env` file:
   
   ```
   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=vacation_plans
   DB_USERNAME=user
   DB_PASSWORD=password
   ```
   
   • Generate the Application Key

   Generate the Laravel application key:
   
   ```
   php artisan key:generate
   ```
   
   • Run Migrations

   Run the migrations to set up the database:

   ```
   php artisan migrate
   ```

   • Run the Seeder

   Run the seeder to create a user in the users table:

   ```
   php artisan db:seed
   ```
   
5. **Access the Project**

   After setup, the API will be available at `http://localhost:8080` (or the port you configured in docker-compose.yml).

   You can use tools like [Postman](https://www.postman.com/downloads/) or [Insomnia](https://insomnia.rest/download) to test the API endpoints.

---

<!-- ---------------------------------------------------------------------- -->

## Authentication

**NOTE**: To avoid issues, include "application/json" in the `Accept` header of your requests:

```
Accept: application/json
```

To access the API endpoints, you will need an authentication token. The API returns a [JWT](https://jwt.io/) token that should be used in subsequent requests. Below are details on how to generate the token. Include the token in the `Authorization` header of your requests:

```
Authorization: Bearer {your-token}
```

#### Authentication Routes

**NOTE**: I recommend using [Postman](https://www.postman.com/downloads/) to test the API.

1. **Generate Access Token**<br>
   • **Method:** `POST`<br>
   • **URL:** `/api/login`<br>
   • **Request Body:**<br>
   
     ```
     {
       "email": "user@test.com",
       "password": "12345",
       "device_name": "Postman"
     }
     ```
     
   • **Success Response:**
   
     ```
     {
       "token": "{your-token}"
     }
     ```
     
   • **Invalid Credentials Response:**
   
     ```
     {
        "message": "The provided credentials are incorrect",
        "errors": {
            "message": [
                "The provided credentials are incorrect"
            ]
        }
     }
     ```
     
     NOTE: Use the email `user@test.com` and password `12345` created when running the `php artisan db:seed` command, and don't forget to include the `device_name`. If you prefer, you 
     can change the email and password by editing `database/seeders/DatabaseSeeder.php` in the project root, updating the data as desired, and running the seeders command again. You can 
     also add more users by updating the data in `DatabaseSeeder.php` and running `php artisan db:seed` again, or by accessing the MySQL container.<br><br>

     **Accessing MySQL Container**

     Use `docker ps` to list the containers:

     ```
     CONTAINER ID     IMAGE                   COMMAND                  CREATED          STATUS          PORTS                   NAMES
     <container-id>   vacation-plans-api-app  "docker-php-entrypoi…"   30 minutes ago   Up 30 minutes   0.0.0.0:8000->80/tcp    <container-name>
     <container-id>   mysql:8.0.39            "docker-entrypoint.s…"   30 minutes ago   Up 30 minutes   0.0.0.0:3306->3306/tcp  <container-name>
     ```

     Enter the MySQL container:

     ```
     docker exec -it <container-name-or-id> bash
     ```

     After entering the container, use the command:
   
     ```
     mysql -u root -p
     ```

     You will need a password to log in, the root user password is set in the `docker-compose.yml` file, "by default the password is `root`", you will see something like:
   
     ```
     Enter password: "root"
     ```

     Inside MySQL, you can check if the `vacation_plans` database was created automatically with the command `SHOW DATABASES;`, you should see something like:

     ```
     +--------------------+
     | Database           |
     +--------------------+
     | information_schema |
     | mysql              |
     | performance_schema |
     | sys                |
     | vacation_plans     |
     +--------------------+
     5 rows in set (0.00 sec)
     ```

     If the `vacation_plans` database is not created, you can create it using SQL commands within the container:

     ```
     CREATE DATABASE vacation_plans;
     ```

     **NOTE**: If you create the database with a different name, don't forget to configure the database variables in the .env file!

     After that, you have created the database, now just run the migrations with `php artisan migrate`.<br><br>
 
1. **Retrieve Logged-In User**<br>
   • **Method:** `GET`<br>
   • **URL:** `/api/user`<br>
   • **Success Response:**<br>
   
     ```
     {
        "user": {
            "id": 1,
            "name": "User Test",
            "email": "user@test.com",
            "email_verified_at": "2024-08-16T00:00:00.000000Z",
            "created_at": "2024-08-16T00:00:00.000000Z",
            "updated_at": "2024-08-16T00:00:00.000000Z"
        }
     }
     ```
     
2. **Logout**<br>
   • **Method:** `POST`<br>
   • **URL:** `/api/logout`<br>
   • **Success Response:**<br>
   
     ```
     {
        "message": "success"
     }
     ```

---

<!-- ---------------------------------------------------------------------- -->

## Usage

**NOTE**: Include "application/json" in the `Accept` header of your requests and the token in the `Authorization` header:

```
Accept: application/json
Authorization: Bearer {your-token} #Include Bearer and then a space and your token
```

The API offers several endpoints to manage vacation plans. Below are details on how to interact with each of them.

#### API Endpoints

1. **Create a New Vacation Plan**<br>
   • **Method:** `POST`<br>
   • **URL:** `/api/vacation-plans`<br>
   • **Request Body:**<br>
   
     ```
     {
       "title": "Summer Vacation",
       "description": "Plan for summer vacation",
       "date": "2024-07-01",
       "location": "Beach",
       "participants": "John, Mary"
     }
     ```
     
   • **Success Response:**<br>
     **Status: 201**
   
     ```
     {
       "id": 1,
       "title": "Summer Vacation",
       "description": "Plan for summer vacation",
       "date": "2024-07-01",
       "location": "Beach",
       "participants": "John, Mary",
       "created_at": "2024-08-16T00:00:00.000000Z",
       "updated_at": "2024-08-16T00:00:00.000000Z"
     }
     ```
     
2. **Retrieve All Vacation Plans**<br>
   • **Method:** `GET`<br>
   • **URL:** `/api/vacation-plans`<br>
   • **Success Response:**<br>
     **Status: 200**
   
     ```
     [
        {
           "id": 1,
           "title": "Summer Vacation",
           "description": "Plan for summer vacation",
           "date": "2024-07-01",
           "location": "Beach",
           "participants": "John, Mary",
           "created_at": "2024-08-16T00:00:00.000000Z",
           "updated_at": "2024-08-16T00:00:00.000000Z"
         }
     ]
     ```

   • **Response Holiday Plan(s) Not Found:**<br>
     **Status: 404**
   
     ```
     {
        "message": "No holiday plans found"
     }
     ```
     
3. **Retrieve a Single Vacation Plan**<br>
   • **Method:** `GET`<br>
   • **URL:** `/api/vacation-plans/{id}`<br>
   • **Parameters:** Holiday plan ID<br>
   • **Success Response:**<br>
     **Status: 200**
   
     ```
     {
       "id": 1,
       "title": "Summer Vacation",
       "description": "Plan for summer vacation",
       "date": "2024-07-01",
       "location": "Beach",
       "participants": "John, Mary",
       "created_at": "2024-08-16T00:00:00.000000Z",
       "updated_at": "2024-08-16T00:00:00.000000Z"
     }
     ```
     
   • **Response Vacation Plan Not Found:**<br>
     **Status: 404**
     ```
     {
        "message": "Holiday plan not found"
     }
     ```
     
4. **Update a Vacation Plan**<br>
   • **Method:** `PUT`<br>
   • **URL:** `/api/vacation-plans/{id}`<br>
   • **Parameters:** Holiday plan ID<br>
   • **Request Body:**
   
     ```
     {
       "title": "Winter Vacation",
       "description": "Updated plan for winter vacation",
       "date": "2024-12-01",
       "location": "Mountains",
       "participants": "Ana, Pedro"
     }
     ```
     
   • **Success Response:**<br>
     **Status: 200**
   
     ```
     {
        "id": 1,
        "title": "Winter Vacation",
        "description": "Updated plan for winter vacation",
        "date": "2024-12-01",
        "location": "Mountains",
        "participants": "Ana, Pedro",
        "created_at": "2024-08-16T00:00:00.000000Z",
        "updated_at": "2024-08-16T00:00:00.000000Z"
     }
     ```
     
   • **Response Vacation Plan Not Found:**<br>
     **Status: 404**
   
     ```
     {
        "message": "Unable to update. Holiday plan not found"
     }
     ```

5. **Partially Update a Holiday Plan**<br>
   • **Method:** `PATCH`<br>
   • **URL:** `/api/vacation-plans/{id}`<br>
   • **Parameters:** Holiday plan ID<br>
   • **Request Body:**
   
     ```
     {
         "description": "Updated plan for winter vacation"
     }
     ```
     
   • **Success Response:**<br>
     **Status: 200**
   
     ```
     {
        "id": 1,
        "title": "Summer Vacation",
        "description": "Updated plan for winter vacation",
        "date": "2024-07-01",
        "location": "Beach",
        "participants": "João, Maria",
        "created_at": "2024-08-16T00:00:00.000000Z",
        "updated_at": "2024-08-16T00:00:00.000000Z"
     }
     ```
     
   • **Response Vacation Plan Not Found:**<br>
     **Status: 404**
   
     ```
     {
        "message": "Unable to update. Holiday plan not found"
     }
     ```
     
6. **Delete a Vacation Plan**<br>
   • **Method:** `DELETE`<br>
   • **URL:** `/api/vacation-plans/{id}`<br>
   • **Parameters:** Holiday plan ID<br>
   • **Success Response:**<br>
     **Status: 200**
    
     ```
     {
       "message": "Plan deleted"
     }
     ```
     
   • **Response Vacation Plan Not Found:**<br>
     **Status: 404**
   
     ```
     {
        "message": "Unable to delete. Holiday plan not found"
     }
     ```
     
7. **Generate PDF for a Vacation Plan**<br>
   • **Method:** `GET`<br>
   • **URL:** `/api/vacation-plans/{id}/pdf`<br>
   • **Parameters:** Holiday plan ID<br>
   • **Success Response:**<br>
     **Status: 200**<br>
     
     #### The PDF will be generated and returned as a download. Make sure you have a PDF viewer to view the document.<br><br>
     
   • **Response Vacation Plan Not Found:**<br>
     **Status: 404**
     
     ```
     {
        "message": "Unable to generate pdf. Holiday plan not found"
     }
     ```

---

<!-- ---------------------------------------------------------------------- -->

## Testing

The file `HolidayPlanTest.php` contains unit tests that validate the functionality of the key endpoints of the `vacation-plans-api`. These tests ensure that the creation, reading, updating, deletion, and PDF generation features of holiday plans are working correctly. Below, I will explain the main tests contained in this file.

1. **Authentication and Initial Setup**<br>
   • `setUp()`: This method is executed before each test. It creates a user using a factory and authenticates this user using Sanctum, ensuring that all subsequent tests can perform 
   authenticated actions.<br>

2. **Creation Tests**<br>
   • `can_i_create_a_vacation_plan()`: This test checks if a new holiday plan can be created correctly. It sends a `POST` request to `/api/vacation-plans` with the plan data and 
   validates if the API returns a 201 Created status and the correct data. Additionally, it checks if the data has been persisted in the database.<br>

3. **Reading Tests**<br>
   • `can_i_get_all_vacation_plans()`: This test checks if all holiday plans can be retrieved from the API. It creates a holiday plan and sends a `GET` request to `/api/vacation-plans`, 
   validating if the response has a 200 OK status.<br>

   • `can_i_get_single_vacation_plan()`: This test checks if a specific holiday plan can be retrieved. It creates a plan, makes a `GET` request to `/api/vacation-plans/{id}`, and 
   validates if the returned data is correct.<br>

4. **Updating Tests**<br>
   • `can_i_update_vacation_plan()`: This test checks if an existing holiday plan can be updated. It sends a `PUT` request to `/api/vacation-plans/{id}` with new data and verifies if 
   the API returns a 200 OK status and if the data has been updated in the database.<br>

   • `can_i_partially_update_vacation_plan()`: Similar to the previous test, but this one uses a `PATCH` request to partially update a holiday plan. It ensures that only the provided       fields are updated while the others remain unchanged.<br>

5. **Deletion Test**<br>
   • `can_i_delete_vacation_plan()`: This test checks if a holiday plan can be deleted. It creates a plan, sends a `DELETE` request to `/api/vacation-plans/{id}`, and validates if the 
   plan has been removed from the database and if the API returns a 200 OK status.<br>

6. **PDF Generation Test**<br>
   • `can_i_generate_pdf_for_vacation_plan()`: This test checks if the API can correctly generate a PDF with the details of a holiday plan. It makes a `GET` request to `/api/vacation-plans/{id}/pdf` and validates if the response contains a PDF file (content-type: application/pdf) and if the status is 200 OK.<br>

**Summary**<br>

These tests ensure that each functionality of the API works as expected in different scenarios. They cover the main flows of creation, reading, updating, deletion, and PDF generation, ensuring that the API is robust and reliable. Each test focuses on a specific aspect, and the use of assertions validates both the API behavior and data persistence in the database.

Use `docker ps` to list the containers:

```
CONTAINER ID     IMAGE                   COMMAND                  CREATED          STATUS          PORTS                   NAMES
<container-id>   vacation-plans-api-app  "docker-php-entrypoi…"   30 minutes ago   Up 30 minutes   0.0.0.0:8000->80/tcp    <container-name>
<container-id>   mysql:8.0.39            "docker-entrypoint.s…"   30 minutes ago   Up 30 minutes   0.0.0.0:3306->3306/tcp  <container-name>
```

To run the unit tests, use PHPUnit:

```
docker exec -it <container-name-or-id> bash
php artisan test
```

#### Tests can be customized, and adding new test cases is a good practice to cover more scenarios, such as testing authentication failures or responses to malformed data, etc.

---

<!-- ---------------------------------------------------------------------- -->

## Contribution

1. Fork the project.
2. Create a new branch with your changes: `git checkout -b my-feature`.
3. Save your changes and create a commit explaining what was changed: `git commit -m "feature: My new feature"`.
4. Push your changes to the main branch: `git push origin my-feature`.
5. Open a pull request on the original repository and wait for the review.

#### Contributions are always welcome! Feel free to open issues to report bugs, suggest improvements, or discuss new features.

---

<!-- ---------------------------------------------------------------------- -->

## Technologies

The following tools were used in the construction of the project:

#### **Back-End**  ([Laravel](https://laravel.com/))

• **[Laravel Sanctum](https://laravel.com/docs/11.x/sanctum)** - For OAuth token authentication.<br>
• **[Composer](https://getcomposer.org/)** - PHP dependency manager.

#### **Database**

• **[MySQL](https://www.mysql.com/)** - Database management system.

#### **Containerization** ([Docker](https://www.docker.com/))

• **[Docker](https://www.docker.com/)** - For containerizing the development environment.<br>
• **[Docker Compose](https://docs.docker.com/compose/)** - For orchestrating multiple containers.

#### **Dependency Management and Build**

• **[PHP](https://www.php.net/)** - Programming language used in the back-end.<br>
• **[PHPUnit](https://phpunit.de/)** - Testing framework for PHP.

#### **Documentation and Testing**

• **[Postman](https://www.postman.com/)** - For testing API endpoints.

---

<!-- ---------------------------------------------------------------------- -->

## Author

<a href="https://www.linkedin.com/in/gabriel-veloso-2183b82b6/">
Gabriel Veloso Pinheiro</a>
 <br />
 
[![Gmail Badge](https://img.shields.io/badge/-gaabrielvelooso@gmail.com-c14438?style=flat-square&logo=Gmail&logoColor=white&link=mailto:gaabrielvelooso@gmail.com)](mailto:gaabrielvelooso@gmail.com)

---

<!-- ---------------------------------------------------------------------- -->

## License

This project is licensed under the [MIT](./LICENSE) License.

Made by Gabriel Veloso Pinheiro👋🏽 [Get in touch!](https://www.linkedin.com/in/gabriel-veloso-2183b82b6/)

