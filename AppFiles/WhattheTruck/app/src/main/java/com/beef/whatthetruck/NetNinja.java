package com.beef.whatthetruck;

import java.util.HashMap;
import java.util.Map;

public interface NetNinja {

    String BASE_URL = "http://ec2-54-234-169-204.compute-1.amazonaws.com/android";

    Map<Function, String[]> fields = new HashMap<>();

    enum Function
    {
        LOGIN("logon"),
        USERID("userid"),
        NAME("name"),
        FNAME("fname"),
        LNAME("lname"),
        CURRENT_DRIVER_COMPANY("current_driver_company"),
        COMPANY_NAME("company_name"),
        PHONE("phone"),
        ADDRESS("address"),
        POINTS("points"),
        UPDATE_NAME("update_name"),
        UPDATE_PHONE("update_phone"),
        UPDATE_ADDRESS("update_address"),
        NULL("null");

        private String name;

        Function(String name) {
            this.name = name;
        }

        @Override
        public String toString() {
            return name;
        }
    }
}
