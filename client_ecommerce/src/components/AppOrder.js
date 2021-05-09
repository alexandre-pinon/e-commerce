import React, { useEffect } from "react";
import "./App.css";
import Order from "./screens/Order";

const AppOrder = (props) => {
  useEffect(() => {
    const token = sessionStorage.getItem("token");
    if (!token) props.history.push("/login");
    // eslint-disable-next-line
  }, []);

  return <Order history={props.history} />;
};

export default AppOrder;
