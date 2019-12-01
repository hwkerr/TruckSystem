package com.beef.whatthetruck.ui.home;

import android.graphics.Bitmap;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;

public class ItemData {
    String name, itemID, catID;
    int cost, count;
    Bitmap img;

    public ItemData(String name, int cost, String itemID, String catID) {
        this.name = name;
        this.cost = cost;
        this.itemID = itemID;
        this.catID = catID;
        this.count = 1;
    }

    public void setImage(Bitmap bmp) {
        this.img = bmp;
    }

    public void add(int number) {
        count += number;
    }

    public int getQuantity() {
        return count;
    }
    public String getItemID() { return itemID; }
    public String getCatID() { return catID; }
    public int getTotalCost() { return count * cost; }

    public static ItemData MakeItemFromID(String itemID, String catID) {
        return new ItemData("N/A", 0, itemID, catID);
    }

    public static ItemData MakeItemForOrder(int quantity, String itemID, String catID) {
        ItemData item = MakeItemFromID(itemID, catID);
        item.count = quantity;
        return item;
    }

    public static ItemData BlankItem() {
        return new ItemData("N/A", 0, null, null);
    }

    public boolean sameID(@Nullable Object obj) {
        if (obj instanceof ItemData) {
            ItemData other = (ItemData)obj;
            return (itemID.equals(other.itemID) && catID.equals(other.catID));
        }
        return false;
    }

    public boolean isValid() {
        return (!itemID.equals("") && !catID.equals(""));
    }

    @Override
    public boolean equals(@Nullable Object obj) {
        if (obj instanceof ItemData) {
            ItemData other = (ItemData)obj;
            return (catID.equals(other.catID) && itemID.equals(other.itemID));
        }
        return false;
    }

    @NonNull
    @Override
    public String toString() {
        return "name: " + name + "; cost: " + cost;
    }

    public String toStringFull() {
        return "itemID: " + itemID + "; "
                + "catID: " + catID + "; "
                + "name: " + name + "; "
                + "cost: " + cost;
    }
}
