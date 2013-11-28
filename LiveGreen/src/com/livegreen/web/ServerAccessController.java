package com.livegreen.web;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;
import java.nio.charset.Charset;
import java.util.concurrent.ExecutionException;

import org.json.JSONArray;
import org.json.JSONException;


import android.os.AsyncTask;

public class ServerAccessController {
	private String url = "http://www.stormworm.com/livegreen/main.php"; 
	public String htmlresult = "";
	InputStreamReader in;
	
	private class JsonNoImage extends AsyncTask<String, Void, String> {
        @Override
        protected String doInBackground(String... urls) {
            try {
                return  processJSON(urls[0],urls[1]);
            } catch (Exception e) {
                return "Unable to retrieve web page. URL may be invalid.";
            }
        }
        @Override
        protected void onPostExecute(String result) {
            htmlresult = result;
       }
    }
	
	@SuppressWarnings("deprecation")
	public String processJSON(String query,String method){
		StringBuilder sb = new StringBuilder();
		try{
			URL url =  new URL(this.url+"?"+query);
	        HttpURLConnection conn = (HttpURLConnection) url.openConnection();
	        conn.setRequestProperty("User-Agent", URLEncoder.encode(Global.md5("LiveGreen/1.0"))+"/"+Global.md5(""+Global.userid));
	        conn.setReadTimeout(10000 /* milliseconds */);
	        conn.setConnectTimeout(15000 /* milliseconds */);
	        conn.setRequestMethod(method);
	        conn.setDoInput(true);
	        // Starts the query
	        conn.connect();
			
			in = new InputStreamReader(conn.getInputStream(),Charset.defaultCharset());
			BufferedReader bufferedReader = new BufferedReader(in);
            if (bufferedReader != null) {
                int cp;
                while ((cp = bufferedReader.read()) != -1) {
                    sb.append((char) cp);
                }
                bufferedReader.close();
            }
			
			in.close();
			return sb.toString();
		} catch (Exception e) {
			return e.toString();
		}
	}
	private String jsonWithNoImage(String query, String method){
		try {
			JsonNoImage task = new JsonNoImage();
			return task.execute(query,method).get();
		} catch (InterruptedException e) {
			return e.toString();
		} catch (ExecutionException e) {
			return e.toString();
		}
	}
	
	@SuppressWarnings("deprecation")
	public String userLogin(String email,String pass){
		return jsonWithNoImage("module=user&option=login&email="+URLEncoder.encode(email)+"&password="+URLEncoder.encode(Global.md5(pass)), "GET");
	}
	public Boolean userRegister(String email, String pass, String confirm, String nickname){
		if(pass.equals(confirm)){
			String result =  jsonWithNoImage("module=user&option=add&&email="+email+"&password="+Global.md5(pass)+"&nickname="+nickname, "GET");
			return "true".equals(result);
		} else {
			return false;
		}
	}
	public String userRecoverLogin(String email){
		return jsonWithNoImage("module=user&option=recover&email="+email, "GET");
	}
	public String userConfirmEmail(String email){
		return jsonWithNoImage("module=user&option=confirm&email="+email, "GET");
	}
	
	/*---------------- Support functions------------------------------------------------ */
	public JSONArray getJson(String jsonstring){
		JSONArray jArray = null;
		try {
			jArray =  new JSONArray(jsonstring);
		} catch (JSONException e) {
			return jArray;
		}
		return jArray;
	}
}
