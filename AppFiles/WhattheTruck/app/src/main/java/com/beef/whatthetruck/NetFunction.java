package com.beef.whatthetruck;

import android.util.Log;

public class NetFunction {

    private Networkable context;
    private NetNinja.Function function;
    private String[] params;

    public NetFunction(Networkable context, NetNinja.Function function, String... params) {
        this.context = context;
        this.function = function;
        this.params = params;
    }

    Networkable getContext() { return context; }
    NetNinja.Function getFunction() { return function; }
    String[] getParams() { return params; }

    void result(Object... output)
    {
        switch (function) {
            case LOGIN:
                login(output[0].toString(), output[1].toString());
                break;
            case PFP:
                useBytes((byte[])output[0]);
                break;
            case ITEM_IMG:
                setItemImage((byte[])output[0], output[1].toString(), output[2].toString());
                break;
            case UPDATE_NAME:
            case UPDATE_PHONE:
            case UPDATE_ADDRESS:
                break;
            case CATALOG_ITEMS:
            case ITEM_INFO:
                defaultFunJSON(output[0].toString());
                break;
            default:
                defaultFun(output[0].toString());
                break;
        }
    }

    private void defaultFun(String val) {
        Log.d("Networking", function.toString() + "=" + val + ";");
        context.getResult(function, val);
    }

    private void defaultFunJSON(String json) {
        Log.d("Networking", function.toString() + "=" + json + ";");
        context.getResult(function, json);
    }

    private void login(String result, String username) {
        boolean success = (result.equals("0") || result.equals("1") || result.equals("2") || result.equals("3"));
        Log.d("Networking", "result=" + result + ";");
        ((LoginActivity)context).login(success, username);
    }

    private void setItemImage(byte[] bytes, String itemID, String catID) {
        Log.d("Networking", "length of " + function.toString() + "=" + bytes.length + ";");
        context.getResult(function, bytes, itemID, catID);
    }

    private void useBytes(byte[] bytes) {
        Log.d("Networking", "length of " + function.toString() + "=" + bytes.length + ";");
        context.getResult(function, bytes);
    }
}
