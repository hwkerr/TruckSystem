package com.beef.whatthetruck;

import android.os.AsyncTask;
import android.util.Log;
import android.view.View;
import android.widget.TextView;

import com.squareup.okhttp.OkHttpClient;
import com.squareup.okhttp.Request;
import com.squareup.okhttp.Response;

public class OkDBNinja implements DBNinja {

    private final OkHttpClient client = new OkHttpClient();

    public void login(LoginActivity loginActivity, String userID, String password) {
        LoginTask task = new LoginTask(loginActivity);
        task.execute(userID, password);
    }

    class LoginTask extends AsyncTask<String, Void, Boolean> {

        LoginActivity loginActivity;
        String userID;
        String password;

        LoginTask(LoginActivity loginActivity) {
            this.loginActivity = loginActivity;
        }

        @Override
        protected Boolean doInBackground(String... params) {
            this.userID = loginActivity.getUsername();
            this.password = loginActivity.getPassword();

            Request.Builder builder = new Request.Builder();
            builder.url(LOGON_URL);
            Request request = builder.build();
            try {
                Response response = client.newCall(request).execute();
                Log.d("BEEF", "login() got response, checking password");
                return (checkPassword(userID, password));
            } catch (Exception e) {
                e.printStackTrace();
                return false;
            }
        }

        private boolean checkPassword(String userID, String password) {
            String passHash = "beefsteak";
            if (userID.equalsIgnoreCase("admin")) /* userID is in the database */
                passHash = "x";
            return password.equals(passHash);
        }

        @Override
        protected void onPostExecute(Boolean success) {
            loginActivity.login(success, userID);
        }
    }
}
