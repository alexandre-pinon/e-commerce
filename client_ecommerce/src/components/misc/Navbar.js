import React from "react";
import { Link } from "react-router-dom";
import { LogoIcon } from "./Icons";

function Navbar() {
    return (
      <div className="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
        <span className="flex title-font font-medium items-center text-white mb-4 md:mb-0">
            <LogoIcon />
            <span className="flex items-center justify-center ml-3 text-xl">
                <span className="inline-block py-1 px-2 text-white text-2xl font-medium tracking-wider">
            
                </span>
            <Link to="/">
                    React Shopper
            </Link>
            <Link to="/login" style={{ marginLeft: 10 }}>Login</Link>
            <Link to="/register" style={{ marginLeft: 10 }}>Register</Link>
          </span>
        </span>
        
      </div>
      
    );
}

export default Navbar;