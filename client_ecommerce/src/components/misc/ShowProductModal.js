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
import ProductCard from "./ProductCard";

const ShowProductModal = (props) => {
  const [open, setOpen] = useState(false);

  return (
    <Modal
      onClose={() => setOpen(false)}
      onOpen={() => setOpen(true)}
      open={open}
      trigger={
        <ProductCard
          key={props.key}
          product={props.product}
          refresherFunc={props.refresherFunc}
          type={props.type}
        />
      }
    >
      <Modal.Header>
        <Header>{props.product.name}</Header>
      </Modal.Header>
      <Modal.Content image>
        <Image size="medium" src={props.product.photo} wrapped />
        <Modal.Description>
          <Header>{props.product.name}</Header>
          <p>
            {props.product.description
              ? props.product.description
              : "No description !"}
          </p>
          <p>{props.product.price}</p>
        </Modal.Description>
      </Modal.Content>
      <Modal.Actions>
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

export default ShowProductModal;
