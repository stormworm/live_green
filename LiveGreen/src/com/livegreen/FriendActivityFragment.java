package com.livegreen;

import java.util.ArrayList;

import com.livegreen.data.DataFriend;

import android.content.Context;
import android.content.res.Configuration;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.TextView;

public class FriendActivityFragment extends Fragment {

	View mainView;
	
	ArrayList<Object> data = new ArrayList<Object>();
	
	@Override
	 public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		mainView = inflater.inflate(R.layout.activity_friend_activity_fragment, container, false);
		return mainView;
	}
	@Override
	public void onConfigurationChanged(Configuration newConfig){
		 super.onConfigurationChanged(newConfig);
	}
	
	class FriendHolder {
		
		int position = 0;
		
		ImageView userimage;
		TextView username;
		
		FriendHolder(View viewUser, int pos){
			position = pos;
		}
		public void populate(DataFriend friend){

		}
	}

	public class StableArrayAdapter extends ArrayAdapter<Object>{
		private LayoutInflater layoutInflater;	
		ArrayList<Object> listData;
		public StableArrayAdapter(Context context, ArrayList<Object> listData) {
			super(getActivity(), R.layout.activity_friend_activity_fragment, data);
			this.listData = listData;
	        this.layoutInflater = LayoutInflater.from(context);
		}
		
		@Override
		public View getView(int position, View convertView, ViewGroup parent) {
			FriendHolder holder;
			if (convertView == null) {
	            convertView = layoutInflater.inflate(R.layout.friends_activity_fragment_row, null);
	            holder = new FriendHolder(convertView,position);
	            convertView.setTag(holder);
	        } else {
	            holder = (FriendHolder) convertView.getTag();
	        }
			 holder.populate((DataFriend)listData.get(position));
			 
			  
	        return convertView;
		}
	}

}
