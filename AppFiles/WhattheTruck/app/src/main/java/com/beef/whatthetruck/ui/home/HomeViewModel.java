package com.beef.whatthetruck.ui.home;

import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Handler;
import android.util.Log;

import androidx.lifecycle.LiveData;
import androidx.lifecycle.MutableLiveData;
import androidx.lifecycle.ViewModel;

import com.beef.whatthetruck.DrawerActivity;
import com.beef.whatthetruck.NetNinja;
import com.beef.whatthetruck.Networkable;
import com.beef.whatthetruck.OkNetNinja;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import static com.beef.whatthetruck.DrawerActivity.DEFAULT_NET_TEXT;

public class HomeViewModel extends ViewModel implements Networkable {

    private MutableLiveData<String> mText;
    private MutableLiveData<List<ItemData>> mItemList;

    private static String uid, cid;

    private List<ItemData> dbItemList = new ArrayList<>();

    public HomeViewModel() {
        mText = new MutableLiveData<>();
        mText.setValue("This is your home fragment");

        mItemList = new MutableLiveData<>();
        mItemList.setValue(dbItemList);

        initValues();
    }

    private void initValues() {
        uid = DrawerActivity.getUserID();

        if (!uid.equals(DEFAULT_NET_TEXT)) {
            OkNetNinja db = new OkNetNinja();
            db.startTask(this, NetNinja.Function.CURRENT_DRIVER_COMPANY, uid);
        }
        else { // After _ milliseconds, try again
            final Handler handler = new Handler();
            handler.postDelayed(new Runnable() {
                @Override
                public void run() {
                    initValues();
                }
            }, 100);
        }
    }

    public LiveData<String> getText() {
        return mText;
    }
    public LiveData<List<ItemData>> getItemList() { return mItemList; }

    @Override
    public void getResult(NetNinja.Function function, Object... results) {
        OkNetNinja db = new OkNetNinja();
        switch (function) {
            case CURRENT_DRIVER_COMPANY:
                cid = results[0].toString();
                db.startTask(this, NetNinja.Function.CATALOG_ITEMS, cid);
                break;
            case CATALOG_ITEMS:
                String in = results[0].toString();
                try {
                    // loop through array and look at the values of each item in the json array
                    dbItemList.clear();
                    JSONObject reader = new JSONObject(in);
                    JSONArray items = reader.getJSONArray("Items");
                    for (int i = 0; i < items.length(); i++) {
                        JSONObject c = items.getJSONObject(i);
                        String name = c.getString("Name");
                        int price = c.getInt("Price");
                        String itemID = c.getString("ItemID");
                        String catID = c.getString("CatalogID");
                        ItemData item = new ItemData(name, price, itemID, catID);

                        if (!dbItemList.contains(item)) {
                            dbItemList.add(item);
                            db.startTask(this, NetNinja.Function.ITEM_IMG, itemID, catID);
                        }
                    }
                    //if (dbItemList.isEmpty()) // Generally if the user is an admin or sponsor
                    //    dbItemList.add(new ItemData("none found", 0));
                    mItemList.setValue(dbItemList);
                } catch (JSONException e) {
                    Log.d("BEEF--CATCH", function.toString() + "=" + in + ";");
                    dbItemList.clear();
                    dbItemList.add(ItemData.BlankItem());
                    mItemList.setValue(dbItemList);
                }
                break;
            case ITEM_IMG:
                byte[] data = (byte[])results[0];
                Bitmap bmp = BitmapFactory.decodeByteArray(data, 0, data.length);
                ItemData testItem = ItemData.MakeItemFromID(results[1].toString(), results[2].toString());
                if (dbItemList.contains(testItem)) {
                    ItemData item = dbItemList.get(dbItemList.indexOf(testItem));
                    item.setImage(bmp);
                    mItemList.setValue(dbItemList);
                }
                break;
            default:
                break;
        }
    }
}