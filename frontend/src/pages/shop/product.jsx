import React from "react";

export const Product = ({ id, name, price, onAddToCart }) => {
    return (
        <div key={id} className="product">
            <img src="https://usedautopartsdenver.co/userfiles/2403/images/engines_LUH_1_lg.jpg" />
            <div className="description">
                <p>name: {name}</p>
                <p>price: $ {price}</p>
            </div>
            <button
                className="addToCartBttn"
                onClick={() => onAddToCart({ id })}
            >
                Add to Cart
            </button>
            {/* remeber to add the others props*/}
        </div>
    );
};
