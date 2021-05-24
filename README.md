# GildedRose API

This API is created to manage Gilded Rose stock and automatically renew Item quality through Cron Job.
## Installation

This API uses:

- PHP 8
- Symfony 5.2
- Composer

Clone the repository

```sh
git clone git@github.com:daumantas-maciulis/GildedRose-API.git
```

or

```shell script
git clone https://github.com/daumantas-maciulis/GildedRose-API.git
```

Install all the dependencies using composer

```shell script
cd ./GildedRose-API/
composer install
```
---
## REST API

### Security - Registration

When new user creates new Account his role automatically set to ROLE_USER

#### Request

``
POST /api/create-account
``

#### User account information constraints

password - at least six symbols </br>
firstName - can only consist of letters and whitespaces, not longer than 40 symbols </br>
lastName - can only consist of letters and whitespaces, not longer than 40 symbols </br>
phoneNumber - must start with +370

#### JSON Body

```Json
{
  "email": "email@email.com",
  "password": "strongAndSecurePassword",
  "firstName": "John",
  "lastName": "Doe",
  "phoneNumber": "+37061234567",
  "position": "Awesome Junior Developer"
}
```

---

### Security - Login

For authentication Lexik/JWT token authenticator was used.

#### Request

``
POST /api/login-check
``

#### JSON Body

```Json
{
  "username": "email@email.com",
  "password": "strongAndSecurePassword"
}
```

#### Response

User will get JWT Token in response. Token life time is 3600 seconds. User must include Token in every header of
the request.
---

### Create new Category
Category creation is possible for both ROLE_USER and ROLE_ADMIN

#### Request

``
POST /api/v1/category
``

#### Category validation

Category name cannot be shorter than 5 symbols

#### JSON Body

```Json
{
  "name": "New Category name"
}
```
---
### Get all items from category
Fetching data with Category Items is possible for both - ROLE_USER and ROLE _ADMIN

#### Request

``
GET /api/v1/category/{name}
``

#### JSON Response Body

```Json
{
  "id": 59,
  "name": "Aged Brie",
  "items": [
    {
      "id": 29,
      "category": "Aged Brie",
      "name": "white one_item",
      "value": 10,
      "quality": 34,
      "sellIn": -8
    }
  ]
}
```

---

### Delete All Items in category
Using this endpoint ROLE_USER and ROLE_ADMIN can only delete all items inside category, but he cannot delete Category

#### Request

``
DELETE /api/v1/category/{name}
``
---
### Add new Item

Using this endpoint ROLE_USER and ROLE_ADMIN can add new Item and assign it to category

#### Request

``
POST /api/v1/item
``

#### Item constraints

categoryName - must be exact one which is already created </br>
name - Item name must end with _item prefix </br>
value - can be float type, at least 10, but not greater than 100 </br>
quality - must be type integer, at least -10, no greater than 50

#### JSON Body

```Json
{
  "categoryName": "Aged brie",
  "name": "Blue type_item",
  "value": 100,
  "quality": 35,
  "sellIn": 10
}
```

---

### Update Item

Using this endpoint ROLE_USER and ROLE_ADMIN can update Item by its ID

#### Request

``
PATCH /api/v1/item/{id}
``

#### Item constraints

categoryName - must be exact one which is already created </br>
name - Item name must end with _item prefix </br>
value - can be float type, at least 10, but not greater than 100 </br>
quality - must be type integer, at least -10, no greater than 50

#### JSON Body

```Json
{
  "categoryName": "Aged brie",
  "name": "Blue type_item",
  "value": 100,
  "quality": 35,
  "sellIn": 10
}
```
---
### Delete Item

Using this endpoint ROLE_ADMIN can delete item by its ID

#### Request

``
DELETE /api/v1/item/{id}
``

---
### Delete Category

Using this endpoint ROLE_ADMIN can delete Category by its name

#### Request

``
DELETE /api/v1/category/{name}
``

---
### Update Category

Using this endpoint ROLE_ADMIN can update Category by its name
#### Request

``
POST /api/v1/category/{name}
``

#### Category validation

Category name cannot be shorter than 5 symbols

#### JSON Body

```Json
{
  "name": "New Category name"
}
```
---
## Automated command for updating Item quality

To use comamnd </br>
```puml
bin/console app:update-items
```

This command is prepared to be used as CronJob, every day after/before workday.