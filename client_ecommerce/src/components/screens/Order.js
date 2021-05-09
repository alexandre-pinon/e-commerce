import React, { useEffect, useState } from "react";
import { API } from "../api/requests";
import { LogoIcon } from "../misc/Icons";
import ProductCard from "../misc/ProductCard";
import Navbar from "../misc/Navbar";
import { Card, Container, Icon, Menu } from "semantic-ui-react";
import OrderCard from "../misc/OrderCard";

const Order = (props) => {
  const [orders, setOrders] = useState(null);

  useEffect(() => {
    fetchOrders();
    // eslint-disable-next-line
  }, []);

  const fetchOrders = async () => {
    const token = sessionStorage.getItem("token");
    const orders = await API.get({
      table: "ORDER",
      type: "all",
      accessToken: token,
    });
    console.log(orders);
    setOrders(orders);
  };

  return (
    <div>
      <Navbar history={props.history} />
      <Container
        style={{
          marginTop: "7em",
          paddingBottom: "7em",
        }}
      >
        {orders?.map((order) => (
          <OrderCard order={order} />
        ))}
      </Container>
    </div>
  );
};

export default Order;
