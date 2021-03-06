<script>
  // card selector
  $('#card').selectpicker();

  // address selector
  var places = places({
    container: document.querySelector('#address'),
    language: 'en',
    countries: 'us',
    templates: {
      value: function(suggestion) {
        return suggestion.name;
      }
    }
  });

  // auto-fill address form
  places.on('change', function resultSelected (event) {
    $('#zipcode').val(event.suggestion.postcode);
  });

  // notification helper
  function notify (message, type) {
    if (type == 'success') {
      toastr.success(message);
    } else if (type == 'danger') {
      toastr.error(message);
    }
  }

  // slugify helper
  function slugify(text) {
    return text.toString().toLowerCase()
      .replace(/\s+/g, '-')    
      .replace(/[^\w\-]+/g, '')
      .replace(/\-\-+/g, '-')
      .replace(/^-+/, '')
      .replace(/-+$/, '');
  }

  // brace yourself this is insultingly long
  $('.join-plan').click(function(event) {
    event.preventDefault();

    // Assume no error
    var cardType = '';
    var errNumber = '';
    var errName = '';
    var errDate = '';
    var errCVV = '';
    var errAddress = '';
    var errZip = '';

    // validate card-number
    $('#card-number').validateCreditCard(function(result) {
      if (!result.luhn_valid) {
        errNumber = $('#card-number').val().length === 0 ? 'Credit card number is required.' : 'Invalid credit card number.';
        $('#number-form').addClass('has-danger');
        $('#card-number').addClass('form-control-danger');
        $('#number-form-input').removeClass('m--hide').html(errNumber);
        notify('Sorry, an error has occurred.', 'danger');
      }
      else {
        errNumber = '';
        $('#number-form').removeClass('has-danger');
        $(this).removeClass('form-control-danger');
        $('#number-form-input').addClass('m--hide').html(errNumber);
      }
      if (result.card_type) {
        cardType = result.card_type.name;
      }
    });

    // then name
    var name = $('#name').val();
    errName = name.length === 0 ? 'Cardholder is required.' : '';

    if (errName.length) {
      $('#name-form').addClass('has-danger');
      $('#name').addClass('form-control-danger');
      $('#name-form-input').removeClass('m--hide').html(errName);

      if (!errNumber.length) {
        notify('Sorry, an error has occurred.', 'danger');
      }
    }
    else {
      errName = '';
      $('#name-form').removeClass('has-danger');
      $('#name').removeClass('form-control-danger');
      $('#name-form-input').addClass('m--hide').html(errName);
    }

    // then date
    var date = $('#date').val();
    errDate = date.length === 0 ? 'Credit card expiration date is required.' : parseInt(date.replace(/ /g, '').split('/')[0]) > 12 ? 'Invalid date.' : '';

    if (errDate.length) {
      $('#date-form').addClass('has-danger');
      $('#date').addClass('form-control-danger');
      $('#date-form-input').removeClass('m--hide').html(errDate);

      if (!errNumber.length && !errName.length) {
        notify('Sorry, an error has occurred.', 'danger');
      }
    }
    else {
      errDate = '';
      $('#date-form').removeClass('has-danger');
      $('#date').removeClass('form-control-danger');
      $('#date-form-input').addClass('m--hide').html(errDate);
    }

    // then cvv
    var cvv = $('#cvv').val();
    errCVV =  cvv.length === 0 || cvv.length < 3 || (cvv.length === 4 && cardType !== 'amex') ? 'CVV must be 4 digits for American Express and 3 digits for other card types.' : ''; 

    if (errCVV.length) {
      $('#cvv-form').addClass('has-danger');
      $('#cvv').addClass('form-control-danger');
      $('#cvv-form-input').removeClass('m--hide').html(errCVV);

      if (!errNumber.length && !errName.length && !errDate.length) {
        notify('Sorry, an error has occurred.', 'danger');
      }
    }
    else {
      errCVV = '';
      $('#cvv-form').removeClass('has-danger');
      $('#cvv').removeClass('form-control-danger');
      $('#cvv-form-input').addClass('m--hide').html(errCVV);
    }

    // then address
    var address = $('#address').val();
    errAddress = address.length === 0 ? 'Billing address is required.' : '';

    if (errAddress.length) {
      $('#address-form').addClass('has-danger');
      $('#address').addClass('form-control-danger');
      $('#address-form-input').removeClass('m--hide').html(errAddress);

      if (!errNumber.length && !errName.length && !errDate.length && !errCVV.length) {
        notify('Sorry, an error has occurred.', 'danger');
      }
    }
    else {
      errAddress = '';
      $('#address-form').removeClass('has-danger');
      $('#address').removeClass('form-control-danger');
      $('#address-form-input').addClass('m--hide').html(errAddress);
    }

    // and finally zipcode
    var zipcode = $('#zipcode').val();
    errZip = zipcode.length !== 5 || !(/^\d+$/.test(zipcode)) ? 'Zipcode must be 5 digit number.' : ''; 

    if (errZip.length) {
      $('#zipcode-form').addClass('has-danger');
      $('#zipcode').addClass('form-control-danger');
      $('#zipcode-form-input').removeClass('m--hide').html(errZip);

      if (!errNumber.length && !errName.length && !errDate.length && !errCVV.length && !errAddress.length) {
        notify('Sorry, an error has occurred.', 'danger');
      }
    }
    else {
      errZip = '';
      $('#zipcode-form').removeClass('has-danger');
      $('#zipcode').removeClass('form-control-danger');
      $('#zipcode-form-input').addClass('m--hide').html(errZip);
    }

    if (!errNumber.length && !errDate.length && !errCVV.length && !errZip.length) {
      $(this).removeClass().addClass('btn btn-focus m-btn m-btn--custom m-btn--wide  m-btn--air m-btn--icon join-plan m-loader m-loader--light m-loader--right disabled');
      $(this).html('Processing');

      // Tokenize credit card
      $.ajax({
        url: '{{ config('app.url') }}/braintree',
        type: 'GET',
        success: function (response) {
          var client = new braintree.api.Client({ clientToken: response.token });
          client.tokenizeCard({
            number: $('#card-number').val().replace(/ /g, ''),
            cardholderName: $('#name').val(),
            expirationDate: $('#date').val().replace(/ /g, ''),
            cvv: $('#cvv').val(),
            billingAddress: {
              address: $('#address').val(),
              postalCode: $('#zipcode').val()
            }
          }, function (error, nonce) {
            if (error) {
              console.error(error);
            } else {
              $('#nonce').val(nonce);
              $('#credit-card-form').submit();
            }
          });
        }
      });
    }
  });

  if ('{{ $errors->has('address') }}') {
    notify('Sorry, an error has occurred.', 'danger');
  }
  else if ('{{ $errors->has('city') }}') {
    notify('Sorry, an error has occurred.', 'danger');
  }
  else if ('{{ $errors->has('zipcode') }}') {
    notify('Sorry, an error has occurred.', 'danger');
  }
  else if ('{{ $errors->has('number') }}') {
    notify('Sorry, an error has occurred.', 'danger');
  }
  else if ('{{ $errors->has('name') }}') {
    notify('Sorry, an error has occurred.', 'danger');
  }
  else if ('{{ $errors->has('date') }}') {
    notify('Sorry, an error has occurred.', 'danger');
  }
  else if ('{{ $errors->has('cvv') }}') {
    notify('Sorry, an error has occurred.', 'danger');
  }
  else if ('{{ $errors->has('nonce') }}') {
    notify('Couldn\'t verify your credit card.', 'danger');
  }

  $(window).on('load', function(event) {
    var index = parseInt($('#card').find(":selected").text());
    var cards = JSON.parse('<?= json_encode($cards); ?>');
    var number = '{{ old('number') }}'.length !== 0 ? '{{ old('number') }}' : Number.isInteger(index) ? cards[index].maskedNumber.replace('******', cards[index].bin).replace(/(\d{4})/g, '$1 ').replace(/(^\s+|\s+$)/,'') : '**** **** **** ****';
    var name = '{{ old('name') }}'.length !== 0 ? '{{ old('name') }}' : Number.isInteger(index) ? cards[index].cardholderName : 'Full name';
    var date = '{{ old('date') }}'.length !== 0 ? '{{ old('date') }}' : Number.isInteger(index) ? cards[index].expirationDate : '**/**';

    $('#card-number').val(number === '**** **** **** ****' ? '' : number);
    $('#name').val(name === 'Full name' ? '' : name);
    $('#date').val(date === '**/**' ? '' : date);

    // setup credit card form
    $('#credit-card-form').card({
      container: '#card-wrapper',
      formSelectors: {
        numberInput: 'input#card-number',
        nameInput: 'input#name',
        expiryInput: 'input#date',
        cvcInput: 'input#cvv',
      },
      placeholders: {
        number: number,
        name: name,
        expiry: date,
        cvv: '***',
      }
    });
  });

  $('#card').on('change', function(event) {
    var index = parseInt($(this).find(":selected").text());
    var cards = JSON.parse('<?= json_encode($cards); ?>');
    var number = '{{ old('number') }}'.length !== 0 ? '{{ old('number') }}' : Number.isInteger(index) ? cards[index].maskedNumber.replace('******', cards[index].bin).replace(/(\d{4})/g, '$1 ').replace(/(^\s+|\s+$)/,'') : '**** **** **** ****';
    var name = '{{ old('name') }}'.length !== 0 ? '{{ old('name') }}' : Number.isInteger(index) ? cards[index].cardholderName : 'Full name';
    var date = '{{ old('date') }}'.length !== 0 ? '{{ old('date') }}' : Number.isInteger(index) ? cards[index].expirationDate : '**/**';

    $('#card-number').val(number === '**** **** **** ****' ? '' : number);
    $('#name').val(name === 'Full name' ? '' : name);
    $('#date').val(date === '**/**' ? '' : date);

    // setup credit card form
    $('#credit-card-form').card({
      container: '#card-wrapper',
      formSelectors: {
        numberInput: 'input#card-number',
        nameInput: 'input#name',
        expiryInput: 'input#date',
        cvcInput: 'input#cvv',
      },
      placeholders: {
        number: number,
        name: name,
        expiry: date,
        cvv: '***',
      }
    });
  });
</script>
