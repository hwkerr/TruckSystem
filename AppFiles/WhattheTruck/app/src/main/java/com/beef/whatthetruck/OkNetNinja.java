package com.beef.whatthetruck;

import android.os.AsyncTask;
import android.util.Log;

import com.beef.whatthetruck.ui.home.ItemData;
import com.squareup.okhttp.OkHttpClient;
import com.squareup.okhttp.Request;
import com.squareup.okhttp.Response;

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
        fields.put(Function.PFP, new String[]{"uid"});
        fields.put(Function.ITEM_INFO, new String[]{"itemid", "catid"});
        fields.put(Function.ITEM_IMG, new String[]{"itemid", "catid"});

        fields.put(Function.UPDATE_NAME, new String[]{"uid", "fname", "lname"});
        fields.put(Function.UPDATE_PHONE, new String[]{"uid", "phone"});
        fields.put(Function.UPDATE_ADDRESS, new String[]{"uid", "street", "street2", "city", "state", "zip"});

        fields.put(Function.CATALOG_ITEMS, new String[]{"cid"});
        fields.put(Function.PLACE_ORDER, new String[]{"count", "did", "quantity#", "itemid#", "catid#"});
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

    private class Task extends AsyncTask<String, Void, byte[]> {

        NetFunction netFunction;
        Function function;
        String[] fieldVals;

        Task(NetFunction netFunction) {
            this.netFunction = netFunction;
            this.function = netFunction.getFunction();
            this.fieldVals = netFunction.getParams();

            if (function == Function.NULL)
                Log.d("BEEF--WARN", "Called null php function");
            int variadic = 0;
            boolean error = false;
            if (fields.get(function).length != fieldVals.length) {
                error = true;
                for (String field : fields.get(function))
                    if (field.contains("#")) {
                        variadic++;
                        error = false;
                    }
            }
            if (variadic != 0 && fieldVals.length % variadic != (fields.get(function).length-variadic))
                error = true;
            if (error)
                Log.d("BEEF--ERROR", "Mismatched php function parameters: " + function
                        + " // " + fields.get(function).length + " != " + fieldVals.length);
        }

        @Override
        protected byte[] doInBackground(String... strings) {

            Request.Builder builder = new Request.Builder();
            String url;
            switch (function) {
                case PLACE_ORDER:
                    url = buildOrderURL();
                    break;
                default:
                    url = buildURL();
                    break;
            }
            builder.url(url);
            Request request = builder.build();
            try {
                Response response = client.newCall(request).execute();
                return response.body().bytes();
            } catch (Exception e) {
                e.printStackTrace();
                return null;
            }
        }

        /**
         * Executes on the UI thread after the Task thread finishes working
         * Selects the correct postExecution function
         * @param result
         */
        @Override
        protected void onPostExecute(byte[] result) {
            switch (function) {
                case LOGIN:
                    netFunction.result(new String(result), fieldVals[0]);
                    break;
                case PFP:
                    netFunction.result(result);
                    break;
                case ITEM_IMG:
                    netFunction.result(result, fieldVals[0], fieldVals[1]);
                    break;
                default:
                    netFunction.result(new String(result));
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

            Log.d("Networking", "[" + function.toString().toUpperCase() + "] Accessing url: " + url);
            return url;
        }

        private String buildOrderURL() {
            String[] fieldNames = fields.get(function);
            if (fieldNames == null) return "null";

            String url = BASE_URL + "/" + function + ".php?";
            for (int i = 0; i < fieldNames.length && !fieldNames[i].contains("#"); i++) {
                if (i > 0)
                    url = url.concat("&");
                url = url.concat(fieldNames[i] + "=" + fieldVals[i]);
            }
            ItemData[] order = itemsFromVarArgs();
            for (int i = 0; i < order.length; i++) {
                url = url.concat("&").concat(itemString(order[i], i));
            }

            Log.d("Networking", "[" + function.toString().toUpperCase() + "] Accessing url: " + url);
            return url;
        }

        /**
         * @requires fieldVals[0] is the number of unique items to be passed in
         * @return list of ItemData objects based on [non-determinant-length] fields and fieldVals
         */
        private ItemData[] itemsFromVarArgs() {
            int headerFields = 0;
            int attributes = 0;
            for (String field : fields.get(function)) {
                if (field.contains("#"))
                    attributes++;
                else
                    headerFields++;
            }
            int numItems = Integer.parseInt(fieldVals[0]);
            ItemData[] items = new ItemData[numItems];
            for (int i = 0; i < items.length; i++) {
                int quantity = Integer.parseInt(fieldVals[(i*attributes)+headerFields]);
                String itemID = fieldVals[(i*attributes)+headerFields+1];
                String catID = fieldVals[(i*attributes)+headerFields+2];
                items[i] = ItemData.MakeItemForOrder(quantity, itemID, catID);
            }
            return items;
        }

        private String itemString(ItemData item, int numInOrder) {
            String[] fieldNames = fields.get(function);
            if (fieldNames == null) return "null";
            return fieldNames[2].substring(0, fieldNames[2].length()-1) + (numInOrder+1) + "=" + item.getQuantity() + "&"
                    + fieldNames[3].substring(0, fieldNames[3].length()-1) + (numInOrder+1) + "=" + item.getItemID() + "&"
                    + fieldNames[4].substring(0, fieldNames[4].length()-1) + (numInOrder+1) + "=" + item.getCatID();
        }
    }
}
