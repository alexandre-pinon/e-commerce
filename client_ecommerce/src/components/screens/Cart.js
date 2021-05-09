import React, { useEffect, useState } from "react";
import { API } from "../api/requests";
import { LogoIcon } from "../misc/Icons";
import ProductCard from "../misc/ProductCard";
import Navbar from "../misc/Navbar";
import { Button, Card, Container, Header, Icon, Menu } from "semantic-ui-react";
import { makeToast } from "../Toaster";
import ShowProductModal from "../misc/ShowProductModal";

const Cart = (props) => {
  const [products, setProducts] = useState(null);

  useEffect(() => {
    fetchCart();
    // eslint-disable-next-line
  }, []);

  const fetchCart = async () => {
    const token = sessionStorage.getItem("token");
    const products = await API.get({
      table: "CART",
      type: "single",
      accessToken: token,
    });
    console.log(products);
    setProducts(products);
  };

  const validateCart = async () => {
    try {
      const token = sessionStorage.getItem("token");
      const response = await API.post({
        table: "CART",
        type: "validate",
        accessToken: token,
      });
      console.log(response);
      fetchCart();
      makeToast("success", response.message);
    } catch (error) {
      console.log(error);
      makeToast("error", error?.response?.data?.message);
    }
  };

  return (
    <div>
      <Navbar history={props.history} />
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
              refresherFunc={fetchCart}
              type="cart"
            />
          ))}
        </Card.Group>
      </Container>

      <Container
        textAlign="center"
        style={{
          marginTop: "7em",
        }}
      >
        {products?.length ? (
          <Button positive size="huge" onClick={validateCart}>
            Validate
            <Icon name="right check" />
          </Button>
        ) : (
          <Header>Your cart is empty !</Header>
        )}
      </Container>
    </div>
  );
};

export default Cart;
