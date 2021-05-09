import React from "react";
import { Button, Card, Icon } from "semantic-ui-react";
import { API } from "../api/requests";
import { makeToast } from "../Toaster";
import EditProductModal from "./EditProductModal";

const ProductCard = (props) => {
  const deleteProduct = async () => {
    const token = sessionStorage.getItem("token");
    try {
      const response = await API.remove({
        table: "PRODUCT",
        type: "single",
        id: props.product.id,
        accessToken: token,
      });
      console.log(response);
      makeToast("success", response.message);
      props.refresherFunc();
    } catch (error) {
      console.log(error);
      makeToast("error", error?.response?.data?.message);
    }
  };

  const addToCart = async () => {
    const token = sessionStorage.getItem("token");
    try {
      const response = await API.post({
        table: "CART",
        type: "single",
        id: props.product.id,
        accessToken: token,
      });
      console.log(response);
      makeToast("success", response.message);
    } catch (error) {
      console.log(error);
      makeToast("error", error?.response?.data?.message);
    }
  };

  const removeFromCart = async () => {
    const token = sessionStorage.getItem("token");
    try {
      const response = await API.remove({
        table: "CART",
        type: "single",
        id: props.product.id,
        accessToken: token,
      });
      console.log(response);
      makeToast("success", response.message);
      props.refresherFunc();
    } catch (error) {
      console.log(error);
      makeToast("error", error?.response?.data?.message);
    }
  };

  const CatalogueButtons = () => (
    <div className="ui three buttons">
      <Button basic color="green" onClick={addToCart}>
        <div>
          <Icon name="add to cart" size="large" />
        </div>
      </Button>
      <EditProductModal
        product={props.product}
        refresherFunc={props.refresherFunc}
      />
      <Button basic color="red" onClick={deleteProduct}>
        <div>
          <Icon name="trash" size="large" />
        </div>
      </Button>
    </div>
  );

  const CartButtons = () => (
    <div className="ui buttons">
      <Button basic color="red" onClick={removeFromCart}>
        <div>
          <Icon name="delete" size="large" />
        </div>
      </Button>
    </div>
  );

  return (
    <Card>
      <Card.Content header={props.product.name} textAlign="center" />
      <Card.Content
        style={{
          height: "200px",
          backgroundImage: `url(${props.product.photo})`,
          backgroundSize: "cover",
        }}
      >
        {props.type ? (
          <Card.Description style={{ color: "white", fontSize: "1.5em" }}>
            {props.product.price} €
          </Card.Description>
        ) : (
          ""
        )}
      </Card.Content>
      <Card.Content extra>
        {props.type ? (
          props.type === "catalogue" ? (
            <CatalogueButtons />
          ) : (
            <CartButtons />
          )
        ) : (
          ""
        )}
      </Card.Content>
    </Card>
  );
};

export default ProductCard;
