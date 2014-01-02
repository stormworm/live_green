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
import org.json.JSONObject;


import android.os.AsyncTask;

public class ServerAccessController {
	private String baseUrl = "http://stormworm.com/LiveGreen/index.php/main/handler/?module=";
	InputStreamReader in;
	public String htmlresult = "";
	
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
	

	@SuppressWarnings("deprecation")
	public String processJSON(String query,String method){
		StringBuilder sb = new StringBuilder();
		try{
			URL url =  new URL(this.baseUrl + query);
	        HttpURLConnection conn = (HttpURLConnection) url.openConnection();
	        //conn.setRequestProperty("User-Agent", URLEncoder.encode(Global.md5("LiveGreen/1.0"))+"/"+Global.md5(""+Global.userid));
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
	
	public Boolean userRegister(String email, String password, String confirm, String nickname){
		String response = jsonWithNoImage("addUser&name="+nickname+"&email="+URLEncoder.encode(email)+"&password="+URLEncoder.encode(password), "GET");
		return "SUCCESS".equals(response);
	}
	
	public int userLogin(String email, String password){
		String response = jsonWithNoImage("login&email="+URLEncoder.encode(email)+"&password="+URLEncoder.encode(password),"GET");
		if("ERROR".equals(response)){
			return -1;
		}		
		JSONArray arr = this.getJson(response);
		if(arr != null){
			try {
				JSONObject obj = arr.getJSONObject(0);
				return obj.getInt("uid");
			} catch (JSONException e) {
				
			}
		}
		return 0;
	}
	
	public String dailyUsage(String sday, String edate){
		String response = jsonWithNoImage("dayDataRange&startDate="+sday+"&endDate="+edate+"&uid="+Global.userid,"GET");
		return response;
	}
	
	

	public String userRecoverLogin(String email){
		return jsonWithNoImage("module=user&option=recover&email="+email, "GET");
	}
	public String userConfirmEmail(String email){
		return jsonWithNoImage("module=user&option=confirm&email="+email, "GET");
	}
	
	
	
}
