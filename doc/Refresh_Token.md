**Refresh Token**
----
  Regenerate user's authentication token.
* **URL:**  `/token/refresh`
* **Method:** `POST`
* **Auth required** : `NO`
* **URL Params** `None`
* **Data Params**
```yaml
{
    "refresh_token":  "refresh_token"
}
```
---
* **Success Response:**
  * **Code:** `200`
    **Content:**
```yaml
{
    "token":  "new_token",
    "refresh_token":  "new_refresh_token"
}
```
---
* **Error Response:**
  * **Code:** `401 UNAUTHORIZED`
    **Content:**
```yaml
{
    "error":  "An authentication exception occurred."
}
```
