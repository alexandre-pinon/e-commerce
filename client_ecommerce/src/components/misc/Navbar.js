import React from "react";
import { Link } from "react-router-dom";

function Navbar() {
    return (
        <div className="navbar">
            <Link to="/">
                <h1>Snippet Manager</h1>
            </Link>
            <Link to="/login">Log in</Link>
            <Link to="/register">Register</Link>
        </div>
    );
}

export default Navbar;