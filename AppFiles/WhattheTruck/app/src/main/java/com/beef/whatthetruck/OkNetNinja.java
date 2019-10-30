package com.beef.whatthetruck;

import android.os.AsyncTask;
import android.util.Log;

import androidx.appcompat.app.AppCompatActivity;

import com.squareup.okhttp.OkHttpClient;
import com.squareup.okhttp.Request;
import com.squareup.okhttp.Response;

public class OkNetNinja implements NetNinja {

    private final OkHttpClient client = new OkHttpClient();

    public void task(AppCompatActivity activity) {
        Task task = new Task(activity);
        task.execute();
    }

    public void login(LoginActivity loginActivity) {
        LoginTask task = new LoginTask(loginActivity);
        task.execute();
    }

    class Task extends AsyncTask<String, Void, Void> {

        AppCompatActivity activity;

        Task(AppCompatActivity activity) { this.activity = activity; }

        @Override
        protected Void doInBackground(String... strings) {
            int val1 = 0;
            int val2 = 0;
            Request.Builder builder = new Request.Builder();
            builder.url(LOGON_URL + "?val1=" + val1 + "&val2=" + val2);
            Request request = builder.build();
            try {
                Response response = client.newCall(request).execute();
                String result = response.body().string();
                return null;
            } catch (Exception e) {
                e.printStackTrace();
                return null;
            }
        }

        @Override
        protected void onPostExecute(Void aVoid) {
            super.onPostExecute(aVoid);
        }
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
            builder.url(LOGON_URL + "?userID=" + userID + "&password=" + password);
            Request request = builder.build();
            try {
                Response response = client.newCall(request).execute();
                String result = response.body().string();
                return checkResponse(result);
            } catch (Exception e) {
                e.printStackTrace();
                return false;
            }
        }

        //1 driver, 2 sponsor, 3 admin, 0 tempPass, else: -1, -2
        private boolean checkResponse(String response) {
            return (response.equals("0") || response.equals("1") || response.equals("2") || response.equals("3"));
        }

        @Override
        protected void onPostExecute(Boolean success) {
            loginActivity.login(success, userID);
        }
    }
}
