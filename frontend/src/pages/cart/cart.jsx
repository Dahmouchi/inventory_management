import React, { useContext, useState } from "react";
import { CartItem } from "./cartItem";
import { CartContext } from "../context/cartContext";
import axios from "axios";

import "./cart.css"

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
    const totalPrice = cart.reduce(
        (accumulator, currentItem) =>
            accumulator + currentItem.sellPrice * currentItem.quantity,
        0
    );
    return (
        <div className="Cart">
            <h1>Shopping Cart</h1>
            {cart.length === 0 ? (
                <div>
                    <p>Your cart is empty.</p>
                    {purchaseMessage && <p> {purchaseMessage} </p>}
                </div>
            ) : (
                <div className="cart">
                    {cart.map((item) => (
                        <CartItem
                            key={item.id}
                            id={item.id}
                            quantity={item.quantity}
                            name={item.name}
                            price={item.sellPrice}
                            onRemove={removeFromCart}
                            onUpdateQuantity={updateQuantity}
                        />
                    ))}
                    <div className="total-price">
                        <p>Total Price: ${totalPrice.toFixed(2)}</p>
                    </div>
                    <button className="cartbtn" onClick={ConfirmPurchase}>
                        Confirm Purchase
                    </button>
                    {purchaseMessage && <p> {purchaseMessage} </p>}
                </div>
            )}
        </div>
    );
};
