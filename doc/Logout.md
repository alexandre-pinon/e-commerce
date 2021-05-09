**Logout**
----
  Logout the user, destroys the refresh token.
* **URL:**  `/api/logout`
* **Method:** `POST`
* **Auth required** : `YES`
* **URL Params** `None`
* **Data Params** `None`
* **Success Response:**
  * **Code:** `200`
    **Content:**
```yaml
{
    "message":  "Successfully logged out !"
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
