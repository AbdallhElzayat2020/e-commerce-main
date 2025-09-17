<div>
     @if($screen == 'address')
        <div class="tab-pane fade @if ($screen == 'address') active show @endif">
            <div class="profile-section address-section addresses">
                <div class="row gy-md-0 g-5">
                    <div class="col-md-6">
                        <div class="seller-info">
                            <h5 class="heading">Address</h5>
                            <div class="info-list">
                                <div class="info-title">
                                    <p>Name:</p>
                                    <p>Email:</p>
                                    <p>Phone:</p>
                                    <p>Country:</p>
                                    <p>Governorate:</p>
                                    <p>City:</p>
                                </div>
                                <div class="info-details">
                                    <p>{{ $auth_user->name }}</p>
                                    <p><a href="javascript:void(0)"
                                            class="__cf_email__"
                                            data-cfemail="791d1c14161c14181015391e14181015571a1614">[{{ $auth_user->email }}]</a>
                                    </p>
                                    <p>{{ $auth_user->phone }}</p>
                                    <p>{{ $auth_user->country->name }}</p>
                                    <p>{{ $auth_user->governorate->name }}</p>
                                    <p>{{ $auth_user->city->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     @endif
</div>
