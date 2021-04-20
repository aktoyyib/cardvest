@extends('layouts.app')

@section('title', 'Cardvest - Notifications')
@section('content')
<div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 main-content">
                    <div class="card content-area">
                        <div class="card-innr">
                            <div class="card-head">
                                <h4 class="card-title">Notifications</h4>
                            </div>
                            <div class="timeline">
                                <div class="timeline-line"></div>
                                <div class="timeline-item">
                                    <div class="timeline-time">09:30 AM</div>
                                    <div class="timeline-content">
                                        <p>Each member have a unique TWZ referral link to share with friends and family and receive a bonus - 15% of the value of their contribution.</p>
                                    </div>
                                </div>
                                <div class="timeline-item success">
                                    <div class="timeline-time">12:27 AM</div>
                                    <div class="timeline-content">
                                        <div class="chat-users">
                                            <div class="chat-users-stack">
                                                <div class="chat-avatar circle">
                                                    <img src="images/user-a.jpg" alt="">
                                                </div>
                                                <div class="chat-avatar circle">
                                                    <img src="images/user-b.jpg" alt="">
                                                </div>
                                                <div class="chat-avatar circle">
                                                    <img src="images/user-c.jpg" alt="">
                                                </div>
                                            </div>
                                            <span>+14</span>
                                        </div>
                                        <span class="timeline-content-info">New User Added</span>
                                    </div>
                                </div>
                                <div class="timeline-item secondary">
                                    <div class="timeline-time">07:45 PM</div>
                                    <div class="timeline-content">
                                        <a href="#" class="timeline-content-url">Now You can send payment directly to our address or you may pay online</a>
                                        <span class="timeline-content-info">New Article on Blog</span>
                                    </div>
                                </div>
                                <div class="timeline-item success">
                                    <div class="timeline-time">03:45 PM</div>
                                    <div class="timeline-content">
                                        <h5 class="timeline-content-title">Get bonus - 15% of the value of your contribution</h5>
                                        <span class="timeline-content-info">Latest Offers</span>
                                    </div>
                                </div>
                                <div class="timeline-item light">
                                    <div class="timeline-time">02:30 PM</div>
                                    <div class="timeline-content">
                                        <p>Each member have a unique TWZ referral link to share with friends and family and receive a bonus - 15% of the value of their contribution.</p>
                                        <span class="timeline-content-info">Announcements</span>
                                    </div>
                                </div>
                                <div class="timeline-item danger">
                                    <div class="timeline-time">12:17 AM</div>
                                    <div class="timeline-content">
                                        <p>You can buy our TWZ tokens using ETH, BTC, LTC or USD.</p>
                                        <span class="timeline-content-info">Announcements</span>
                                    </div>
                                </div>
                                <div class="timeline-item primary">
                                    <div class="timeline-time">09:31 AM</div>
                                    <div class="timeline-content">
                                        <div class="chat-users">
                                            <div class="chat-users-stack">
                                                <div class="chat-avatar circle">
                                                    <img src="images/user-a.jpg" alt="">
                                                </div>
                                                <div class="chat-avatar circle">
                                                    <img src="images/user-c.jpg" alt="">
                                                </div>
                                                <div class="chat-avatar circle">
                                                    <img src="images/user-b.jpg" alt="">
                                                </div>
                                                <div class="chat-avatar circle">
                                                    <img src="images/user-d.jpg" alt="">
                                                </div>
                                            </div>
                                            <span>+122</span>
                                        </div>
                                        <span class="timeline-content-info">New User Added</span>
                                    </div>
                                </div>
                                <div class="timeline-item warning">
                                    <div class="timeline-time">08:57 AM</div>
                                    <div class="timeline-content">
                                        <p>Enter your amount, you would like to contribute and calculate the amount of token you will received. To get tokens please make a payment. You can send payment directly to our address or you may pay online. Once you paid, you will receive an email about the successfull deposit.</p>
                                        <span class="timeline-content-info">Announcements</span>
                                    </div>
                                </div>
                            </div>
                        </div><!-- .card-innr -->
                    </div><!-- .card -->
                </div>
                <div class="col-lg-4 aside sidebar-right">
                    <div class="account-info card">
                        <div class="card-innr">
                            <h6 class="card-title card-title-sm">Your Account Status</h6>
                            <ul class="btn-grp">
                                <li><a href="#" class="btn btn-auto btn-xs btn-success">Email Verified</a></li>
                                <li><a href="#" class="btn btn-auto btn-xs btn-warning">KYC Pending</a></li>
                            </ul>
                            <div class="gaps-2-5x"></div>
                            <h6 class="card-title card-title-sm">Receiving Wallet</h6>
                            <div class="d-flex justify-content-between">
                                <span><span>0x39deb3.....e2ac64rd</span> <em class="fas fa-info-circle text-exlight" data-toggle="tooltip" data-placement="bottom" title="1 ETH = 100 TWZ"></em></span>
                                <a href="#" data-toggle="modal" data-target="#edit-wallet" class="link link-ucap">Edit</a>
                            </div>
                        </div>
                    </div>
                    <div class="referral-info card">
                        <div class="card-innr">
                            <h6 class="card-title card-title-sm">Earn with Referral</h6>
                            <p class=" pdb-0-5x">Invite your friends &amp; family and receive a <strong><span class="text-primary">bonus - 15%</span> of the value of contribution.</strong></p>
                            <div class="copy-wrap mgb-0-5x">
                                <span class="copy-feedback"></span>
                                <em class="fas fa-link"></em>
                                <input type="text" class="copy-address" value="https://demo.themenio.com/ico?ref=7d264f90653733592" disabled>
                                <button class="copy-trigger copy-clipboard" data-clipboard-text="https://demo.themenio.com/ico?ref=7d264f90653733592"><em class="ti ti-files"></em></button>
                            </div><!-- .copy-wrap -->
                        </div>
                    </div>
                    <div class="kyc-info card">
                        <div class="card-innr">
                            <h6 class="card-title card-title-sm">Identity Verification - KYC</h6>
                            <p>To comply with regulation, participant will have to go through indentity verification.</p>
                            <p class="lead text-light pdb-0-5x">You have not submitted your KYC application to verify your indentity.</p>
                            <a href="#" class="btn btn-primary btn-block">Click to Proceed</a>
                            <h6 class="kyc-alert text-danger">* KYC verification required for purchase token</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .container -->
    </div><!-- .page-content -->
@endsection()