import React from "react";
import { BrowserRouter, Route, Switch } from "react-router-dom";
import Home from "./components/home/Home";
import Navbar from "./components/misc/Navbar";
import Login from "./components/auth/Login";
import Register from "./components/auth/Register";

function Router() {
    return (
        <BrowserRouter>
            <Navbar />
            <Switch>
                <Route exact path="/">
                    <Home />
                </Route>
                <Route path="/login" component={Login}><Login /></Route>
                <Route path="/register" component={Register}><Register /></Route>
            </Switch>
        </BrowserRouter>
    );
}

export default Router;