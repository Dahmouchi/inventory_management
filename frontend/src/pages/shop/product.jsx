import React, { useContext } from "react";
import { CartContext } from "../context/cartContext";

export const Product = ({ id, name, price, onAddToCart }) => {
    const { cart } = useContext(CartContext);
    const existingProduct = cart.find((item) => item.id === id);
    const productCount = existingProduct ? existingProduct.quantity : 0;
    return (
        <div key={id} className="product">
            <img
                src="https://usedautopartsdenver.co/userfiles/2403/images/engines_LUH_1_lg.jpg"
                alt="product"
            />
            <div className="description">
                <p> {name}</p>
                <p> $ {price}</p>
            </div>
            <button
                className="addToCartBttn"
                onClick={() => onAddToCart({ id })}
            >
                Add to Cart {productCount > 0 && <span>({productCount})</span>}
            </button>
            {/* remeber to add the others props*/}
        </div>
    );
};
