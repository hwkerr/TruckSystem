package com.beef.whatthetruck;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import java.util.Dictionary;
import java.util.Hashtable;

public class LoginActivity extends AppCompatActivity {

    public static final String EXTRA_USERNAME = "com.app.whatthetruck.USERNAME";

    private EditText Name;
    private EditText Password;
    private Button Login;
    private TextView LoginFailure;

    private Dictionary<String, String> userpass = new Hashtable<>();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        Name = findViewById(R.id.etName);
        Password = findViewById(R.id.etPassword);
        Login = findViewById(R.id.btnLogin);
        LoginFailure = findViewById(R.id.tvLoginFailure);

        LoginFailure.setVisibility(View.INVISIBLE);
        Login.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                validate(Name.getText().toString().toLowerCase(), Password.getText().toString());
                //test();
            }
        });

        populateUsers();
    }

    public void validate(String userName, String userPassword) {
        DBNinja db = new OkDBNinja();
        db.login(this, userName, userPassword);
    }

    public String getUsername()
    {
        return Name.getText().toString().toLowerCase();
    }

    public String getPassword()
    {
        return Password.getText().toString();
    }

    public void login(boolean success, String userID)
    {
        if (success) {
            Log.d("BEEF--*****", "login...");
            LoginFailure.setVisibility(View.INVISIBLE);
            Intent intent = new Intent(this, HomeActivity.class);
            if (!userID.isEmpty())
                userID = userID.substring(0, 1).toUpperCase() + userID.substring(1);
            intent.putExtra(LoginActivity.EXTRA_USERNAME, userID);
            startActivity(intent);
        } else {
            LoginFailure.setVisibility(View.VISIBLE);
        }
    }

    public void forgotPassword(View view) {
        Intent intent = new Intent(this, ForgotPasswordActivity.class);
        startActivity(intent);
    }

    private void populateUsers()
    {
        String pass = "beefsteak";
        userpass.put("admin", pass);
        userpass.put("user", pass);
    }
}
