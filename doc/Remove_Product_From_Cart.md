**Remove Product from Cart**
----
  Add a product to the shopping cart.
* **URL:**  `/api/cart/{productId}`
* **Method:** `DELETE`
* **Auth required** : `YES`
* **URL Params** `None`
* **Data Params** `None`
---
* **Success Response:**
  * **Code:** `200`
    **Content:**
```yaml
{
    "message":  "Successfully removed product to cart ! (id: {productId})"
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
   * **Code:** `404 NOT FOUND`
    **Content:**
```yaml
{
    "error":  "Product {productId} not found in cart {cartId} !"
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
