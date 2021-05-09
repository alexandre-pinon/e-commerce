import React, { useState } from "react";
import { API } from "../api/requests";
import {
  Grid,
  Form,
  Segment,
  Button,
  Header,
  Message,
  Icon,
} from "semantic-ui-react";
import { Link } from "react-router-dom";
import { makeToast } from "../Toaster";

const Login = (props) => {
  const [login, setLogin] = useState("");
  const [password, setPassword] = useState("");
  const [loading, setLoading] = useState(false);

  const handleFailedAuth = (response) => {
    makeToast("error", response.message);
    setPassword("");
    setLoading(false);
  };

  const handleAuth = (response) => {
    makeToast("success", "Successfully logged in !");
    sessionStorage.setItem("token", response.token);
    sessionStorage.setItem("refresh_token", response.refresh_token);
    props.history.push("/");
  };

  const handleSubmit = async (event) => {
    setLoading(true);
    event.preventDefault();
    const data = { login, password };
    try {
      const response = await API.post({
        table: "USER",
        type: "login",
        data,
      });
      console.log(response);
      handleAuth(response);
    } catch (error) {
      handleFailedAuth(error?.response?.data)
    }
  };

  return (
    <Grid textAlign="center" verticalAlign="middle" className="app">
      <Grid.Column style={{ maxWidth: 450 }}>
        <Header as="h2" icon color="purple" textAlign="center">
          <Icon name="chat" color="purple" />
          Login for PlooV4
        </Header>
        <Form onSubmit={handleSubmit} size="large">
          <Segment stacked>
            <Form.Input
              fluid
              name="login"
              icon="user"
              iconPosition="left"
              placeholder="Login"
              onChange={(e) => setLogin(e.target.value)}
              value={login}
              type="text"
              required
            />
            <Form.Input
              fluid
              name="password"
              icon="lock"
              iconPosition="left"
              placeholder="Password"
              onChange={(e) => setPassword(e.target.value)}
              value={password}
              type="password"
              required
            />
            <Button
              disabled={loading}
              className={loading ? "loading" : ""}
              color="purple"
              fluid
              size="large"
            >
              Login
            </Button>
          </Segment>
        </Form>
        <Message>
          Don't have an account ?<Link to="/register"> Register</Link>
        </Message>
      </Grid.Column>
    </Grid>
  );
};

export default Login;
