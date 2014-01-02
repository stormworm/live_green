package com.livegreen;

import java.util.ArrayList;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.jjoe64.graphview.GraphView;
import com.jjoe64.graphview.GraphViewDataInterface;
import com.jjoe64.graphview.GraphViewSeries;
import com.jjoe64.graphview.GraphView.GraphViewData;
import com.jjoe64.graphview.LineGraphView;
import com.livegreen.web.ServerAccessController;

import android.content.res.Configuration;
import android.graphics.Color;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.SeekBar;
import android.widget.Toast;

public class UserActivityFragment extends Fragment {

	ServerAccessController webc = new ServerAccessController();
	View mainView;

	ArrayList<GraphViewData> days = new ArrayList<GraphViewData>();
	ArrayList<GraphViewData> weeks = new ArrayList<GraphViewData>();
	ArrayList<GraphViewData> months = new ArrayList<GraphViewData>();
	ArrayList<GraphViewData> years = new ArrayList<GraphViewData>();

	GraphView graphView;
	
	SeekBar seekbarday;
	SeekBar seekbarweek;
	SeekBar seekbarmonth;
	SeekBar seekbaryear;

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container,
			Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		mainView = inflater.inflate(R.layout.activity_user_activity_fragment,
				container, false);

		setupGraphs();

		return mainView;
	}

	@Override
	public void onConfigurationChanged(Configuration newConfig) {
		super.onConfigurationChanged(newConfig);

	}

	private void updateDayGraph() {
		days.add(new GraphViewData(10, 100.0d));
		GraphViewSeries series = new GraphViewSeries(
				new GraphViewDataInterface[days.size()]);
		graphView.addSeries(series);
	}

	private void setupGraphs() {
		double today = 0;
		double yesterday = 0;
		
		//Daily Usage
		String jis = webc.dailyUsage("2012-04-1","2012-04-04");
		JSONArray jArray = webc.getJson(jis);
		
		try {	
			if(jArray !=  null){
				for(int i = 0;i < jArray.length();i++){
					JSONObject obj = jArray.getJSONObject(i);
					Double usage = obj.getDouble("usage");
					Double cost = obj.getDouble("cost");
					String day = obj.getString("date");
					
					System.out.println(usage + " " + cost + " " + day);
				}
			}			
		} catch (JSONException e) {
			Toast.makeText(getActivity(), e.toString(),Toast.LENGTH_LONG).show();
		} catch (Exception e){
			Toast.makeText(getActivity(), e.toString(),Toast.LENGTH_LONG).show();
		}

		days.add(new GraphViewData(1, 100.0d));
		days.add(new GraphViewData(2, 1.5d));
		days.add(new GraphViewData(2.5, 3.0d));
		days.add(new GraphViewData(3, 2.5d));
		days.add(new GraphViewData(4, 1.0d));
		days.add(new GraphViewData(5, 3.0d));

		// graph with dynamically genereated horizontal and vertical labels
		// init example series data
		GraphViewSeries daySeries = new GraphViewSeries(
				days.toArray(new GraphViewDataInterface[days.size()]));

		daySeries.setStyle(Color.WHITE, 5);

		graphView = new LineGraphView(getActivity().getApplicationContext()// context
				, "" // heading
		);
		graphView.addSeries(daySeries); // data

		
		
		//Weekly Seriies
		
		GraphViewSeries weekSeries = new GraphViewSeries(
				days.toArray(new GraphViewDataInterface[days.size()]));

		daySeries.setStyle(Color.WHITE, 5);
		GraphView graphView1 = new LineGraphView(getActivity()
				.getApplicationContext()// context
				, "" // heading
		);
		graphView1.addSeries(weekSeries); // data
		
		
		//Monthly Series
		
		GraphViewSeries monthSeries = new GraphViewSeries(
				days.toArray(new GraphViewDataInterface[days.size()]));

		monthSeries.setStyle(Color.WHITE, 5);

		GraphView graphView2 = new LineGraphView(getActivity()
				.getApplicationContext()// context
				, "" // heading
		);
		graphView2.addSeries(monthSeries); // data

		
		
		
		//Yearly Series
		
		GraphViewSeries yearSeries = new GraphViewSeries(
				days.toArray(new GraphViewDataInterface[days.size()]));

		yearSeries.setStyle(Color.WHITE, 5);
		
		GraphView graphView3 = new LineGraphView(getActivity()
				.getApplicationContext()// context
				, "" // heading
		);
		graphView3.addSeries(yearSeries); // data

		
		
		
		LinearLayout layout = (LinearLayout) mainView.findViewById(R.id.graph1);
		layout.addView(graphView);

		LinearLayout layout1 = (LinearLayout) mainView
				.findViewById(R.id.graph2);
		layout1.addView(graphView1);

		LinearLayout layout2 = (LinearLayout) mainView
				.findViewById(R.id.graph3);
		layout2.addView(graphView2);

		LinearLayout layout3 = (LinearLayout) mainView
				.findViewById(R.id.graph4);
		layout3.addView(graphView3);
		
		
		seekbarday = (SeekBar) mainView.findViewById(R.id.seekbarday);
		seekbarweek = (SeekBar) mainView.findViewById(R.id.seekbarweek);
		seekbarmonth = (SeekBar) mainView.findViewById(R.id.seekbarmonth);
		seekbaryear = (SeekBar) mainView.findViewById(R.id.seekbaryear);
	}

}
