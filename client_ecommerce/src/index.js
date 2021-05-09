import React from "react";
import ReactDOM from "react-dom";
import App from "./components/App";
import AppCart from "./components/AppCart";
import AppOrder from "./components/AppOrder";
import Login from "./components/auth/Login";
import Register from "./components/auth/Register";
import "semantic-ui-css/semantic.min.css";

import { BrowserRouter as Router, Switch, Route } from "react-router-dom";

const Root = () => {
  return (
    <Router>
      <Switch>
        <Route exact path="/" component={App} />
        <Route exact path="/login" component={Login} />
        <Route exact path="/register" component={Register} />
        <Route exact path="/cart" component={AppCart} />
        <Route exact path="/order" component={AppOrder} />
      </Switch>
    </Router>
  );
};

ReactDOM.render(<Root />, document.getElementById("root"));
