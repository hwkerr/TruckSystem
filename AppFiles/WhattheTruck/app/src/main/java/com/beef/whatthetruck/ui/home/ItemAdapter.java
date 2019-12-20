package com.beef.whatthetruck.ui.home;

import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.Color;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.RecyclerView;

import com.beef.whatthetruck.BeefApplication;
import com.beef.whatthetruck.ItemActivity;
import com.beef.whatthetruck.R;
import com.beef.whatthetruck.ui.cart.CartFragment;

import java.util.List;

public class ItemAdapter extends RecyclerView.Adapter {

    public static final String EXTRA_ITEM_ID = "com.beef.whatthetruck.ITEM_ID";
    public static final String EXTRA_CATALOG_ID = "com.beef.whatthetruck.CATALOG_ID";
    //public static final String EXTRA_IMAGE = "com.beef.whatthetruck.IMAGE";

    Fragment myFragment;
    List<ItemData> itemDataList;

    public ItemAdapter(List itemList, Fragment myFragment) {
        this.itemDataList = itemList;
        this.myFragment = myFragment;
    }

    @NonNull
    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_list_row, parent, false);
        if (myFragment instanceof CartFragment)
            itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_list_row_cart, parent, false);
        return new MyViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(@NonNull RecyclerView.ViewHolder absHolder, int position) {
        if (absHolder instanceof MyViewHolder) {
            MyViewHolder viewHolder = (MyViewHolder) absHolder;
            ItemData data = itemDataList.get(position);
            int currentColor = Color.WHITE;
            viewHolder.parent.setBackgroundColor(currentColor);
            viewHolder.name.setText(data.name);
            String message = "Price: " + data.cost;
            viewHolder.cost.setText(message);
            if (viewHolder.count != null) {
                message = "In Cart: " + data.count;
                viewHolder.count.setText(message);
            }
            if (viewHolder.img != null) {
                viewHolder.img.setImageBitmap(data.img);
            }
            viewHolder.setClickAction(data);

        }
        else {
            Log.d("BEEF--ERROR", "ItemAdapter.onBindViewHolder does not use custom ViewHolder: MyViewHolder");
        }
    }

    @Override
    public int getItemCount() {
        return itemDataList.size();
    }

    class MyViewHolder extends RecyclerView.ViewHolder {
        ImageView img;
        Button bttnAdd, bttnRemove;
        TextView name, cost, count;
        LinearLayout parent;

        public MyViewHolder(View itemView) {
            super(itemView);
            parent = itemView.findViewById(R.id.parent);
            img = itemView.findViewById(R.id.imgItem);
            name = itemView.findViewById(R.id.name);
            cost = itemView.findViewById(R.id.cost);
            count = itemView.findViewById(R.id.count);
            bttnAdd = itemView.findViewById(R.id.bttnAdd);
            bttnRemove = itemView.findViewById(R.id.bttnRemove);
        }

        public void setClickAction(final ItemData item) {
            if (item.isValid()) {
                parent.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        if (myFragment instanceof CartFragment) {
                            ((CartFragment)myFragment).updateList();
                        }

                        Intent intent = new Intent(myFragment.getActivity(), ItemActivity.class);
                        intent.putExtra(ItemAdapter.EXTRA_ITEM_ID, item.itemID);
                        intent.putExtra(ItemAdapter.EXTRA_CATALOG_ID, item.catID);
                        //intent.putExtra(ItemAdapter.EXTRA_IMAGE, item.img);
                        myFragment.getActivity().startActivity(intent);
                    }
                });
                if (bttnAdd != null)
                    bttnAdd.setOnClickListener(new View.OnClickListener() {
                        @Override
                        public void onClick(View v) {
                            item.add(1);
                            if (count != null) {
                                String message = "In Cart: " + item.count;
                                count.setText(message);
                            }
                            if (myFragment instanceof CartFragment)
                                ((CartFragment) myFragment).updateList();
                        }
                    });
                if (bttnRemove != null)
                    bttnRemove.setOnClickListener(new View.OnClickListener() {
                        @Override
                        public void onClick(View v) {
                            if (item.count > 1)
                                item.add(-1);
                            else
                                ((BeefApplication)(myFragment.getActivity().getApplication())).removeFromCart(item);
                            if (count != null) {
                                String message = "In Cart: " + item.count;
                                count.setText(message);
                            }
                            if (myFragment instanceof CartFragment)
                                ((CartFragment) myFragment).updateList();
                        }
                    });
            }
        }
    }
}
