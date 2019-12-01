package com.beef.whatthetruck.ui.home;

import android.os.Bundle;
import android.os.Handler;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.Nullable;
import androidx.annotation.NonNull;
import androidx.fragment.app.Fragment;
import androidx.lifecycle.Observer;
import androidx.lifecycle.ViewModelProviders;
import androidx.recyclerview.widget.GridLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.beef.whatthetruck.R;

import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.List;

public class HomeFragment extends Fragment {

    private HomeViewModel homeViewModel;

    private RecyclerView recyclerView;
    private ItemAdapter itemAdapter;
    private List<ItemData> itemDataList = new ArrayList<>();

    private Sort sortStyle;

    public View onCreateView(@NonNull LayoutInflater inflater,
                             ViewGroup container, Bundle savedInstanceState) {
        homeViewModel =
                ViewModelProviders.of(this).get(HomeViewModel.class);
        View root = inflater.inflate(R.layout.fragment_home, container, false);
        /*final TextView textView = root.findViewById(R.id.text_home);
        homeViewModel.getText().observe(this, new Observer<String>() {
            @Override
            public void onChanged(@Nullable String s) {
                textView.setText(s);
            }
        });*/

        sortStyle = Sort.NameDesc;
        recyclerView = root.findViewById(R.id.recyclerView);
        final HomeFragment self = this;
        homeViewModel.getItemList().observe(this, new Observer<List<ItemData>>() {
            @Override
            public void onChanged(@Nullable List<ItemData> list) {
                sortStyle.sort(list);
                itemAdapter = new ItemAdapter(list, self);
                recyclerView.setAdapter(itemAdapter);
            }
        });
        RecyclerView.LayoutManager manager = new GridLayoutManager(getActivity(), 2);
        recyclerView.setLayoutManager(manager);

        return root;
    }

    enum Sort
    {
        NameAsc,
        NameDesc,
        CostAsc,
        CostDesc;

        public void sort(List<ItemData> list) {
            if (list == null || list.isEmpty())
                return;
            switch (this) {
                default:
                case NameAsc:
                    Collections.sort(list, new SortByNameAsc());
                    break;
                case NameDesc:
                    Collections.sort(list, new SortByNameDesc());
                    break;
                case CostAsc:
                    Collections.sort(list, new SortByCostAsc());
                    break;
                case CostDesc:
                    Collections.sort(list, new SortByCostDesc());
                    break;
            }
        }
    }

    private static class SortByNameAsc implements Comparator<ItemData> {
        public int compare(ItemData a, ItemData b) { return a.name.compareTo(b.name); }
    }

    private static class SortByNameDesc implements Comparator<ItemData> {
        public int compare(ItemData a, ItemData b) { return -1 * a.name.compareTo(b.name); }
    }

    private static class SortByCostAsc implements Comparator<ItemData> {
        public int compare(ItemData a, ItemData b) {
            return a.cost - b.cost;
        }
    }

    private static class SortByCostDesc implements Comparator<ItemData> {
        public int compare(ItemData a, ItemData b) { return -1 * (a.cost - b.cost); }
    }
}