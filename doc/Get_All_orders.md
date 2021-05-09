
**Get All**
----
  Display all orders.
* **URL:**  `/api/orders`
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
        "id":  "{orderId1}",
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
    },
    {
        "id":  "{orderId2}",
        "totalPrice":  XX.XX,
        "creationDate":  "creationDate",
        "products":  [
            {
                "id":  "{productId3}",
                "name":  "name",
                "description":  "description",
                "photo":  "photo_link",
                "price":  XX.XX
            }
        ]
    },
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
