**Get Cart Products**
----
  Displays the products of the current user's shopping cart.
* **URL:**  `/api/cart`
* **Method:** `GET`
* **Auth required** : `YES`
* **URL Params** `None`
* **Data Params** `None`
---
* **Success Response:**
  * **Code:** `200`
    **Content:**
```yaml
[
    {
        "id":  "idproduct_1",
        "name":  "name_1",
        "description":  "description_1",
        "photo":  "photo_link_1",
        "price":  XX.XX
    },
    {
        "id":  "idproduct_2",
        "name":  "name_2",
        "description":  "description_2",
        "photo":  "photo_link_2",
        "price":  XX.XX
    },
    ...
]
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
