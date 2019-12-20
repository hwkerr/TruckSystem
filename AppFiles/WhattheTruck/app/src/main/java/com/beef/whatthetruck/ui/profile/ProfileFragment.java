package com.beef.whatthetruck.ui.profile;

import android.graphics.Bitmap;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.Nullable;
import androidx.annotation.NonNull;
import androidx.fragment.app.Fragment;
import androidx.lifecycle.Observer;
import androidx.lifecycle.ViewModelProviders;

import com.beef.whatthetruck.R;
import com.google.android.material.snackbar.Snackbar;

public class ProfileFragment extends Fragment {

    private ProfileViewModel profileViewModel;

    public View onCreateView(@NonNull LayoutInflater inflater,
                             ViewGroup container, Bundle savedInstanceState) {
        profileViewModel =
                ViewModelProviders.of(this).get(ProfileViewModel.class);
        View root = inflater.inflate(R.layout.fragment_profile, container, false);

        final ImageView imgPFP = root.findViewById(R.id.imgPFP);
        profileViewModel.getPFP().observe(this, new Observer<Bitmap>() {
            @Override
            public void onChanged(@Nullable Bitmap bmp) {
                imgPFP.setImageBitmap(bmp);
            }
        });

        final EditText etFName = root.findViewById(R.id.etFName);
        profileViewModel.getFName().observe(this, new Observer<String>() {
            @Override
            public void onChanged(@Nullable String s) {
                etFName.setText(s);
            }
        });

        final EditText etLName = root.findViewById(R.id.etLName);
        profileViewModel.getLName().observe(this, new Observer<String>() {
            @Override
            public void onChanged(@Nullable String s) {
                etLName.setText(s);
            }
        });

        final TextView tvCompanyName = root.findViewById(R.id.tvCompanyName);
        profileViewModel.getCompanyName().observe(this, new Observer<String>() {
            @Override
            public void onChanged(@Nullable String s) {
                tvCompanyName.setText(s);
            }
        });

        final EditText etPhone = root.findViewById(R.id.etPhone);
        profileViewModel.getPhone().observe(this, new Observer<String>() {
            @Override
            public void onChanged(@Nullable String s) {
                etPhone.setText(s);
            }
        });

        final EditText etAddressStreet = root.findViewById(R.id.etAddressStreet);
        profileViewModel.getAddressStreet().observe(this, new Observer<String>() {
            @Override
            public void onChanged(@Nullable String s) {
                etAddressStreet.setText(s);
            }
        });
        final EditText etAddressStreet2 = root.findViewById(R.id.etAddressStreet2);
        profileViewModel.getAddressStreet2().observe(this, new Observer<String>() {
            @Override
            public void onChanged(@Nullable String s) {
                etAddressStreet2.setText(s);
            }
        });
        final EditText etAddressCity = root.findViewById(R.id.etAddressCity);
        profileViewModel.getAddressCity().observe(this, new Observer<String>() {
            @Override
            public void onChanged(@Nullable String s) {
                etAddressCity.setText(s);
            }
        });
        final EditText etAddressState = root.findViewById(R.id.etAddressState);
        profileViewModel.getAddressState().observe(this, new Observer<String>() {
            @Override
            public void onChanged(@Nullable String s) {
                etAddressState.setText(s);
            }
        });
        final EditText etAddressZip = root.findViewById(R.id.etAddressZip);
        profileViewModel.getAddressZip().observe(this, new Observer<String>() {
            @Override
            public void onChanged(@Nullable String s) {
                etAddressZip.setText(s);
            }
        });

        final TextView tvEmail = root.findViewById(R.id.tvEmail);
        profileViewModel.getEmail().observe(this, new Observer<String>() {
            @Override
            public void onChanged(@Nullable String s) {
                tvEmail.setText(s);
            }
        });

        final TextView tvPoints = root.findViewById(R.id.tvPoints);
        profileViewModel.getPoints().observe(this, new Observer<String>() {
            @Override
            public void onChanged(@Nullable String s) {
                tvPoints.setText(s);
            }
        });

        final Button bttnSave = root.findViewById(R.id.bttnSave);
        bttnSave.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                //function to start tasks
                profileViewModel.updateName(getInput(etFName), getInput(etLName));
                profileViewModel.updatePhone(getInput(etPhone));
                profileViewModel.updateAddress(getInput(etAddressStreet), getInput(etAddressStreet2),
                        getInput(etAddressCity), getInput(etAddressState), getInput(etAddressZip));
                Snackbar.make(v, "Saved changes to profile", Snackbar.LENGTH_LONG)
                        .setAction("Action", null).show();
            }
        });

        return root;
    }

    private String getInput(EditText et) { return et.getText().toString(); }
}