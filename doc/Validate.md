**Get Cart Products**
----
  Displays the products of the current user's shopping cart.
* **URL:**  `/api/cart/validate`
* **Method:** `POST`
* **Auth required** : `YES`
* **URL Params** `None`
* **Data Params** `None`
---
* **Success Response:**
  * **Code:** `201 CREATED`
    **Content:**
```yaml
{
    "message": "Successfully validated order ! (id: {idorder})"
}
```
---
* **Error Response:**
   * **Code:** `400`
    **Content:**
```yaml
{
    "error":  "Cart is empty !"
}
```
* **OR**
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
