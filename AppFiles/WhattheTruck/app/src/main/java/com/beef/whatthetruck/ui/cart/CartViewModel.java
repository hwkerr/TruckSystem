package com.beef.whatthetruck.ui.cart;

import androidx.lifecycle.LiveData;
import androidx.lifecycle.MutableLiveData;
import androidx.lifecycle.ViewModel;

import com.beef.whatthetruck.BeefApplication;
import com.beef.whatthetruck.DrawerActivity;
import com.beef.whatthetruck.NetNinja;
import com.beef.whatthetruck.Networkable;
import com.beef.whatthetruck.OkNetNinja;
import com.beef.whatthetruck.ui.home.ItemData;

import java.util.List;

public class CartViewModel extends ViewModel implements Networkable {

    private BeefApplication app;

    private MutableLiveData<String> mText;

    public CartViewModel() {
        mText = new MutableLiveData<>();
        mText.setValue("Your Shopping Cart for the current session");
    }

    public void setApp(BeefApplication app) {
        this.app = app;
    }

    public LiveData<String> getText() {
        return mText;
    }

    public void placeOrder(List<ItemData> myOrder) {
        ItemData[] orderArray = myOrder.toArray(new ItemData[0]);
        String[] fieldVals = new String[orderArray.length*3+2];
        fieldVals[0] = Integer.toString(orderArray.length);
        fieldVals[1] = DrawerActivity.getUserID();
        for (int i = 0; i < orderArray.length; i++) {
            fieldVals[i*3+2] = Integer.toString(orderArray[i].getQuantity());
            fieldVals[i*3+3] = orderArray[i].getItemID();
            fieldVals[i*3+4] = orderArray[i].getCatID();
        }
        OkNetNinja db = new OkNetNinja();
        db.startTask(this, NetNinja.Function.PLACE_ORDER, fieldVals);
    }

    @Override
    public void getResult(NetNinja.Function function, Object... results) {
        switch (function) {
            case PLACE_ORDER:
                app.emptyCart();
                break;
            default:
                break;
        }
    }
}