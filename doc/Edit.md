**Edit**
----
  Edit a product.
* **URL:**  `/api/product/{productId}`
* **Method:** `PUT`
* **Auth required** : `YES`
* **URL Params** `productId`
* **Data Params** 
```yaml
{
    "name":  "new_name",               (OPTIONAL)
    "description":  "new_description", (OPTIONAL)
    "photo":  "new_photo_link",        (OPTIONAL)
    "price":  XX.XX                    (OPTIONAL)
}
```
---
* **Success Response:**
  * **Code:** `200`
    **Content:**
```yaml
{
    "id":  "{productId}",
    "name":  "new_name",
    "description":  "new_description",
    "photo":  "new_photo_link",
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
