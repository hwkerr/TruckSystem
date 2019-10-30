package com.beef.whatthetruck;

public interface NetNinja {

    String BASE_URL = "http://ec2-54-234-169-204.compute-1.amazonaws.com/android";
    String LOGON_URL = BASE_URL + "/logon.php";

    void login(LoginActivity loginActivity);
}
