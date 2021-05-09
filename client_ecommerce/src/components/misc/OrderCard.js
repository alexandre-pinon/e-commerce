import React, { useEffect, useState } from "react";
import { API } from "../api/requests";
import { LogoIcon } from "../misc/Icons";
import ProductCard from "../misc/ProductCard";
import Navbar from "../misc/Navbar";
import { Card, Container, Icon, Menu } from "semantic-ui-react";
import ShowProductModal from "./ShowProductModal";

const OrderCard = (props) => {
  return (
    <Container
      style={{
        marginTop: "7em",
      }}
    >
      <Card fluid>
        <Card.Content header={`Order n°${props.order.id}`} textAlign="center" />
        <Card.Content>
          <Card.Group>
            {props.order?.products?.map((product) => (
              <ShowProductModal key={product.id} product={product} />
            ))}
          </Card.Group>
        </Card.Content>
        <Card.Content extra>{`TOTAL : ${props.order.totalPrice} €`}</Card.Content>
      </Card>
    </Container>
  );
};

export default OrderCard;
