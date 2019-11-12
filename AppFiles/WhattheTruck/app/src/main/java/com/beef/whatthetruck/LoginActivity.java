package com.beef.whatthetruck;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

public class LoginActivity extends AppCompatActivity implements Networkable {

    public static final String EXTRA_USERNAME = "com.app.whatthetruck.USERNAME";

    private EditText Name;
    private EditText Password;
    private Button Login;
    private TextView LoginFailure;

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
                validate();
            }
        });
    }

    public void validate() {
        OkNetNinja db = new OkNetNinja(); // change back to NetNinja

        String username = Name.getText().toString().toLowerCase();
        String password = Password.getText().toString();

        db.startTask(new NetFunction(this, NetNinja.Function.LOGIN, username, password));
    }

    public void login(boolean success, String username)
    {
        if (success) {
            Log.d("BEEF--", "login...");
            LoginFailure.setVisibility(View.INVISIBLE);
            Intent intent = new Intent(this, DrawerActivity.class);
            intent.putExtra(LoginActivity.EXTRA_USERNAME, username);
            startActivity(intent);
        } else {
            LoginFailure.setVisibility(View.VISIBLE);
        }
    }

    public void forgotPassword(View view) {
        Intent intent = new Intent(this, ForgotPasswordActivity.class);
        startActivity(intent);
    }

    @Override
    public void getResult(NetNinja.Function function, Object... results) {

        // boolean success, String username
        if (function == NetNinja.Function.LOGIN) {
            boolean success = (boolean)results[1];
            String username = (String)results[2];
            if (success) {
                Log.d("BEEF--", "login...");
                LoginFailure.setVisibility(View.INVISIBLE);
                Intent intent = new Intent(this, DrawerActivity.class);
                intent.putExtra(LoginActivity.EXTRA_USERNAME, username);
                startActivity(intent);
            } else {
                LoginFailure.setVisibility(View.VISIBLE);
            }
        }
    }
}
