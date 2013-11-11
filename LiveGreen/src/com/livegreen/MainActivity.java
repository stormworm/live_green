package com.livegreen;

import android.content.res.Configuration;
import android.graphics.Color;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentActivity;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentStatePagerAdapter;
import android.support.v4.view.PagerAdapter;
import android.support.v4.view.PagerTabStrip;
import android.support.v4.view.ViewPager;
import android.view.Menu;
import android.view.MenuItem;

public class MainActivity extends FragmentActivity {

	private static final int NUM_PAGES = 4;
	private ViewPager mPager;
	private PagerAdapter mPagerAdapter;
	
	
	
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        
        
        PagerTabStrip pagerTabStrip = (PagerTabStrip) findViewById(R.id.pager_title_strip);
        pagerTabStrip.setDrawFullUnderline(true);
        pagerTabStrip.setTabIndicatorColor(Color.parseColor("#DADADA"));
        
        mPager = (ViewPager) findViewById(R.id.pager);
        mPagerAdapter = new ScreenSlidePagerAdapter(getSupportFragmentManager());
        mPager.setAdapter(mPagerAdapter);
        
        
    }


    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.main, menu);
        return true;
    }
    
    @Override
	public boolean onOptionsItemSelected(MenuItem item){
		switch (item.getItemId()) {
			case R.id.action_logout:
				doLogout();
				break;
		}
		return true;
    }
    
    @Override
   	public void onConfigurationChanged(Configuration newConfig){
    	super.onConfigurationChanged(newConfig);
    	
    }
    
    private void doLogout(){
    	finish();
    }
    
    public class ScreenSlidePagerAdapter extends FragmentStatePagerAdapter {
        public ScreenSlidePagerAdapter(FragmentManager fragmentManager) {
            super(fragmentManager);
        }

		@Override
        public Fragment getItem(int position) {
			Bundle bundle=new Bundle();
			
			switch(position){
				case 0:
					UserActivityFragment uaf1 = new UserActivityFragment();
					return uaf1;
				case 1:
					FriendActivityFragment uaf2 = new FriendActivityFragment();
					return uaf2;
				case 2:
					AchievementActivityFragment uaf3 = new AchievementActivityFragment();
					return uaf3;
				default:
					TipOffersActivityFragment uaf4 = new TipOffersActivityFragment();
					return uaf4;
			}
            
	   }
	
        @Override
        public int getCount() {
            return NUM_PAGES;
        }
	        
        @Override
		public CharSequence getPageTitle(int position) {
        	switch(position){
				case 0:
					return "My Usage";
				case 1:
					return "My Friends";
				case 2:
					return "My Achievements";
				default:
					return "Tips & Offers";
			}
        }
    }
    
}
