import React from "react";

export const CartItem = ({
    id,
    quantity,
    name,
    onUpdateQuantity,
    onRemove,
    price,
}) => {
    return (
        <div>
            <img
                src="https://usedautopartsdenver.co/userfiles/2403/images/engines_LUH_1_lg.jpg"
                alt="product"
            />
            <p>
                {name} &nbsp;&nbsp;&nbsp;&nbsp; price:{price}$ ({quantity})
            </p>
            <button
                className="cartbtn"
                onClick={() => onUpdateQuantity(id, quantity - 1)}
                disabled={quantity === 1}
            >
                Decrease Quantity
            </button>
            <button
                className="cartbtn"
                onClick={() => onUpdateQuantity(id, quantity + 1)}
            >
                Increase Quantity
            </button>
            <button className="cartbtn" onClick={() => onRemove(id)}>
                Remove Item
            </button>
        </div>
    );
};
