import React from 'react'
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

const getUserExample = async () => {
    try {
      const data = {
          login: "test2",
      }
      const response = await API.get({
        table: "USER",
      //   id: 1,
        data
      //   accessToken: "accessToken"
      });
      console.log(response);
    } catch (error) {
      console.log(error);
    }
};

const updateUserExample = async () => {
    try {
      const data = {
          login: "test2",
          password: "123456",
          email: "blackpink@gmail.com",
          firstname: "Black",
          lastname: "Pink",
      }
      const response = await API.put({
        table: "USER",
      //   id: 1,
        data
      //   accessToken: "accessToken"
      });
      console.log(response);
    } catch (error) {
      console.log(error);
    }
};

function Login() {
    return (
        <div className="home">
        <section className="text-gray-400 bg-gray-900 body-font">
            <div className="container px-5 py-24 mx-auto">
                <div className="text-center mb-20">
                    <h1 className="sm:text-3xl text-2xl font-medium title-font text-white mb-4">
                        <button onClick={requestExample}>Click Here To Test Login</button>
                    </h1>
                    <h1>
                        <button onClick={getUserExample}>Click Here To Get User</button>
                    </h1>
                    <h1>
                        <button onClick={updateUserExample}>Click Here To Update User</button>
                    </h1>
            <div className="flex mt-6 justify-center">
            <div className="w-16 h-1 rounded-full bg-indigo-500 inline-flex" />
            </div>
            </div>
            </div>
        </section>
        </div>
    );
}

export default Login