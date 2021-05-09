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

const Register = (props) => {
  const [login, setLogin] = useState("");
  const [password, setPassword] = useState("");
  const [passwordConfirmation, setPasswordConfirmation] = useState("");
  const [email, setEmail] = useState("");
  const [firstname, setFirstname] = useState("");
  const [lastname, setLastname] = useState("");
  const [loading, setLoading] = useState(false);

  const handleError = (error) => {
    makeToast("error", error);
    setPassword("");
    setPasswordConfirmation("");
    setLoading(false);
  };

  const isPasswordValid = (password, passwordConfirmation) => {
    if (password !== passwordConfirmation) {
      handleError("Passwords are different !");
      return false;
    }
    return true;
  };

  const handleFailedAuth = (response) => {
    setLogin("");
    handleError(response.message);
  };

  const handleAuth = (response) => {
    makeToast("success", response.message);
    props.history.push("/login");
  };

  const handleSubmit = async (event) => {
    setLoading(true);
    event.preventDefault();
    if (!isPasswordValid(password, passwordConfirmation)) return;
    const data = { login, password, email, firstname, lastname };
    try {
      const response = await API.post({
        table: "USER",
        type: "register",
        data,
      });
      console.log(response);
      handleAuth(response);
    } catch (error) {
      handleFailedAuth(error.response.data);
    }
  };

  return (
    <Grid textAlign="center" verticalAlign="middle" className="app">
      <Grid.Column style={{ maxWidth: 450 }}>
        <Header as="h2" icon color="blue" textAlign="center">
          <Icon name="chat" color="blue" />
          Register for PlooV4
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
            <Form.Input
              fluid
              name="passwordConfirmation"
              icon="repeat"
              iconPosition="left"
              placeholder="Password Confirmation"
              onChange={(e) => setPasswordConfirmation(e.target.value)}
              value={passwordConfirmation}
              type="password"
              required
            />
            <Form.Input
              fluid
              name="email"
              icon="mail"
              iconPosition="left"
              placeholder="Email"
              onChange={(e) => setEmail(e.target.value)}
              value={email}
              type="email"
              required
            />
            <Form.Input
              fluid
              name="firstname"
              icon="user"
              iconPosition="left"
              placeholder="Firstname - Optional"
              onChange={(e) => setFirstname(e.target.value)}
              value={firstname}
              type="text"
            />
            <Form.Input
              fluid
              name="lastname"
              icon="user"
              iconPosition="left"
              placeholder="Lastname - Optional"
              onChange={(e) => setLastname(e.target.value)}
              value={lastname}
              type="text"
            />
            <Button
              disabled={loading}
              className={loading ? "loading" : ""}
              color="blue"
              fluid
              size="large"
            >
              Register
            </Button>
          </Segment>
        </Form>
        <Message>
          Already a user ?<Link to="/login"> Login</Link>
        </Message>
      </Grid.Column>
    </Grid>
  );
};

export default Register;
