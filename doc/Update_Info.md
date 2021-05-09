**Update Info**
----
  Update current user information, regenerate authentication/refresh token if needed
* **URL:**  `/api/user`
* **Method:** `PUT`
* **Auth required** : `YES`
* **URL Params** `None`
* **Data Params**
```yaml
{
    "login":  "new_login",                     (OPTIONAL)
    "password":  "new_password",               (OPTIONAL)
    "email":  "new_email@new_email.new_email", (OPTIONAL)
    "firstname":  "new_firstname",             (OPTIONAL)
    "lastname":  "new_lastname",               (OPTIONAL)
}
```
---
* **Success Response:**
  * **Code:** `200`
    **Content:**
```yaml
{
    "id":  "id",
    "email":  "new_email@new_email.new_email",
    "firstname":  "new_firstname",
    "lastname":  "new_lastname",
    "login":  "new_login"
}
```
* **OR**
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
    "error":  "JWT Token not found"
}
```
* **OR**
  * **Code:** `401 UNAUTHORIZED`
    **Content:**
```yaml
{
    "error":  "Invalid JWT Token"
}
```
* **OR**
  * **Code:** `401 UNAUTHORIZED`
    **Content:**
```yaml
{
    "error":  "Expired JWT Token"
}
```
