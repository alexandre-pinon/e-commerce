import React, { useEffect, useState } from "react";
import { API } from "../api/requests";
import { LogoIcon } from "../misc/Icons";
import ProductCard from "../misc/ProductCard";
import Navbar from "../misc/Navbar";
import { Card, Container, Icon, Menu } from "semantic-ui-react";
import ShowProductModal from "../misc/ShowProductModal";

const Catalog = (props) => {
  const [products, setProducts] = useState(null);

  useEffect(() => {
    fetchProducts();
    // eslint-disable-next-line
  }, []);

  const fetchProducts = async () => {
    const products = await API.get({
      table: "PRODUCT",
      type: "all",
    });
    console.log(products);
    setProducts(products);
  };

  return (
    <div>
      <Navbar history={props.history} refresherFunc={fetchProducts} />
      <Container
        style={{
          marginTop: "7em",
        }}
      >
        <Card.Group>
          {products?.map((product) => (
            <ShowProductModal
              key={product.id}
              product={product}
              refresherFunc={fetchProducts}
              type="catalogue"
            />
          ))}
        </Card.Group>
      </Container>
    </div>
  );
};

export default Catalog;
