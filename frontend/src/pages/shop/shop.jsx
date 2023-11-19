import React, { useEffect, useState, useContext } from "react";
import axios from "axios";
import { Product } from "./product";
import { CartContext } from "../context/cartContext";
import "./shop.css";
export const Shop = () => {
    const [products, setProducts] = useState([]);
    const { addToCart } = useContext(CartContext);

    useEffect(() => {
        // Fetch data from  API
        axios
            .get("http://127.0.0.1:8000/api/parts")
            .then((response) => {
                setProducts(response.data);
            })
            .catch((error) => {
                console.error("Error fetching products:", error);
            });
    }, []);

    return (
        <div className="Shop">
            <div className="shopTitle">
                <h1>Wa Hassan Shop</h1>
                <h2>Products</h2>
            </div>
            <div className="products">
                {products.map((product) => (
                    <Product
                        key={product.id}
                        id={product.id}
                        name={product.name}
                        price={product.sellPrice}
                        onAddToCart={() => addToCart(product)}
                    />
                ))}
            </div>
        </div>
    );
};
