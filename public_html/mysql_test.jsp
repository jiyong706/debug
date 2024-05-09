<html>
<body>
<%@ page import="java.sql.*" contentType="text/html;charset=utf-8"%>
<% 
    String DB_URL = "jdbc:mysql://azza.gwangju.ac.kr/";
    String DB_USER = "dbuser211694";
    String DB_PASSWORD = "ce1234";
    Connection conn;
    Statement stmt;

    try {
        Class.forName("com.mysql.cj.jdbc.Driver"); // Updated the driver class name
        conn = DriverManager.getConnection(DB_URL, DB_USER, DB_PASSWORD);
        stmt = conn.createStatement();

        // Perform your database operations here
        
                 conn.close(); // Close the connection after use
                         out.println("Mysql 연결 성공!!");
                            }
                                 catch(Exception e) {
                                       out.println(e);
                                            }
                                             %> </body> </html>
