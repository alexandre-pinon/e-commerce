import React from "react";
import { API } from "../api/requests";

const requestExample = async () => {
  try {
    const data = {
        login: "test2",
        password: "123456",
    }
    const response = await API.post({
      table: "USER",
      type: "login",
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
      Home<br></br>
      <button onClick={requestExample}>Test Request</button>
    </div>
  );
}

export default Home;
