package com.beef.whatthetruck.ui.profile;

import android.os.Handler;
import android.util.Log;

import androidx.lifecycle.LiveData;
import androidx.lifecycle.MutableLiveData;
import androidx.lifecycle.ViewModel;

import com.beef.whatthetruck.DrawerActivity;
import com.beef.whatthetruck.NetNinja;
import com.beef.whatthetruck.Networkable;
import com.beef.whatthetruck.OkNetNinja;

import static com.beef.whatthetruck.DrawerActivity.DEFAULT_NET_TEXT;

public class ProfileViewModel extends ViewModel implements Networkable {

    private static String email, uid, cid, name, fname, lname, company_name, phone, address, points;

    private MutableLiveData<String> mText;
    private static MutableLiveData<String> nameText, fnameText, lnameText, company_nameText, phoneText,
            addressStreetText, addressStreet2Text, addressCityText, addressStateText, addressZipText,
            emailText, pointsText;

    public ProfileViewModel() {


        email = DrawerActivity.getEmail();
        emailText = new MutableLiveData<>();
        emailText.setValue(email);

        mText = new MutableLiveData<>();
        mText.setValue(DEFAULT_NET_TEXT);

        nameText = new MutableLiveData<>();
        nameText.setValue(DEFAULT_NET_TEXT);

        fnameText = new MutableLiveData<>();
        fnameText.setValue(DEFAULT_NET_TEXT);

        lnameText = new MutableLiveData<>();
        lnameText.setValue(DEFAULT_NET_TEXT);

        company_nameText = new MutableLiveData<>();
        company_nameText.setValue(DEFAULT_NET_TEXT);

        phoneText = new MutableLiveData<>();
        phoneText.setValue(DEFAULT_NET_TEXT);

        addressStreetText = new MutableLiveData<>();
        addressStreetText.setValue(DEFAULT_NET_TEXT);
        addressStreet2Text = new MutableLiveData<>();
        addressStreet2Text.setValue(DEFAULT_NET_TEXT);
        addressCityText = new MutableLiveData<>();
        addressCityText.setValue(DEFAULT_NET_TEXT);
        addressStateText = new MutableLiveData<>();
        addressStateText.setValue(DEFAULT_NET_TEXT);
        addressZipText = new MutableLiveData<>();
        addressZipText.setValue(DEFAULT_NET_TEXT);

        pointsText = new MutableLiveData<>();
        pointsText.setValue(DEFAULT_NET_TEXT);

        initText();
    }

    private void initText() {
        uid = DrawerActivity.getUserID();

        mText.setValue(uid);

        if (!uid.equals(DEFAULT_NET_TEXT)) {
            OkNetNinja db = new OkNetNinja();
            db.startTask(this, NetNinja.Function.NAME, uid);
            db.startTask(this, NetNinja.Function.FNAME, uid);
            db.startTask(this, NetNinja.Function.LNAME, uid);
            db.startTask(this, NetNinja.Function.CURRENT_DRIVER_COMPANY, uid);
            db.startTask(this, NetNinja.Function.ADDRESS, uid);
            db.startTask(this, NetNinja.Function.PHONE, uid);
        }
        else { // After _ milliseconds, try again
            final Handler handler = new Handler();
            handler.postDelayed(new Runnable() {
                @Override
                public void run() {
                    initText();
                }
            }, 100);
        }
    }

    public LiveData<String> getText() { return mText; }
    public LiveData<String> getName() { return nameText; }
    public LiveData<String> getFName() { return fnameText; }
    public LiveData<String> getLName() { return lnameText; }
    public LiveData<String> getCompanyName() { return company_nameText; }
    public LiveData<String> getPhone() { return phoneText; }
    public LiveData<String> getAddressStreet() { return addressStreetText; }
    public LiveData<String> getAddressStreet2() { return addressStreet2Text; }
    public LiveData<String> getAddressCity() { return addressCityText; }
    public LiveData<String> getAddressState() { return addressStateText; }
    public LiveData<String> getAddressZip() { return addressZipText; }
    public LiveData<String> getEmail() { return emailText; }
    public LiveData<String> getPoints() { return pointsText; }

    public void updateName(String fname, String lname) {
        OkNetNinja db = new OkNetNinja();
        db.startTask(this, NetNinja.Function.UPDATE_NAME, uid, fname, lname);
    }
    public void updatePhone(String phone) {
        OkNetNinja db = new OkNetNinja();
        phone = phone.replaceAll("[^0-9]", "");
        if (phone.length() > 10)
            phone = phone.substring(phone.length()-10); // ensures phone is no more than 10 digits
        db.startTask(this, NetNinja.Function.UPDATE_PHONE, uid, phone);
    }
    public void updateAddress(String street, String street2, String city, String state, String zip) {
        OkNetNinja db = new OkNetNinja();
        db.startTask(this, NetNinja.Function.UPDATE_ADDRESS, uid, street, street2, city, state, zip);
    }

    @Override
    public void getResult(NetNinja.Function function, Object... results) {
        OkNetNinja db = new OkNetNinja();
        switch (function) {
            case NAME:
                name = results[0].toString();
                nameText.setValue(name);
                break;
            case FNAME:
                fname = results[0].toString();
                fnameText.setValue(fname);
                break;
            case LNAME:
                lname = results[0].toString();
                lnameText.setValue(lname);
                break;
            case CURRENT_DRIVER_COMPANY:
                cid = results[0].toString();
                db.startTask(this, NetNinja.Function.COMPANY_NAME, cid);
                db.startTask(this, NetNinja.Function.POINTS, uid, cid);
                break;
            case PHONE:
                phone = results[0].toString();
                phoneText.setValue(phone);
                break;
            case ADDRESS:
                address = results[0].toString();
                setAddressTextValues(address);
                break;
            case COMPANY_NAME:
                company_name = results[0].toString();
                if (company_name.isEmpty())
                    company_name = "none found";
                company_nameText.setValue(company_name);
                break;
            case POINTS:
                points = results[0].toString();
                pointsText.setValue(points);
                break;
            default:
                break;
        }
    }

    private void setAddressTextValues(String address) {
        String street, street2, city, state, zip;

        street = address.substring(0, address.indexOf("\n"));
        address = address.substring(address.indexOf("\n")+1);
        addressStreetText.setValue(street);

        street2 = address.substring(0, address.indexOf("\n"));
        address = address.substring(address.indexOf("\n")+1);
        addressStreet2Text.setValue(street2);

        city = address.substring(0, address.indexOf("\n"));
        address = address.substring(address.indexOf("\n")+1);
        addressCityText.setValue(city);

        state = address.substring(0, address.indexOf("\n"));
        address = address.substring(address.indexOf("\n")+1);
        addressStateText.setValue(state);

        zip = address;
        addressZipText.setValue(zip);
    }
}