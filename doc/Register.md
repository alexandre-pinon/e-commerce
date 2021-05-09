**Register**
----
  Register the user with the given parameters.
* **URL:**  `/api/register`
* **Method:** `POST`
* **Auth required** : `NO`
* **URL Params** `None`
* **Data Params**
```yaml
{
    "login":  "login",
    "password":  "password",
    "email":  "email@email.email",
    "firstname":  "firstname",     (OPTIONAL)
    "lastname":  "lastname",       (OPTIONAL)
}
```
---
* **Success Response:**
  * **Code:** `201 CREATED`
    **Content:**
```yaml
{
    "message":  "Successfully saved new user ! (id: {iduser})"
}
```
---
* **Error Response:**
  * **Code:** `409 CONFLICT`
    **Content:**
```yaml
{
    "error":  "Username {login} is already taken !"
}
```
* **OR**
   * **Code:** `400`
    **Content:**
```yaml
{
    "error":  "One or more field is missing !"
}
```
