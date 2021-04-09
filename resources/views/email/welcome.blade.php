@extends('layouts.email')

@section('content')
<div class="page-content">

  <div class="content-area card">
    <div class="card-innr">

      <table class="email-wraper bg-primary">
        <tbody>
          <tr>
            <td class="pdt-4x pdb-4x">
              <table class="email-header">
                <tbody>
                  <tr>
                    <td class="text-center pdb-2-5x">
                      <a href="#"><img class="email-logo" src="{{ asset('images/logo.png') }}" alt="logo"></a>
                      <p class="email-title text-center">The most profitable giftcard trading platform</p>
                    </td>
                  </tr>
                </tbody>
              </table>
              <table class="email-body">
                <tbody>
                  <tr>
                    <td class="pd-3x pdb-2x">
                      <p class="mgb-1x">Hi {{ $username }},</p>
                      <p class="mgb-1x">We are pleased to have you as a member of Cardvest Family.</p>
                      <!-- <p class="mgb-1x">Your account is now verified and you can purchase tokens for the ICO. Also
                              you can submit your documents for the KYC from my Account page.</p> -->
                      <p class="pdb-1x">Hope you'll enjoy the experience, we're here if you have any questions,
                        drop us a line at <a href="mailto:info@cardvest.ng">info@cardvest.ng</a> anytime.
                      </p>
                    </td>
                  </tr>
                </tbody>
              </table>
              <table class="email-footer">
                <tbody>
                  <tr>
                    <td class="text-center pdt-2-5x pdl-2x pdr-2x">
                      <p class="email-copyright-text text-white text-center">Copyright © <?= Date('Y') ?>
                        Cardvest.</p>
                      <!-- <ul class="email-social text-center">
                              <li><a href="#"><img src="images/brand-b.png" alt="brand"></a></li>
                              <li><a href="#"><img src="images/brand-e.png" alt="brand"></a></li>
                              <li><a href="#"><img src="images/brand-d.png" alt="brand"></a></li>
                              <li><a href="#"><img src="images/brand-a.png" alt="brand"></a></li>
                              <li><a href="#"><img src="images/brand-c.png" alt="brand"></a></li>
                            </ul> -->
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="gaps-0-5x"></div><!-- .gaps -->
    </div><!-- .card-innr -->
  </div><!-- .card -->

</div><!-- .page-content -->
@endsection