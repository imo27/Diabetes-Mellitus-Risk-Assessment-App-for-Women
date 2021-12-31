Group: Jintong Hou, Ifeanyi Osuchukwu, Samuel Yoon

Install server : apache, php.
To install server, refer to https://sacan.biomed.drexel.edu/index.php?id=course:bcomp2:web:installingwebserver
or use a pre-packaged “stack”, such as Ammps: https://www.ampps.com/downloads/

Python: need version 3.0 or later
Make sure all Python Modules could be imported:
sys os sqlite3 pandas numpy matplotlib statsmodels sklearn statistics

Use command prompt to install packagies:
Your python engine may be called "python" or "py". take "py" for example, below command lines to install packagies:

py -m pip install "pandas"

py -m pip install "numpy"

py -m pip install "matplotlib"

py -m pip install "statsmodels"

py -m pip install "sklearn"

py -m pip install "statistics"

Enter the project from index.html

Files in project:

Ifeanyi's files:
Index.html: main menu page that has links to the Analysis.php, New_records.php, and Search.php files. users are directed back to this page when they select "Main Menu".
Search.php: interface where users are able to search for all current records in our database this page has links to update.php and delete.php.
delete.php: is a hidden file meant to work in conjuction with the Search.php file users are able to delete records from the database.
update.php: is a hidden file meant to work in conjuction with the Search.php file users are able to update records within the database and have them saved afterwards.
mainstyle.css: is a file containing style protocols for our webpages.


Samuel' files:
New_records.php: Input pages for data acquisition. User types all information and new data will be recorded in DMdata.db file with sqlite module. These records will be accessible/usable by other php, py files in this package program.
table.php: Review page for DMdata.db file after new information recorded. User can make sure that his/her inputs are stored properly in db file. 

Jintong's files:
Analysis.php: interface for all analysis, including calculate risk, and summary statistics, plots for ROC curve, histogram
Calculate_Risk.py: python script for calculating risk and ROC curve given at least one predictor value.
NpInputforRisk.py: python script for calculating risk if no predictor is provided.
Summary_Statistic.py: python script for calculating summary statistics for a single variable, and and histogram for predictors.



