package com.livegreen;

import java.util.ArrayList;



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

public class AchievementActivityFragment extends Fragment {

	class Achievement{
		
	}
	
	ArrayList<Object> data = new ArrayList<Object>();
	
	View mainView;
	
	@Override
	 public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		mainView = inflater.inflate(R.layout.activity_achievement_activity_fragment, container, false);
		
		return mainView;
	}
	
	@Override
	public void onConfigurationChanged(Configuration newConfig){
		super.onConfigurationChanged(newConfig);
	}
	
	public class AchievementHolder {
		
		int position = 0;
		
		ImageView userimage;
		TextView username;
		
		AchievementHolder(View view, int pos){
			position = pos;

		}
		public void populate(Achievement user){

		}
	}
	
	public class StableArrayAdapter extends ArrayAdapter<Object> {
		private LayoutInflater layoutInflater;
		ArrayList<Object> listData;
		public StableArrayAdapter(Context context, ArrayList<Object> listData) {
			super(getActivity(), R.layout.activity_achievement_activity_fragment , data);
			this.listData = listData;
	        this.layoutInflater = LayoutInflater.from(context);
		}
		
		@Override
		public View getView(int position, View convertView, ViewGroup parent) {
			
			AchievementHolder holder;
			if (convertView == null) {
	            convertView = layoutInflater.inflate(R.layout.achievement_fragment_row, null);
	            holder = new AchievementHolder(convertView,position);
	            convertView.setTag(holder);
	        } else {
	            holder = (AchievementHolder) convertView.getTag();
	        }
			 holder.populate((Achievement)listData.get(position));
			  
	        return convertView;
		}
	
	}

}
