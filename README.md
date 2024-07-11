## Documentation on how to setup this project

> This project is a simple Symfony REST API server for product CRUD and the authentication using JWT.
> The REST API allows user to register, login and manager products through CRUD endpoints. The Documentation below explains how to run and test the project

## Assummption made during development

Only the first user has ROLE_ADMIN role, which the role of the user who can create, edit and delete products.
Any user created after the first one won't have this role and call only view products

### Local setup

#### General Requirements

1. **PHP:** version 8.2 and above
2. **Mysql:** version 8.0 and above
3. **Composer:** version 2.5.8 and above
4. **Symfony:** version 2.5.8 and above, follow this link to install symfony https://symfony.com/download

#### Clone this project

```sh
  git clone https://github.com/Stancobridge/gitstart-assessment
  cd ./gitstart-assessment
```


#### setup .env

Create an .env file in the root folder and copy the contents of .env.example to it.
Change the database username and password in the .env to your local mysql database details

```sh
DATABASE_URL="mysql://username:password@127.0.0.1:3306/gistart_assessment"
```

the username and password should be your local details

#### install packages

```sh
  composer install
```

#### Setup database

create database

```sh
php bin/console doctrine:database:create
```

run migration

```sh
php bin/console doctrine:migrations:migrate
```
setup jwt hash keys
```sh
php bin/console lexik:jwt:generate-keypair
```

Start server
```sh
symfony server:start
```
## Docker setup


#### setup .env

Create an .env file in the root folder and copy the contents of .env.example to it.
Replace this with the DABASE_URL value
```sh
DATABASE_URL=mysql://symfony:secret@db:3306/symfony
```

Build project

```sh
 docker compose -f ./docker-compose.yml up --build
```

Migrate database

```sh
docker exec -it gitstart-assessment-web-1 php bin/console doctrine:migrations:migrate
```
When asked enter "yes" without the quote

You can access the docker endpoint from http://localhost:8080

## Test Documentation

Edit the database username and password to match with your local database details

```sh
DATABASE_URL="mysql://username:password@127.0.0.1:3306/gistart_assessment"
```

the username and password should be your local details

Create database

```sh
php bin/console --env=test doctrine:database:create
```

Create database schema

```sh
php bin/console --env=test doctrine:schema:create
```

Create dummy data for testing

```
php bin/console --env=test doctrine:fixtures:load
```

Run test

```sh
php bin/phpunit
```

## API Documentation

# User Account Creation and Login API Documentation

This documentation outlines how to create a user account and login using the provided API endpoints.

## Base URL

```
http://localhost:8000/api
```

### Authentication

- **Authorization:** No authentication required for account creation. Token is only required for secured endpoints.

### Endpoints

#### 1. Create User Account

**Request:**

```http
POST /register
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password123",
    "fullName": "John Doe"
}
```

**Response:**

```json
{
  "message": "Account created successfully",
  "statusCode": 201,
  "data": {
    "auth_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
    "user": {
      "id": 4,
      "email": "user@example.com",
      "fullName": "John Doe",
      "updatedAt": {
        "date": "2024-07-11 09:46:19.450846",
        "timezone_type": 3,
        "timezone": "UTC"
      },
      "createdAt": {
        "date": "2024-07-11 09:46:19.450837",
        "timezone_type": 3,
        "timezone": "UTC"
      }
    }
  },
  "timestamp": "2024-07-11 09:46:19"
}
```

#### 2. User Login

**Request:**

```http
POST /login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password123"
}
```

**Response:**

```json
{
  "message": "User logged successfully",
  "statusCode": 200,
  "data": {
    "user": {
      "id": 1,
      "fullName": "Okechukwu",
      "email": "soromgawide@gmail.com",
      "updatedAt": {
        "date": "2024-07-10 21:50:52.000000",
        "timezone_type": 3,
        "timezone": "UTC"
      },
      "createdAt": {
        "date": "2024-07-10 21:50:52.000000",
        "timezone_type": 3,
        "timezone": "UTC"
      }
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MjA2OTEyNDgsImV4cCI6MTc..."
  },
  "timestamp": "2024-07-11 09:47:28"
}
```

### Usage

- **Authentication:** Include the JWT token in the `Authorization` header for subsequent authenticated requests.

---

# CRUD API Documentation for Products

This documentation outlines how to perform CRUD (Create, Read, Update, Delete) operations on the products API endpoint.

## Base URL

```
http://localhost:8000/api
```

### Authentication

- **Authorization:** Make sure to include proper authentication token in Authorization headers.

### Endpoints

#### 1. Create a new product

**Request:**

```http
POST /products
Content-Type: application/json

{
  "name": "Product 1",
  "price": 2323,
  "description": "Product 1 description"
}
```

**Response:**

```json
{
  "message": "Product created successfully",
  "statusCode": 201,
  "data": {
    "id": 5,
    "name": "Product 1",
    "price": 2323,
    "description": "Product 1 description",
    "createdAt": {
      "date": "2024-07-11 09:52:15.395043",
      "timezone_type": 3,
      "timezone": "UTC"
    },
    "updatedAt": {
      "date": "2024-07-11 09:52:15.395044",
      "timezone_type": 3,
      "timezone": "UTC"
    }
  },
  "timestamp": "2024-07-11 09:52:15"
}
```

#### 2. Retrieve all products

**Request:**

```http
GET /products
```

**Response:**

```json
{
  "message": "Products fetched successfully",
  "statusCode": 200,
  "data": [
    {
      "id": 2,
      "name": "Product 1",
      "price": 2323,
      "description": "Product 1 Description",
      "createdAt": {
        "date": "2024-07-10 00:00:00.000000",
        "timezone_type": 3,
        "timezone": "UTC"
      },
      "updatedAt": {
        "date": "2024-07-10 00:00:00.000000",
        "timezone_type": 3,
        "timezone": "UTC"
      }
    },
    {
      "id": 3,
      "name": "Product 2",
      "price": 2323,
      "description": "Product 2 Description",
      "createdAt": {
        "date": "2024-07-10 00:00:00.000000",
        "timezone_type": 3,
        "timezone": "UTC"
      },
      "updatedAt": {
        "date": "2024-07-10 00:00:00.000000",
        "timezone_type": 3,
        "timezone": "UTC"
      }
    },
    {
      "id": 4,
      "name": "Main",
      "price": 2323,
      "description": "Hello",
      "createdAt": {
        "date": "2024-07-10 00:00:00.000000",
        "timezone_type": 3,
        "timezone": "UTC"
      },
      "updatedAt": {
        "date": "2024-07-10 00:00:00.000000",
        "timezone_type": 3,
        "timezone": "UTC"
      }
    }
  ],
  "timestamp": "2024-07-11 09:49:12"
}
```

#### 3. Retrieve a single product

**Request:**

```http
GET /products/{id}
```

Replace `{id}` with the ID of the product.

**Response:**

```json
{
  "message": "Product fetched successfully",
  "statusCode": 200,
  "data": {
    "id": 2,
    "name": "Product 1",
    "price": 2323,
    "description": "Product 1 Description",
    "createdAt": {
      "date": "2024-07-10 00:00:00.000000",
      "timezone_type": 3,
      "timezone": "UTC"
    },
    "updatedAt": {
      "date": "2024-07-10 00:00:00.000000",
      "timezone_type": 3,
      "timezone": "UTC"
    }
  },
  "timestamp": "2024-07-11 09:50:11"
}
```

#### 4. Update a product

**Request:**

```http
PUT /products/{id}
Content-Type: application/json

{
  "name": "Update name",
  "price": 4000,
}
```

Replace `{id}` with the ID of the product to update.

**Response:**

```json
{
  "message": "Product updated successfully",
  "statusCode": 200,
  "data": {
    "id": 2,
    "name": "Update name",
    "price": 4000,
    "description": "Product 1 description",
    "createdAt": {
      "date": "2024-07-10 00:00:00.000000",
      "timezone_type": 3,
      "timezone": "UTC"
    },
    "updatedAt": {
      "date": "2024-07-11 09:54:05.451059",
      "timezone_type": 3,
      "timezone": "UTC"
    }
  },
  "timestamp": "2024-07-11 09:54:05"
}
```

#### 5. Delete a product

**Request:**

```http
DELETE /products/{id}
```

Replace `{id}` with the ID of the product to delete.

**Response:**

```json
{
  "message": "Product deleted successfully",
  "statusCode": 200,
  "data": true,
  "timestamp": "2024-07-11 09:55:19"
}
```
