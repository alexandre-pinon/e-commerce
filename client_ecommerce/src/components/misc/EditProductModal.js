import React, { useState } from "react";
import {
  Button,
  Form,
  Header,
  Icon,
  Image,
  Input,
  Modal,
  Segment,
} from "semantic-ui-react";
import { API } from "../api/requests";
import { makeToast } from "../Toaster";

const EditProductModal = (props) => {
  const [open, setOpen] = useState(false);
  const [name, setName] = useState("");
  const [description, setDescription] = useState("");
  const [price, setPrice] = useState("");
  const [photo, setPhoto] = useState("");
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (event) => {
    setLoading(true);
    event.preventDefault();
    const data = {
      name: name || props.product.name,
      description: description || props.product.description,
      price: price || props.product.price,
      photo: photo || props.product.photo,
    };
    const token = sessionStorage.getItem("token");
    try {
      const response = await API.put({
        table: "PRODUCT",
        type: "single",
        id: props.product.id,
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

  const clearForm = () => {
    setName("");
    setDescription("");
    setPrice("");
    setPhoto("");
  };

  return (
    <Modal
      onClose={() => setOpen(false)}
      onOpen={() => setOpen(true)}
      open={open}
      trigger={
        <Button basic color="blue">
          <div>
            <Icon name="edit" size="large" />
          </div>
        </Button>
      }
    >
      <Modal.Header>
        <Header>{props.product.name}</Header>
      </Modal.Header>
      <Modal.Content image>
        <Image size="medium" src={props.product.photo} wrapped />
        <Modal.Description>
          <Form size="large">
            <Form.Input
              label="Product name"
              name="name"
              placeholder={props.product.name}
              onChange={(e) => setName(e.target.value)}
              value={name}
              type="text"
            />
            <Form.Input
              label="Product description"
              name="description"
              placeholder={props.product.description}
              onChange={(e) => setDescription(e.target.value)}
              value={description}
              type="textarea"
            />
            <Form.Input
              label="Product price"
              name="price"
              placeholder={props.product.price}
              onChange={(e) => setPrice(e.target.value)}
              value={price}
              type="number"
              step="0.01"
            />
            <Form.Input
              label="Photo url"
              name="photo"
              placeholder={props.product.photo}
              onChange={(e) => setPhoto(e.target.value)}
              value={photo}
              type="text"
            />
          </Form>
        </Modal.Description>
      </Modal.Content>
      <Modal.Actions>
        <Button
          content="Edit"
          icon="edit"
          disabled={loading}
          className={loading ? "loading" : ""}
          onClick={handleSubmit}
          positive
        />
        <Button
          content="Cancel"
          labelPosition="right"
          icon="close"
          onClick={() => {
            clearForm();
            setOpen(false);
          }}
          negative
        />
      </Modal.Actions>
    </Modal>
  );
};

export default EditProductModal;
