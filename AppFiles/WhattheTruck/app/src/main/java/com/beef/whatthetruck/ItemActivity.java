package com.beef.whatthetruck;

import androidx.appcompat.app.AppCompatActivity;

import android.content.ClipData;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

import com.beef.whatthetruck.ui.home.ItemAdapter;
import com.beef.whatthetruck.ui.home.ItemData;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class ItemActivity extends AppCompatActivity implements Networkable {

    BeefApplication app;

    TextView tvName, tvPrice, tvDesc;
    ImageView imgItem;
    Button bttnAddToCart;

    String itemID, catalogID, name;
    int price, numberInCart;
    Bitmap image;

    int netLock;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_item);

        app = (BeefApplication)getApplication();
        // Get the Intent that started this activity and extract the string
        Intent intent = getIntent();
        itemID = intent.getStringExtra(ItemAdapter.EXTRA_ITEM_ID);
        catalogID = intent.getStringExtra(ItemAdapter.EXTRA_CATALOG_ID);
        //image = intent.getParcelableExtra(ItemAdapter.EXTRA_IMAGE);

        tvName = findViewById(R.id.tvName);
        tvPrice = findViewById(R.id.tvPrice);
        tvDesc = findViewById(R.id.tvDesc);
        imgItem = findViewById(R.id.imgItem);
        bttnAddToCart = findViewById(R.id.bttnAddToCart);

        if (itemID.isEmpty() || catalogID.isEmpty()) {
            String invalidString = "missing data";
            String invalidPrice = "Price: 0";
            tvName.setText(invalidString);
            tvPrice.setText(invalidPrice);
            tvDesc.setText(invalidString);
        }
        else {
            numberInCart = app.getItemCount(ItemData.MakeItemFromID(itemID, catalogID));
            setButtonText(numberInCart);
            imgItem.setImageBitmap(image);

            bttnAddToCart.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    if (netLock == 0) {
                        ItemData item = new ItemData(name, price, itemID, catalogID);
                        addToCart(item);
                    }
                }
            });

            OkNetNinja db = new OkNetNinja();
            db.startTask(this, NetNinja.Function.ITEM_INFO, itemID, catalogID); netLock++;
            db.startTask(this, NetNinja.Function.ITEM_IMG, itemID, catalogID); netLock++;
        }
    }

    private void setButtonText(int numberInCart) {
        String bttnText = "Add to Cart (In Cart: " + numberInCart + ")";
        bttnAddToCart.setText(bttnText);
    }

    private void addToCart(ItemData item) {
        app.addToCart(item);
        if (image != null)
            item.setImage(image);
        numberInCart++;
        setButtonText(numberInCart);
    }

    @Override
    public void getResult(NetNinja.Function function, Object... results) {
        switch (function) {
            case ITEM_INFO:
                String in = results[0].toString();
                try {
                    JSONObject reader = new JSONObject(in);
                    String name = reader.getString("Name");
                    this.name = name;
                    int price = reader.getInt("Price");
                    this.price = price;
                    String desc = reader.getString("Desc");
                    tvName.setText(name);
                    String priceText = "Price: " + price + " points ";
                    tvPrice.setText(priceText);
                    tvDesc.setText(desc);
                } catch (JSONException e) {
                    Log.d("BEEF--CATCH", function.toString() + "=" + in + ";");
                }
                break;
            case ITEM_IMG:
                byte[] data = (byte[])results[0];
                Bitmap bmp = BitmapFactory.decodeByteArray(data, 0, data.length);
                this.image = bmp;
                imgItem.setImageBitmap(bmp);
                app.updateImage(bmp, ItemData.MakeItemFromID(itemID, catalogID));
                break;
        }
        netLock--;
    }
}
