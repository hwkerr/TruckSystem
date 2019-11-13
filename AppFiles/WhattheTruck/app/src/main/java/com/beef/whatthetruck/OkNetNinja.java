package com.beef.whatthetruck;

import android.os.AsyncTask;
import android.util.Log;

import com.squareup.okhttp.OkHttpClient;
import com.squareup.okhttp.Request;
import com.squareup.okhttp.Response;
import com.squareup.okhttp.ResponseBody;

import java.io.IOException;

public class OkNetNinja implements NetNinja {

    private final OkHttpClient client = new OkHttpClient();

    public OkNetNinja() {
        fields.put(Function.LOGIN, new String[]{"email", "password"});
        fields.put(Function.USERID, new String[]{"email"});
        fields.put(Function.NAME, new String[]{"uid"});
        fields.put(Function.FNAME, new String[]{"uid"});
        fields.put(Function.LNAME, new String[]{"uid"});
        fields.put(Function.CURRENT_DRIVER_COMPANY, new String[]{"uid"});
        fields.put(Function.COMPANY_NAME, new String[]{"cid"});
        fields.put(Function.PHONE, new String[]{"uid"});
        fields.put(Function.ADDRESS, new String[]{"uid"});
        fields.put(Function.POINTS, new String[]{"uid", "cid"});

        fields.put(Function.UPDATE_NAME, new String[]{"uid", "fname", "lname"});
        fields.put(Function.UPDATE_PHONE, new String[]{"uid", "phone"});
        fields.put(Function.UPDATE_ADDRESS, new String[]{"uid", "street", "street2", "city", "state", "zip"});
    }

    public void startTask(NetFunction function) {
        Task task = new Task(function);
        task.execute();
    }

    public void startTask(Networkable context, NetNinja.Function function, String... params) {
        NetFunction fun = new NetFunction(context, function, params);
        Task task = new Task(fun);
        task.execute();
    }

    private class Task extends AsyncTask<String, Void, Response> {

        NetFunction netFunction;
        Function function;
        String[] fieldVals;

        Task(NetFunction netFunction) {
            this.netFunction = netFunction;
            this.function = netFunction.getFunction();
            this.fieldVals = netFunction.getParams();

            if (function == Function.NULL)
                Log.d("BEEF--WARN", "Called null php function");
            if (fields.get(function).length != fieldVals.length)
                Log.d("BEEF--ERROR", "Mismatched php function parameters: " + function
                        + " // " + fields.get(function).length + " != " + fieldVals.length);
        }

        @Override
        protected Response doInBackground(String... strings) {

            Request.Builder builder = new Request.Builder();
            builder.url(buildURL());
            Request request = builder.build();
            try {
                return client.newCall(request).execute();
            } catch (Exception e) {
                e.printStackTrace();
                return null;
            }
        }

        /**
         * Executes on the UI thread after the Task thread finishes working
         * Selects the correct postExecution function
         * @param response
         */
        @Override
        protected void onPostExecute(Response response) {
            ResponseBody responseBody = response.body();
            String result;
            try {
                result = responseBody.string();
                responseBody.close();
            } catch (IOException e) {
                e.printStackTrace();
                result = "IOException";
            }
            switch (function) {
                case LOGIN:
                    netFunction.result(result, fieldVals[0]);
                    break;
                case USERID:
                case NAME:
                case FNAME:
                case LNAME:
                case CURRENT_DRIVER_COMPANY:
                case COMPANY_NAME:
                case PHONE:
                case ADDRESS:
                case POINTS:
                case UPDATE_NAME:
                case UPDATE_PHONE:
                case UPDATE_ADDRESS:
                default:
                    netFunction.result(result);
                    break;
            }
        }

        private String buildURL() {
            String[] fieldNames = fields.get(function);
            if (fieldNames == null) return "null";

            String url = BASE_URL + "/" + function + ".php?";
            for (int i = 0; i < fieldNames.length; i++) {
                if (i > 0)
                    url = url.concat("&");
                url = url.concat(fieldNames[i] + "=" + fieldVals[i]);
            }

            Log.d("BEEF--NET", "Accessing url: " + url);
            return url;
        }
    }
}
