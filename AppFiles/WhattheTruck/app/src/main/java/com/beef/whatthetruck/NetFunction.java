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
            case UPDATE_NAME:
            case UPDATE_PHONE:
            case UPDATE_ADDRESS:
                break;
            /*case USERID:
                userid(output[0].toString());
                break;
            case NAME:
                name(output[0].toString());
            case FNAME:
                fname(output[0].toString());
            case LNAME:
                lname(output[0].toString());
            case CURRENT_DRIVER_COMPANY:
                current_driver_company(output[0].toString());
                break;
            case COMPANY_NAME:
                company_name(output[0].toString());
                break;
            case PHONE:
                phone(output[0].toString());
                break;
            case ADDRESS:
                address(output[0].toString());
                break;
            case POINTS:
                points(output[0].toString());
                break;*/
            default:
                defaultFun(output[0].toString());
                break;
        }
    }

    private void defaultFun(String val) {
        Log.d("BEEF--NETVAL", function.toString() + "=" + val + ";");
        context.getResult(function, val);
    }

    private void login(String result, String username) {
        boolean success = (result.equals("0") || result.equals("1") || result.equals("2") || result.equals("3"));
        Log.d("BEEF--NETVAL", "result=" + result + ";");
        ((LoginActivity)context).login(success, username);
    }

    /*private void userid(String userid) {
        Log.d("BEEF--NETVAL", "userid=" + userid + ";");
        context.getResult(function, userid);
    }

    private void name(String name) {
        Log.d("BEEF--NETVAL", "name=" + name + ";");
        context.getResult(function, name);
    }

    private void fname(String fname) {
        Log.d("BEEF--NETVAL", "fname=" + fname + ";");
        context.getResult(function, fname);
    }

    private void lname(String lname) {
        Log.d("BEEF--NETVAL", "lname=" + lname + ";");
        context.getResult(function, lname);
    }

    private void current_driver_company(String companyid) {
        Log.d("BEEF--NETVAL", "companyid=" + companyid + ";");
        context.getResult(function, companyid);
    }

    private void company_name(String companyname) {
        Log.d("BEEF--NETVAL", "companyname=" + companyname + ";");
        context.getResult(function, companyname);
    }

    private void phone(String phone) {
        Log.d("BEEF--NETVAL", "phone=" + phone + ";");
        context.getResult(function, phone);
    }

    private void address(String address) {
        Log.d("BEEF--NETVAL", "address=" + address + ";");
        context.getResult(function, address);
    }
    private void points(String points) {
        Log.d("BEEF--NETVAL", "points=" + points + ";");
        context.getResult(function, points);
    }*/
}
