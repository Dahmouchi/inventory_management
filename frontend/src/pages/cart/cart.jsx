import React, { useContext, useState } from "react";
import { CartItem } from "./cartItem";
import { CartContext } from "../context/cartContext";
import axios from "axios";

export const Cart = () => {
    const { updateQuantity, removeFromCart, cart, setCart } =
        useContext(CartContext);
    const [purchaseMessage, setPurchaseMessage] = useState("");
    const ConfirmPurchase = () => {
        const parts = cart.map((item) => {
            return {
                id: item.id,
                quantity: item.quantity,
            };
        });

        axios
            .post("http://127.0.0.1:8000/api/parts/buy", { parts })
            .then((response) => {
                setPurchaseMessage(response.data.message);
                console.log(response.data.message);
                setCart([]);
            })
            .catch((err) => {
                console.log(err);
                setPurchaseMessage(err.response.data.message);
            });
    };
    return (
        <div className="Cart">
            <h1>Shopping Cart</h1>
            {cart.length === 0 ? (
                <div>
                    <p>Your cart is empty.</p>
                    {purchaseMessage && <p> {purchaseMessage} </p>}
                </div>
            ) : (
                <div>
                    <ul>
                        {cart.map((item) => (
                            <CartItem
                                key={item.id}
                                id={item.id}
                                quantity={item.quantity}
                                name={item.name}
                                onRemove={removeFromCart}
                                onUpdateQuantity={updateQuantity}
                            />
                        ))}
                    </ul>
                    <button onClick={ConfirmPurchase}>Confirm Purchase</button>
                    {purchaseMessage && <p> {purchaseMessage} </p>}
                </div>
            )}
        </div>
    );
};
