# BookReviewAPI

A PHP-based REST API for managing book review form submissions, user accounts, and application data. This project focuses on backend development, database operations, authentication, HTTP request handling, and reusable software architecture.

## Overview

BookReviewAPI demonstrates the development of a backend application using PHP and MySQL. It provides API functionality for retrieving and managing application data, protects endpoints through Bearer token authentication, and records API activity for monitoring and troubleshooting.

The project also includes a reusable HTTP client and a custom ORM-style data access layer to organize network communication and database operations.

## Features

- **REST API:** Handles HTTP requests and returns structured JSON responses.
- **Authentication:** Uses Bearer tokens to authenticate API requests and restrict access to protected endpoints.
- **HTTP Status Codes:** Handles unauthorized and invalid authentication requests using appropriate HTTP responses.
- **Database Operations:** Supports reusable CRUD (Create, Read, Update, Delete) operations for managing application data.
- **User Management:** Includes database structures for user accounts, authentication-related fields, and administrator privileges.
- **Book Review Management:** Handles review form data, including book titles, ratings, written reviews, reading formats, genres, and recommendations.
- **API Activity Logging:** Records request information such as timestamps, HTTP methods, response codes, and associated identifiers.
- **Reusable HTTP Client:** Provides a cURL-based utility supporting GET, POST, PUT, PATCH, and DELETE requests, with headers, JSON payloads, authentication, and structured response handling.
- **Custom Data Access Layer:** Organizes database persistence through reusable methods for loading, saving, inserting, updating, and deleting records.
- **Docker Support:** Includes a Dockerfile for running the PHP application with Apache.

## Technologies Used

- PHP 7.4
- MySQL
- PDO and PDO MySQL
- Apache HTTP Server
- Docker
- REST APIs
- JSON
- cURL
- Bearer Token Authentication
- Git and GitHub

## Project Structure

The application contains PHP files responsible for API request handling, database operations, HTTP communication, and application functionality.

Key files include:

| File | Purpose |
|---|---|
| `final/api_form.php` | Handles API requests for form data, including authentication and HTTP method routing. |
| `final/class_HTTP.php` | Provides reusable HTTP request functionality using cURL. |
| `final/class_data_operations.php` | Implements reusable database access and CRUD operations. |
| `final/` | Contains the PHP application files copied into the Apache document root by Docker. |

The exact project structure may include additional files for user management, database configuration, logging, and other application functionality.

## API Usage

The application includes an API endpoint for accessing book review form data.

### Example: Retrieve Form Data

**Endpoint**

`GET /api_form.php`

**Authentication**

The endpoint uses Bearer token authentication.

Include an authorization header with a valid token:

`Authorization: Bearer YOUR_TOKEN`

**Example Request**

```http
GET /api_form.php HTTP/1.1
Host: localhost:8080
Authorization: Bearer YOUR_TOKEN
Accept: application/json
```

**Example Response Structure**

```json
{
  "status": "success",
  "count": 29,
  "data": [
    {
      "form_review_id": 1,
      "form_book_title": "Example Book",
      "form_rating": 5,
      "form_review": "Example review"
    }
  ]
}
```

The JSON above illustrates the response structure. Actual fields and values depend on the records returned by the application.

### Authentication Responses

The API includes authentication checks before processing requests.

- `200 OK`: The request succeeded.
- `401 Unauthorized`: Authentication credentials are missing.
- `403 Forbidden`: The supplied token is invalid.

Other responses depend on the endpoint, request method, and application logic.

## Database

The application uses MySQL to store and manage its data.

Database structures include:

- **Users:** Account-related fields, email uniqueness constraints, timestamps, API token fields, and administrator flags.
- **Book Review Forms:** Review submissions containing book details, ratings, written reviews, and related metadata.
- **API Logs:** Request records used to track API activity and assist with troubleshooting.

The project uses PDO and MySQLi database extensions, along with a custom data operations layer that organizes database access into reusable methods.

Database credentials and connection settings must be configured for the environment in which the application runs.

## Running with Docker

The included Dockerfile uses PHP with Apache and enables the MySQL database extensions required by the application.

### Prerequisites

- Docker Desktop or Docker Engine
- A MySQL database accessible to the application
- The required database schema and configuration

### 1. Clone the Repository

```bash
git clone https://github.com/maki0311/BookReviewAPI.git
cd BookReviewAPI
```

### 2. Build the Docker Image

Run this command from the directory containing the Dockerfile:

```bash
docker build -t bookreviewapi .
```

The Dockerfile copies the contents of `final/` into Apache's web root.

### 3. Start the Container

```bash
docker run -d -p 8080:80 --name bookreviewapi bookreviewapi
```

The application will be accessible at:

`http://localhost:8080/`

### 4. Configure the Database

Configure the application's database connection settings to point to a reachable MySQL server. Ensure that the required database and tables have been created.

**Important:** Inside Docker, `localhost` refers to the application container itself. If MySQL runs in another container, configure the application to use the database container's service name or hostname. If MySQL runs on the host machine, use an appropriate host address for your Docker environment.

The supplied Dockerfile creates the PHP/Apache application container only. A MySQL container is not automatically created.

### 5. Test the API

Use Postman or another HTTP client to send a GET request to the API endpoint:

`http://localhost:8080/api_form.php`

Configure Bearer token authentication with a valid token for the application, then send the request and inspect the HTTP status and JSON response.

These commands provide the initial container setup. Database configuration and successful API execution depend on the application's connection settings, schema, and authentication configuration being correctly prepared.

## Development and Testing

Postman can be used to test API endpoints, inspect JSON responses, and verify HTTP status codes.

Useful test scenarios include:

- Retrieving form records with valid authentication.
- Sending requests without an authentication token.
- Sending requests with an invalid token.
- Testing supported HTTP methods.
- Verifying database operations and persistence.
- Reviewing API activity logs.

## Security Considerations

- Do not commit real database credentials, API tokens, or passwords to version control.
- Store passwords using secure password hashing rather than plaintext.
- Use HTTPS when deploying the application to a publicly accessible server.
- Protect administrative functionality with appropriate authorization checks.
- Review API responses and logs to avoid exposing sensitive user information.
- PHP 7.4 is end-of-life. Upgrading to a supported PHP release should be considered before using the application in a production environment.

## Future Improvements

Potential improvements include:

- Updating the application to a supported PHP version.
- Improving environment-based configuration and secret management.
- Expanding automated API and authentication tests.
- Improving request validation and error handling.
- Adding reproducible database initialization and Docker Compose configuration.
- Expanding API documentation with complete endpoint specifications and response examples.

## Author

**Marko Vukovic**

Computer Science graduate with interests in backend development, API design, database architecture, and software engineering.

GitHub: [maki0311](https://github.com/maki0311)

**Repository:** [BookReviewAPI](https://github.com/maki0311/BookReviewAPI)
