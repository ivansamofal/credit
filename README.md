Client API

Overview

The Client API allows you to manage client data, including creating, retrieving, updating clients, and assigning credits to them.

Base URL

http://localhost/api/v1

Endpoints and Examples

1. Get All Clients

Endpoint:

GET /clients

Example Request:

curl -X GET "http://localhost/api/v1/clients" -H "Accept: application/json"

Example Response:

[
{
"id": 1,
"name": "Ivan",
"surname": "Smith",
"age": 30,
"address": "New York, some street",
"email": "my@test.com",
"ssn": "000-00-0000",
"ficoRating": 350,
"phone": "2454245245"
}
]

2. Get a Specific Client by ID

Endpoint:

GET /clients/{id}

Example Request:

curl -X GET "http://localhost/api/v1/clients/1" -H "Accept: application/json"

Example Response:

{
"id": 1,
"name": "Ivan",
"surname": "Smith",
"age": 30,
"address": "New York, some street",
"email": "my@test.com",
"ssn": "000-00-0000",
"ficoRating": 350,
"phone": "2454245245"
}

3. Create a New Client

Endpoint:

POST /clients

Example Request:

curl -X POST "http://localhost/api/v1/clients" \
-H "Content-Type: application/json" \
-d '{
"email": "my@test.com",
"age": 30,
"name": "Ivan",
"surname": "Smith",
"ssn": "000-00-0000",
"address": "New York, some street",
"fico_rating": 350,
"phone": "2454245245",
"salary": 1200,
"password": "securepassword"
}'

Example Response:

{
"data": {
"id": 2,
"email": "my@test.com",
"age": 30,
"name": "Ivan",
"surname": "Smith",
"ssn": "000-00-0000",
"address": "New York, some street",
"fico_rating": 350,
"phone": "2454245245",
"salary": 1200,
"password": "securepassword"
},
"message": "success"
}

4. Update an Existing Client

Endpoint:

PUT /clients/{id}

Example Request:

curl -X PUT "http://localhost/api/v1/clients/1" \
-H "Content-Type: application/json" \
-d '{
"name": "John",
"surname": "Doe",
"age": 35,
"email": "john.doe@example.com"
}'

Example Response:

{
"data": {
"id": 1,
"name": "John",
"surname": "Doe",
"age": 35,
"email": "john.doe@example.com"
},
"message": "success"
}

5. Check Client's Credit Permission

Endpoint:

GET /clients/{id}/permission

Example Request:

curl -X GET "http://localhost/api/v1/clients/1/permission" -H "Accept: application/json"

Example Response:

{
"result": true
}

6. Assign a Credit to a Client

Endpoint:

POST /clients/{clientId}/credit/{creditId}

Example Request:

curl -X POST "http://localhost/api/v1/clients/1/credit/5" -H "Accept: application/json"

Example Response:

{
"message": "Credit has been added successfully to this client"
}

Error Handling

If a request contains invalid data or references a non-existent resource, the API will return a JSON response with an appropriate HTTP status code and error message. Example:

{
"error": "Client not found"
}

Authentication & Security

Ensure that authentication and authorization mechanisms are in place for production use. You may require API keys, OAuth, or token-based authentication depending on your security requirements.

Contact

For any questions or issues, please contact the API support team at support@localhost.