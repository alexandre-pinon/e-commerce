**Info**
----
  Display current user information.
* **URL:**  `/api/user`
* **Method:** `GET`
* **Auth required** : `YES`
* **URL Params** `None`
* **Data Params** `None`
---
* **Success Response:**
  * **Code:** `200`
    **Content:**
```yaml
{
    "id":  "id",
    "email":  "email@email.email",
    "firstname":  "firstname",
    "lastname":  "lastname",
    "login":  "login"
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
