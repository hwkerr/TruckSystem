package com.beef.whatthetruck;

import android.util.Log;

import java.sql.DriverManager;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;

import at.favre.lib.crypto.bcrypt.BCrypt;

public class DriverNetNinja implements NetNinja {

    private static Connection connect = null;

    private PreparedStatement preparedStatement = null;
    private ResultSet resultSet = null;

    private static boolean connect_to_db()
    {
        try {
            // This will load the MySQL driver, each DB has its own driver
            Class.forName("com.mysql.jdbc.Driver");
            //Class.forName("oracle.jdbc.driver.OracleDriver");
            // Setup the connection with the DB
            Log.d("SUCCESS", "Found Driver");

            String url = "database-1.c3icyvcxw6xg.us-east-1.rds.amazonaws.com";
            //String port = "3306";
            String database_name = "trucks";
            String user = "admin";
            String password = "beefsteak";
            connect = DriverManager
                    .getConnection(url + "/" + database_name, user, password);
        } catch (Exception e) {
            Log.d("ERROR","Failed to connect to database: " + e);
            return false;
        }
        return true;
    }

    public void login(LoginActivity loginActivity)
    {
        boolean success;
        String userID = loginActivity.getUsername();
        String password = loginActivity.getPassword();

        try {
            if (!connect_to_db()) {
                success = false;
            }
            String command = "SELECT PassHash, TempPass, UserID FROM Account WHERE Email = ?";
            preparedStatement = connect.prepareStatement(command);
            preparedStatement.setString(1, userID);
            resultSet = preparedStatement.executeQuery();

            String hashData;
            if (resultSet.next()) {
                hashData = resultSet.getString("PassHash");
                // compare password to passHash (BCRYPT algorithm)
                BCrypt.Result result = BCrypt.verifyer().verify(password.getBytes(), hashData.getBytes());
                success = result.verified;
            }
            else success = false;
        } catch (Exception e) {
            success = false;
        }

        loginActivity.login(success, userID);
    }
}
