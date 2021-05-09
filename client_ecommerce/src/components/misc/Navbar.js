import React from "react";
import { Link } from "react-router-dom";
import { Container, Icon, Menu } from "semantic-ui-react";
import { makeToast } from "../Toaster";
import { API } from "../api/requests";
import CreateProductModal from "./CreateProductModal";
import { LogoIcon } from "./Icons";

const Navbar = (props) => {
  const logout = async () => {
    const token = sessionStorage.getItem("token");
    try {
      const response = await API.post({
        table: "USER",
        type: "logout",
        accessToken: token,
      });
      console.log(response);
      makeToast("success", response.message);
      sessionStorage.removeItem("token");
      props.history.push("/login");
    } catch (error) {
      console.log(error);
      makeToast("error", error?.response?.data?.message);
    }
  };

  return (
    <Menu fixed="top" inverted>
      <Container>
        <Menu.Item as="a" header onClick={() => props.history.push("/")}>
          <LogoIcon />
          <span style={{ marginLeft: "1.5em" }}>E-Commerce</span>
        </Menu.Item>
        <Menu.Item as="a" onClick={() => props.history.push("/cart")}>
          <Icon name="shopping cart" />
          <span style={{ marginLeft: ".5em" }}>Cart</span>
        </Menu.Item>
        <Menu.Item as="a" onClick={() => props.history.push("/order")}>
          <Icon name="money" />
          <span style={{ marginLeft: ".5em" }}>Orders</span>
        </Menu.Item>
        <CreateProductModal refresherFunc={props.refresherFunc} />
        <Menu.Item as="a" position="right" onClick={logout}>
          <Icon name="log out" size="large" />
        </Menu.Item>
      </Container>
    </Menu>
  );
};

export default Navbar;
