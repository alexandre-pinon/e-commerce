import React from "react";
import { API } from "../api/requests";

const createProductExample = async () => {
  try {
    const data = {
        name: "lamedesenfers",
        photo: "",
        price: 25,
    }
    const response = await API.post({
      table: "PRODUCT",
    //   id: 1,
      data
    //   accessToken: "accessToken"
    });
    console.log(response);
  } catch (error) {
    console.log(error);
  }
};

const getProductExample = async () => {
  try {
    const data = {
        name: "lamedesenfers",
    }
    const response = await API.get({
      table: "PRODUCT",
    //   id: 1,
      data
    //   accessToken: "accessToken"
    });
    console.log(response);
  } catch (error) {
    console.log(error);
  }
};

const updateProductExample = async () => {
  try {
    const data = {
        name: "lamedesenfers",
        photo: "",
        price: 50,
    }
    const response = await API.put({
      table: "PRODUCT",
    //   id: 1,
      data
    //   accessToken: "accessToken"
    });
    console.log(response);
  } catch (error) {
    console.log(error);
  }
};

function Home() {
  return (
    <div className="home">
      <section className="text-gray-400 bg-gray-900 body-font">
      <div className="container px-5 py-24 mx-auto">
        <div className="text-center mb-20">
          <h1 className="sm:text-3xl text-2xl font-medium title-font text-white mb-4">
            E-Commerce App in Symfony
          </h1>
          
          <p className="text-base leading-relaxed xl:w-2/4 lg:w-3/4 mx-auto text-gray-400 text-opacity-80">
            Little Front ♥
          </p>
          <h2>
            <button onClick={createProductExample}>Create Product</button>
          </h2>
          <h2>
            <button onClick={getProductExample}>Get Product</button>
          </h2>
          <h2>
            <button onClick={updateProductExample}>Update Product</button>
          </h2>
          <div className="flex mt-6 justify-center">
            <div className="w-16 h-1 rounded-full bg-indigo-500 inline-flex" />
          </div>
        </div>
      </div>
    </section>
    </div>
  );
}

export default Home;
