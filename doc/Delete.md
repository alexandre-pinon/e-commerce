**Delete**
----
  Delete a product.
* **URL:**  `/api/product/{productId}`
* **Method:** `DELETE`
* **Auth required** : `YES`
* **URL Params** `productId`
* **Data Params**  `None`
---
* **Success Response:**
  * **Code:** `200`
    **Content:**
```yaml
{
    "message":  "Successfully deleted product ! (id: {productId})"
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
