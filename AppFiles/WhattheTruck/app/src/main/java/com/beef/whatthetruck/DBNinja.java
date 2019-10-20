package com.beef.whatthetruck;

public interface DBNinja {

    String BASE_URL = "http://ec2-54-234-169-204.compute-1.amazonaws.com/";
    String LOGON_URL = BASE_URL + "/logon.php";

    void login(LoginActivity loginActivity, String userID, String password);
}
