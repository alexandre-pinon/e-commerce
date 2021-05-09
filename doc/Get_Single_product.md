**Get Single**
----
  Display a single product.
* **URL:**  `/api/product/{productId}`
* **Method:** `GET`
* **Auth required** : `NO`
* **URL Params** `productId`
* **Data Params** `None`
---
* **Success Response:**
  * **Code:** `200`
    **Content:**
```yaml
{
    "id":  "{productId}",
    "name":  "name",
    "description":  "description",
    "photo":  "photo_link",
    "price":  XX.XX
}
```
---
* **Error Response:**
   * **Code:** `404 NOT FOUND`
    **Content:**
```yaml
{
    "error":  "No product found for productId {productId} !"
}
```
