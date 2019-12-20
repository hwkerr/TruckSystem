package com.beef.whatthetruck.ui.cart;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.TextView;

import androidx.annotation.Nullable;
import androidx.annotation.NonNull;
import androidx.fragment.app.Fragment;
import androidx.lifecycle.Observer;
import androidx.lifecycle.ViewModelProviders;
import androidx.recyclerview.widget.GridLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.beef.whatthetruck.BeefApplication;
import com.beef.whatthetruck.R;
import com.beef.whatthetruck.ui.home.ItemAdapter;
import com.beef.whatthetruck.ui.home.ItemData;
import com.google.android.material.snackbar.Snackbar;

import java.util.List;

public class CartFragment extends Fragment {

    private CartViewModel cartViewModel;
    private BeefApplication app;

    private RecyclerView recyclerView;
    private ItemAdapter itemAdapter;
    private Button bttnOrder;
    private TextView tvPrice;

    public View onCreateView(@NonNull LayoutInflater inflater,
                             ViewGroup container, Bundle savedInstanceState) {
        cartViewModel =
                ViewModelProviders.of(this).get(CartViewModel.class);
        View root = inflater.inflate(R.layout.fragment_cart, container, false);
        final TextView textView = root.findViewById(R.id.text_cart);
        cartViewModel.getText().observe(this, new Observer<String>() {
            @Override
            public void onChanged(@Nullable String s) {
                textView.setText(s);
            }
        });

        app = ((BeefApplication)(getActivity().getApplication()));
        app.registerCart(this);
        cartViewModel.setApp(app);

        tvPrice = root.findViewById(R.id.tvPrice);

        bttnOrder = root.findViewById(R.id.bttnOrder);
        bttnOrder.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (app.getCart().isEmpty())
                    Snackbar.make(v, "Cart is empty", Snackbar.LENGTH_LONG)
                            .setAction("Action", null).show();
                else
                    cartViewModel.placeOrder(app.getCart());
            }
        });

        recyclerView = root.findViewById(R.id.recyclerView);
        updateList();
        RecyclerView.LayoutManager manager = new GridLayoutManager(getActivity(), 1);
        recyclerView.setLayoutManager(manager);

        return root;
    }

    public void updateList() {
        List<ItemData> myCart = app.getCart();
        itemAdapter = new ItemAdapter(myCart, this);
        recyclerView.setAdapter(itemAdapter);
        String message = "Total Cost: " + app.getTotalCost() + " points";
        tvPrice.setText(message);
    }

    public void clearList() {
        app = ((BeefApplication)(getActivity().getApplication()));
        app.emptyCart();
    }
}