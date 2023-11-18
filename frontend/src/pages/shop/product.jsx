import React from "react";

export const Product = ({ id, name, price, onAddToCart }) => {
    return (
        <div>
            <li key={id}>
                <p>{name}</p>
                <p>{price}</p>
                <button onClick={() => onAddToCart({ id })}>Add to Cart</button>
                {/* remeber to add the others props*/}
            </li>
        </div>
    );
};
