package com.beef.whatthetruck.ui.send;

import androidx.lifecycle.LiveData;
import androidx.lifecycle.MutableLiveData;
import androidx.lifecycle.ViewModel;

public class SendViewModel extends ViewModel {

    private MutableLiveData<String> mText;

    public SendViewModel() {
        mText = new MutableLiveData<>();
        mText.setValue("Submit a Help Request (feature not yet available)");
    }

    public LiveData<String> getText() {
        return mText;
    }
}