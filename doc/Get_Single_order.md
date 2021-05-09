
**Get Single**
----
  Display a single order.
* **URL:**  `/api/order{orderId}`
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
    "id":  "{orderId}",
    "totalPrice":  XX.XX,
    "creationDate":  "creationDate",
    "products":  [
        {
            "id":  "{productId1}",
            "name":  "name",
            "description":  "description",
            "photo":  "photo_link",
            "price":  XX.XX
        },
        {
            "id":  "{productId2}",
            "name":  "name",
            "description":  "description",
            "photo":  "photo_link",
            "price":  XX.XX
        }
    ]
}
```

---
* **Error Response:**
   * **Code:** `404 NOT FOUND`
    **Content:**
```yaml
{
    "error":  "No order found for order {orderId} !"
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
