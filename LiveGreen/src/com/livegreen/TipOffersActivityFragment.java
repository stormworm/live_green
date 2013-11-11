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

public class TipOffersActivityFragment extends Fragment {

	View mainView;
	ArrayList<Object> data = new ArrayList<Object>();
	
	class Tip {
		
	}
	
	@Override
	 public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		mainView = inflater.inflate(R.layout.activity_tip_offers_activity_fragment, container, false);
		return mainView;
	}
	@Override
	public void onConfigurationChanged(Configuration newConfig){
		super.onConfigurationChanged(newConfig);
		 
	}
	
	public class TipHolder {
		
		int position = 0;
		
		ImageView userimage;
		TextView username;
		
		TipHolder(View view, int pos){
			position = pos;

		}
		public void populate(Tip user){

		}
	}
	
	public class StableArrayAdapter extends ArrayAdapter<Object> {
		private LayoutInflater layoutInflater;
		ArrayList<Object> listData;
		public StableArrayAdapter(Context context, ArrayList<Object> listData) {
			super(getActivity(), R.layout.activity_tip_offers_activity_fragment , data);
			this.listData = listData;
	        this.layoutInflater = LayoutInflater.from(context);
		}
		
		@Override
		public View getView(int position, View convertView, ViewGroup parent) {
			
			TipHolder holder;
			if (convertView == null) {
	            convertView = layoutInflater.inflate(R.layout.tips_offers_fragment_row, null);
	            holder = new TipHolder(convertView,position);
	            convertView.setTag(holder);
	        } else {
	            holder = (TipHolder) convertView.getTag();
	        }
			 holder.populate((Tip)listData.get(position));
			  
	        return convertView;
		}
	
	}
}
