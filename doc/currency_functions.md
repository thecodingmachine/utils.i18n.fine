Currency functions
==================

FINE comes with a set of utility functions to help you display currencies. Below is a list of those functions:

Currency symbols
----------------

###function fine_get_currency_symbol($isocode)

Return the currency symbol based on the currency ISO 4217 code.

Example:
```
// This will display €
echo fine_get_currency_symbol("EUR");

// This will display $
echo fine_get_currency_symbol("USD");	
```

###function fine_get_price($price, $currency_iso_code)

Return the currency symbol based on the currency ISO 4217 code.

Example:
```
// This will display €
echo fine_get_currency_symbol("EUR");

// This will display $
echo fine_get_currency_symbol("USD");	
```
