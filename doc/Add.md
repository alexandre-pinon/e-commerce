**Add**
----
  Add a product.
* **URL:**  `/api/product`
* **Method:** `POST`
* **Auth required** : `YES`
* **URL Params** `None`
* **Data Params** 
```yaml
{
    "name":  "name",
    "description":  "description",
    "photo":  "photo_link",
    "price":  XX.XX
}
```
---
* **Success Response:**
  * **Code:** `201 CREATED`
    **Content:**
```yaml
{
    "message":  "Successfully saved new product ! (id: {idproduct})"
}
```
---
* **Error Response:**
   * **Code:** `400`
    **Content:**
```yaml
{
    "error":  "One or more field is missing !"
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
