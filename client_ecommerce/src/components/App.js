import React, { useEffect } from "react";
import Catalog from "./screens/Catalog";
import "./App.css";

const App = (props) => {
  useEffect(() => {
    const token = sessionStorage.getItem("token");
    if (!token) props.history.push("/login");
    // eslint-disable-next-line
  }, []);

  return <Catalog history={props.history} />;
};

export default App;
