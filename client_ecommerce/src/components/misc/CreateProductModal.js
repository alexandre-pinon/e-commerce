import React, { useState } from "react";
import { Button, Form, Header, Icon, Menu, Modal } from "semantic-ui-react";
import { API } from "../api/requests";
import { makeToast } from "../Toaster";

const CreateProductModal = (props) => {
  const [open, setOpen] = useState(false);
  const [name, setName] = useState("");
  const [description, setDescription] = useState("");
  const [price, setPrice] = useState("");
  const [photo, setPhoto] = useState("");
  const [loading, setLoading] = useState(false);

  const clearForm = () => {
    setName("");
    setDescription("");
    setPrice("");
    setPhoto("");
  };

  const handleSubmit = async (event) => {
    setLoading(true);
    event.preventDefault();
    const data = { name, description, price, photo };
    const token = sessionStorage.getItem("token");
    try {
      const response = await API.post({
        table: "PRODUCT",
        type: "single",
        accessToken: token,
        data,
      });
      console.log(response);
      props.refresherFunc();
      clearForm();
      makeToast("success", response.message);
      setOpen(false);
      setLoading(false);
    } catch (error) {
      console.log(error);
      makeToast("error", error?.response?.data?.message);
    }
  };

  return (
    <Modal
      onClose={() => setOpen(false)}
      onOpen={() => setOpen(true)}
      open={open}
      trigger={
        <Menu.Item as="a">
          <Icon name="plus" size="large" />
          <span style={{ marginLeft: ".5em" }}>Add new product</span>
        </Menu.Item>
      }
    >
      <Modal.Header>
        <Header>Add a new product</Header>
      </Modal.Header>
      <Modal.Content image>
        <Modal.Description>
          <Form size="large">
            <Form.Input
              label="Product name"
              name="name"
              placeholder="Name"
              onChange={(e) => setName(e.target.value)}
              value={name}
              type="text"
              required
            />
            <Form.Input
              label="Product description"
              name="description"
              placeholder="Description - Optional"
              onChange={(e) => setDescription(e.target.value)}
              value={description}
              type="textarea"
              required
            />
            <Form.Input
              label="Product price"
              name="price"
              placeholder="XX.XX"
              onChange={(e) => setPrice(e.target.value)}
              value={price}
              type="number"
              step="0.01"
              required
            />
            <Form.Input
              label="Photo url"
              name="photo"
              placeholder="Photo"
              onChange={(e) => setPhoto(e.target.value)}
              value={photo}
              type="text"
              required
            />
          </Form>
        </Modal.Description>
      </Modal.Content>
      <Modal.Actions>
        <Button
          content="Create"
          icon="add"
          disabled={loading}
          className={loading ? "loading" : ""}
          onClick={handleSubmit}
          positive
        />
        <Button
          content="Cancel"
          labelPosition="right"
          icon="close"
          onClick={() => setOpen(false)}
          negative
        />
      </Modal.Actions>
    </Modal>
  );
};

export default CreateProductModal;
