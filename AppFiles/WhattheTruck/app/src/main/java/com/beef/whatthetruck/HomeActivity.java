package com.beef.whatthetruck;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.widget.TextView;

public class HomeActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_home);

        Intent intent = getIntent();
        String userName = intent.getStringExtra(LoginActivity.EXTRA_USERNAME);

        TextView Name = findViewById(R.id.tvName);
        Name.setText(userName);
    }
}
