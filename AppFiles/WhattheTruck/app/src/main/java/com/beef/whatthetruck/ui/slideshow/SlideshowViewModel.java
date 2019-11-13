package com.beef.whatthetruck.ui.slideshow;

import androidx.lifecycle.LiveData;
import androidx.lifecycle.MutableLiveData;
import androidx.lifecycle.ViewModel;

public class SlideshowViewModel extends ViewModel {

    private MutableLiveData<String> mText;

    public SlideshowViewModel() {
        mText = new MutableLiveData<>();
        mText.setValue("Slideshow? idk, let's just say this is a placeholder fragment");
    }

    public LiveData<String> getText() {
        return mText;
    }
}