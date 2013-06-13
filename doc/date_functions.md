Date functions
==============

FINE comes with a set of utility functions to help you display dates. Below is a list of those functions:

Date format
-----------

###function fine_get_dateformat_php_short()

Return the short date format depending on the language of the user.
For instance, this function will return 'mm/dd/yy' for English speaking users and 'dd/mm/yy'
for French speaking users. You can use this function with PHP's <code>date</code> function to
display the date according to user's preferences.

###function fine_get_day($day)

Return the translation of the day. The day is passed as a number, 0 for Sunday, 6 for Saturday.

###function fine_get_month($month)

Return the translation of the month. The month is passed as a number, 1 for January, 12 for December.

