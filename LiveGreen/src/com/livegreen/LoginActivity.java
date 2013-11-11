package com.livegreen;

import org.json.JSONArray;

import com.livegreen.web.*;
import android.os.Bundle;
import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

public class LoginActivity extends Activity {

	Menu menu;
	
	ServerAccessController webc = new ServerAccessController();
	ProgressDialog pd;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_login);
		pd = new ProgressDialog(this);
			addLoginListeners();
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.login, menu);
		this.menu = menu;
		MenuItem item2 = this.menu.findItem(R.id.action_signin);
		item2.setVisible(false);
		
		return true;
	}
	
	@Override
	public boolean onOptionsItemSelected(MenuItem item){
		switch (item.getItemId()) {
        	case R.id.action_forgot_password:
        		setContentView(R.layout.activity_recover_password);
        		addLoginListenersRecover();
        		return true;
        	case R.id.action_register:
        		setContentView(R.layout.activity_register);
				addRegisterListeners();
        		break;
        	case R.id.action_signin:
        		setContentView(R.layout.activity_login);
 	   			addLoginListeners();
        		break;
        	case R.id.action_confirm:
        		setContentView(R.layout.activity_resend_confirmation);
        		confirmListenersRecover();
        		break;
		}
		return true;
	}
	
	public void addLoginListenersRecover(){
		if(menu != null){
			MenuItem item = this.menu.findItem(R.id.action_register);
			MenuItem item2 = this.menu.findItem(R.id.action_signin);
			item.setVisible(true);
			item2.setVisible(true);
		}
		Button button = (Button) findViewById(R.id.btnRecover);
		button.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				
				pd.setTitle("Processing...");
				pd.setMessage("Please wait.");
				pd.setCancelable(false);
				pd.setIndeterminate(true);
				pd.show();
				
				
				final EditText email = (EditText) findViewById(R.id.email);
         	   	
         	   Thread backgroundThread = new Thread(new Runnable() {            
			        @Override
			        public void run() {
			           

			            runOnUiThread(new Runnable() {                    
			                @Override
			                public void run() {
			                	try {
			                		String jis = webc.userRecoverLogin(email.getText().toString());
			             	   		if(jis.equals("true")){
			             	   			setContentView(R.layout.activity_login);
			             	   			addLoginListeners();
			             	   			Toast.makeText(getApplicationContext(), "Please check email for new password",Toast.LENGTH_LONG).show();
			             	   		} else {
			             	   			Toast.makeText(getApplicationContext(), "Recover failed",Toast.LENGTH_LONG).show();
			             	   		}
			             	   	} catch(Exception e){
			             	   		Toast.makeText(getApplicationContext(), e.toString(),Toast.LENGTH_LONG).show();
			             	   		
			             	   	} 
			                	pd.dismiss();
			                }
			            });
			        }
			    });
			    backgroundThread.start();
			}
		});
	}
	public void confirmListenersRecover(){
		if(menu != null){
			MenuItem item = this.menu.findItem(R.id.action_register);
			MenuItem item2 = this.menu.findItem(R.id.action_signin);
			item.setVisible(true);
			item2.setVisible(true);
		}
		Button button = (Button) findViewById(R.id.btnSendCode);
		button.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				
				pd.setTitle("Processing...");
				pd.setMessage("Please wait.");
				pd.setCancelable(false);
				pd.setIndeterminate(true);
				pd.show();
				
				
				final EditText email = (EditText) findViewById(R.id.email);
         	   	
         	   Thread backgroundThread = new Thread(new Runnable() {            
			        @Override
			        public void run() {
			           

			            runOnUiThread(new Runnable() {                    
			                @Override
			                public void run() {
			                	try {
			                		String jis = webc.userConfirmEmail(email.getText().toString());
			                		if(jis.equals("true")){
			             	   			setContentView(R.layout.activity_login);
			             	   			addLoginListeners();
			             	   			Toast.makeText(getApplicationContext(), "Email verication code sent",Toast.LENGTH_LONG).show();
			             	   		} else {
			             	   			Toast.makeText(getApplicationContext(), "Please try recover password",Toast.LENGTH_LONG).show();
			             	   		}
			             	   	} catch(Exception e){
			             	   		Toast.makeText(getApplicationContext(), e.toString(),Toast.LENGTH_LONG).show();
			             	   		
			             	   	} 
			                	pd.dismiss();
			                }
			            });
			        }
			    });
			    backgroundThread.start();
			}
		});
	}
	public void addLoginListeners(){
		if(menu != null){
			MenuItem item = this.menu.findItem(R.id.action_register);
			MenuItem item2 = this.menu.findItem(R.id.action_signin);
			item.setVisible(true);
			item2.setVisible(false);
		}
		
		Button button = (Button) findViewById(R.id.btnLogin);
		button.setOnClickListener(new View.OnClickListener() {
			
			
			@Override
			public void onClick(View v) {
				
				displayLocationIntent(1,"");
				
				pd.setTitle("Processing...");
				pd.setMessage("Please wait.");
				pd.setCancelable(false);
				pd.setIndeterminate(true);
				pd.show();
				
				
				final EditText email = (EditText) findViewById(R.id.email);
         	   	final EditText pass = (EditText)  findViewById(R.id.password);
         	   	
         	   Thread backgroundThread = new Thread(new Runnable() {            
			        @Override
			        public void run() {
			           

			            runOnUiThread(new Runnable() {                    
			                @Override
			                public void run() {
			                	try {
			                		Global.userid = 0;
			                		String jis = webc.userLogin(email.getText().toString(), pass.getText().toString());
			                		if(!"blocked".equals(jis)){
				                		JSONArray jArray = webc.getJson(jis);
				             	   		int userid = jArray.getJSONObject(0).getInt("ID"); 
				             	   		String username = jArray.getJSONObject(0).getString("nickname");
				             	   		if(userid > 0){
				             	   			SharedPreferences settings = getSharedPreferences(Global.PREFS_NAME, 0);
				             	   			SharedPreferences.Editor editor = settings.edit();
				             	   			editor.putInt("userid", userid);
				             	   			editor.putString("username", username);
				             	   			editor.commit();
				             	   			displayLocationIntent(userid,username);
				             	   		} else {
				             	   			Toast.makeText(getApplicationContext(), "Login failed.",Toast.LENGTH_LONG).show();
				             	   		}
			                		} else {
			                			Toast.makeText(getApplicationContext(), "Account blocked. Contact administrator",Toast.LENGTH_LONG).show();
			                		}
			             	   	} catch(Exception e){
			             	   		Toast.makeText(getApplicationContext(), "System busy try again later",Toast.LENGTH_LONG).show();
			             	   		
			             	   	}    
			                	pd.dismiss();
			                }
			            });
			        }
			    });
			    //backgroundThread.start();
         	   pd.dismiss();
         	   	
         	   	
			}
		});
		
		
	}

	public void addRegisterListeners(){
		if(menu!=null){
			MenuItem item = this.menu.findItem(R.id.action_register);
			MenuItem item2 = this.menu.findItem(R.id.action_signin);
			item.setVisible(false);
			item2.setVisible(true);
		}
		
		
		Button button = (Button) findViewById(R.id.btnRegister);
	      button.setOnClickListener(new View.OnClickListener() {
	         public void onClick(View v) {
	        	 
	        	 String email = "";
	        	 String pass  = "";
	        	 String nickname = "";
	        	 
	        	 EditText regNickname = (EditText) findViewById(R.id.reg_nickname);
	        	 nickname = regNickname.getText().toString();
	        	 EditText regEmail = (EditText) findViewById(R.id.reg_email);
	        	 email = regEmail.getText().toString();
	        	 EditText regPass = (EditText) findViewById(R.id.reg_password);
	        	 pass = regPass.getText().toString();
	        	 
	        	 if(webc.userRegister(email, pass, pass, nickname)){
	        		 setContentView(R.layout.activity_login);
	        	 } else {
	        		 Toast.makeText(getApplicationContext(), "Registration fail",Toast.LENGTH_LONG).show();
	        	 }
	          }
	     });
	}
	
	private void displayLocationIntent(int userid,String username){
		//Global.userid = userid;
		//Global.username = username;
		Intent intent;
		/*String jis = webc.getShoutoutsByFollowing();
		JSONArray jArray = webc.getJson(jis);
		if(jArray != null){
			intent = new Intent(this, MainActivity.class);
		} else {*/
			intent = new Intent(this, MainActivity.class);
		//}
		startActivity(intent);  
	}

}
