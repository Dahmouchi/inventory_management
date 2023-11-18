import React, { useContext } from "react";
import { CartItem } from "./cartItem";
import { CartContext } from "../context/cartContext";

export const Cart = () => {
    const { updateQuantity, removeFromCart } = useContext(CartContext);
    const { cart } = useContext(CartContext);
    return (
        <div className="Cart">
            <h1>Shopping Cart</h1>
            {cart.length === 0 ? (
                <p>Your cart is empty.</p>
            ) : (
                <div>
                    <ul>
                        {cart.map((item) => (
                            <CartItem
                                key={item.id}
                                onRemove={removeFromCart}
                                onUpdateQuantity={updateQuantity}
                            />
                        ))}
                    </ul>
                    <button>Confirm Purchase</button>
                </div>
            )}
        </div>
    );
};
