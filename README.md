convert-csv-to-xls
==================

Simple script which converts uploaded CSV files into XLS format.

If converts uploaded CSV file, finds columns by their names in first row, and processes rows as follow

1) clear "Grand Total" if "Billing Description" is (not equal) to COD
2) delete record if "Shipping Country" is (not equal) to "Italia"
2) set "Weight" value to "1"
4) set "Quantity Items" value to "1" 

Then save to XLS format.

This script uses PHPExcel to save files in XLS format.
