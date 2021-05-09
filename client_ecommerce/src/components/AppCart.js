import React, { useEffect } from "react";
import "./App.css";
import Cart from "./screens/Cart";

const AppCart = (props) => {
  useEffect(() => {
    const token = sessionStorage.getItem("token");
    if (!token) props.history.push("/login");
    // eslint-disable-next-line
  }, []);

  return <Cart history={props.history} />;
};

export default AppCart;
