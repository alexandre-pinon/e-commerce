**Get All**
----
  Display all products.
* **URL:**  `/api/products`
* **Method:** `GET`
* **Auth required** : `NO`
* **URL Params** `None`
* **Data Params** `None`
---
* **Success Response:**
  * **Code:** `200`
    **Content:**
```yaml
[
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
    },
    ...
]
```
