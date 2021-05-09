**Login**
----
  Login the user with the given credentials, retrieving the authentication token and the associated refresh token.
* **URL:**  `/api/login`
* **Method:** `POST`
* **Auth required** : `NO`
* **URL Params** `None`
* **Data Params**
```yaml
{
    "login":  "login",
    "password":  "password",
}
```
---
* **Success Response:**
  * **Code:** `200`
    **Content:**
```yaml
{
    "token":  "token",
    "refresh_token":  "refresh_token"
}
```
---
* **Error Response:**
  * **Code:** `401 UNAUTHORIZED`
    **Content:**
```yaml
{
    "error":  "Invalid credentials."
}
```
