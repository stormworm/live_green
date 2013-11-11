package com.livegreen.web;

import java.math.BigInteger;
import java.security.MessageDigest;

public class Global {
	
	public static final String PREFS_NAME = "livegreen";
	public static int userid = 0;
	public static String username = "";
	
	
	public static String md5(String input) {
	    String result = input;
	    try {
		    if(input != null) {
		        MessageDigest md = MessageDigest.getInstance("MD5"); //or "SHA-1"
		        md.update(input.getBytes());
		        BigInteger hash = new BigInteger(1, md.digest());
		        result = hash.toString(16);
		        while(result.length() < 32) { //40 for SHA-1
		            result = "0" + result;
		        }
		    }
	    } catch (Exception e){
	    	
	    }
	    return result;
	}

	
}
