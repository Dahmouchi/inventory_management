import React from "react";

export const CartItem = ({ id, quantity, onUpdateQuantity, onRemove }) => {
    return (
        <div>
            <p>id: {id}</p>
            <p>Quantity: {quantity}</p>
            <button
                onClick={() => onUpdateQuantity(id, quantity - 1)}
                disabled={quantity === 1}
            >
                Decrease Quantity
            </button>
            <button onClick={() => onUpdateQuantity(id, quantity + 1)}>
                Increase Quantity
            </button>
            <button onClick={() => onRemove(id)}>Remove Item</button>
        </div>
    );
};
