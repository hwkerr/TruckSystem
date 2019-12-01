package com.beef.whatthetruck;

import android.app.Application;
import android.graphics.Bitmap;

import com.beef.whatthetruck.ui.cart.CartFragment;
import com.beef.whatthetruck.ui.home.ItemData;

import java.util.ArrayList;
import java.util.List;

public class BeefApplication extends Application {

    private List<ItemData> myCart = new ArrayList<>();
    private CartFragment cartFragment;

    public List<ItemData> getCart() {
        return myCart;
    }

    public void addToCart(ItemData item) {
        if (myCart.contains(item))
            myCart.get(myCart.indexOf(item)).add(1);
        else
            myCart.add(item);
        updateCart();
    }

    public void removeFromCart(ItemData item) {
        myCart.remove(item);
        updateCart();
    }

    public void updateImage(Bitmap bmp, ItemData item) {
        if (myCart.contains(item))
            myCart.get(myCart.indexOf(item)).setImage(bmp);
    }

    public void emptyCart() {
        myCart.clear();
        updateCart();
    }

    public int getItemCount(ItemData item) {
        int count = 0;
        for (ItemData i : myCart) {
            if (i.sameID(item))
                count += i.getQuantity();
        }
        return count;
    }

    public int getTotalCost() {
        int sum = 0;
        for (ItemData item : myCart)
            sum += item.getTotalCost();
        return sum;
    }

    public void newSession() {
        emptyCart();
    }

    public void registerCart(CartFragment cartFragment) {
        this.cartFragment = cartFragment;
    }

    private void updateCart() {
        if (cartFragment != null)
            this.cartFragment.updateList();
    }
}
