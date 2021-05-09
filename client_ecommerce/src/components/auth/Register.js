import React from 'react'
import { API } from "../api/requests";

const registerExample = async () => {
    try {
      const data = {
          login: "test2",
          password: "123456",
          email: "kpopdu75@gmail.com",
          firstname: "",
          lastname: "",
      }
      const response = await API.post({
        table: "USER",
        type: "register",
      //   id: 1,
        data
      //   accessToken: "accessToken"
      });
      console.log(response);
    } catch (error) {
      console.log(error);
    }
  };

function Register() {
    return (
        <div className="home">
        <section className="text-gray-400 bg-gray-900 body-font">
            <div className="container px-5 py-24 mx-auto">
                <div className="text-center mb-20">
                    <h1 className="sm:text-3xl text-2xl font-medium title-font text-white mb-4">
                        <button onClick={registerExample}>Click Here To Test Register</button>
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

export default Register